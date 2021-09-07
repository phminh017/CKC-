<?php
class Controller
{
    public $id = 'site';
    public $defaultActionId = "";

    public $action = NULL;
    public $actionParams = [];

    public $style = NULL;
    public $app = NULL;

    private $renderType = 0; // 0 - render; 1 - renderPartial

    function __construct($controllerConfig = [])
    {
        if (array_key_exists('app', $controllerConfig)) {
            $this->app = $controllerConfig['app'];
            $this->style = $this->app->style;
        }
        if (array_key_exists('id', $controllerConfig)) {
            $this->id = $controllerConfig['id'];
        }
        if (array_key_exists('defaultActionId', $controllerConfig)) {
            $this->defaultActionId = $controllerConfig['defaultActionId'];
        }
        if (array_key_exists('actionParams', $controllerConfig)) {
            $this->actionParams = $controllerConfig['actionParams'];
        };
    }

    public function run()
    {
        $actionCofig = [
            'controller' => $this,
            'id' => $this->defaultActionId,
            'params' => $this->actionParams
        ];
        // tạo Action và run
        $this->action = new Action($actionCofig);
        $content = $this->action->run();
        return $content;
    }
    function render($view, $data = [])
    {
        //render with layouts
        $this->renderType = 0; 

        //Create View Object, render
        $viewConfig = [
            'context' => $this,
            'view' => $view,
            'data' => $data
        ];
        $viewObject = new View($viewConfig);
        return $viewObject->render();
    }
    function renderPartial($view, $data = [])
    {
        //renderPartial, render without layouts
        $this->renderType = 1; 

        $viewConfig = [
            'context' => $this,
            'view' => $view,
            'data' => $data
        ];
        $viewObject = new View($viewConfig);
        return $viewObject->render();
    }

    function redirect($url = [])
    {
        //redirect to local web
        $queryString = new Querystring($url);
        $href = $queryString->toString();
        $hostname = gethostname();
        $header = "/" . $href;
        header("Location: $header");
    }
    function redirectInternet($linkInternet = '')
    {
        //redirect to Internet
        $header = $linkInternet;
        header("Location: $header");
    }
    function getRenderType()
    {
        return $this->renderType;
    }
    function redirectDefault()
    {
        $this->redirect();
    }
    public function getApp()
    {
        return $this->app;
    }
}
