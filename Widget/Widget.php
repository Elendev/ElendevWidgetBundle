<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Elendev\WidgetBundle\Widget;
/**
 * Description of Widget
 *
 * @author jonas
 */
class Widget {

    private $uid;

    private $service;
    
    private $method;
    
    private $tag;
    
    private $priority;
    
    public function __construct($uid, $service, $method, $tag, $priority = null){
        $this->uid = $uid;
        $this->service = $service;
        $this->method = $method;
        $this->tag = $tag;
        $this->priority = $priority;
    }
    
    public function doCall(){
        return call_user_func_array(array($this->service, $this->method), func_get_args());
    }
    
    public function getService() {
        return $this->service;
    }

    public function setService($service) {
        $this->service = $service;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
    }
    
    public function getTag() {
        return $this->tag;
    }

    public function setTag($tag) {
        $this->tags = $tag;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}
