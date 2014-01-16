<?php

namespace Elendev\WidgetBundle;

use Symfony\Component\HttpKernel\Kernel;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Elendev\WidgetBundle\DependencyInjection\Compiler\WidgetsLoaderPass;

class ElendevWidgetBundle extends Bundle
{
	
	private $kernel;
	
	public function __construct(Kernel $kernel = null){
		$this->kernel = $kernel;
	}
	
    public function build(ContainerBuilder $container) {
        parent::build($container);
        
        $container->addCompilerPass(new WidgetsLoaderPass($this->kernel));
    }
}
