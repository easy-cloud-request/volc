<?php

namespace EasyCloudRequest\Volc;

use EasyCloudRequest\Core\Exceptions\InvalidArgumentException;
use GuzzleHttp\Psr7\Request;
use EasyCloudRequest\Core\Gateways\BaseGateway;
use EasyCloudRequest\Core\Support\RequestBag;
use EasyCloudRequest\Core\Support\Response;
use GuzzleHttp\Psr7\Uri;

class Gateway extends BaseGateway
{
    /**
     * ucloud sender
     *
     * @param $requestBag
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function requests(RequestBag $requestBag): Response
    {
        $this->filledQueryParams($requestBag);
        $this->validate($requestBag);

        $request = new Request(
            $requestBag->method,
            (string) $this->getUri($requestBag),
            $requestBag->headerParams,
            \json_encode($requestBag->body)
        );

        $signer = new SignatureV4();
        $credentials = array_merge($this->config->all(), $requestBag->queryParams);
        $request = $signer->signRequest($request, $credentials);
        try {
            $response = $this->getHttpClient()->send($request, $this->getBaseOptions());
            $result = $this->unwrapResponse($response);

            return new Response(200, $result);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $result = $this->unwrapResponse($e->getResponse());
                return new Response($e->getCode(), $result);
            }
            return new Response($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        } catch (\Throwable $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    protected function filledQueryParams(RequestBag $r)
    {
        if (empty($r->queryParams['st'])) {
            $r->queryParams['st'] = '';
        }
    }

    protected function validate(RequestBag $r)
    {
        $queryCheckFields = ['service', 'region'];
        foreach ($queryCheckFields as $queryField) {
            if (empty($r->queryParams[$queryField])) {
                throw new InvalidArgumentException("query string must contain {$queryField} field");
            }
        }
    }

    protected function getUri(RequestBag $requestBag)
    {
        $uri = new Uri($requestBag->url);
        $queryParams = $requestBag->queryParams;

        if ($queryString = $uri->getQuery()) {
            parse_str($queryString, $queryParams);
            $queryParams = array_merge($queryParams, $requestBag->queryParams);
        }

        return $uri->withQuery(http_build_query($queryParams));
    }
}
