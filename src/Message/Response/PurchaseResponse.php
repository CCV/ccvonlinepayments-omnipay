<?php

namespace CCVOnlinePayments\Omnipay\Message\Response;

use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PurchaseResponse extends FetchTransactionResponse
{

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);
    }

    public function isSuccessful(): bool
    {
        return false;
    }
}
