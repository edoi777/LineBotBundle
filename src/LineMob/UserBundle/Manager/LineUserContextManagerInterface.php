<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Manager;

use LineMob\UserBundle\Model\LineUserInterface;

interface LineUserContextManagerInterface
{
    /**
     * @param LineUserInterface $user
     * @return void
     */
    public function setContext(LineUserInterface $user): void;

    /**
     * @return mixed
     */
    public function getLoggedUser();

    /**
     * @param mixed $user
     */
    public function setLoggedUser($user);
}
