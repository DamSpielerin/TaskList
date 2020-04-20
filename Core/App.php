<?php

use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;
use Whoops\Run as WhoopsRun;

class App
{
    protected $controller = 'TaskController';

    protected $method = 'taskListAction';

    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        $this->initWhoopsErrorHandler();
        if (!empty($url['query'])) {
            parse_str($url['query'], $this->params);
        }

        if (in_array($url['path'], array('login', 'logout'))) {
            $this->controller = 'LoginController';
            require_once '../Controller/LoginController.php';
        } else {
            require_once '../Controller/TaskController.php';
        }
        $this->controller = new $this->controller();
        if (isset($url['path'])) {
            if (method_exists($this->controller, $url['path'] . 'Action')) {
                $this->method = $url['path'] . 'Action';
            }
        }
        $this->getPostParameters();
        require_once '../Entity/AdminSession.php';
        session_start();
        $sessionAdmin = AdminSession::firstOrNew(['session_id' => session_id(), 'login' => 'admin']);
         define('IS_ADMIN', $sessionAdmin->is_logged);
         call_user_func([$this->controller, $this->method], $this->params);
    }

    public function initWhoopsErrorHandler()
    {
        $whoops = new WhoopsRun();
        $handler = new WhoopsPrettyPageHandler();

        $whoops->pushHandler($handler)->register();

        return $this;
    }

    public function getPostParameters()
    {
        if (is_array($_POST)) {
            $this->params['post'] = $_POST;
        }
    }

    public function parseUrl()
    {
        if ($_SERVER['REQUEST_URI']) {
            $a = parse_url($_SERVER['REQUEST_URI']);
            $a['path'] = substr($a['path'], 1);

            return $a;
        }
    }

}
