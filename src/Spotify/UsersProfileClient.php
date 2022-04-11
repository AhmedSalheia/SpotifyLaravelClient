<?php

namespace SpotifyClient\Spotify;

class UsersProfileClient extends AbstractClient
{
    private string $userId;
    public function __construct($userId=null)
    {
        var_dump($userId);
        parent::__construct();
        $this->userId = $userId;
    }

    public function me()
    {
        return (new AuthClient)->user();
    }

    public function user()
    {
        return $this->sendRequest('get', '/users/'.$this->userId);
    }
}
