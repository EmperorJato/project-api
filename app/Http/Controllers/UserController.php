<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\ResponseTrait;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    use ResponseTrait;

    public function __construct(private UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function index()
    {

        try {

            return $this->responseSuccess($this->userRepository->getAll(request()), "Users fetched successfully");
        } catch (Exception $e) {

            return $this->responseError([], $e->getMessage(), $e->getCode());
        }
    }

    public function profile()
    {
        try {
            $profile = new UserResource($this->userRepository->getAuthUser());

            return $this->responseSuccess($profile, 'User fetched successfully');
            
        } catch (Exception $e) {

            return $this->responseError([], $e->getMessage(), $e->getCode());
        }
    }
}
