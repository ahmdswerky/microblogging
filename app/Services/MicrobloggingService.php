<?php

namespace App\Services;

use App\Models\User;
use App\Services\TwitterMicroblogging;
use App\Interfaces\MicrobloggingInterface;

class MicrobloggingService
{
    public $provider;

    public function __construct($provider)
    {
        switch ($provider) {
            case 'twitter':
                $this->provider = new TwitterMicroblogging;
                break;

            // default:
            //     throw new \Exception('This provider isn\'t allowed yet for microblogging');
        }

        // if ( $provider instanceof MicrobloggingInterface ) {
        //     $this->provider = $provider;
        // } else {
        //     throw new \Exception('This provider isn\'t allowed yet for microblogging');
        // }
    }

    public function createOrUpdateUserAccount(User $user, array $data)
    {
        return $this->provider->createOrUpdateUserAccount($user, $data);
    }

    public function account($account)
    {
        $this->provider->account($account);

        return $this;
    }

    public function timeline(array $options = [])
    {
        return $this->provider->timeline($options);
    }

    public function show($id)
    {
        return $this->provider->show($id);
    }

    public function publish(array $data)
    {
        return $this->provider->publish($data);
    }

    public function delete($id)
    {
        return $this->provider->delete($id);
    }

    public function follow($user)
    {
        return $this->provider->follow($user);
    }
}
