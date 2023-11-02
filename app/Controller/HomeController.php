<?php

namespace GroupDuaPBD\Management\Login\Php\Controller;

use GroupDuaPBD\Management\Login\Php\App\View;

class HomeController
{
    function index()
    {
        View::render('Home/index', [
            "title" => "PHP Login Management"
        ]);
    }
}