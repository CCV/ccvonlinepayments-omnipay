<?php

namespace CCVOnlinePayments\Omnipay\Message\Request;

use CCVOnlinePayments\Omnipay\Message\Response\FetchMethodsResponse;
use Omnipay\Common\Message\ResponseInterface;

class FetchMethodsRequest extends CCVOnlinePaymentsRequest
{

    public function getData() {
        return [];
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            self::GET,
            "api/v1/method",
            $data
        );

        return $this->response = new FetchMethodsResponse($this, $response);
    }
}
