<?php
class View
{
    public $context = NULL; // controller Object gọi View

    public $viewFile = 'index.php';
    public $data = [];

    function __construct($viewConfig = [])
    {
        if (array_key_exists('context', $viewConfig)) {
            $this->context = $viewConfig['context'];
        }
        if (array_key_exists('view', $viewConfig)) {
            $this->viewFile = $viewConfig['view'] . ".php";
        }
        if (array_key_exists('data', $viewConfig)) {
            $this->data = $viewConfig['data'];
        }
    }
    function render()
    {
        //Tạo biến $GLOBALS để truy cập trong view.php
        foreach ($this->data as $key => $value) {
            $$key = $value;
        }

        // sử dụng output buffering để render view trong bộ nhớ, 
        // sau đó lấy kết quả dạng chuỗi trả về, đưa ra layout xuất kết quả

        ob_start();
        require_once APPROOT . DS . "views" . DS . $this->context->id . DS . $this->viewFile;
        $strRender = ob_get_contents();
        ob_end_clean();
        return $strRender;
    }
}
