<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Model;

use LineMob\Core\Storage\CommandDataInterface;
use PhpMob\CoreBundle\Model\WebUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface LineUserInterface extends ResourceInterface, CommandDataInterface, TimestampableInterface
{
    /**
     * @return null|WebUserInterface
     */
    public function getLoggedUser(): ?WebUserInterface;

    /**
     * @param null|WebUserInterface $user
     */
    public function setLoggedUser(?WebUserInterface $user);

    /**
     * @return null|WebUserInterface
     */
    public function getWebUserLocked(): ?WebUserInterface;

    /**
     * @param null|WebUserInterface $user
     */
    public function setWebUserLocked(?WebUserInterface $user);

    /**
     * @return string
     */
    public function getAuthState(): ?string;

    /**
     * @param string $authState
     */
    public function setAuthState(?string $authState);
}
