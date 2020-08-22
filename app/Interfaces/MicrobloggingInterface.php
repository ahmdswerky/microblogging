<?php

namespace App\Interfaces;

use App\Models\User;

interface MicrobloggingInterface
{
    public function createOrUpdateUserAccount(User $user, array $data);

    public function account($account);

    public function timeline(array $options);

    public function show($id);

    public function publish(array $data);

    public function delete($id);

    public function follow($user);
}
