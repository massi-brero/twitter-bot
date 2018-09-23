<?php
/**
 * Created by PhpStorm.
 * User: massi
 * Date: 23.09.18
 * Time: 15:33
 */

namespace TwitterBot\Models;


use phpDocumentor\Reflection\Types\Object_;

class SentimentAnalyzerFactory
{

    const WATSON = 0;
    private $config;

    public static function getAnalyzer(int $type) : Analyzer
    {

        $analyzer = null;
        $factory = new SentimentAnalyzerFactory();

        switch ($type)
        {
            case 0:
            default:
               $analyzer = $factory->setUpWatsonToneAnalyzer();
        }


        return $analyzer;
    }

    private function setUpWatsonToneAnalyzer()
    {
        $user = getenv('WATSON_USER');
        $pw = getenv('WATSON_PASSWORD');

        $analyzer = new WatsonToneAnalyzer();
        $analyzer->setCredentials($user, $pw);

        return $analyzer;
    }
}