<?php
namespace EasyCloudRequest\Volc;

use EasyCloudRequest\Core\Support\Config;
use EasyCloudRequest\Core\Support\RequestBag;

class Signer extends SignatureV4
{

    /**
     * request bag
     *
     * @var RequestBag
     */
    protected $requestBag;

    /**
     * config
     *
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     * @return void
     */
    public function __construct(Config $config, RequestBag $requestBag)
    {
        $this->requestBag = $requestBag;
        $this->config = $config;
    }
}
