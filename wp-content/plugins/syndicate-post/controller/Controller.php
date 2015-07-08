<?php

/**
 * Description of Controller
 *
 * @author dkociuba
 */
abstract class Controller {

    const ROUTE_PARAMETER_NAME = 'route_param';

    private $routes = array();

    public function addRoute($route, $actionName, $method = 'GET') {
        $this->routes[] = array(
            'route' => $route,
            'actionName' => $actionName,
            'method' => $method
        );
    }

    public function getLinkToRoute($route, $noHeader = false, $data = array()) {
        $url = $this->currentPageURL();

        list($baseUrl, $getVariables) = explode('?', $url);
        
        $newGet['page'] = $_GET['page']; //rewrite wordpress param
        $newGet[self::ROUTE_PARAMETER_NAME] = $route;
        if ($noHeader) {
            $newGet['noheader'] = true;
        } 
        
        //rewriting $data to $_GET
        foreach ($data as $key => $value) {
            $newGet[$key] = $value;
        }

        return $baseUrl . '?' . http_build_query($newGet);
    }

    private function currentPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"])) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function handleExecution() {
        $method = $_SERVER['REQUEST_METHOD'];
        $route = filter_input(INPUT_GET, self::ROUTE_PARAMETER_NAME);
        if ($route === null) {
            $route = 'index';
        }
        $actionName = $this->getActionName($route, $method);
        if ($actionName === null) {
            throw new Exception("Route $route for method $method not found");
        }

        $this->runActionByName($actionName);
    }

    private function getActionName($route, $method) {
        foreach ($this->routes as $oneRoute) {
            if ($oneRoute['route'] === $route && $oneRoute['method'] === $method) {
                return $oneRoute['actionName'];
            }
        }
        return null;
    }

    private function runActionByName($name) {
        $functionName = 'action' . ucfirst($name);
        if (is_callable(array($this, $functionName))) {
            $this->$functionName();
        } else {
            throw new Exception("Action $functionName is not callable");
        }
    }

}
