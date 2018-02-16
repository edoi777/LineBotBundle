<?php

namespace LineMob\KeywordBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class ReplyStickerMessageType extends AbstractReplyMessageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('packageId', IntegerType::class, [
                'required' => true,
                'label' => 'linemob_keyword.form.reply_sticker_message.package_id.label',
            ])
            ->add('packageCode', IntegerType::class, [
                'required' => true,
                'label' => 'linemob_keyword.form.reply_sticker_message.package_code.label',
            ])
        ;
    }
}
