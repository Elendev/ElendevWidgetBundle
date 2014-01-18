<?php

namespace Elendev\WidgetBundle\DependencyInjection\Compiler;

use Symfony\Component\Finder\Finder;

use Symfony\Component\HttpKernel\Kernel;

use Symfony\Component\DependencyInjection\Definition;

use Doctrine\Common\Annotations\AnnotationReader;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WidgetsLoaderPass implements CompilerPassInterface {
	private $kernel;
	
	public function __construct(Kernel $kernel = null){
		$this->kernel = $kernel;
	}
	
    public function process(ContainerBuilder $container) {
        
        $definition = $container->getDefinition("elendev.widget.widget_repository");
        
        $isAnnotationSupportEnabled = $container->getParameter('elendev.widget.annotation.enabled');
        $scanServices = $container->getParameter('elendev.widget.annotation.services.scan');
        $scanWidgetDirectory = $container->getParameter('elendev.widget.annotation.widget_directory.scan');
        
        if($isAnnotationSupportEnabled) {
        	$reader = new AnnotationReader();
        	
        	if($scanServices){
        		$this->tagWidgetServices($container, $reader);
        	}
        	
        	if($scanWidgetDirectory && $this->kernel){
        		$this->tagWidgetDirectory($container, $reader);
        	}
        }
        $this->processTaggedServices($container, $definition);
    }
    
    /**
     * Process tagged services
     * @param ContainerBuilder $container
     * @param Definition $definition
     */
    private function processTaggedServices(ContainerBuilder $container, Definition $definition){
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
    
    /**
     * Pass through widget directory to find Widget annotations
     * @param ContainerBuilder $container
     * @param AnnotationReader $reader
     * @param Definition $definition
     */
    private function tagWidgetDirectory(ContainerBuilder $container, AnnotationReader $reader) {
    	
    	$widgetDirectory = $container->getParameter('elendev.widget.widget_directory');
    	
    	foreach($this->kernel->getBundles() as $bundle){
    		$directory = $bundle->getPath() . '/' . $widgetDirectory;
    		
    		if(!file_exists($directory)){
    			continue;
    		}
    		
    		$finder = new Finder();
    		$finder->in($directory)->files()->name('*.php');
    		
    		$baseNamespace = $bundle->getNamespace() . '\\' . $widgetDirectory . '\\';
    		
    		foreach($finder as $file){
    			$className = $baseNamespace . $file->getBasename('.php');
    			
    			if(!class_exists($className)){
    				continue;
    			}
    			
    			$reflectionClass = new \ReflectionClass($className);
    			
    			$serviceClassDefinition = null;
    			$serviceClassId = 'elendev.widget.widget_directory_lists.' . str_replace('\\', '_', $className);
    			
    			foreach($reflectionClass->getMethods() as $reflectionMethod){
    				$annotation = $reader->getMethodAnnotation($reflectionMethod, 'Elendev\\WidgetBundle\\Annotation\\Widget');
    				if($annotation){
    					if(!$serviceClassDefinition){
    						$serviceClassDefinition = new Definition($className);
    						$container->setDefinition($serviceClassId, $serviceClassDefinition);
    							
    							
    						if($reflectionClass->implementsInterface('Symfony\\Component\\DependencyInjection\\ContainerAwareInterface')) {
    							$serviceClassDefinition->addMethodCall('setContainer', array(new Reference('service_container')));
    						}
    					}
    					
    					$serviceClassDefinition->addTag('elendev.widget', array(
    							'method' => $reflectionMethod->getShortName(),
	    						'tag' => $annotation->getTag(),
	    						'priority' => $annotation->getPriority()
    						)
    					);
    				}
    			}
    			
    		}
    	}
    }
    
    /**
     * pass through all services to find Widget annotations
     * @param ContainerBuilder $container
     * @param AnnotationReader $reader
     * @param Definition $definition
     */
    private function tagWidgetServices(ContainerBuilder $container, AnnotationReader $reader){
    	foreach($container->getServiceIds() as $serviceId){
    		if(!$container->hasDefinition($serviceId)){
    			continue;
    		}
    		
    	    $serviceDefinition = $container->getDefinition($serviceId);
	    	$serviceClass = $serviceDefinition->getClass();
	    	if(strlen($serviceClass) <= 0){
	    		continue;
	    	}
	    	
	    	//get real class name when it's a parameter
	    	if(substr($serviceClass, 0, 1) === '%') {
	    		$serviceClass = $container->getParameter(substr($serviceClass, 1, -1));
	    	}
	    	
	    	if(!class_exists($serviceClass)){
	    		continue;
	    	}
	    	
		    $reflectionClass = new \ReflectionClass($serviceClass);
		    
		    foreach($reflectionClass->getMethods() as $reflectionMethod){
		    	$annotation = $reader->getMethodAnnotation($reflectionMethod, 'Elendev\\WidgetBundle\\Annotation\\Widget');
		    	if($annotation){
		    		$serviceDefinition->addTag('elendev.widget', array(
		    				'method' => $reflectionMethod->getShortName(),
		    				'tag' => $annotation->getTag(),
		    				'priority' => $annotation->getPriority()
		    			)
		    		);
		    	}
		    }
    	}
    }
}