<?php
declare(strict_types=1);

namespace TwitterBot\Tests;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;


abstract class BaseTest extends TestCase
{
    private $dotenv;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->dotenv = new Dotenv();
        $this->dotenv->load(__DIR__ . '/../configuration/.env');
    }

}