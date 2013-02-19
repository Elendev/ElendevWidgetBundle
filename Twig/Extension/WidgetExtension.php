<?php

namespace Elendev\WidgetBundle\Twig\Extension;

use Elendev\WidgetBundle\Widget\WidgetRepository;

class WidgetExtension extends \Twig_Extension
{
    
    private $widgetRepository;
    private $environment;
   
    public function __construct(WidgetRepository $widgetRepository){
        $this->widgetRepository = $widgetRepository;
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(){
        return 'elendev_widget';
    }
    
    public function displayWidgets($tag){
        
        $args = func_get_args();
        array_shift($args);
        
        $widgetResults = array();
        
        foreach($this->widgetRepository->getWidgets($tag) as $widget){
            $widgetResults[] = call_user_func_array(array($widget, "doCall"), $args);
        }
        
        return $this->environment->render("ElendevWidgetBundle:Widget:list.html.twig", array("widgets" => $widgetResults, "tag" => $tag));
    }
    
    

   
    /**
     * {@inheritdoc}
     */
    public function getFunctions(){
        return array(
            'widgets' => new \Twig_Function_Method($this, 'displayWidgets', array('is_safe' => array('html'))),
        );
    }
    
    

    public function initRuntime(\Twig_Environment $environment){
        $this->environment = $environment;
    }
}
