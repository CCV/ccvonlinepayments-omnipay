<?php namespace CCVOnlinePayments\Omnipay;

use CCVOnlinePayments\Omnipay\Message\Request\CompletePurchaseRequest;
use CCVOnlinePayments\Omnipay\Message\Request\FetchMethodsRequest;
use CCVOnlinePayments\Omnipay\Message\Request\FetchTransactionRequest;
use CCVOnlinePayments\Omnipay\Message\Request\PurchaseRequest;
use CCVOnlinePayments\Omnipay\Message\Request\RefundRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * @method RequestInterface acceptNotification(array $options = array())
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return "CCV Online Payments";
    }

    public function getDefaultParameters(): array
    {
        return [
            'apiKey' => '',
        ];
    }

    /**
     * @var array|null
     */
    public function getMetadata() {
        return $this->getParameter('metadata');
    }

    /**
     * @param  array $metadata
     * @return $this
     */
    public function setMetadata(array $metadata) {
        return $this->setParameter('metadata', $metadata);
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return $this
     */
    public function setMetadataValue($key, $value) {
        $metadata = $this->getMetadata() ?: [];
        $metadata[$key] = $value;

        return $this->setMetadata($metadata);
    }

    public function purchase(array $options = []): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = []): CompletePurchaseRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): FetchTransactionRequest
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    public function refund(array $options = []): RefundRequest
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    public function fetchMethods(array $options = []): FetchMethodsRequest
    {
        return $this->createRequest(FetchMethodsRequest::class, $options);
    }

    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value): self
    {
        return $this->setParameter('apiKey', $value);
    }
}
