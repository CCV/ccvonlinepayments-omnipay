<?php

namespace CCVOnlinePayments\Omnipay\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FetchTransactionResponse extends CCVOnlinePaymentsResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);

        if(isset($this->data[0])) {
            $this->data = $this->data[0];
        }
    }

    public function isRedirect()
    {
        return isset($this->data['payUrl']);
    }

    public function getRedirectUrl()
    {
        return $this->data['payUrl'];
    }


    public function isPending()
    {
        if($this->isRedirect()) {
            return false;
        }else {
            return isset($this->data['status']) && $this->data['status'] === "pending";
        }
    }

    public function isPaid()
    {
        return isset($this->data['status']) && $this->data['status'] === "success";
    }

    public function isExpired()
    {
        return isset($this->data['status']) && $this->data['status'] === "failed";
    }

    public function getTransactionReference()
    {
        return $this->data['reference'];
    }
}
