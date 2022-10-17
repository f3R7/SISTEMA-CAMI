<?php

namespace App\Controllers;
header('Access-Control-Allow-Origin: *');
class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }
}
