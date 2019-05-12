<?php

declare(strict_types = 1);

namespace App\Command;

use App\Parser\MoneyTransferMessageParserInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команды по получению сообщения о переводе средств
 */
class GetMoneyTransferMessageCommand extends Command
{
    /**
     * Наименование команды
     *
     * @var string
     */
    protected static $defaultName = 'app:get-money-transfer-message';

    /**
     * HTTP клиент
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * URL для выгрузки данных по категориям
     *
     * @var string
     */
    private $apiGetMessageUrl;

    /**
     * Парсер сообщения о переводе средств
     *
     * @var MoneyTransferMessageParserInterface
     */
    private $messageParser;

    /**
     * Конструктор
     *
     * @param ClientInterface                     $client
     * @param string                              $apiGetMessageUrl
     * @param MoneyTransferMessageParserInterface $messageParser
     */
    public function __construct(
        ClientInterface $client,
        string $apiGetMessageUrl,
        MoneyTransferMessageParserInterface $messageParser
    ) {
        $this->httpClient       = $client;
        $this->apiGetMessageUrl = $apiGetMessageUrl;
        $this->messageParser    = $messageParser;

        parent::__construct();
    }

    /**
     * Конфигурация
     */
    protected function configure()
    {
        $this
            ->setDescription('Получить сообщение о переводе средств')
            ->setHelp('Данная команда позволяет получить сообщения о переводе средств')
            ->addArgument('receiver', InputArgument::REQUIRED, 'Кошелек')
            ->addArgument('sum', InputArgument::REQUIRED, 'Сумма');
    }

    /**
     * Выполнение команды
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->httpClient->request(
            'POST',
            $this->apiGetMessageUrl,
            [
                'form_params' => [
                    'receiver' => $input->getArgument('receiver'),
                    'sum'      => $input->getArgument('sum'),
                ],
                'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
            ]
        );

        $body = (string) $response->getBody();

        if (200 === $response->getStatusCode()) {
            try {
                $moneyTransferMessage = $this->messageParser->parse($body);

                $output->writeln('Сообщение было успешно распознано');
                $output->writeln(sprintf('Код подтверждения: %s', $moneyTransferMessage->getConfirmationCode()));
                $output->writeln(sprintf('Сумма: %s', $moneyTransferMessage->getAmount()));
                $output->writeln(sprintf('Кошелек: %s', $moneyTransferMessage->getWallet()));
            } catch (\Exception $exception) {
                $output->writeln('Ошибка при распознавании сообщения');
                $output->writeln($body);
                $output->writeln($exception->getMessage());
            }
        } else {
            $output->writeln('Ошибка при обращении к ресурсу');
            $output->writeln($body);
        }
        return 0;
    }
}
