<?php

declare(strict_types = 1);

namespace App\Tests\Command;

use App\Tests\MockWebServerTrait;
use donatj\MockWebServer\Response;

/**
 * Тестирование команды по получению сообщения о переводе средств
 */
class GetMoneyTransferMessageCommandTest extends CommandTestCase
{
    use MockWebServerTrait;

    /**
     * Установка окружения
     */
    public function setUp()
    {
        parent::setUp();

        $this->startMockServer();
    }
    /**
     * Сброс окружения
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->stopMockServer();
    }
    /**
     * Тестирование выполнение команды
     */
    public function testExecute()
    {
        $this->mockApiCategoryUrl();

        $output = $this->executeCommand(
            'app:get-money-transfer-message',
            [
                'receiver' => '4100143322865',
                'sum'      => 100,
            ]
        );

        $this->assertContains('Сообщение было успешно распознано', $output);
        $this->assertContains('Код подтверждения: 6561', $output);
        $this->assertContains('Сумма: 100.51', $output);
        $this->assertContains('Кошелек: 4100143322865', $output);
    }
    /**
     * Мок выгрузки категорий
     *
     * @return void
     */
    private function mockApiCategoryUrl()
    {
        $body = 'Пароль: 6561
Спишется 100,51р.
Перевод на счет 4100143322865';

        $response = new Response(
            $body,
            [],
            200
        );

        $this->server->setResponseOfPath(
            '/yandex/emulator',
            $response
        );
    }
}
