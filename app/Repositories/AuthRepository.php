<?php

namespace App\Repositories;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{

    public function login(array $data): array
    {

        $user = $this->getUserByEmail($data['email']);

        if (!$user) {
            throw new Exception("User does not exist.", Response::HTTP_NOT_FOUND);
        }


        if (!$this->isValidPassword($user, $data)) {
            throw new Exception("Password does not match.", Response::HTTP_UNAUTHORIZED);
        }

        $tokenInstance = $this->createAuthToken($user);

        return $this->getAuthData($user, $tokenInstance);
    }

    public function register(array $params): User
    {

        $data =  [
            'name'     => $params['name'],
            'email'    => $params['email'],
            'password' => Hash::make($params['password'])
        ];

        $user = User::create($data);

        if (!$user) {
            throw new Exception("User does not registered, Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //Attach User Role

        $userRole = Role::user()->first();

        $user->roles()->attach($userRole->id);

        //return the newly created user
        return $user->fresh();
    }

    /**
     * @param string $email
     * @return object|User|null
     */

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param User $user
     * @param array $data
     * @return NewAccessToken
     */

    //Check if the User Password is matched
    public function isValidPassword(User $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }


    /**
     * @param User $user
     * @return NewAccessToken
     */

    //Generate New Access Token
    public function createAuthToken(User $user): NewAccessToken
    {
        return $user->createToken('task-token', ['*'], now()->addWeek());
    }

    /**
     * @param User $user
     * @param NewAccessToken $tokenInstance
     * @return array
     */

    //Generate Access Token for Credentials

    public function getAuthData(User $user, NewAccessToken $tokenInstance): array
    {
        return [
            'user'         => $user,
            'access_token' => $tokenInstance->plainTextToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenInstance->accessToken->expires_at)->toDateTimeString()
        ];
    }
}
