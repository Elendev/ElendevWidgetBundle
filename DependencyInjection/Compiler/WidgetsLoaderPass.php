<?php

namespace Elendev\WidgetBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WidgetsLoaderPass implements CompilerPassInterface {
    public function process(ContainerBuilder $container) {
        
        $definition = $container->getDefinition("elendev.widget.widget_repository");
        
        foreach($container->findTaggedServiceIds("elendev.widget") as $id => $tags){
            foreach($tags as $attributes){
                $priority = array_key_exists("priority", $attributes) ? $attributes["priority"] : null;
            
                $definition->addMethodCall("registerWidget", array(
                    new Reference($id),
                    $attributes["method"],
                    $attributes["tag"],
                    $priority
                ));
            }
        }
        
    }
}