<?php

namespace GroupDuaPBD\Management\Login\Php\Controller;

use GroupDuaPBD\Management\Login\Php\App\View;
use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Reposittory\SessionRepository;
use GroupDuaPBD\Management\Login\Php\Reposittory\UserRepository;
use GroupDuaPBD\Management\Login\Php\Service\SessionService;

class HomeController
{

    private SessionService $sessionService;

    public function __construct()
    {
        $coonection = Database::getConnection();
        $sessionRepository = new SessionRepository($coonection);
        $userRepository = new UserRepository($coonection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    function index(){
       $user = $this->sessionService->current();
       if($user == null){
           View::render('Home/index', [
               "title" => "PHP Login Management"
           ]);
       }else{
           View::render('Home/dashboard', [
               "title" => "Dashboard",
               "user" => [
                   "name" => $user->name
               ]
           ]);

       }

    }
}