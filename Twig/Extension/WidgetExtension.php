<?php

namespace Elendev\WidgetBundle\Twig\Extension;

use Elendev\WidgetBundle\Widget\WidgetRepository;
use Symfony\Component\HttpFoundation\Response;

class WidgetExtension extends \Twig_Extension
{
    
    private $widgetRepository;
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
    
    public function displayWidgets(\Twig_Environment $environment, $tag){

        if($this->force == 'enabled') {
            return $this->displayAsyncWidgets($tag, func_get_args());
        }

        $args = func_get_args();
        $widgetResults = array();

        foreach($this->widgetRepository->getWidgets($tag) as $widget){
            $result = call_user_func_array(array($widget, "doCall"), $args);
            if($result instanceof Response){
                $result = $result->getContent();
            }
            $widgetResults[] = $result;
        }

        return $environment->render("ElendevWidgetBundle:Widget:list.html.twig", array("widgets" => $widgetResults, "tag" => $tag));
    }

    public function displayAsyncWidgets(\Twig_Environment $environment, $tag){
        if($this->force == 'disabled') {
            return $this->displayWidgets($tag, func_get_args());
        }

        $args = func_get_args();
        array_shift($args);

        return $environment->render("ElendevWidgetBundle:Widget:asynchronousList.html.twig", array("widgets" => $this->widgetRepository->getWidgets($tag), "args" => $args, "tag" => $tag));
    }
    
    

   
    /**
     * {@inheritdoc}
     */
    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('widgets', [$this, 'displayWidgets'], array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('widgets_async', [$this, 'displayAsyncWidgets'], array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }
}
