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

    private $widgetUID = 0;

    private $uidWidget = array();
    
    public function registerWidget($service, $method, $tag, $priority = null){
        $this->widgetUID ++;

        $widget = new Widget($this->widgetUID, $service, $method, $tag, $priority);
        $this->uidWidget[$this->widgetUID] = $widget;

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


    /**
     * @param $tag
     * @return Widget[]
     */
    public function getWidgets($tag){
    	if(!array_key_exists($tag, $this->widgets)){
    		return array();
    	}
        return $this->widgets[$tag];
    }

    /**
     * @param $id widget UID
     * @return Widget
     */
    public function getWidget($id) {
        return $this->uidWidget[$id];
    }
}
