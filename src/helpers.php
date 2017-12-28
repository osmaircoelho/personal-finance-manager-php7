<?php
function dateParse($date)
{
    $dateArray = explode('/', $date);
    $dateArray = array_reverse($dateArray);
    return implode('-', $dateArray); //default mysql date 0000-00-00

}

function numberParse($number)
{
    $newNumber = str_replace('.', '', $number);
    $newNumber = str_replace(',', '.', $newNumber);
    return $newNumber;
}
