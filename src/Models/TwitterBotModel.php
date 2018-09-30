<?php

namespace TwitterBot\Models;

use Codebird\Codebird;
use Symfony\Component\Dotenv\Dotenv;

class TwitterBotModel
{
    private $codebird;
    private $followedUsers = [];
    private $db;

    /**
     * TwitterBotModel constructor.
     * @param Codebird $cb
     * @param PDO|null $db
     * @throws \Exception
     */
    public function __construct(Codebird $cb, PDO $db = null)
    {
        $this->codebird = $cb;
        $setUsers = getenv('FOLLOWED_USERS');

        if (is_null($db))
        {
            $this->setDb();
        }

        if (!empty($setUsers))
        {
            $this->followedUsers = explode(
                ',',
                $setUsers
            );
        }
    }

    /**
     * @param \PDO|null $db If this is null a standard localhost database for testing purposes will be set.
     * @throws \Exception
     */
    public function setDb(\PDO $db = null)
    {

        try
        {
            if (empty($db))
            {
                $dbDetails = sprintf('mysql:host=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_NAME'));
                $this->db = new \PDO($dbDetails, getenv('DB_USER'), getenv('DB_PW'));
                $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            }
        } catch (\Exception $e)
        {
            throw $e;
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
        $mentions = $this->codebird->statuses_mentionsTimeline(
            $this->getSinceID()
        );

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

    /**
     * @param string $reply
     * @param Tweet $tweet
     * @param string $emotion
     * @param string $analyzer
     * @return mixed
     */
    public function saveReply(string $reply,
                              Tweet $tweet,
                              string $emotion,
                              string $analyzer) : bool
    {

        try
        {
            $stmt = sprintf('INSERT INTO %s VALUES' .
                     ' (NULL, :mention_id, :tweet, :emotion, :reply, :analyzer, NOW())', getenv('DB_MENTION_TABLE'));
            $sql = $this->db->prepare($stmt);
            $sql->bindParam('mention_id', $tweet->getId(), \PDO::PARAM_INT);
            $sql->bindParam('tweet', $tweet->getText(), \PDO::PARAM_STR);
            $sql->bindParam('emotion', $emotion, \PDO::PARAM_STR);
            $sql->bindParam('reply', $reply, \PDO::PARAM_STR);
            $sql->bindParam('analyzer', $analyzer, \PDO::PARAM_STR);
            return $sql->execute();
        } catch (\PDOException $e)
        {
            throw $e;
        }
    }

    public function replyToTweet(Tweet $tweet, string $text) : void
    {
        $responseText = sprintf('@%s %s', $tweet->getUserScreenname(), html_entity_decode($text), 0, 'UTF-8 ');

        $response = $this->codebird->statuses_update([
            'status' => $responseText,
            'in_reply_to_status_id' => $tweet->getId()
        ]);


        if (empty($response) || $response['httpstatus'] != 200) {
            $error = "Unknown error";
            if (is_array($response['errors']) && !empty($response['errors']))
            {
                $error = $response['errors'][0]['message'];
            }

            throw new \Exception($error);
        }
    }


    protected function getLastID() : string
    {
        try
        {
            $result = $this->db->query(sprintf('
                      SELECT mention_id FROM %s
                        ORDER BY mention_id
                        DESC LIMIT 1;
                    ', getenv('DB_MENTION_TABLE')))
                    ->fetch();
            return $result['mention_id'] ?? '';
        } catch (\PDOException $e)
        {
            throw $e;
        }
    }

    /**
     * @return string
     */
    protected function getSinceID(): string
    {
        $lastID = $this->getLastID();
        $lastIDParam = $lastID ? 'since_id=' . $lastID : '';
        return $lastIDParam;
    }


}