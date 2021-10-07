<?php

namespace CCVOnlinePayments\Omnipay\Message\Request;

use CCVOnlinePayments\Omnipay\Message\Request\CCVOnlinePaymentsRequest;
use CCVOnlinePayments\Omnipay\Message\Response\RefundResponse;
use Omnipay\Common\Message\ResponseInterface;

class RefundRequest extends CCVOnlinePaymentsRequest
{

    public function getData(): array
    {
        $this->validate('apiKey', 'transactionReference', 'amount');

        $data = [
            "reference" => $this->getTransactionReference(),
            "amount"    => $this->getAmount()
        ];

        if (is_string($this->getParameter('description'))) {
            $data['description'] = $this->getParameter('description');
        }

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(self::POST, 'api/v1/refund', $data);

        return $this->response = new RefundResponse($this, $response);
    }
}
