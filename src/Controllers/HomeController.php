<?php

namespace App\Controllers;

use App\Controller;

class HomeController extends Controller
{

    public function indexAction()
    {
        return $this->render('index');
    }
}
