<?php

namespace SpotifyClient\Spotify;

class PlaylistsClient extends AbstractClient
{
    private string $playlistId;
    private int $offset = 0;
    private int $limit = 20;

    public function __construct($playlistId=null)
    {
        parent::__construct();
        $this->playlistId = $playlistId;
    }
    public function offset(int $offset)
    {
        $this->offset =$offset;
        return $this;
    }
    public function limit(int $limit)
    {
        $this->limit =$limit;
        return $this;
    }

    /**
     * Get Current User's Playlists
     */
    public function list()
    {
        return $this->sendRequest('get', '/me/playlists', ['offset'=>$this->offset, 'limit'=>$this->limit]);
    }

    /**
     * Get Playlist Cover Image
     */
    public function image()
    {

    }

    /**
     * Get Playlist Items
     */
    public function items()
    {

    }

    /**
     * Get Playlist
     */
    public function playlist()
    {

    }

    /**
     * Get User's Playlists
     */
    public function users()
    {

    }
}
