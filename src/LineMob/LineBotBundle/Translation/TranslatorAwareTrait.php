<?php

namespace LineMob\LineBotBundle\Translation;

use Symfony\Component\Translation\TranslatorInterface;

trait TranslatorAwareTrait
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}
