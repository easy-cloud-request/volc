# core
provide cloud operation interface for volc Cloud


# Install
```
composer require easy-cloud-request/volc -vvv
```

# Usage

## 获取刷新与预热任务列表
> https://www.volcengine.com/docs/6454/70437
```php
require './vendor/autoload.php';
use EasyCloudRequest\Volc\Gateway;

$type = 'file';
$urls = [
    'your link'
];
$request = new RequestBag(
    'GET',
    // 'https://cdn.volcengineapi.com?Version=2021-03-01&Action=SubmitRefreshTask',
    'https://cdn.volcengineapi.com',
    [
        "Action" => 'SubmitRefreshTask',
        "Version" => '2021-03-01',
        'region' => 'cn-north-1',
        'service' => 'CDN',
    ],
    [],
    [
        "Type" => $type,
        "Urls" => implode('\n', $urls)
    ]
);
```

## 获取热点及访客的统计排名
> https://www.volcengine.com/docs/6454/79322
```php
$request = new RequestBag(
    'POST',
    'https://cdn.volcengineapi.com',
    [
        "Action" => 'DescribeEdgeTopStatisticalData',
        "Version" => '2021-03-01',
        'region' => 'cn-north-1',
        'service' => 'CDN',
    ],
    [],
    [
        'StartTime' => $startFieldCarbon->getTimestamp(),
        'EndTime' => $endFieldCarbon->getTimestamp(),
        'Domain' => 'www.baidu.com',
        'Item' => 'url',
        'Metric' => 'pv',
    ]
);
```

## send request
```php
require './vendor/autoload.php';
use EasyCloudRequest\Volc\Gateway;
use EasyCloudRequest\Core\Support\RequestBag;


$cloud = new SimpleCloud([
    'default' => Gateway::class,
    'gateway' => [
        'volc' => [
            'ak' => 'your ak',
            'sk' => 'your sk',
        ]
    ],
    'http_options' => [
        "http_errors" => false,
        "proxy" => [],
        "verify" => false,
        "timeout" => 120,
        "connect_timeout" => 60,
    ]
]);
$result = $cloud->requests($request);
var_dump($result);
```

# Others
```bash
composer run-script analyse
composer run-script analyse ./src
```