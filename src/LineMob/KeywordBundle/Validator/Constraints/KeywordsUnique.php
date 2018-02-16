<?php

namespace LineMob\KeywordBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class KeywordsUnique extends Constraint
{
    public $message = 'ค่านี้ถูกใช้ไปแล้ว';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'keyword_unique';
    }
}
