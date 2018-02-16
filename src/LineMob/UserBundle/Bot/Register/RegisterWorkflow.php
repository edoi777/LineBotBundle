<?php

namespace LineMob\UserBundle\Bot\Register;

use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Template\TextTemplate;
use LineMob\Core\Workflow\AbstractWorkflow;
use LineMob\Core\Workflow\WorkflowRegistryInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareTrait;
use LineMob\UserBundle\Manager\LineUserRegistrationable;
use LineMob\UserBundle\Model\LineUserInterface;
use Symfony\Component\Workflow\Workflow;


class RegisterWorkflow extends AbstractWorkflow implements TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    /**
     * @var array
     */
    protected $mappingMethods = [
        'start',
        'enterUsername',
        'enterPassword',
        'enterPasswordConfirm',
        'enterName'
    ];

    /**
     * @var array
     */
    private $configs;

    /**
     * @var LineUserRegistrationable
     */
    private $userManager;

    public function __construct(
        WorkflowRegistryInterface $registry,
        LineUserRegistrationable $userManager,
        array $configs
    )
    {
        $this->configs = $configs;
        $this->userManager = $userManager;
        parent::__construct($registry);
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfig()
    {
        return [
            'name' => 'Register',
            'marking_store' => [
                'type' => 'single_state',
                'arguments' => ['authState'],
            ],
            'supports' => [
                $this->configs['user_class'],
            ],
            'places' => [
                'started',
                'wait_for_username',
                'wait_for_password',
                'wait_for_password_confirm',
                'wait_for_name',
                'finished',
            ],
            'transitions' => [
                'start' => [
                    'from' => 'started',
                    'to' => 'wait_for_username',
                ],
                'enter_username' => [
                    'from' => 'wait_for_username',
                    'to' => 'wait_for_password',
                ],
                'enter_password' => [
                    'from' => 'wait_for_password',
                    'to' => 'wait_for_password_confirm',
                ],
                'enter_password_confirm' => [
                    'from' => 'wait_for_password_confirm',
                    'to' => 'wait_for_name',
                ],
                'enter_name' => [
                    'from' => 'wait_for_name',
                    'to' => 'finished',
                ],
            ],
        ];
    }

    /**
     * @param AbstractCommand $command
     * @param Workflow $workflow
     *
     * @return bool
     */
    protected function start(AbstractCommand $command, Workflow $workflow)
    {
        if (!$workflow->can($command->storage, 'start')) {
            return false;
        }

        $workflow->apply($command->storage, 'start');

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_user.register.email_input');

        return true;
    }

    /**
     * @param AbstractCommand $command
     * @param Workflow $workflow
     *
     * @return bool
     */
    protected function enterUsername(AbstractCommand $command, Workflow $workflow)
    {
        if (!$workflow->can($subject = $command->storage, 'enter_username')) {
            return false;
        }

        if (!filter_var($command->input->text, FILTER_VALIDATE_EMAIL)) {
            $command->message = new TextTemplate();
            $command->message->text = $this->translator->trans('linemob_user.register.email_input_invalid');

            return true;
        }

        if ($this->userManager->isExistsUser($command->input->text)) {
            $command->message = new TextTemplate();
            $command->message->text = $this->translator->trans('linemob_user.register.email_input_already_used');

            return true;
        }

        $subject->mergeLineCommandData([
            'username' => $command->input->text
        ]);
        $workflow->apply($command->storage, 'enter_username');

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_user.register.password_input');

        return true;
    }

    /**
     * @param AbstractCommand $command
     * @param Workflow $workflow
     *
     * @return bool
     */
    protected function enterPassword(AbstractCommand $command, Workflow $workflow)
    {
        if (!$workflow->can($subject = $command->storage, 'enter_password')) {
            return false;
        }

        $subject->mergeLineCommandData([
            'plain_password' => $command->input->text
        ]);
        $workflow->apply($command->storage, 'enter_password');

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_user.register.password_confirm_input');

        return true;
    }

    /**
     * @param AbstractCommand $command
     * @param Workflow $workflow
     *
     * @return bool
     */
    protected function enterPasswordConfirm(AbstractCommand $command, Workflow $workflow)
    {
        if (!$workflow->can($command->storage, 'enter_password_confirm')) {
            return false;
        }

        if ($password = $command->input->text !== $command->storage->getLineCommandData()['plain_password']) {
            $command->message = new TextTemplate();
            $command->message->text = $this->translator->trans('linemob_user.register.password_not_match');

            return true;
        }

        $workflow->apply($command->storage, 'enter_password_confirm');

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_user.register.name_input');

        return true;
    }

    /**
     * @param AbstractCommand $command
     * @param Workflow $workflow
     *
     * @return bool
     */
    protected function enterName(AbstractCommand $command, Workflow $workflow)
    {
        if (!$workflow->can($subject = $command->storage, 'enter_name')) {
            return false;
        }

        $subject->mergeLineCommandData([
            'name' => $command->input->text
        ]);
        $workflow->apply($command->storage, 'enter_name');

        /** @var LineUserInterface $lineUser */
        $lineUser = $command->storage;

        $data = $subject->getLineCommandData();
        $this->userManager->register($data);

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_user.register.success');

        $lineUser->clearLineActiveCmd();
        $lineUser->setLineCommandData([]);

        return true;
    }
}
