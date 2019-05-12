<?php

declare(strict_types = 1);

namespace App\DTO;

/**
 * Сообщение о переводе средств
 */
class MoneyTransferMessage
{
    /**
     * Содержание сообщения
     *
     * @var string
     */
    private $content;

    /**
     * Код подтверждения
     *
     * @var int
     */
    private $confirmationCode;

    /**
     * Сумма
     *
     * @var float
     */
    private $amount;

    /**
     * Кошелек
     *
     * @var string
     */
    private $wallet;

    /**
     * Конструктор
     *
     * @param string $content
     * @param int    $confirmationCode
     * @param float  $amount
     * @param string $wallet
     */
    public function __construct(
        string $content,
        int $confirmationCode,
        float $amount,
        string $wallet
    ) {
        $this->content          = $content;
        $this->confirmationCode = $confirmationCode;
        $this->amount           = $amount;
        $this->wallet           = $wallet;
    }

    /**
     * Получить содержимое сообщения
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Получить код подтверждения
     *
     * @return int
     */
    public function getConfirmationCode(): int
    {
        return $this->confirmationCode;
    }

    /**
     * Получить сумму перевода
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Получить кошелек
     *
     * @return string
     */
    public function getWallet(): string
    {
        return $this->wallet;
    }
}
