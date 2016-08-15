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

        $rates = $this->removeUnusedRates((array)$ratesData->rates);

        return $this->formatRates($ratesData->base, $ratesData->date, $rates);
    }

    /**
     * Format the rates for the message
     * @param $ratesBase
     * @param $ratesDate
     * @param $rates
     * @return string
     */
    private function formatRates(string $ratesBase, string $ratesDate, array $rates)
    {
        $returnMessage = '💰 Your rates based on ' . $ratesBase . ':\r\n' . 'Date: ' . $ratesDate . '\r\n';

        foreach ($rates as $key => $rate) {
            $returnMessage .= $key . ' ' . $rate . '\n\r';
        }

        return $returnMessage;

    }

    /**
     * Remove some unused rates
     * @param array $rates
     * @return array
     */
    private function removeUnusedRates(array $rates)
    {
        return array_filter($rates, function ($key) {
            return !in_array($key, $this->unusedRates);
        }, ARRAY_FILTER_USE_KEY);
    }

}