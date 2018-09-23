<?php
namespace TwitterBot\Tests;

use Codebird\Codebird;
use TwitterBot\Models\TwitterBotModel;

final class TwitterBotModelTest extends BaseTest
{
    private $model;

    public function  setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $cb = Codebird::getInstance();
        $this->model = new TwitterBotModel($cb);
    }

    public function testNormalizeText()
    {
        $str = '@bikerdoktor @fhhffghj @JoeBFlat @RobertHabeck This should work #metoo.';
        $expected = 'This should work .';

        $actual = $this->model->normalizeText($str);

        $this->assertEquals($actual, $expected);
    }
}