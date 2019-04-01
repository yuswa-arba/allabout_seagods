<?php

// Currency for rupiah
const CURRENCY_IDR = 'Rp';
const CURRENCY_IDR_CODE = 'IDR';

// Currency for US Dollar
const CURRENCY_USD = '$';
const CURRENCY_USD_CODE = 'USD';

function get_currency($currency_code)
{
    switch ($currency_code) {
        case CURRENCY_IDR_CODE:
            return CURRENCY_IDR;
        case CURRENCY_USD_CODE:
            return CURRENCY_USD;
        default:
            return CURRENCY_USD;
    }
}

function get_all_currency()
{
    return [
        CURRENCY_USD_CODE,
        CURRENCY_IDR_CODE
    ];
}