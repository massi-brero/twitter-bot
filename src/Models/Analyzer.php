<?php
namespace TwitterBot\Models;

interface Analyzer {
    public function getAnalyzedText(string $text);
}