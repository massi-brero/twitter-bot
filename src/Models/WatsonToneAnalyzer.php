<?php
/**
 * Created by PhpStorm.
 * User: massi
 * Date: 23.09.18
 * Time: 16:07
 */

namespace TwitterBot\Models;
use GuzzleHttp\Client;



class WatsonToneAnalyzer implements Analyzer
{
    const SERVICE_PATH = 'tone-analyzer/api/v3/tone';
    const SERVICE_VERSION = 'version=2017-09-21';

    private $user = '';
    private $password = '';
    private $client;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->client = new Client([
             'base_uri' => getenv('WATSON_URI'),
             'timeout'  => 2.0
         ]);
    }

    public function setCredentials(string $user, string $pw) : void
    {
        if (empty($user) || empty($pw))
        {
            throw new \Exception("Please provide complete credential");
        }

        $this->user = $user;
        $this->password = $pw;
    }

    /**
     * Sends text to the Watsone tone analyzer and returns the result json;
     *
     * @param string $text
     * @return mixed
     */
    public function getAnalyzedText(string $text) : array
    {
        var_dump($this->user . ' ' . $this->password);
        $params = [
            'query' => [
                'version' => '2017-09-21',
                'text' => $this->normalizeText($text),
            ],
            'auth' => [
                $this->user,
                $this->password
            ]
        ];

        /*
         * We even send the result of an empty tweet;
         */
        $result = $this->client->get(self::SERVICE_PATH, $params)->getBody();

        if (empty($result))
        {
            throw new \Exception('Not expected answer from Watson API.');
        }

        return json_decode($result, true);
    }

    private function normalizeText(string $text) : string
    {
        return urlencode($text);
    }
}