<?php
namespace TwitterBot\Tests;

use Codebird\Codebird;
use TwitterBot\Models\TwitterBotModel;
use TwitterBot\Models\TwitterStatistic;

final class TwitterBotModelTest extends BaseTest
{
    private $model;

    public function  setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $cb = Codebird::getInstance();
        $this->model = new TwitterBotModel($cb);
    }

    public function testNormalizeText()
    {
        $str = '@bikerchecker @fhhffghj @JoeBFlat @RobertHabeck This should work #metoo.';
        $expected = 'This should work .';

        $actual = $this->model->normalizeText($str);

        $this->assertEquals($actual, $expected);
    }

    /**
     * A simple functional test. Needs an existing database.
     */
    public function testFetchAll()
    {
        $this->assertNotFalse($this->model->fetchAll());
    }

    /**
     * A simple functional test. Needs an existing database.
     */
    public function testGetStatistics()
    {
        $actual = $this->model->getStatistics();

        $this->assertInstanceOf(TwitterStatistic::class, $actual);

        if(!empty($this->model->fetchAll()))
        {
            $this->assertGreaterThan(0, $actual->getTotal());
        }

        var_dump($actual);
    }
}