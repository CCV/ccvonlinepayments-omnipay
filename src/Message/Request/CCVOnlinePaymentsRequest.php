<?php

namespace CCVOnlinePayments\Omnipay\Message\Request;

use Http\Client\Exception\HttpException;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Http\Exception\RequestException;
use Omnipay\Common\Message\AbstractRequest;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class CCVOnlinePaymentsRequest extends AbstractRequest
{
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';

    const ENDPOINT = 'https://redirect.jforce.be/';

    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getMetadata() : ?array {
        return $this->getParameters('metadata');
    }

    public function setMetadata($metadata) {
        return $this->setParameter("metadata", $metadata);
    }

    private function getUrl(string $endpoint): string
    {
        return sprintf('%s%s', self::ENDPOINT, $endpoint);
    }

    public function sendRequest(string $method, string $endpoint, array $data = []): ResponseInterface
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => sprintf('Basic %s', \base64_encode(trim($this->getApiKey()).":")),
        ];

        if($method === self::GET && sizeof($data) > 0) {
            $endpoint .= "?".http_build_query($data);
            $data = [];
        }

        try {
            $response = $this->httpClient->request(
                $method,
                $this->getUrl($endpoint),
                $headers,
                empty($data) ? null : \json_encode($data)
            );
        }catch(\Omnipay\Common\Http\Exception\RequestException $ex) {
            $previousException = $ex->getPrevious();
            if($previousException instanceof HttpException) {
                return $previousException->getResponse();
            }else{
                throw $ex;
            }
        }

        return $response;
    }
}
