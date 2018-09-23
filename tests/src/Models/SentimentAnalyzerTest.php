<?php
namespace TwitterBot\Tests;

use Codebird\Codebird;
use TwitterBot\Models\SentimentAnalyzerFactory;
use TwitterBot\Models\TwitterBotModel;

final class SentimentAnalyzerTest extends BaseTest
{
    private $analyzer;

    public function  setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->analyzer = SentimentAnalyzerFactory::getAnalyzer(SentimentAnalyzerFactory::WATSON);
    }

    public function testgetAnalyzedText()
    {
        $text = 'I like you and I\'m happy.';

        $result = $this->analyzer->getAnalyzedText($text);

        $this->assertArrayHasKey('document_tone', $result);
        $this->assertArrayHasKey('tones', $result['document_tone']);
    }
}