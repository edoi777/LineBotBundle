<?php

namespace LineMob\UserBundle\DependencyInjection;

use LineMob\UserBundle\Audit\LineInputAuditorInterface;
use LineMob\UserBundle\Model\LineUser;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('line_mob_user');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->scalarNode('manager')->end()
                ->scalarNode('user_class')->defaultValue(LineUser::class)->end()
                ->booleanNode('stick_line_user')->defaultValue(false)->end()
                ->booleanNode('allow_logout')->defaultValue(true)->end()
                ->scalarNode('active_cmd_ttl')->defaultValue('+30 minutes')->end()
                ->enumNode('audit_mode')->values(array(LineInputAuditorInterface::ALL_MODE, LineInputAuditorInterface::BY_USER_MODE))->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
