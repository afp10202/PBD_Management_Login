<?php

namespace GroupDuaPBD\Management\Login\Php\Repository;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\Session;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->userRepository->deleteAll();
        $this->sessionRepository->deleteAll();

        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);
    }

    public function testSaveSucess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'eko';

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);


    }

    public function testDeleteByIdSucess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'eko';

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($session->id);

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);

    }

    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById('notfound');
        self::assertNull($result);
    }


}
