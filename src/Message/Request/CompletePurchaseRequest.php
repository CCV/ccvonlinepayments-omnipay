<?php

namespace CCVOnlinePayments\Omnipay\Message\Request;

use CCVOnlinePayments\Omnipay\Message\Response\CompletePurchaseResponse;
use Omnipay\Common\Message\ResponseInterface;

class CompletePurchaseRequest extends FetchTransactionRequest
{

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            self::GET,
            "api/v1/transaction",
            $data
        );

        return $this->response = new CompletePurchaseResponse($this, $response);
    }
}
