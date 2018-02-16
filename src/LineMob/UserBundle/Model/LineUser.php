<?php

declare(strict_types = 1);

namespace LineMob\UserBundle\Model;

use LineMob\Core\Storage\CommandData;
use PhpMob\CoreBundle\Model\WebUserInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;


class LineUser extends CommandData implements LineUserInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var WebUserInterface
     */
    protected $loggedUser;

    /**
     * Use for stick_user mode.
     * @var WebUserInterface
     */
    protected $webUserLocked;

    /**
     * @var string
     */
    protected $authState;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoggedUser(): ?WebUserInterface
    {
        return $this->loggedUser;
    }

    /**
     * {@inheritdoc}
     */
    public function setLoggedUser(?WebUserInterface $user)
    {
        $this->loggedUser = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebUserLocked(): ?WebUserInterface
    {
        return $this->webUserLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function setWebUserLocked(?WebUserInterface $user)
    {
        $this->webUserLocked = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthState(): ?string
    {
        return $this->authState;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthState(?string $authState)
    {
        $this->authState = $authState;
    }
}
