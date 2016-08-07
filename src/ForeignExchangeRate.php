<?php

namespace App;

class ForeignExchangeRate
{


    protected $unusedRates = [
        'TRY',
        'THB',
        'CAD',
        'CZK',
        'DKK',
        'KRW',
        'SGD',
        'ZAR',
        'NOK',
        'INR',
        'ILS',
        'PHP',
        'RON',
        'RUB',
        'BGN',
        'HKD',
        'MXN',
        'IDR',
        'BRL'
    ];

    /**
     * Collect the rates form the JSON api
     * @param $base
     * @return mixed
     */
    public function getRates($base)
    {

        $ratesJsonData = file_get_contents('http://api.fixer.io/latest?base=' . $base, true);

        if (!$ratesJsonData) {
            return 'Sorry I dont know this rate base. Try EUR, USD, CHF...';
        }

        $ratesData = json_decode($ratesJsonData);

        $ratesData = $this->removeUnusedRates($ratesData);

        return $this->formatRates($ratesData);
    }

    /**
     * Format the rates for the message
     * @param $rates
     * @return string
     */
    private function formatRates($rates)
    {
        $returnMessage = '💰 Your rates based on ' . $rates->base . ':\r\n' . 'Date: ' . $rates->date . '\r\n';

        foreach ($rates->rates as $key => $rate) {
            $returnMessage .= $key . ' ' . $rate . '\n\r';
        }

        return $returnMessage;

    }

    /**
     * Remove some unused rates
     * @param $ratesData
     * @return mixed
     */
    private function removeUnusedRates($ratesData)
    {
        foreach ($ratesData->rates as $key => $rate) {
            if (in_array($key, $this->unusedRates)) {
                unset($ratesData->rates->$key);
            }
        }

        return $ratesData;
    }

}