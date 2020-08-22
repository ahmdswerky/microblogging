<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\TwitterAccount;
use Thujohn\Twitter\Facades\Twitter;
use Illuminate\Support\Facades\Config;
use App\Interfaces\MicrobloggingInterface;

class TwitterMicroblogging implements MicrobloggingInterface
{
    public $account;

    public function account($account)
    {
        $this->setAccount($account);

        Config::set('ttwitter.ACCESS_TOKEN', $this->account->access_token);
        Config::set('ttwitter.ACCESS_TOKEN_SECRET', $this->account->access_token_secret);
    }

    public function createOrUpdateUserAccount(User $user, array $data)
    {
        if ( $account = $user->twitterAccount ) {
            $account->update([
                'username' => array_key_exists('username', $data) ? $data['username'] : $account->username,
                'access_token' => array_key_exists('access_token', $data) ? $data['access_token'] : $account->access_token,
                'access_token_secret' => array_key_exists('access_token_secret', $data) ? $data['access_token_secret'] : $account->access_token_secret,
            ]);
            return $account;
        }

        return $user->twitterAccount()->create($data);
    }

    protected function setAccount($account)
    {
        if ( $account instanceof User ) {
            $this->account = $account->twitterAccount;
        } else {
            $this->account = TwitterAccount::where('account_id', $account)->first();
        }

        if ( is_null($this->account) ) {
            throw new \Exception('Account doesn\'t exist');
        }
    }

    public function timeline(array $options)
    {
        return Twitter::getHomeTimeline($options, ['format' => 'object']);
    }

    public function show($id)
    {
        return Twitter::getTweet($id, ['format' => 'object']);
    }

    public function publish(array $data)
    {
        return Twitter::postTweet($data, ['format' => 'object']);
    }

    public function delete($id)
    {
        try {
            Twitter::destroyTweet($id);
        } catch (\Throwable $th) {
            switch (true) {
                case Str::startsWith($th->getMessage(), '[183]'):
                    abort(401, __('errors.twitter.unauthorized_delete'));

                case Str::startsWith($th->getMessage(), '[144]'):
                    abort(404, __('errors.twitter.not_found'));

                default:
                    abort(400, __('errors.wrong'));
                    break;
            }
        }
    }

    public function follow($user)
    {
        return Twitter::postFollow(['screen_name' => $user]);
    }
}
