<?php

namespace LineMob\KeywordBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class KeywordType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word', TextType::class, [
                'required' => true,
                'label' => 'linemob_keyword.form.keyword.word.label'
            ])
            ->add('additionalKeywords', CollectionType::class, [
                'required' => false,
                'entry_type' => AdditionalKeywordType::class,
                'label' => 'linemob_keyword.form.keyword.additional_keyword.label',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('messages', CollectionType::class, [
                'entry_type' => MessageType::class,
                'label' => 'linemob_keyword.form.keyword.messages.label',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr'         => [
                    'class' => "messages-collection",
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart'] = true;
    }
}
