<?php
namespace TwitterBot\Controllers;

use TwitterBot\Models\Analyzer;
use TwitterBot\Models\TwitterBotModel;
use TwitterBot\Models\WatsonToneAnalyzerModel;
use TwitterBot\TwitterConfig;

class TwitterBotController
{
    private $model;
    private $analyzer;

    public function __construct(TwitterBotModel $m = null, Analyzer $a = null)
    {
        $this->model = $m;
        $this->analyzer = $a;
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
        }

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

}