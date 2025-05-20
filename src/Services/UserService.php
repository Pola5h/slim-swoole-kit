<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function get($id)
    {
        return User::find($id);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        if (!$user) return null;
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) return false;
        return $user->delete();
    }
}
