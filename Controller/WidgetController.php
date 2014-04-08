<?php
/**
 * Created by PhpStorm.
 * User: jre
 * Date: 08.04.14
 * Time: 11:24
 */

namespace Elendev\WidgetBundle\Controller;


use Elendev\WidgetBundle\Widget\Widget;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class WidgetController implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function getWidgetAction($widgetId){
        $widget = $this->container->get('elendev.widget.widget_repository')->getWidget($widgetId);
        $result = $widget->doCall();

        if($result instanceof Response){
            return $result;
        } else {
            return new Response($result);
        }
    }


    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}