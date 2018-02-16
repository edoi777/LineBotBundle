<?php

namespace LineMob\KeywordBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractReplyMessageType extends AbstractResourceType
{
    private $identifier;
    public function __construct($dataClass, $validationGroups = [], string $identifier)
    {
        parent::__construct($dataClass, $validationGroups);

        $this->identifier = $identifier;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', HiddenType::class, [
                'mapped' => false,
                'data' => $this->identifier
            ])
        ;
    }
}
