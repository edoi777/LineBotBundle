<?php

namespace LineMob\KeywordBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use LineMob\KeywordBundle\Model\AdditionalKeywordInterface;
use LineMob\KeywordBundle\Model\Keyword;
use LineMob\KeywordBundle\Model\KeywordInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class KeywordsUniqueValidator extends ConstraintValidator
{
    /**
     * @var RepositoryInterface
     */
    public $keywordRepository;

    /**
     * @var EntityManager
     */
    public $keywordManager;

    /**
     * @var RepositoryInterface
     */
    public $additionalKeywordRepository;

    /**
     * @var EntityManager
     */
    public $additionalKeywordManager;

    public function __construct(
        RepositoryInterface $keywordRepository,
        EntityManager $keywordEntityManager,
        RepositoryInterface $additionalKeywordRepository,
        EntityManager $additionalKeywordManager
    )
    {
        $this->keywordRepository = $keywordRepository;
        $this->keywordManager = $keywordEntityManager;
        $this->additionalKeywordRepository = $additionalKeywordRepository;
        $this->additionalKeywordManager = $additionalKeywordManager;
    }

    /**
     * @param mixed $value
     * @param Constraint|KeywordsUnique $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        $object = $this->context->getObject();

        /** @var KeywordInterface $keyword */
        $keyword = $this->keywordRepository->findOneBy(['word' => $value]);

        if (!$keyword) {
            /** @var AdditionalKeywordInterface $additionalKeyword */
            $keyword = $this->additionalKeywordRepository->findOneBy(['word' => $value]);
        }

        if (!$keyword) {
            return;
        }

        if ($keyword->getId() === $object->getId()) {
            return;
        }

        $builder = $this->context->buildViolation($constraint->message);
        $builder->addViolation();
    }
}
