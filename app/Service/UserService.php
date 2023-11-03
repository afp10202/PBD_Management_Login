<?php

namespace GroupDuaPBD\Management\Login\Php\Service;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use GroupDuaPBD\Management\Login\Php\Exception\ValidationException;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterRequest;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterResponse;
use GroupDuaPBD\Management\Login\Php\Reposittory\UserRepository;

class UserService
{
    private  UserRepository $userReposittory;

    public function __construct(UserRepository $userReposittory)
    {
        $this->userReposittory = $userReposittory;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userReposittory->findById($request->id);
            if($user != null){
                throw new ValidationException("User Id already exixts");
        }

        $user = new User();
        $user->id = $request->id;
        $user->name = $request->name;
        $user->password = password_hash($request->password, PASSWORD_BCRYPT);

        $this->userReposittory->save($user);

        $response = new UserRegisterResponse();
        $response ->user = $user;

        Database::commitTransaction();
        return $response;
    }catch (\Exception $exception){
        Database::rollbackTransaction();
        throw $exception;
        }
    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if($request->id == null || $request->name == null || $request->password == null ||
            trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == ""){
            throw new ValidationException("Id, Name, Password can not blank");
        }
    }
}