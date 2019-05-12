<?php

declare(strict_types = 1);

/**
 * Функция которая принимает строку (текст сообщения) от "Яндекс.Денег"
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
 * @return array
 */
function parser(string $str): array
{
    preg_match('/\s(?P<confirmationCode>\d{4,6})(<br \/>)?$/m', $str, $confirmationCodeMatches);
    $confirmationCode = $confirmationCodeMatches['confirmationCode'] ?? null;

    preg_match('/(?P<amount>\d+[.,]?\d*)\s*р/', $str, $amountMathces);
    $amount = $amountMathces['amount'] ?? null;

    preg_match('/(?P<wallet>\d{11,20})/m', $str, $walletMatches);
    $wallet = $walletMatches['wallet'] ?? null;

    return [
        $confirmationCode,
        $amount,
        $wallet,
    ];
}

$messages = [
    'Пароль: 1723<br />'."\n".'Спишется 100,51р.<br />'."\n".'Перевод на счет 4100143322865',
    'Пароль: 4186'."\n".'Спишется 343,03р.'."\n".'Перевод на счет 4100143322854',
    'Пароль: 4186
    Спишется 343,03р.
    Перевод на счет 4100143322854',
    'Перевод на счет 410014332285434
    Пароль: 44343
    Спишется 3423,23 руб.',
    'Пароль: 4186
    Перевод на счет 4100143322854
    Спишется 1005,03 рубля.',
    'Пароль: 4186
    Спишется 2323 р.
    Перевод на счет 34343243243434',
];

foreach ($messages as $message) {
    list($confirmationCode, $amount, $wallet) = \parser($message);

    echo "Сообщение: $message".PHP_EOL;

    echo ($confirmationCode ? "Код подтверждения: $confirmationCode" : 'Ошибка при получении кода подтверждения').PHP_EOL;
    echo ($amount ? "Сумма: $amount р." : 'Ошибка при получении суммы').PHP_EOL;
    echo ($wallet ? "Кошелек: $wallet" : 'Ошибка при получении кошелька').PHP_EOL;

    echo '--------------------------------------------------------'.PHP_EOL;
}