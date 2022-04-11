<?php

namespace SpotifyClient\Spotify;

use InvalidArgumentException;

class AuthClient extends AbstractClient
{
    private $authUrl = "https://accounts.spotify.com/en/authorize";
    private $tokenUrl = "https://accounts.spotify.com/api/token";
    protected array $scopes = [];
    public function scopes($scopes): static
    {
        $this->scopes = array_unique(array_merge($this->scopes, (array)$scopes));
        return $this;
    }

    public function authorize(): string
    {
        request()->session()->put("state",\Str::random(6));
        $params = collect([
            'response_type' =>'code',
            'redirect_uri'  => self::$redirectUrl,
            'client_id'     => self::$clientId,
            'state'         => request()->session()->get('state'),
            'scope'         => implode('+', $this->scopes)
        ]);

        return $this->authUrl.'?'.$params->map(function($value, $key) {
                return $key.'='.$value;
            })->implode('&');
    }

    private function getToken($code)
    {
        $r = \Http::withHeaders(['Accept' => 'application/json'])
            ->bodyFormat('form_params')
            ->post($this->tokenUrl, [
                'grant_type' => 'authorization_code',
                'client_id' => self::$clientId,
                'client_secret' => self::$clientSecret,
                'redirect_uri' => self::$redirectUrl,
                'code' => $code,
            ]);
        if ($r->status()===400)
            throw new \RuntimeException('Authentication Code Expired', 400);

        return $r->json();
    }

    public function refresh_token(): void
    {
        $access_token = \Http::withHeaders(['Accept' => 'application/json'])
            ->withBasicAuth(self::$clientId,self::$clientSecret)
            ->bodyFormat('form_params')
            ->post($this->tokenUrl, [
                'grant_type' => 'refresh_token',
                'client_id' => self::$clientId,
                'client_secret' => self::$clientSecret,
                'redirect_uri' => self::$redirectUrl,
                'refresh_token' => $this->user()['token']['refresh_token'],
            ])
            ->json()['access_token'];
        $this->user()['token']['access_token'] = $access_token;
        $this->updateToken($access_token);
    }

    public function user()
    {
        if (self::$user)
            return self::$user;

        if (!$this->isValidState())
            throw new InvalidArgumentException('Invalid State Returned');

        $token_data = $this->getToken(request('code'));
        $this->updateToken($token_data['access_token']);

        self::$user = $this->sendRequest('get', 'me');
        self::$user['token'] = $token_data;

        request()->session()->put('SpotifyUserData',self::$user);
        return self::$user;
    }
}
