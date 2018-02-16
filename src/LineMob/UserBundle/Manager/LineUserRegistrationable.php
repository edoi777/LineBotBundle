<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Manager;


interface LineUserRegistrationable extends LineUserContextManagerInterface
{
    /**
     * @param string $identity
     *
     * @return bool
     */
    public function isExistsUser(string $identity): bool;

    /**
     * @param array $data
     * @return void
     */
    public function register(array $data): void;
}
