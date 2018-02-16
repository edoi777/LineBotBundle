<?php

namespace Linemob\LinebotBundle\Tests\Bot\Core\Image;

use LineMob\LineBotBundle\Bot\Core\Image\ImagePathResolver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\KernelInterface;


final class ImagePathResolverTest extends KernelTestCase
{
    /**
     * @var \AppTestKernel
     */
    protected static $kernel;

    /**
     * @var ImagePathResolver
     */
    private $imageResolver;

    /**
     * @var string
     */
    private $mediaPath;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->imageResolver = $kernel->getContainer()->get('linemob.image_path_resolver');
        $this->mediaPath = $kernel->getContainer()->getParameter('phpmob.uploader.local_directory');
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function it_should_throw_when_calling_get_media_path_during_path_not_exist()
    {
        $this->imageResolver->getMediaPath('/some_path_has_no_in_dir', 'strip');
    }

    /**
     * @test
     */
    public function it_should_return_absolute_path_when_call_get_asset_path()
    {
        $path = $this->imageResolver->getAssetPath('/some_asset_path');
        $this->assertNotEquals(-1, strpos($path, '/some_asset_path'));
        $this->assertNotEquals(-1, strpos($path, 'http'));
    }

    /**
     * @test
     */
    public function it_should_return_cache_path_when_call_get_media_path()
    {
        $this->uploadTestFile();
        $path = $this->imageResolver->getMediaPath('/test.jpeg', 'strip');

        $this->assertNotEquals(-1, strpos($path, 'media/cache/strip/test.jpeg'));
    }

    /**
     * @test
     */
    public function it_should_return_cache_path_with_ngrok_url_when_parameter_ngroj_dev_url_is_defined()
    {
        $ngrokUrl = "https://test.ngrok.io";
        static::$kernel->setKernelModifier(function(KernelInterface $kernel) use ($ngrokUrl) {
            $kernel->getContainer()->set('linemob.image_path_resolver', new ImagePathResolver(
                $kernel->getContainer()->get('liip_imagine.cache.manager'),
                $kernel->getContainer()->get('liip_imagine.controller'),
                $kernel->getContainer()->get('assets.packages'),
                $kernel->getContainer()->get('twig.extension.httpfoundation'),
                $ngrokUrl
            ));
        });

        $this->uploadTestFile();
        $path = static::$kernel->getContainer()->get('linemob.image_path_resolver')->getMediaPath('/test.jpeg', 'strip');
        $this->assertEquals($ngrokUrl . '/media/cache/strip/test.jpeg', $path);
    }

    private function uploadTestFile()
    {
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'test.jpeg', $this->mediaPath . DIRECTORY_SEPARATOR . 'test.jpeg');
    }
}
