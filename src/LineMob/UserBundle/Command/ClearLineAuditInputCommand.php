<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearLineAuditInputCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('linemob:user:clear-audit')
            ->setDescription('Clearing audit input')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('linemob.manager.line_audit_input');

        $em->createQuery('DELETE '.$this->getContainer()->getParameter('linemob.model.line_audit_input.class').' u')->execute();
    }
}
