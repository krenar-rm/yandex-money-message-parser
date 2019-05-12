<?php

declare(strict_types = 1);

namespace App\Parser;

use App\DTO\MoneyTransferMessage;

interface MoneyTransferMessageParserInterface
{
    /**
     * Выполняет парсинг сообщения о переводе средств
     *
     * @param string $str
     *
     * @return MoneyTransferMessage
     */
    public function parse(string $str): MoneyTransferMessage;
}
