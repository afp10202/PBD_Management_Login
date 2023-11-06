<?php

namespace GroupDuaPBD\Management\Login\Php\Service;

use GroupDuaPBD\Management\Login\Php\Domain\Session;
use GroupDuaPBD\Management\Login\Php\Domain\User;
use GroupDuaPBD\Management\Login\Php\Repository\SessionRepository;
use GroupDuaPBD\Management\Login\Php\Repository\UserRepository;


class SessionService
{

    public static string $COOKIE_NAME = "X-PZN-SESSION";


    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(string $userId): Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId =$userId;

        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 24 * 30), "/");

        return $session;
    }

    public function destory()
    {
        $sesionId = $_COOKIE[self::$COOKIE_NAME] ??'';
        $this->sessionRepository->deleteById($sesionId);

        setcookie(self::$COOKIE_NAME, '',1, "/");
    }

    public function current(): ?User
    {
        $sesionId = $_COOKIE[self::$COOKIE_NAME] ??'';

        $session = $this->sessionRepository->findById($sesionId);
        if ($session== null){
            return null;
        }

       return $this->userRepository->findById($session->userId);
    }

}