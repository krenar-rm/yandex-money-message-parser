<?php

declare(strict_types = 1);

namespace App\Parser;

use App\DTO\MoneyTransferMessage;

/**
 * Парсер сообщения о переводе средств
 */
class MoneyTransferMessageParser implements MoneyTransferMessageParserInterface
{
    /**
     * Выполняет парсинг текста сообщения от "Яндекс.Денег"
     * и возвращает извлеченные из неё код подтверждения, сумму и кошелек
     *
     * Код подтверждения целые числа длиной от 4 до 6 символов
     *
     * Номер кошелька пользователя в Яндекс.Деньгах, например 4100175017397. Длина — от 11 до 20 цифр.
     * https://kassa.yandex.ru/tech/payout/wallet.html
     *
     * Возможные ошибки при некорректных данных:
     * - Недостаточно средств.
     * - Кошелек Яндекс.Денег указан неверно.
     * - Сумма указана неверно.
     *
     * @param string $str
     *
     * @return MoneyTransferMessage
     */
    public function parse(string $str): MoneyTransferMessage
    {
        preg_match('/\s(?P<confirmationCode>\d{4,6})(<br \/>)?$/m', $str, $confirmationCodeMatches);
        $confirmationCode = $confirmationCodeMatches['confirmationCode'];

        preg_match('/(?P<amount>\d+[.,]?\d*)\s*р/', $str, $amountMathces);
        $amount = $amountMathces['amount'];

        preg_match('/(?P<wallet>\d{11,20})/m', $str, $walletMatches);
        $wallet = $walletMatches['wallet'];

        return new MoneyTransferMessage(
            $str,
            (int) $confirmationCode,
            (float) \str_replace(',', '.', $amount),
            $wallet
        );
    }
}
