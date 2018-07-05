<?php

    $constants = json_decode(file_get_contents('constants.json'));
    $R = $_POST['R'];
    $time = $_POST['time'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    if(!property_exists($constants->R, $R['from'].''.$R['to'])){$constants->R->{$R['from'].''.$R['to']} = (object) array();}
    $constants->R->{$R['from'].''.$R['to']}->standard = $R['standard'];
    $constants->R->{$R['from'].''.$R['to']}->express = $R['express'];
    $constants->R->{$R['from'].''.$R['to']}->sea = $R['sea'];
    if($R['unit'] == 'kg'){
        $constants->R->{$R['from'].''.$R['to']}->standard *= .45;
        $constants->R->{$R['from'].''.$R['to']}->express *= .45;
        $constants->R->{$R['from'].''.$R['to']}->sea *= .45;
    }
    if(!property_exists($constants->time, $R['from'].''.$R['to'])){$constants->time->{$R['from'].''.$R['to']} = (object) array();}
    $constants->time->{$R['from'].''.$R['to']}->standard = $time['standard'];
    $constants->time->{$R['from'].''.$R['to']}->express = $time['express'];
    $constants->time->{$R['from'].''.$R['to']}->sea = $time['sea'];
    $constants->background_color = $_POST['color']['background'];
    $constants->inputs_color = $_POST['color']['inputs'];
    $constants->text_color = $_POST['color']['text'];

    // $constants->location_charges->{$from['region']} = (object) array();
    $constants->location_charges->{$to['region']} = (object) array();
    // if($from['location_charges'] != '' or $from['location_charges'] > 0){
    //     $constants->location_charges->{$from['region']}->{$from['city']} = $from['location_charges'];
    // } else {
    //     $constants->location_charges->{$from['region']}->{$from['city']} = 0;
    // }
    if($to['location_charges'] != '' or $to['location_charges'] > 0){
        $constants->location_charges->{$to['region']}->{$to['city']} = $to['location_charges'];
    } else {
        $constants->location_charges->{$to['region']}->{$to['city']} = 0;
    }
    $constants->vat = $_POST['vat'];
    $constants->hazadous = $_POST['hazadous'];
    file_put_contents('constants.json', json_encode($constants));
    
    
