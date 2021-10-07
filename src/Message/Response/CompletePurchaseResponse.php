<?php

namespace CCVOnlinePayments\Omnipay\Message\Response;

class CompletePurchaseResponse extends FetchTransactionResponse
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && $this->isPaid();
    }

    public function isRedirect()
    {
        return false;
    }
}
