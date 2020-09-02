# Json-parser

![build](https://github.com/Nastro/json-parser/workflows/build/badge.svg)
[![codecov](https://codecov.io/gh/Nastro/json-parser/branch/master/graph/badge.svg)](https://codecov.io/gh/Nastro/json-parser)

Json-parser - это универсальный парсер, который с помощью определенных правил, переводит json в массив PHP,
при этом в качестве источника данных может использоваться файл, текст или url.

```php

use JsonParser\Config;
use JsonParser\JsonParser;
use JsonParser\Rules\CallableRule;
use JsonParser\Rules\DotPathRule;
use JsonParser\Loader\TextLoader;

$json = <<<JSON
    {
        "data": {
            "general": {
                "id": 12,
                "category": "Eat",
                "url": "/goods/category/eat"
            },
            "goods": [
                {
                    "id":1,
                    "names": {
                        "ru_name":"Печенье",
                        "en_name":"Cookie"
                    },
                    "cost":12.5
                },{
                    "id":2,
                    "names": {
                        "ru_name":"Молоко",
                        "en_name":"Milk"
                    },
                    "cost":17
                },{
                    "id":3,
                    "names": {
                        "ru_name":"Яблоко",
                        "en_name":"Apple"
                    },
                    "cost":19
                }
            ]
        }
    }
JSON;

$rules = [
    'ru_name' => new DotPathRule('names.ru_name'),
    'en_name' => new DotPathRule('names.en_name'),
    'custom' => new CallableRule(function ($item) {
        return $item['names']['ru_name'].$item['names']['en_name'];
    })
];

$config = (new Config($rules))
    ->setLoader(new TextLoader($json))
    ->setBasePath('data.goods')
    ->setIgnoreErrors(true);

$result = (new JsonParser($config))->parse();
var_dump($result);
```
Результат:
```
array(3) {
  [0] => array(3) {
    'ru_name' => string(14) "Печенье"
    'en_name' => string(6) "Cookie"
    'custom' => string(20) "ПеченьеCookie"
  }
  [1] => array(3) {
    'ru_name' => string(12) "Молоко"
    'en_name' => string(4) "Milk"
    'custom' => string(16) "МолокоMilk"
  }
  [2] => array(3) {
    'ru_name' => string(12) "Яблоко"
    'en_name' => string(5) "Apple"
    'custom' => string(17) "ЯблокоApple"
  }
}
```

### Установка
`composer require ybushenko/json-parser`

### Источник данных (Loader)
#### Текст (TextLoader)
Для использования текстового источника данных, достаточно в конструктор `TextLoader` передать json в виде текста:
```php
$loader = new JsonParser\Loader\TextLoader('[{"name": "a", "text": "foo"}, {"name": "b", "text": "bar"}]');
```

#### Файл (FileLoader)
Для того, чтобы загрузка json происходила из файла, нужно передать в конструктор `FileLoader` абсолютный путь до файла:
```php
$loader = new JsonParser\Loader\FileLoader(__DIR__ . '/data/goods.json');
```

#### Url (UrlLoader)
Также возможно загружать json по url, для этого нужно использовать `UrlLoader`.
Вторым аргументом можно передать клиент для http запросов совместимый с `GuzzleHttp\ClientInterface`
```php
$loader = new JsonParser\Loader\UrlLoader('http://url');
```

### Правила обработки (Rules)
Для преобразования данных в массив используются специальные правила,
с помощью которых можно получать данные и преобразовывать их в нужный вид

#### Путь через точку (DotPathRule)
При использовании данного правила, парсер будет пытаться получить данные по указанному пути.
```php
$rule = new \JsonParser\Rules\DotPathRule('path.to.node');
```

#### Массив данных (ArrayRule)
При использовании данного правила, парсер будет пытаться получить данные по указанному пути
и вернет массив массивов с указанными нодами во втором аргументе.
```php
$rule = new \JsonParser\Rules\ArrayRule('path.to.nodes', ['node1', 'node2', 'node3']);
```

#### Кастомная обработка (CallableRule)
Для собственной обработки значения можно использовать `CallableRule`. Правило принимает анонимную функцию,
и в качестве входного аргумента передаст объект целиком, чтобы пользователь смог обработать значение сам.
```php
$rule = new \JsonParser\Rules\CallableRule(function ($item) { return $item['node']; });
```

#### Из словаря (FromDictionaryRule)
Для того, чтобы использовать данное правило, для начала нужно добавить словарь в объект парсера:
```php
$parser = new \JsonParser\JsonParser($config);
$parser->addDictionary('name', [1 => 'foo', 2 => 'bar']);
```
Первым аргументом метода `addDictionary` выступает имя словаря, которое потребуется в будущем,
вторым аргументом может быть либо массив(как в примере выше), либо строка с путем через точку
до массива данных в самом json. Например:
```php
$parser = new \JsonParser\JsonParser($config);
$parser->addDictionary('name', 'path.to.categories');
```
В этом случае в качестве словаря будет использоваться сам json, а точнее его часть указанная во втором аргументе.
Для того, чтобы словарь мог использоваться парсером, он должен состоять из ключ-значение.
В качестве значения допускается массив, объект и другие типы.

Правило используется таким образом:
```php
$rule = new \JsonParser\Rules\FromDictionaryRule('name', 'path.to.node_id');
```
Для начала парсер будет пытаться получить значение из `path.to.node_id`,
а дальше сопоставит это значение со значением из словаря.
Если такое значение в словаре будет найдено, то он заменит его.

Дополнительно полученное значение можно обработать (например удалить начальные и конечные пробелы),
для этого используется третий аргумент, который принимает анонимную функцию:
```php
$rule = new \JsonParser\Rules\FromDictionaryRule('name', 'path.to.node_id', function ($value) {
    return trim($value);
});
```

### Настройки
#### Игнорирование ошибок (setIgnoreErrors)
Для проверки целостности данных, по умолчанию парсер будет выкидывать исключение
если по заданному пути не была найдена нода.
Для игнорирования таких ошибок следует использовать настройку `setIgnoreErrors(true)`
```php
$config->setIgnoreErrors(true);
```

#### Базовый путь (setBasePath)
По умолчанию парсер ожидает json с массивом в корневой ноде. Эту ноду можно
изменить с помощью метода `setBasePath`. Данная настройка позволяет задать
путь до данных, которые следует парсить.
```php
$config->setBasePath('path.to.nodes');
```
Нода по указанному пути должна являться массивом.

### Дополнительно
#### Добавление json без лоадеров (setJson)
Возможно использование json прямую, без лоадеров. Для этого можно воспользоваться методом `setJson`
```php
$parser->setJson('[{"name": "a", "text": "foo"}, {"name": "b", "text": "bar"}]');
```
При добавлении "сырого" json кода, он все равно превратится в массив,
как и во всех предыдущих случаях, а также применится базовый путь (basePath),
оригинальный(полный) json можно получить с помощью метода `getOriginalJson`. Этот метод также вернет массив

