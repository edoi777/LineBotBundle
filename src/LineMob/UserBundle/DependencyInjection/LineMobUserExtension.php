<?php

namespace LineMob\UserBundle\DependencyInjection;

use LineMob\UserBundle\Audit\LineInputAuditorInterface;
use LineMob\UserBundle\Manager\LineUserSecurityManagerInterface;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Webmozart\Assert\Assert;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class LineMobUserExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->registerResources('line_mob_user', $config['driver'], [], $container);

        $container->setParameter('line_mob_user.user.active_cmd_ttl', $config['active_cmd_ttl']);
        $container->setParameter('line_mob_user.user.allow_logout', $config['allow_logout']);
        $container->setParameter('line_mob_user.user.class', $config['user_class']);
        $container->setParameter('line_mob_user.config', $config);

        if (isset($config['manager'])) {
            $container->setAlias('line_mob_user.manager.user_security', $config['manager']);
        }

        $container->setParameter('line_mob_user.audit.is_by_user_mode', @$config['audit_mode'] === LineInputAuditorInterface::BY_USER_MODE);
        if (isset($config['audit_mode'])) {
            $container->getDefinition('line_mob_user.audit.line_input_auditor')->replaceArgument(3, $config['audit_mode']);
        }
    }
}
