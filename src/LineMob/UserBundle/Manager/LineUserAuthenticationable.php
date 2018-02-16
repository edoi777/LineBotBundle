<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Manager;


interface LineUserAuthenticationable extends LineUserContextManagerInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login(string $username, string $password): bool;

    /**
     * @return void
     */
    public function logout(): void;
}
