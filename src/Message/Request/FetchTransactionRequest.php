<?php

namespace CCVOnlinePayments\Omnipay\Message\Request;

use CCVOnlinePayments\Omnipay\Message\Response\FetchTransactionResponse;
use Omnipay\Common\Message\ResponseInterface;

class FetchTransactionRequest extends CCVOnlinePaymentsRequest
{
    public function getData(): array
    {
        return ["reference" => $this->getTransactionReference()];
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            self::GET,
            "api/v1/transaction",
            $data
        );

        return $this->response = new FetchTransactionResponse($this, $response);
    }
}
