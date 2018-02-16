<?php

namespace LineMob\UserBundle\Audit;

use LineMob\UserBundle\Model\LineUserInterface;

interface LineInputAuditorInterface
{
    const BY_USER_MODE = 'by_user';
    const ALL_MODE = 'all';

    /**
     * @param $keyword
     * @param $type
     * @param LineUserInterface|null|null $lineUser
     */
    public function audit($keyword, $type, ?LineUserInterface $lineUser = null);
}
