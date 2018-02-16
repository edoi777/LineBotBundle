<?php

namespace LineMob\KeywordBundle\Form\Type;

use LineMob\KeywordBundle\Model\MessageInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Valid;

class MessageType extends AbstractResourceType
{
    /**
     * @var FormTypeRegistryInterface
     */
    private $formTypeRegistry;

    /**
     * @var array
     */
    private $replyMessageTypes;

    public function __construct($dataClass, $validationGroups = [], FormTypeRegistryInterface $formTypeRegistry, array $replyMessageTypes = [])
    {
        parent::__construct($dataClass, $validationGroups);
        $this->formTypeRegistry = $formTypeRegistry;
        $this->replyMessageTypes = $replyMessageTypes;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required' => false,
                'label' => 'linemob_keyword.form.message.type.label',
                'choices' => array_flip($this->replyMessageTypes),
            ])
            ->add('position', IntegerType::class, [
                'required' => false,
                'empty_data' => '0',
                'label' => 'linemob_keyword.form.message.position.label',
            ])
        ;

        $prototypes = [];

        foreach (array_keys($this->replyMessageTypes) as $name) {
            $formBuilder = $builder->create(
                'replyMessage', $this->formTypeRegistry->get($name, 'default'), []
            );

            $prototypes[$name] = $formBuilder->getForm();
        }

        $builder
            ->setAttribute('prototypes', $prototypes)

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var MessageInterface $data */
                $data = $event->getData();

                if (!$data || !$type = $data->getType()) {
                    return;
                }

                $event->getForm()->add('replyMessage', $this->formTypeRegistry->get($type, 'default'), []);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                // Use old type, that mean user can't change type after save
                if ($form->getData() && $form->getData()->getType()) {
                    $data['type'] = $form->getData()->getType();
                }

                // It'll validated NotBlank on type
                if (!isset($data['type']) || !$this->formTypeRegistry->has($data['type'], 'default')) {
                    return;
                }

                $form->add('replyMessage', $this->formTypeRegistry->get($data['type'], 'default'), []);
                $event->setData($data);
            })
        ;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     *
     * @return mixed
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['prototypes'] = [];
        $prototypes = $form->getConfig()->getAttribute('prototypes');

        foreach ($prototypes as $type => $prototype) {
            /** @var FormInterface $prototype */
            $view->vars['prototypes'][$type] = $prototype->createView($view);
        }
    }

    public function getBlockPrefix()
    {
        return 'message_type';
    }
}
