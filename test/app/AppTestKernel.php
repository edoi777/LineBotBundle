<?php

declare(strict_types=1);

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppTestKernel extends Kernel
{
    private $kernelModifier = null;

    public function boot()
    {
        parent::boot();

        /** @var callable $kernelModifier */
        if ($kernelModifier = $this->kernelModifier) {
            $kernelModifier($this);
            $this->kernelModifier = null;
        };
    }

    public function setKernelModifier(callable $kernelModifier)
    {
        $this->kernelModifier = $kernelModifier;

        // We force the kernel to shutdown to be sure the next request will boot it
        $this->shutdown();
        $this->boot();
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Media
            new PhpMob\MediaBundle\PhpMobMediaBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            new League\Tactician\Bundle\TacticianBundle(),
            new LineMob\LineBotBundle\LineMobLineBotBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}
