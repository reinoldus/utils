<?php

namespace Reinoldus\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class RouteCheck extends AbstractHelper {
    private $sm;

    /**
     * @param \Zend\Mvc\Application $app
     */
    public function __construct($app) {
        $this->app = $app;
        $this->sm = $app->getServiceManager();
    }

    public function getRoute() {
        return $this->app->getMvcEvent()->getRouteMatch();
    }

    public function isHere($route) {
        if(!isset($route["return"])) {
            $return = array("active", "");
        } else {
            $return = $route["return"];
        }
        $params = $this->app->getMvcEvent()->getRouteMatch()->getParams();

        if($route['name'] != $this->app->getMvcEvent()->getRouteMatch()->getMatchedRouteName()) {
            return $return[1];
        }

        if(isset($params['action']) && isset($route['action'])) {
            if($route['action'] != $params['action']) {
                return $return[1];
            }
        }

        return $return[0];
    }

    public function __invoke($func, $param) {

        return $this->{$func}($param);
    }
}
