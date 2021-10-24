<?php

namespace App\Controllers;
use Modules\About\Controllers\About;
class Home extends BaseController
{
    public function index()
    {
        $about = new About();
         $about->index();
        //return view('welcome_message');
    }
}
