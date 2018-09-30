<?php

namespace TwitterBot;
use Symfony\Component\Dotenv\Dotenv;
use TwitterBot\Controllers\TwitterBotController;
use TwitterBot\Models\SentimentAnalyzerFactory;
use TwitterBot\Models\TwitterBotModel;
use Codebird\Codebird;

/**
 * Class App
 * Bootstraps the application.
 * @package TwitterBot
 */
class App
{
    // GET CONFIGURATION
    private $dotenv;

    public function __construct()
    {
        $this->dotenv = new Dotenv();
        $this->dotenv->load(__DIR__ . '/../configuration/.env');
    }

    public static function bootstrap() : void
    {

        $app = new App();

        try
        {
            $twitterModel = new TwitterBotModel($app->getCodeBird());
            $analyzer = SentimentAnalyzerFactory::getAnalyzer(SentimentAnalyzerFactory::WATSON);
            $twitterCtrl = new TwitterBotController();
            $twitterCtrl->setModel($twitterModel);
            $twitterCtrl->setAnalyzer($analyzer);
            $twitterCtrl->startBot();
        }
        catch (\Exception $e)
        {
            //todo log this
            echo $e;
        }

    }

    public function getCodeBird()
    {
        $config = new TwitterConfig();
        $config->setKey(getenv('TWITTER_API_KEY'))
               ->setKeysecret(getenv('TWITTER_API_KEY_SECRET'))
               ->setToken(getenv('TWITTER_ACCESS_TOKEN'))
               ->setTokenSecret(getenv('TWITTER_ACCESS_TOKEN_SECRET'));

        Codebird::setConsumerKey($config->getKey(), $config->getKeysecret());
        $cb = Codebird::getInstance();
        $cb->setToken($config->getToken(), $config->getTokenSecret());

        return $cb;
    }

}