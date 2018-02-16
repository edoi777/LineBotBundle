<?php

namespace LineMob\KeywordBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ReplyImageMessageType extends AbstractReplyMessageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('picture', ReplyImageMessagePictureType::class, [
                'required' => true
            ])
        ;
    }
}
