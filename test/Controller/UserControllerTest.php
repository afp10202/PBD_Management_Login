<?php
namespace GroupDuaPBD\Management\Login\Php\App {

    function header(string $value){
        echo $value;
    }
}

namespace GroupDuaPBD\Management\Login\Php\Controller{

    use GroupDuaPBD\Management\Login\Php\Config\Database;
    use GroupDuaPBD\Management\Login\Php\Domain\User;
    use GroupDuaPBD\Management\Login\Php\Reposittory\UserRepository;
    use PHPUnit\Framework\TestCase;

    class UserControllerTest extends TestCase
    {
        private UserController $userController;
        private UserRepository $userRepository;
        protected function setUp():void
        {
            $this->userController = new UserController();

            $userRepository = new UserRepository(Database::getConnection());
            $userRepository->deleteAll();

            putenv("mode=test");
        }
        public  function testRegister()
        {
            $this->userController->register();

            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Register new user]");
        }

        public function testPostRegisterSuccess()
        {
            $_POST['id'] = 'eko';
            $_POST['name'] = 'Eko';
            $_POST['password'] = 'rahasia';

            $this->userController->postRegister();

            $this->expectOutputRegex("[Location: /users/login]");
        }

        public function testPostRegisterValidationError()
        {
            $_POST['id'] = '';
            $_POST['name'] = 'Eko';
            $_POST['password'] = 'rahasia';

            $this->userController->postRegister();

            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Register new user]");
            $this->expectOutputRegex("[Id, Name, Password can not blank]");


        }

        public function testPostRegisterDuplicate()
        {
            $user = new User();
            $user->id = "eko";
            $user->name = "Eko";
            $user->password = "rahasia";

            $this->userRepository->save($user);

            $_POST['id'] = '';
            $_POST['name'] = 'Eko';
            $_POST['password'] = 'rahasia';

            $this->userController->postRegister();

            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Register new user]");
            $this->expectOutputRegex("[User Id already exists]");

        }
    }
}


