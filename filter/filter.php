<?php
/**
 * Created by PhpStorm.
 * User: carl
 * Date: 18/02/2016
 * Time: 14:34
 */


function getAllCountries(){
    require_once 'class/class-prefixes.php';

    $countries=new TPCCountries();

//    return $prefixes->getCountryDBPrefix();

//    return $prefixes->getAllLangauages();

    return $countries->getAllCountries();

}

