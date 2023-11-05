<?php

namespace GroupDuaPBD\Management\Login\Php\Controller;

use GroupDuaPBD\Management\Login\Php\Config\View;
use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Exception\ValidationException;
use GroupDuaPBD\Management\Login\Php\Model\UserLoginRequest;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterRequest;
use GroupDuaPBD\Management\Login\Php\Repository\SessionRepository;
use GroupDuaPBD\Management\Login\Php\Repository\UserRepository;
use GroupDuaPBD\Management\Login\Php\Service\SessionService;
use GroupDuaPBD\Management\Login\Php\Service\UserService;

class UserController
{
    private  UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function register(){
        View::render('User/register',[
            'title' => 'Register new User'
        ]);
    }

    public function postRegister(){
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        }catch (ValidationException $exception){
            View::render('User/register',[
                'title' => 'Register new User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function login()
    {
        View::render("User/Login", [
            "title" => "Login user"
        ]);

    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password =$_POST['password'];

        try {
           $response = $this->userService->login($request);

           $this->sessionService->create($response->user->id);
            View::redirect('/');
        }catch (validationException $exception){
            View::render('User/login',[
                'title' => 'Login user',
                'error' => $exception->getMessage()
            ]);

        }



    }


}