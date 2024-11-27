<?php
namespace App\Controllers;
use Error;

class Controller{
    private $VIEW_DIR ;
    private $PAGE_404 ;
    public function __construct() {
        $this->VIEW_DIR = config('app.view_dir');
        $this->PAGE_404 = config('app.page_404');
    }

    function view( $fileName ){
        $filePath = $this->VIEW_DIR . $fileName;
        if (file_exists($filePath . ".php")) {
            require_once $filePath . ".php";
        } elseif (file_exists($filePath)) {
            require_once $filePath;
        } else {
            throw new Error("View not found", 404);
        }
    }    
    function show404(){
        if (file_exists($this->VIEW_DIR  . $this->PAGE_404 )) 
            require_once $this->VIEW_DIR  . $this->PAGE_404 ;
        else
            throw new Error("404 View not found " , 404);
    }
}