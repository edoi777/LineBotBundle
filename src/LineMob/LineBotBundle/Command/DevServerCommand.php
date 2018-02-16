<?php

namespace LineMob\LineBotBundle\Command;

use LineMob\Core\Constants;
use LineMob\UserBundle\Model\LineUserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DevServerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('linemob:bot:mock_run')
            ->setDescription('bot testing')
            ->addArgument('bot_name', InputArgument::REQUIRED)
            ->addOption('text', 't', InputOption::VALUE_REQUIRED)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mockContent = self::getLineHookMockUp($input->getOption('text'), null);

        $results = $this->getContainer()
            ->get(sprintf('linemob.%s.bot', $input->getArgument('bot_name')))
            ->disableValidationHeader()
            ->run(json_encode($mockContent), 'mock')
        ;

        dump($results);
    }

    /**
     * @param string $text
     * @param LineUserInterface|null $lineUser
     *
     * @return array
     */
    public static function getLineHookMockUp(string $text, ?LineUserInterface $lineUser = null)
    {
        return [
            'events' => [
                [
                    'type' => Constants::REVEIVE_TYPE_MESSAGE,
                    'replyToken' => 'mock:replyToken',
                    'source' => [
                        'userId' => $lineUser ? $lineUser->getLineUserId() : 'mock:userId',
                    ],
                    'message' => [
                        'type' => Constants::REVEIVE_TYPE_MESSAGE_TEXT,
                        'text' => $text,
                    ],
                ],
            ],
        ];
    }
}
