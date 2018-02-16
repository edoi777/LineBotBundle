<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface LineAuditInputInterface extends ResourceInterface, TimestampableInterface
{
    /**
     * @return LineUserInterface
     */
    public function getUser(): ?LineUserInterface;

    /**
     * @param LineUserInterface $userId
     */
    public function setUser(?LineUserInterface $userId);

    /**
     * @return int
     */
    public function getHit(): int;

    /**
     * @param int $hit
     */
    public function setHit(int $hit);

    /**
     * @return void
     */
    public function hit(): void;

    /**
     * @return string|null
     */
    public function getKeyword(): ?string;

    /**
     * @param string|null $keyword
     */
    public function setKeyword(?string $keyword);

    /**
     * @return string
     */
    public function getType(): ?string;

    /**
     * @param string $type
     */
    public function setType(?string $type);
}
