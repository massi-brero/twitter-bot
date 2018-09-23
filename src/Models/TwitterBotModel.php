<?php

namespace TwitterBot\Models;

use Codebird\Codebird;
use Symfony\Component\Dotenv\Dotenv;

class TwitterBotModel
{
    private $codebird;
    private $followedUsers = [];

    /**
     * TwitterBotModel constructor.
     * @param Codebird $cb
     */
    public function __construct(Codebird $cb)
    {
        $this->codebird = $cb;
        $setUsers = getenv('FOLLOWED_USERS');

        if (!empty($setUsers))
        {
            $this->followedUsers = explode(
                ',',
                $setUsers
            );
        }
    }

    /**
     * if FOLLOWED_USERS in .env is not empty only thos users' mentions will be returned
     * @return array
     */
    public function getMentions() : array
    {
        $tweets = [];
        $this->codebird->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
        $mentions = $this->codebird->statuses_mentionsTimeline();

        foreach ($mentions as $mention)
        {
            if (isset($mention['id'])
                && (empty($this->followedUsers) || in_array($mention['user']['screen_name'], $this->followedUsers)))
            {
                $tweet = new Tweet();
                $tweet->setId($mention['id']);
                $tweet->setUserScreenname($mention['user']['screen_name']);
                $tweet->setText($mention['text']);
                $tweets[] = $tweet;
            }
        }

        return $tweets;
    }

    /**
     * @return array string
     */
    public function getTweetTexts() : array
    {
        $tweets = $this->getMentions();
        return array_map(function ($tweet)
        {
            return $this->normalizeText(
                $tweet->getText()
            );
        }, $tweets);
    }

    /**
     * Removes @xy and trailing adresses from twitter texts.
     * @param string $text
     * @return string
     */
    public function normalizeText(string $text) : string
    {
        $result = '';

        if (!empty($text))
        {
            $pattern = "/(#\w* ?|@\w* ?)/";
            $result = preg_replace($pattern, '', $text);
        }

        return $result;
    }


}