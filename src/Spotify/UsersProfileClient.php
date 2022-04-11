<?php

namespace SpotifyClient\Spotify;

class UsersProfileClient extends AbstractClient
{
    private string $userId;
    public function __construct($userId=null)
    {
        parent::__construct();
        $this->userId = $userId;
    }

    public function me()
    {
        return (new AuthClient)->user();
    }

    public function user()
    {
        if ($this->userId===null)
            throw new \RuntimeException('Must Provide UserId',400);

        return $this->sendRequest('get', '/users/'.$this->userId);
    }
}
