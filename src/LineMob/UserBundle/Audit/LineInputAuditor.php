<?php

namespace LineMob\UserBundle\Audit;

use Doctrine\ORM\EntityManagerInterface;
use LineMob\UserBundle\Model\LineAuditInputInterface;
use LineMob\UserBundle\Model\LineUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class LineInputAuditor implements LineInputAuditorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $mode;

    public function __construct(RepositoryInterface $repository, FactoryInterface $factory, EntityManagerInterface $em, string $mode = null)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->em = $em;
        $this->mode = $mode;
    }

    public function audit($keyword, $type, ?LineUserInterface $lineUser = null)
    {
        if (!$this->mode) {
            return;
        }

        $criteria = [
            'keyword' => $keyword,
        ];

        if ($this->mode === self::BY_USER_MODE) {
            if (!$lineUser) {
                throw new \InvalidArgumentException(sprintf('In order to use mode %s, lineUser cannot be null', self::BY_USER_MODE));
            }

            $criteria['user'] = $lineUser;
        }

        /** @var LineAuditInputInterface $audit */
        $audit = $this->repository->findOneBy($criteria) ?? $this->factory->createNew();

        if ($this->mode === self::BY_USER_MODE) {
            $audit->setUser($lineUser);
        }

        $audit->setType($type);
        $audit->setKeyword($keyword);

        $audit->hit();

        $this->em->persist($audit);
        $this->em->flush();
    }
}
