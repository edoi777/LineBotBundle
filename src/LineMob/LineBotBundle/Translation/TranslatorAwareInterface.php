<?php

namespace LineMob\LineBotBundle\Translation;

use Symfony\Component\Translation\TranslatorInterface;

interface TranslatorAwareInterface
{
    public function setTranslator(TranslatorInterface $translator);
}
