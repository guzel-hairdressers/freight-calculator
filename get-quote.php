<?php

    $constants = json_decode(file_get_contents('constants.json'));
    $from = $_POST['from'];
    $to = $_POST['to'];
    $R = 1;
    if(property_exists($constants->R, $from['country'].''.$to['country'])){
        $R = $constants->R->{$from['country'].''.$to['country']};
    }
    $time = $constants->time->{$from['country'].''.$to['country']};
    if($R->unit == 'kg'){
        $R->standard *= .45;
        $R->express *= .45;
        $R->sea *= .45;
    }

    $addidtional_charges = 0;
    $location_charges = $constants->location_charges;
    // if(property_exists($location_charges, $from['state'])){
    //     if(property_exists($location_charges->{$from['state']}, $from['city'])){
    //         $addidtional_charges += $location_charges->{$from['state']}->{$from['city']};
    //     } else {$addidtional_charges += $location_charges->{$from['state']}->{'All'};}
    // };
    if(property_exists($location_charges, $to['state'])){
        if(property_exists($location_charges->{$to['state']}, $to['city'])){
            $addidtional_charges += $location_charges->{$to['state']}->{$to['city']};
        } else {$addidtional_charges += $location_charges->{$to['state']}->{'All'};}
    }

    $standard = round(($R->standard * $_POST['dim'] + $addidtional_charges) * (1 + $constants->vat/100), 2);
    $hazadous = $standard + $constants->hazadous;
    $express = round(($R->express * $_POST['dim'] + $addidtional_charges) * (1 + $constants->vat/100), 2);
    $sea = round(($R->sea * $_POST['dim'] + $addidtional_charges) * (1 + $constants->vat/100), 2);
    $res = (object) array('standard' => $standard, 'hazadous' => $hazadous, 'express' => $express, 'sea' => $sea);
    echo json_encode($res);
