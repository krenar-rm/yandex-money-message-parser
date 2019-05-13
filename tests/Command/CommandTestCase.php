<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Базовый класс для выполнения команд
 */
abstract class CommandTestCase extends TestCase
{
    /**
     * Приложение
     *
     * @var Application
     */
    protected $application;

    /**
     * Установка окружения
     */
    protected function setUp()
    {
        parent::setUp();

        $kernel            = self::$client->getKernel();
        $this->application = new Application($kernel);
    }

    /**
     * Сброс окружения
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->application);
    }

    /**
     * Выполнение команды по наименованию
     *
     * @param string $commandName
     * @param array  $options
     *
     * @return string
     */
    protected function executeCommand(string $commandName, array $options = []): string
    {
        $command       = $this->application->find($commandName);
        $commandTester = new CommandTester($command);

        $commandData = array_merge(
            ['command' => $command->getName()],
            $options
        );
        $commandTester->execute($commandData);

        $output = $commandTester->getDisplay();

        return $output;
    }
}
