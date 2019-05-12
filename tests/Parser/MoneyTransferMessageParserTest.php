<?php

declare(strict_types = 1);

namespace App\Tests\Parser;

use App\DTO\MoneyTransferMessage;
use App\Parser\MoneyTransferMessageParser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Тестирование парсера сообщения о переводе средств
 */
class MoneyTransferMessageParserTest extends WebTestCase
{
    /**
     * Парсер сообщения о переводе средств
     *
     * @var MoneyTransferMessageParser
     */
    private $parser;

    /**
     * Установка окружения
     */
    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->parser = $container->get('test.App\Parser\MoneyTransferMessageParserInterface');
    }

    /**
     * Сброс окружения
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->parser);
    }

    /**
     * Провайдер данных для теста
     *
     * @return array
     */
    public function provideTestParse()
    {
        return [
            [
                'Пароль: 1723<br />'."\n".'Спишется 100,51р.<br />'."\n".'Перевод на счет 4100143322865',
                1723,
                100.51,
                '4100143322865',
            ],
            [
                'Пароль: 4186'."\n".'Спишется 343,03р.'."\n".'Перевод на счет 4100143322854',
                4186,
                343.03,
                '4100143322854',
            ],
            [
                'Перевод на счет 410014332285434
                Пароль: 44343
                Спишется 3423,23 руб.',
                44343,
                3423.23,
                '410014332285434',
            ],
            [
                'Пароль: 4186
                Перевод на счет 4100143322854
                Спишется 1005,03 рубля.',
                4186,
                1005.03,
                '4100143322854',
            ],
            [
                'Пароль: 4186
                Спишется 2323 р.
                Перевод на счет 34343243243434',
                4186,
                2323.00,
                '34343243243434',
            ],
            [
                'Спишется 2323 р.
                Перевод на счет 34343243243434
                Пароль: 4186',
                4186,
                2323.00,
                '34343243243434',
            ],
        ];
    }

    /**
     * Выполняет парсинг сообщения о переводе средств
     *
     * @param string $content
     * @param int    $confirmationCode
     * @param float  $amount
     * @param string $wallet
     *
     * @dataProvider provideTestParse
     */
    public function testParse($content, $confirmationCode, $amount, $wallet)
    {
        $message = $this->parser->parse($content);

        $this->assertInstanceOf(MoneyTransferMessage::class, $message);
        $this->assertSame($confirmationCode, $message->getConfirmationCode());
        $this->assertSame($amount, $message->getAmount());
        $this->assertSame($wallet, $message->getWallet());
    }
}
