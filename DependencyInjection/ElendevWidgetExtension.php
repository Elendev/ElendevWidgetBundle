<?php

namespace Elendev\WidgetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ElendevWidgetExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('elendev.widget.widget_directory', $config['widget_directory']);
        $container->setParameter('elendev.widget.annotation.enabled', $config['enable_annotations']);
        $container->setParameter('elendev.widget.annotation.services.scan', $config['scan_services']);
        $container->setParameter('elendev.widget.annotation.widget_directory.scan', $config['scan_widget_directory']);

        $container->setParameter('elendev_widget.hinclude.behavior', $config['hinclude']['behavior']);
    }
}
