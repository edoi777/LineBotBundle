<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

class LineAuditInput implements LineAuditInputInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var LineUserInterface
     */
    protected $user;

    /**
     * @var int
     */
    protected $hit = 0;

    /**
     * @var string|null
     */
    protected $keyword;

    /**
     * @var string
     */
    protected $type;

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
    public function getUser(): ?LineUserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(?LineUserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getHit(): int
    {
        return $this->hit;
    }

    /**
     * {@inheritdoc}
     */
    public function setHit(int $hit)
    {
        $this->hit = $hit;
    }

    /**
     * {@inheritdoc}
     */
    public function hit(): void
    {
        $this->hit++;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * {@inheritdoc}
     */
    public function setKeyword(?string $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(?string $type)
    {
        $this->type = $type;
    }
}
