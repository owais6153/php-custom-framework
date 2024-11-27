<?php
namespace App\Includes;

class HttpRequest{   
    public $uri;
    public $method;
    public $data;

    public function __construct() {
        $this->uri =urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        $this->method = $_SERVER['REQUEST_METHOD'];;
        $this->data = $_REQUEST;
    }

    public function getURI(){
        return $this->uri;
    }

    public function getMethod(){
        return $this->method;
    }

    public function getData(){
        return $this->data;
    }
}