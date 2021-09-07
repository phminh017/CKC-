<?php
class App
{
    public $id = "appId";
    public $controllerNamespace = 'controllers';
    public $favicon = 'favicon.png';

    public $title = "App Title";
    public $layout = 'main';
    public $defaultControllerId = 'site';

    public static $db = NULL;
    public static $dbsession = NULL;
    public static $url = NULL;

    public $request = NULL;
    public $style = NULL;

    private $controller = NULL;

    public function __construct($appConfig = [])
    {
        if (array_key_exists('id', $appConfig)) {
            $this->id = $appConfig['id'];
        }
        if (array_key_exists('title', $appConfig)) {
            $this->title = $appConfig['title'];
        }
        if (array_key_exists('layout', $appConfig)) {
            $this->layout = $appConfig['layout'];
        }
        if (array_key_exists('defaultControllerId', $appConfig)) {
            $this->defaultControllerId = $appConfig['defaultControllerId'];
        }
        if (array_key_exists('controllerNamespace', $appConfig)) {
            $this->controllerNamespace = $appConfig['controllerNamespace'];
        }

        //new Style object
        if (array_key_exists('styleConfig', $appConfig)) {
            $styleConfig = $appConfig['styleConfig'];
            $this->style = new Style($styleConfig);
        }

        //Create Db Object
        self::$db = new Db($appConfig['dbConfig']);

        // Create Dbsession Object
        // self::$dbsession = new Dbsession($appConfig['dbsessionConfig']);
        self::$dbsession = new Dbsession();

        //Create Url Object
        self::$url = new Url();

        if (array_key_exists('urlConfig', $appConfig)) {
            $urlConfig = $appConfig['urlConfig'];
            self::$url = new Url($urlConfig);
        }
    }

    public function run()
    {
        $controllerId =  $this->defaultControllerId;
        $actionId = "index";

        //get controllerID & actionId from $_REQUEST
        $requestArray = $_REQUEST;
        if (array_key_exists('r', $requestArray)) {
            // $route = $requestArray['r'];
            $route = array_shift($requestArray);
            $route_array = explode("/", $route);
            if (strlen($route_array[0]) > 0) {
                $controllerId = $route_array[0];
            }
            if (array_key_exists(1, $route_array) && strlen($route_array[1]) > 0) {
                $actionId = $route_array[1];
            }
        }

        //get actionMethod's params
        $actionParams = $requestArray;

        $controllerConfig = [
            'app' => $this,
            'id' => $controllerId,
            'defaultActionId' => $actionId,
            'actionParams' => $actionParams,
        ];
        try {
            $classname = ucfirst($controllerId) . "Controller";
            $controller = new $classname($controllerConfig);
            if (method_exists($controller, "init")) {
                $controller->init();
            }
            $content = $controller->run();
            $this->controller = $controller;
            //dùng $app để sử dụng ở layout và view  truy xuất đến App;
            $app = $this;
            // run layout main.php
            if ($controller->getRenderType() == 0) {
                //render with layout
                require_once APPROOT . DS . "views" . DS . "layouts" . DS . $this->layout . ".php";
            } else {
                //renderPartial = render without layout
                echo $content;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    function loadHeader()
    {
        $this->loadFavicon();
        $this->style->loadMainCss();
    }
    function loadFavicon()
    {
        $href = ICONS . DS . $this->favicon;
        echo "<link rel='icon' href='$href' type='image/png'>";
    }
    function getDb()
    {
        return self::$db;
    }
    function getController()
    {
        return $this->controller;
    }
}
