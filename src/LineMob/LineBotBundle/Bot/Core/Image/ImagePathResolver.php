<?php

declare(strict_types = 1);

namespace LineMob\LineBotBundle\Bot\Core\Image;

use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;

final class ImagePathResolver implements ImagePathResolverInterface
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var ImagineController
     */
    private $imagineController;

    /**
     * @var Packages
     */
    private $assetPackages;

    /**
     * @var HttpFoundationExtension
     */
    private $httpFoundationExtension;

    /**
     * @var string
     */
    private $ngrokUrl;

    public function __construct(
        CacheManager $cacheManager,
        ImagineController $imagineController,
        Packages $assetPackages,
        HttpFoundationExtension $httpFoundationExtension,
        $ngrokUrl = null
    )
    {
        $this->cacheManager = $cacheManager;
        $this->imagineController = $imagineController;
        $this->assetPackages = $assetPackages;
        $this->httpFoundationExtension = $httpFoundationExtension;
        $this->ngrokUrl = $ngrokUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaPath($path, $filter): string
    {
        $this->cacheResolve($path, $filter);

        $botPath = $this->cacheManager->getBrowserPath($path, $filter);

        // For dev server
        if ($this->ngrokUrl) {
            $botPath = $this->ngrokUrl . substr($botPath, strpos($botPath, '/media'));
        }

        return $botPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssetPath($path): string
    {
        $botPath = $this->assetPackages->getUrl($path);
        $botPath = $this->httpFoundationExtension->generateAbsoluteUrl($botPath);

        // For dev server
        if ($this->ngrokUrl) {
            $botPath = $this->ngrokUrl . '/' . substr($botPath, strpos($botPath, $path));
        }
 
        return $botPath;
    }

    /**
     * Force Cache Resolve
     *
     * @param string $path
     * @param string $filter
     */
    private function cacheResolve(string $path, string $filter)
    {
        $this->imagineController->filterAction(
            new Request(),
            $path,
            $filter
        );
    }
}
