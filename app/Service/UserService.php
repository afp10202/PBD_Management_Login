<?php

namespace GroupDuaPBD\Management\Login\Php\Service;

use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use GroupDuaPBD\Management\Login\Php\Exception\ValidationException;
use GroupDuaPBD\Management\Login\Php\Model\UserLoginRequest;
use GroupDuaPBD\Management\Login\Php\Model\UserLoginResponse;
use GroupDuaPBD\Management\Login\Php\Model\UserProfileUpdateRequest;
use GroupDuaPBD\Management\Login\Php\Model\UserProfileUpdateResponse;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterRequest;
use GroupDuaPBD\Management\Login\Php\Model\UserRegisterResponse;
use GroupDuaPBD\Management\Login\Php\Repository\UserRepository;

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

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userReposittory->findById($request->id);
        if($user ==null ){
            throw new ValidationException ("Id or password is worng");
        }
        if(password_verify($request->password, $user->password)){
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;

        }else{
            throw new ValidationException ("Id or password is worng");
        }
        }


    private function validateUserLoginRequest(UserLoginRequest $request){

        if($request->id == null ||  $request->password == null ||
            trim($request->id) == "" || trim($request->password) == ""){
            throw new ValidationException("Id, Password can not blank");
        }

    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse{
        $this->validateUserProfileUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if($user == null){
                throw new ValidationException("User is not found");
            }

            $user->name = $request->name;
            $this->userRepository->save($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;

        }catch (\Exception $exception){
            Database::rollbackTransaction();
            throw $exception;

        }
    }
    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request){
    if($request->id == null ||  $request->password == null ||
        trim($request->id) == "" || trim($request->password) == ""){
        throw new ValidationException("Id, Password can not blank");
    }
}

}
