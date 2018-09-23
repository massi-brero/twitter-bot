<?php
namespace TwitterBot\Controllers;

use TwitterBot\Models\TwitterBotModel;
use TwitterBot\TwitterConfig;

class TwitterBotController
{
    private $model;

    public function __construct(TwitterBotModel $m = null)
    {
        $this->model = $m;
    }

    /**
     * starts the bot
     */
    public function startBot() : void
    {
        $mentions = $this->model->getMentions();

        if (is_array($mentions))
        {
            var_dump($mentions);
        }

    }

    /**
     * @param TwitterBotModel $m
     */
    public function setModel(TwitterBotModel $m): void
    {
        $this->model = $m;
    }




}