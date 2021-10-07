<?php

namespace CCVOnlinePayments\Omnipay\Message\Response;

class RefundResponse extends CCVOnlinePaymentsResponse
{
    public function getRefundId(): string
    {
        return $this->data['id'];
    }
}
