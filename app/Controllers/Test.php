<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function getXxx()
    {
        echo '123';
    }

    public function postZzz()
    {
        echo '123';
    }
}
