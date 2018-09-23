<?php
/**
 * Created by PhpStorm.
 * User: massi
 * Date: 19.09.18
 * Time: 22:00
 */

namespace TwitterBot;


class TwitterConfig
{
    private $key;
    private $keysecret;
    private $token;
    private $tokenSecret;

    /**
     * @return mixed
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return TwitterConfig
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeysecret() : string
    {
        return $this->keysecret;
    }

    /**
     * @param mixed $keysecret
     * @return TwitterConfig
     */
    public function setKeysecret(string $keysecret)
    {
        $this->keysecret = $keysecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return TwitterConfig
     */
    public function setToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenSecret() : string
    {
        return $this->tokenSecret;
    }

    /**
     * @param mixed $tokenSecret
     * @return TwitterConfig
     */
    public function setTokenSecret(string $tokenSecret)
    {
        $this->tokenSecret = $tokenSecret;
        return $this;
    }
}