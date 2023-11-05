<?php

namespace GroupDuaPBD\Management\Login\Php\Service;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\Session;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use GroupDuaPBD\Management\Login\Php\Repository\SessionRepository;
use GroupDuaPBD\Management\Login\Php\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

function setcookie(string $name, string $value){
    echo"$name: $value";
}

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);

    }

    public function testCreate()
    {

       $session = $this->sessionService->create("eko");

        $this->expectOutputRegex("[X-PZN-SESSION: $session->id]");

       $result = $this->sessionRepository->findById($session->id);

        self::assertEquals("eko", $result->userId);

    }

    public function testDestory()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId= "eko";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destory();

        $this->expectOutputRegex("[X-PZN-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId= "eko";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);



    }


}





