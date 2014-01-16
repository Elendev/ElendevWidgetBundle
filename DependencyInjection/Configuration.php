<?php

namespace Elendev\WidgetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elendev_widget');

        $rootNode
        	->children()
        		->booleanNode('enable_annotations')
        			->defaultTrue()
        		->end()
        		->booleanNode('scan_services')
        			->defaultTrue()
        		->end()
        		->booleanNode('scan_widget_directory')
        			->defaultTrue()
        		->end()
        		->scalarNode('widget_directory')
        			->defaultValue('Widget')
        		->end()
        	->end();

        return $treeBuilder;
    }
}
