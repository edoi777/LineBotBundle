<?php

declare(strict_types=1);

namespace LineMob\Behat\Context;

use Behat\Behat\Context\Context;
use LineMob\Behat\Service\SharedStorageInterface;

final class LineBotTransformContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(SharedStorageInterface $sharedStorage)
    {
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Transform ข้อความจากไลน์บอท
     */
    public function getResponse()
    {
        return $this->sharedStorage->get('lineResponse');
    }
}
