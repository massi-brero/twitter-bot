<?php
/**
 * Created by PhpStorm.
 * User: massi
 * Date: 21.09.18
 * Time: 22:53
 */

namespace TwitterBot\Models;


class Tweet
{
    private $id;
    private $userScreenname;
    private $text;

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return Tweet
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserScreenname() : string
    {
        return $this->userScreenname;
    }

    /**
     * @param string $userScreenname
     * @return $this
     */
    public function setUserScreenname(string $userScreenname)
    {
        $this->userScreenname = $userScreenname;
        return $this;
    }

    /**
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Tweet
     */
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }

}