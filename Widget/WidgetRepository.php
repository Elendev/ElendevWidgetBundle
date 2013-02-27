<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Elendev\WidgetBundle\Widget;
/**
 * Description of WidgetManager
 *
 * @author jonas
 */
class WidgetRepository {
    
    private $widgets = array();
    
    
    public function registerWidget($service, $method, $tag, $priority = null){
        
        $widget = new Widget($service, $method, $tag, $priority);
        
        if(!array_key_exists($tag, $this->widgets)){
            $this->widgets[$tag] = array();
        }
        
        $this->widgets[$tag][] = $widget;
        
        //reorder widgets
        usort($this->widgets[$tag], function(Widget $a, Widget $b){
            if($a->getPriority() == $b->getPriority()){
                return 0;
            }else if($a->getPriority() > $b->getPriority()){
                return -1;
            }else{
                return 1;
            }
        });
    }
    
    
    
    public function getWidgets($tag){
    	if(!array_key_exists($tag, $this->widgets)){
    		return array();
    	}
        return $this->widgets[$tag];
    }
}
