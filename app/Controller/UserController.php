<?php

namespace GroupDuaPBD\Management\Login\Php\Controller;

use GroupDuaPBD\Management\Login\Php\Config\View;
use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Exception\ValidationException;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterRequest;
use GroupDuaPBD\Management\Login\Php\Reposittory\UserRepository;
use GroupDuaPBD\Management\Login\Php\Service\UserService;

class UserController
{
    private  UserService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
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

}