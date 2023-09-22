<?php

namespace CCVOnlinePayments\Omnipay\Message\Response;

class FetchMethodsResponse extends CCVOnlinePaymentsResponse
{

    public function getPaymentMethods() {
        $nameById = [
            "banktransfer"      => "Bank Transfer",
            "card_amex"         => "American Express",
            "card_bcmc"         => "Bancontact",
            "card_maestro"      => "Maestro",
            "card_mastercard"   => "Mastercard",
            "card_visa"         => "Visa",
            "eps"               => "Eps",
            "giropay"           => "Giropay",
            "ideal"             => "iDeal",
            "applepay"          => "Apple Pay",
            "payconiq"          => "Payconiq",
            "paypal"            => "Paypal",
            "sofort"            => "Sofort",
            "terminal"          => "Terminal (instore solution)",
        ];

        $methods = [];
        foreach($this->getData() as $apiPaymentMethod) {
            $methodId = $apiPaymentMethod['method'];

            if($methodId === "card") {
                foreach($apiPaymentMethod['options'] as $option) {
                    $methods[] = [
                        "id"        => "card_".$option['brand'],
                        "name"      => $nameById["card_".$option['brand']] ?? $option['brand'],
                        "apiMethod" => "card",
                        "apiBrand"  => $option['brand'],
                        "apiIssuer" => null,
                    ];
                }
            }elseif($methodId === "ideal") {
                $method = [
                    "id"        => $methodId,
                    "name"      => $nameById[$methodId] ?? $methodId,
                    "issuers"   => [],
                    "apiMethod" => $methodId,
                    "apiBrand"  => null,
                ];

                foreach($apiPaymentMethod['options'] as $option) {
                    $method['issuers'][] = [
                        "id"        => $option['issuerid'],
                        "name"      => $option['issuerdescription']
                    ];
                }

                $methods[] = $method;
            }else{
                $methods[] = [
                    "id"        => $methodId,
                    "name"      => $nameById[$methodId] ?? $methodId,
                    "apiMethod" => $methodId,
                    "apiBrand"  => null,
                    "apiIssuer" => null
                ];
            }
        }

        return $methods;
    }
}
