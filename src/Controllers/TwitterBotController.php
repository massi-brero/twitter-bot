<?php
namespace TwitterBot\Controllers;

use function GuzzleHttp\default_user_agent;
use TwitterBot\Models\Analyzer;
use TwitterBot\Models\EmailModel;
use TwitterBot\Models\TwitterBotModel;
use TwitterBot\Models\WatsonToneAnalyzerModel;
use TwitterBot\TwitterConfig;

class TwitterBotController
{
    private $model;
    private $email;
    private $analyzer;

    public function __construct(TwitterBotModel $m = null, Analyzer $a = null, EmailModel $e = null)
    {
        $this->model = $m;
        $this->analyzer = $a;
        $this->email = $e;
    }

    /**
     * starts the bot
     */
    public function startBot() : void
    {
        $tweets = $this->model->getMentions();
        $analyzer= getenv('ANALYZER');
        $emoji = '';

        if (is_array($tweets) && count($tweets) > 0)
        {
            $analyzed = array_map(function ($tweet) {
                return $this->analyzer->getAnalyzedText($tweet->getText());
            }, $tweets);


            foreach ($analyzed as $index => $result)
            {
                $analyzedEmotion = '';

                try
                {
                    if (isset($analyzed[$index]) && !empty($analyzed[$index]['document_tone']['tones']))
                    {
                        $analyzedEmotion = $analyzed[$index]['document_tone']['tones'][0]['tone_id'];
                    }

                    switch ($analyzedEmotion)
                    {
                        case WatsonToneAnalyzerModel::EMOTION_ANGER:
                            $emoji = getenv('EMOJI_ANGER');
                            break;
                        case WatsonToneAnalyzerModel::EMOTION_SAD:
                            $emoji = getenv('EMOJI_SAD');
                            break;
                        case WatsonToneAnalyzerModel::EMOTION_FEAR:
                            $emoji = getenv('EMOJI_FEAR');
                            break;
                        case WatsonToneAnalyzerModel::EMOTION_JOY:
                            $emoji = getenv('EMOJI_GRIN');
                            break;
                        default:
                            $emoji = getenv('EMOJI_NEUTRAL');
                    }
                } catch (\Exception $e)
                {
                    throw $e;
                }

                if (isset($tweets[$index]))
                {
                    $this->model->replyToTweet(
                        $tweets[$index],
                        $emoji
                    );



                    $success = $this->model->saveReply(
                        $emoji,
                        $tweets[$index],
                        $analyzedEmotion,
                        $analyzer
                    );

                    if (!$success)
                    {
                        throw new \PDOException('Error while trying to insert tweet');
                    }

                }
            }
            echo 'Reply to tweets were sent. ';
            echo 'The last analysis results werde stored. ';
        }
    }

    public function sendStatisticsMail()
    {
        $statistics = $this->model->getStatistics();

        $message = "Hallo Massi,\n hier die aktuellen Tweet Stats:\n\n\n\n" .
        'Gesamt:' . $statistics->getTotal() .'\n\n' .
        'Freude:' . $statistics->getJoyPercentage() . '\n\n'.
        'Ärger:' . $statistics->getAngryPercentage() . '\n\n' .
        'Traurig:' . $statistics->getSadPercentage() . '\n\n' .
        'Angst:' . $statistics->getFearPercentage() . '\n\n';

        $success = $this->email->send(
            getenv('MAIL_TO'),
            getenv('MAIL_SUBJECT'),
            $message
        );

        if (!$success)
        {
            throw new \Exception('Could not send mail!');
        }

        echo 'Email to ' . getenv('MAIL_TO') . ' was sent. ';
    }

    /**
     * @param TwitterBotModel $m
     */
    public function setModel(TwitterBotModel $m): void
    {
        $this->model = $m;
    }

    /**
     * @param Analyzer $analyzer
     */
    public function setAnalyzer(Analyzer $analyzer): void
    {
        $this->analyzer = $analyzer;
    }

    /**
     * @param EmailModel $emailModel
     */
    public function setEmailModel(EmailModel $mailModel): void
    {
        $this->email = $mailModel;
    }

}