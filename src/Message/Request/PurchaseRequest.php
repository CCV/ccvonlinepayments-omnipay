<?php

namespace CCVOnlinePayments\Omnipay\Message\Request;

use CCVOnlinePayments\Omnipay\Message\Response\PurchaseResponse;
use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Brick\PhoneNumber\PhoneNumberParseException;

class PurchaseRequest extends CCVOnlinePaymentsRequest
{
    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->getParameter('brand');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBrand($value)
    {
        return $this->setParameter('brand', $value);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setOrderNumber($value)
    {
        return $this->setParameter('orderNumber', $value);
    }

    /**
     * @return string
     */
    public function getBrowserAcceptHeaders()
    {
        return $this->getParameter('browserAcceptHeaders');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBrowserAcceptHeaders($value)
    {
        return $this->setParameter('browserAcceptHeaders', $value);
    }

    /**
     * @return string
     */
    public function getBrowserLanguage()
    {
        return $this->getParameter('browserLanguage');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBrowserLanguage($value)
    {
        return $this->setParameter('browserLanguage', $value);
    }

    /**
     * @return string
     */
    public function getBrowserIpAddress()
    {
        return $this->getParameter('browserIpAddress');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBrowserIpAddress($value)
    {
        return $this->setParameter('browserIpAddress', $value);
    }

    /**
     * @return string
     */
    public function getBrowserUserAgent()
    {
        return $this->getParameter('browserUserAgent');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBrowserUserAgent($value)
    {
        return $this->setParameter('browserUserAgent', $value);
    }

    public function getMetadataString() :string {
        $metadata = $this->getParameter('metadata');

        if(is_array($metadata)) {
            $metadata["PHP"] = phpversion();
            $metadata["OS"]  = php_uname();

            $parts = [];
            foreach ($metadata as $key => $value) {
                $parts[] = $key .":".$value;
            }

            $string = implode(";", $parts);
            return substr($string,0,255);
        }

        return "";
    }

    public function getData()
    {
        $this->validate('apiKey', 'amount', 'language', 'currency', 'orderNumber', 'returnUrl');

        $data = [
            "amount"                    => number_format($this->getAmount(),2,".",""),
            "currency"                  => $this->getCurrency(),
            "returnUrl"                 => $this->getReturnUrl(),
            "method"                    => $this->getPaymentMethod(),
            "language"                  => $this->getLanguage(),
            "merchantOrderReference"    => $this->getOrderNumber(),
            "description"               => $this->getDescription(),
            "webhookUrl"                => $this->getNotifyUrl(),
            "issuer"                    => $this->getIssuer(),
            "brand"                     => $this->getBrand(),
            "metadata"                  => $this->getMetadataString(),  // FIXME
            "scaReady"                  => false,
            "billingAddress"            => $this->getCard()->getBillingAddress1(),
            "billingCity"               => $this->getCard()->getBillingCity(),
            "billingState"              => $this->getCard()->getBillingState(),
            "billingPostalCode"         => $this->getCard()->getBillingPostcode(),
            "billingCountry"            => $this->getCard()->getBillingCountry(),
            "billingPhoneNumber"        => $this->getPhoneNumber($this->getCard()->getBillingPhone(),$this->getCard()->getBillingCountry()),
            "billingPhoneCountry"       => $this->getPhoneCountryNumber($this->getCard()->getBillingPhone(),$this->getCard()->getBillingCountry()),
            "shippingAddress"           => $this->getCard()->getShippingAddress1(),
            "shippingCity"              => $this->getCard()->getShippingCity(),
            "shippingState"             => $this->getCard()->getShippingState(),
            "shippingPostalCode"        => $this->getCard()->getShippingPostcode(),
            "shippingCountry"           => $this->getCard()->getShippingCountry(),
            "accountInfo" => [
                "email"                 =>  $this->getCard()->getEmail(),
                "homePhoneNumber"       =>  $this->getPhoneNumber($this->getCard()->getBillingPhone(),$this->getCard()->getBillingCountry()),
                "homePhoneCountry"      =>  $this->getPhoneCountryNumber($this->getCard()->getBillingPhone(),$this->getCard()->getBillingCountry()),
            ],
            "merchantRiskIndicator" => [
                "deliveryEmailAddress"  => $this->getCard()->getEmail()
            ],
            "browser" => [
                "acceptHeaders" => $this->getBrowserAcceptHeaders(),
                "language"      => $this->getBrowserLanguage(),
                "ipAddress"     => $this->getBrowserIpAddress(),
                "userAgent"     => $this->getBrowserUserAgent()
            ]
        ];

        $this->removeNull($data);

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, 'api/v1/payment', $data);

        return $this->response = new PurchaseResponse($this, $response);
    }

    private function getPhoneNumber($phoneNumber, $countryCode) {
        try {
            $number = PhoneNumber::parse($phoneNumber, $countryCode);
            return $number->getNationalNumber();
        }catch(PhoneNumberParseException $phoneNumberParseException) {
            return null;
        }
    }

    private function getPhoneCountryNumber($phoneNumber, $countryCode) {
        try {
            $number = PhoneNumber::parse($phoneNumber, $countryCode);
            return $number->getCountryCode();
        }catch(PhoneNumberParseException $phoneNumberParseException) {
            return null;
        }
    }

    private function removeNull(&$array) {
        foreach($array as $key => &$value) {
            if($value === null) {
                unset($array[$key]);
            }elseif(is_array($value)) {
                $this->removeNull($value);
                if(sizeof($value) === 0) {
                    unset($array[$key]);
                }
            }
        }
    }
}
