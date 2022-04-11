<?php

namespace SpotifyClient;


use Prophecy\Exception\Doubler\ClassNotFoundException;

class SpotifyClient
{
    public static function __callStatic($name, $params)
    {
        $class = "SpotifyClient\Spotify\\".ucfirst($name).'Client';
        if (class_exists($class))
            return $class::makeInstance($params);
        throw new ClassNotFoundException("Class {$class} Does Not Exist",$class);
    }
}
