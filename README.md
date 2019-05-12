# yandex-message-parser

Данное приложение является реализацией задачи:

    Напишите на PHP функцию, которая принимает строку (текст сообщения) и возвращает извлеченные из неё код 
    подтверждения, сумму и кошелек. Для генерации сообщений воспользуйтесь эмулятором. Мы написали эмулятор 
    (а не просто текст) не просто так: выдаваемый им текст может изменяться (точно так же, как это происходит 
    с реальными "Яндекс.Деньгами"), поэтому вам нужно как следует исследовать его. В перспективе, пожалуйста,
    учтите так же, что порядок полей, пунктуация и слова со временем могут быть изменены, что является обычным
    делом для платежных систем. Поэтому вам нужна универсальная функция, написанная с помощью регулярных 
    выражений, которая будет работать в любом случае.
    
    Решение должно полностью соответствовать заданию, ни больше ни меньше. Оно должны быть качественным,
    красивым и простым. Требуемая функциональность должна быть реализована практически идеально. Учтите, 
    мы будем придираться к мелочам. При этом, пожалуйста, не усложняйте. Не нужно создавать десятки 
    интерфейсов и вспомогательных классов, не нужно писать тесты, не нужно создавать готовые приложения.

#### Требования

- PHP ^7.1.3+

#### Аннотация к решению

В проекте представлено 2 решение:

##### 1 решение:

Путь до файла `public/simple_parser.php`

Здесь я реализовал функцию согласно описанию, нужно создать функцию принимающая строку, без тестов и готового приложения

Для запуска в консоли необходимо набрать `php public/simple_parser.php`

##### 2 решение:

Здесь уже реализовал в качестве проекта с тестами в ООП стиле

Для проверки приложения необходимо запустить тесты: `./bin/phpunit`

Метод отвечающий за парсинг сообщения `\App\Parser\MoneyTransferMessageParser::parse`

Так же подготовил команду `app:get-money-transfer-message`, которая подключается к `https://funpay.ru/yandex/emulator`, получает эмулированное сообщение и проводит парсинг сообщения

Для запуска команды необходимо набрать в консоли из корня проекта

`./bin/console app:get-money-transfer-message 4100143322854 150` , где 1-ый аргумент является номер кошелька, а 2-ой сумма для перевода

в консоли на данную команду должны получить

    Сообщение было успешно распознано
    Код подтверждения: 8589
    Сумма: 150.76
    Кошелек: 4100143322854

Данную команду предпологается подключить к CI к ночным сборкам. Чтобы при изменении ответа от "Яндекс. Деньги" мы смогли оперативно поправить парсер сообщения
