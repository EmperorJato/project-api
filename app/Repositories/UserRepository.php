<?php

namespace App\Repositories;
use Exception;
use App\Models\User;
use Illuminate\Http\Response;
use App\Interfaces\CrudInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getAll(object $request) : array {
        $offset = $request->has('offset') ? $request->get('offset') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $search = $request->get('search');

        $users = User::where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', '=', $search . '%');
            }
        })
            ->limit($limit)
            ->offset(($offset - 1) * $limit)
            ->get()
            ->toArray();

        $data = [
            'total' => count($users),
            'records' => $users,
            'offset' => $offset,
            'limit' => $limit
        ];

        return $data;
    }

    public function getByID(int $id): ?User
    {
        $user = User::find($id);

        if (empty($user)) {
            throw new Exception("User does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    public function create(array $params): array
    {

        $user =  [
            'name'     => $params['name'],
            'email'    => $params['email'],
            'password' => Hash::make($params['password'])
        ];

        $create = User::create($user);

        if (!$create) {
            throw new Exception("User does not created, Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //return the newly created user
        return $create->fresh();
    }

    public function update(int $id, array $params): ?User
    {
        $user = $this->getById($id);

        $data = [
            'name' => $params['name'] ?? $user['name'],
            'email' => $params['email'] ?? $user['email'],
        ];

        $updated = $user->update($data);
        
        if ($updated) {
            $user = $this->getById($id);
        }

        return $user;
    }

    public function getAuthUser() : User {
        return Auth::user();
    }
}