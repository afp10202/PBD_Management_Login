<?php

namespace GroupDuaPBD\Management\Login\Php\Service;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use GroupDuaPBD\Management\Login\Php\Exception\ValidationException;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterRequest;
use GroupDuaPBD\Management\Login\Php\Reposittory\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected  function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public  function testRegisterSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id  = "eko";
        $request->name = "Eko";
        $request->password = "rahasia";

        $response = $this->userService->register($request);

        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->name, $response->user->name);
        self::assertNotEquals($request->password, $response->user->password);

        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id  = "";
        $request->name = "";
        $request->password = "";

        $this->userService->register($request);
    }

    public function testRegisterDuplicate()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "eko";
        $request->name = "Eko";
        $request->password = "rahasia";

        $this->userService->register($request);
    }

}
