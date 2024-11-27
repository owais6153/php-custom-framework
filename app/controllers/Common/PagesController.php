<?php
namespace App\Controllers\Common;
use App\Controllers\Controller;

class PagesController extends Controller{
    public function home() {
        return $this->view('home');    
    }
}