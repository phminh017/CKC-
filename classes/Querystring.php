<?php
class Querystring
{
    public $controllerId = "site";
    public $actionId = "index";
    public $params = [];

    function __construct($config = [])
    {
        $route = array_shift($config);
        $route_array = explode("/", $route);
        if (strlen($route_array[0]) > 0) {
            $this->controllerId = strtolower($route_array[0]);
        }
        if (array_key_exists(1, $route_array) && strlen($route_array[1]) > 0) {
            $this->actionId = strtolower($route_array[1]);
        }
        $this->params = $config;
    }
    function toString()
    {
        $url = App::$url;
        $toString = "";
        if ($url->enablePrettyUrl == false) {
            //Hoat dong binh thuong theo dang r=controllerid/actionid&param1=value1&param2=values...
            $route = HTACCESSNAME;
            if (strlen($this->controllerId) > 0) {
                $route .=  "?r=" . $this->controllerId;
            }
            if (strlen($this->actionId) > 0) {
                $route .=  "/" . $this->actionId;
            }
            $strParams = '';
            foreach ($this->params as $key => $value) {
                $strParams .= "&" . $key . "=" . $value;
            }
            $toString = $route . $strParams;
        } else {
            //chuyển sang dạng path to file
            $route = HTACCESSNAME;
            if (strlen($this->controllerId) > 0) {
                $route .=  "/" . $this->controllerId;
            }
            if (strlen($this->actionId) > 0) {
                $route .=  "/" . $this->actionId;
            }
            $strParams = '';
            foreach ($this->params as $key => $value) {
                $strParams .= "/" . $key . "/" . $value;
            }

            //so sánh với url.rules để rút gọn path to file
            
            $toString = $route . $strParams . $url->suffix;
        }
        return $toString;
    }
}
