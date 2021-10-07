<?php

namespace CCVOnlinePayments\Omnipay\Message\Response;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CCVOnlinePaymentsResponse extends AbstractResponse
{
    /**
     * @var int
     */
    private $statusCode;

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->statusCode = $response->getStatusCode();

        parent::__construct($request, \json_decode($response->getBody(), true));
    }

    public function isSuccessful(): bool
    {
        return $this->statusCode < 400;
    }

    public function getMessage(): string
    {
        if(isset($this->data['message']) && isset($this->data['failureCode'])){
            return $this->data['message'] . " (" . $this->data['failureCode'] .")";
        }elseif(isset($this->data['failureCode'])) {
            return $this->data['failureCode'];
        }else{
            return "";
        }
    }
}
