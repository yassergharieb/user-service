<?php

namespace App\Service\Implementation;

use App\Helpers\IPubSubPublisher;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Service\IAuthService;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthService implements IAuthService
{
    public function __construct(
        public IUserRepository $authRepository,
        public IPubSubPublisher $pubSubPublisher,
    ) {
    }

    public function register(array $data)
    {
        $userObj = $this->authRepository->createUser(
            $data['name'],
            $data['email'],
            $data['password'],
        );

        $this->pubSubPublisher->publish('user_registered', [
            'type' => 'user_registered',
            'user_id' => $userObj->id,
            'username' => $userObj->name,
        ]);

        return [
            'user' => $userObj,
            'token' => $this->generateJwtToken($userObj),
        ];
    }

    public function login(array $data)
    {
        $userObj = $this->authRepository->getUserByEmail($data['email']);
        if (!is_object($userObj)) {
            return [
                'error' => 'invalid email or password',
            ];
        }

        $passwordIsCorrect = Hash::check($data['password'], $userObj->password);
        if (!$passwordIsCorrect) {
            return [
                'error' => 'invalid email or password',
            ];
        }

        return [
            'user' => $userObj,
            'token' => $this->generateJwtToken($userObj),
        ];
    }

    public function logout(object|array $request)
    {
        $token = $request->header('authorization');
        if (!$token) {
            return [
                'error' => 'something went wrong',
            ];
        }

        //add centeral cache to add logged-out tokens
        Cache::set($token, 'not valid', 3600);

        return [
            'message' => 'logged out',
        ];
    }

    private function generateJwtToken(User $userObj)
    {
        return JWT::encode([
            'userId' => $userObj->id,
            'username' => $userObj->name,
            'expiredAt' => date("Y-m-d H:i:s", strtotime("+2 hour")),
        ], env('JWT_SECRET'), 'HS256');
    }

    //todo move this to INotesService
    public function sendMessageToUser()
    {
        var_dump("message is sent");
    }


    public function updateNotesCount(int $count , int $id) {
           $user =  $this->authRepository->getById($id);
           var_dump($user);
           if($user){
            $user->update(['notes_count' => $count]);
           }  

    }
}
