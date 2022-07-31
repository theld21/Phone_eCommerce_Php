<?php
include_once("db.php");

function price_format($priceFloat)
{
    $symbol = '<sup>₫</sup>';
    $symbol_thousand = '.';
    $decimal_place = 0;
    $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
    return $price . $symbol;
}
?>