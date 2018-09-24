<?php
namespace TwitterBot\Controllers;

use TwitterBot\Models\Analyzer;
use TwitterBot\Models\TwitterBotModel;
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
        $mentions = $this->model->getMentions();

        if (is_array($mentions) && count($mentions) > 0)
        {
            $emotions = array_map(function ($mention) {
                $this->analyzer->getAnalyzedText($mention);
            });
        }

        var_dump($emotions);

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