<?php

namespace GroupDuaPBD\Management\Login\Php\Middleware{

    require_once __DIR__ . '/../Helper/helper.php';

    use GroupDuaPBD\Management\Login\Php\Config\Database;
    use GroupDuaPBD\Management\Login\Php\Domain\Session;
    use GroupDuaPBD\Management\Login\Php\Repository\SessionRepository;
    use GroupDuaPBD\Management\Login\Php\Domain\User;
    use GroupDuaPBD\Management\Login\Php\Repository\UserRepository;
    use GroupDuaPBD\Management\Login\Php\Service\SessionService;
    use PHPUnit\Framework\TestCase;

    class MustLoginMiddlewareTest extends TestCase
    {
        private MustLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp(): void
        {
            $this->middleware = new MustLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();
        }

        public function testBeforeGuest()
        {
            $this->middleware->before();

            $this->expectOutputRegex("[Location: /users/login]");
        }
        public function testBeforeLoginUser()
        {
            $user = new User();
            $user->id = "eko";
            $user->name = "Eko";
            $user->password = "rahasia";
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqId();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->middleware->before();
            $this->expectOutputString("");
        }
    }
}

