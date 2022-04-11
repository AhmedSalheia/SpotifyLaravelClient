<?php

namespace SpotifyClient\Spotify;

class AbstractClient
{
    protected static $clientId;
    protected static $clientSecret;
    protected static $redirectUrl;

    private static $instances = [];
    protected static $user = null;
    private static $token = '';

    private string $url = "https://api.spotify.com/v1/";

    public function __construct()
    {
        self::$clientId = env('SPOTIFY_CLIENT_ID');
        self::$clientSecret = env('SPOTIFY_CLIENT_SECRET');
        self::$redirectUrl = env('SPOTIFY_REDIRECT_URI');

        $this->checkUser();
    }

    public static function makeInstance($args)
    {
        if (empty(self::$instances[static::class]))
            self::$instances[static::class] = new static(...$args);
        return self::$instances[static::class];
    }
    private function checkUser()
    {
        var_dump(request()->session()->get('SpotifyUserData'));
        self::$user = request()->session()->get('SpotifyUserData');
    }

    protected function isValidState() : bool
    {
        return request()->session()->has('state') && request()->session()->get('state')===request('state');
    }

    public function token() {
        return self::$token;
    }
    protected function updateToken($token)
    {
        self::$token = $token;
    }


    /**
     * @throws \Exception
     */
    public function sendRequest($type, $route, $data=[])
    {
        $route = trim($route,'/');
        return \Http::withToken($this->token())->$type($this->url.$route, $data)->json();
    }

}
