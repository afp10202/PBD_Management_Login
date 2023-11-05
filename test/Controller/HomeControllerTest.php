<?php

namespace GroupDuaPBD\Management\Login\Php\Controller;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\Session;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use GroupDuaPBD\Management\Login\Php\Repository\SessionRepository;
use GroupDuaPBD\Management\Login\Php\Repository\UserRepository;
use GroupDuaPBD\Management\Login\Php\Service\SessionService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp():void
    {
        $this->homeController = new HomeController();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }


    public function testGuest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Management]");

    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id ="eko";
        $user->name ="Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);

        $session = new Session();
        $session = uniqid();
        $session->userId= $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        $this->homeController->index();

        $this->expectOutputRegex("[Hello Eko]");
    }




}
