<?php 

class App {
    protected $url;

    protected $controller = DEFAULT_CONTROLLER;
    protected $method = DEFAULT_METHOD;
    protected $params = [];

    public function __construct()
    {
        $this->parseURL();
        
        // controller
        $this->setController();
        
        // method
        $this->setMethod();
        
        // params
        $this->setParams();
        
        // jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);

    }

    private function setController()
    {
        if ( isset($this->url[0]) ) {
            $controller = ucfirst($this->url[0]);
            if( file_exists('../app/controllers/' . $controller . '.php') ) {
                $this->controller = $controller;
                unset($this->url[0]);
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
    }

    private function setMethod()
    {
        if( isset($this->url[1]) ) {
            if( method_exists($this->controller, $this->url[1]) ) {
                $this->method = $this->url[1];
                unset($this->url[1]);
            }
        }
    }

    private function setParams()
    {
        if( !empty($this->url) ) {
            $this->params = array_values($this->url);
        }
    }

    public function parseURL()
    {
        if( isset($_GET['url']) ) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->url = $url;
        }
    }
}





