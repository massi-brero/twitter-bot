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
    private const SERVICE_PATH = 'tone-analyzer/api/v3/tone';
    private const SERVICE_VERSION = '2017-09-21';
    const EMOTION_SAD = 'sadness';
    const EMOTION_FEAR = 'fear';
    const EMOTION_JOY = 'joy';
    const EMOTION_ANGER = 'sadness';
    const LANGUAGE_ANALYTICAL = 'analytical';
    const LANGUAGE_CONFIDENT = 'confident';
    const LANGUAGE_TENTATIVE = 'tentative';

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

    /**
     * @param string $user
     * @param string $pw
     * @throws \Exception
     */
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
     * Sends text to the Watson tone analyzer and returns the result json;
     * @param string $text
     * @return array
     * @throws \Exception
     */
    public function getAnalyzedText(string $text) : array
    {
        $headers = ['headers' => [
            'Content-Type' => 'text/plain;charset=utf-8',
            ]
        ];
        $params = [
            $headers,
            'query' => [
                'version' => self::SERVICE_VERSION,
                'text' => $this->normalizeText($text),
            ],
            'auth' => [
                $this->user,
                $this->password
            ]
        ];

        /*
         * We even send the response of an empty tweet;
         */
        $response = $this->client->get(self::SERVICE_PATH, $params);
        $body = (string) $response->getBody();

        if (empty($response) || $response->getStatusCode() != 200)
        {
            $code =  $response ?  $response->getStatusCode() : null;
            throw new \Exception(
                sprintf('Error from Watson API. - Code: %i - Message: %s', $code),
                $body
            );
        }

        return json_decode($body, true);
    }

    private function normalizeText(string $text) : string
    {
        return urlencode($text);
    }
}