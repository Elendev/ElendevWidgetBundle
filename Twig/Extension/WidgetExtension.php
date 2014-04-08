<?php

namespace Elendev\WidgetBundle\Twig\Extension;

use Elendev\WidgetBundle\Widget\WidgetRepository;
use Symfony\Component\HttpFoundation\Response;

class WidgetExtension extends \Twig_Extension
{
    
    private $widgetRepository;
    private $environment;
    private $behavior;

    public function __construct(WidgetRepository $widgetRepository, $behavior){
        $this->widgetRepository = $widgetRepository;
        $this->behavior = $behavior;
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(){
        return 'elendev_widget';
    }
    
    public function displayWidgets($tag, $asynchronousInclude = null){
        
        $args = func_get_args();
        array_shift($args);
        array_unshift($args, $this->environment);
        $widgetResults = array();

        if($this->behavior == 'disabled'){
            $asynchronousInclude = false;
        } else if($this->behavior == 'enabled') {
            $asynchronousInclude = true;
        } else if($asynchronousInclude == null && $this->behavior == 'default_disabled'){
            $asynchronousInclude = false;
        } else if($asynchronousInclude == null && $this->behavior == 'default_enabled') {
            $asynchronousInclude = true;
        }

        if(!$asynchronousInclude){
            foreach($this->widgetRepository->getWidgets($tag) as $widget){
                $result = call_user_func_array(array($widget, "doCall"), $args);
                if($result instanceof Response){
                    $result = $result->getContent();
                }
                $widgetResults[] = $result;
            }

            return $this->environment->render("ElendevWidgetBundle:Widget:list.html.twig", array("widgets" => $widgetResults, "tag" => $tag));
        } else {
            return $this->environment->render("ElendevWidgetBundle:Widget:asynchronousList.html.twig", array("widgets" => $this->widgetRepository->getWidgets($tag), "tag" => $tag));
        }
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
