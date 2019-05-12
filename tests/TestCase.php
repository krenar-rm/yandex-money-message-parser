<?php

declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Базовый класс для функциональных тестов
 */
abstract class TestCase extends WebTestCase
{
    /**
     * Client
     *
     * @var Client|null
     */
    protected static $client;

    /**
     * Установка окружения
     */
    protected function setUp()
    {
        parent::setUp();

        self::$client = self::createClient();
        self::$client->disableReboot();
    }

    /**
     * Сброс окружения
     */
    protected function tearDown()
    {
        self::$client = null;

        parent::tearDown();
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected static function createClient(array $options = [], array $server = [])
    {
        if (null === self::$client) {
            self::$client = parent::createClient($options, $server);
        }

        return self::$client;
    }
}
