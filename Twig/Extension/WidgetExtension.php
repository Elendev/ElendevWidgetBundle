<?php

namespace Elendev\WidgetBundle\Twig\Extension;

use Elendev\WidgetBundle\Widget\WidgetRepository;
use Symfony\Component\HttpFoundation\Response;

class WidgetExtension extends \Twig_Extension
{
    
    private $widgetRepository;
    private $environment;
    private $force;

    public function __construct(WidgetRepository $widgetRepository, $force){
        $this->widgetRepository = $widgetRepository;
        $this->force = $force;
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

        if($this->force == 'enabled') {
            return $this->displayAsyncWidgets($tag, func_get_args());
        }

        $args = func_get_args();
        array_shift($args);
        array_unshift($args, $this->environment);
        $widgetResults = array();

        foreach($this->widgetRepository->getWidgets($tag) as $widget){
            $result = call_user_func_array(array($widget, "doCall"), $args);
            if($result instanceof Response){
                $result = $result->getContent();
            }
            $widgetResults[] = $result;
        }

        return $this->environment->render("ElendevWidgetBundle:Widget:list.html.twig", array("widgets" => $widgetResults, "tag" => $tag));
    }

    public function displayAsyncWidgets($tag){
        if($this->force == 'disabled') {
            return $this->displayWidgets($tag, func_get_args());
        }

        $args = func_get_args();
        array_shift($args);

        return $this->environment->render("ElendevWidgetBundle:Widget:asynchronousList.html.twig", array("widgets" => $this->widgetRepository->getWidgets($tag), "args" => $args, "tag" => $tag));
    }
    
    

   
    /**
     * {@inheritdoc}
     */
    public function getFunctions(){
        return array(
            'widgets' => new \Twig_Function_Method($this, 'displayWidgets', array('is_safe' => array('html'))),
            'widgets_async' => new \Twig_Function_Method($this, 'displayAsyncWidgets', array('is_safe' => array('html'))),
        );
    }
    
    

    public function initRuntime(\Twig_Environment $environment){
        $this->environment = $environment;
    }
}
