<?php
class Action
{
    public $id;
    public $controller = NULL;
    public $params = [];

    public function __construct($actionCofig)
    {
        if (array_key_exists('controller', $actionCofig)) {
            $this->controller = $actionCofig['controller'];
        }
        if (array_key_exists('id', $actionCofig)) {
            $this->id = $actionCofig['id'];
        }
        if (array_key_exists('params', $actionCofig)) {
            $this->params = $actionCofig['params'];
        }
    }
    public function run()
    {
        if (strlen($this->id) == 0) {
            // Nếu không truyền vào id thì id = index
            $this->id = "index";
        }
        $method = "action" . ucfirst($this->id);
        //Tìm đến method của Controller tương ứng 
        // và truyền tham số vào để thực hiện
        return call_user_func_array(array($this->controller, $method), $this->params);
    }
    public function getController()
    {
        return $this->controller;
    }
}
