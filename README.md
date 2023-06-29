# core
provide cloud operation interface for volc Cloud


# Usage

## refresh CDN
```php
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