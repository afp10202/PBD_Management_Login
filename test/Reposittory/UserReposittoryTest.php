<?php

namespace GroupDuaPBD\Management\Login\Php\Reposittory;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use PHPUnit\Framework\TestCase;

class UserReposittoryTest extends TestCase
{
    private UserRepository $userReposittory;

    protected function setUp(): void
    {
        $this->userReposittory = new UserRepository(Database::getConnection());
        $this->userReposittory->deleteAll();;
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";

        $this->userReposittory->save($user);

        $result = $this->userReposittory->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userReposittory->findById("notfound");
        self::assertNull($user);
    }
}
