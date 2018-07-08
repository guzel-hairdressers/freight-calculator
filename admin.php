<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Freight calculator</title>
</head>
<body>
    <div class="container">
        <div id="info" class="row">
            <div id="from" class="col-md-4">
                <h5>From</h5>
                <div class="line"></div>
                <div class="country">
                    <span>Select coutry</span>
                    <select name="country" class="right">
                    </select>
                </div>
                <div class="shipping-cost">
                    <span>R(standard)</span>
                    <input type="number" class="right" name="RStandard">
                </div>
                <div class="shipping-cost">
                    <span>R(express)</span>
                    <input type="number" class="right" name="RExpress">
                </div>
                <div class="shipping-cost">
                    <span>R(sea)</span>
                    <input type="number" class="right" name="RSea">
                </div>
                <div id="unit">
                    <span>Select unit</span>
                    <select name="unit" class="right">
                        <option value="lbs" selected>pound</option>
                        <option value="kg">kilogram</option>
                    </select>
                </div>
                <div class="time">
                    <span>Standard time(days)</span>
                    <input type="text" class="right" name="TStandard">
                </div>
                <div class="time">
                    <span>Express time(days)</span>
                    <input type="text" class="right" name="TExpress">
                </div>
                <div class="time">
                    <span>Sea time(weeks)</span>
                    <input type="text" class="right" name="TSea">
                </div>
                <!--<div class="state">
                    <span>Select state</span><br>  
                    <select name="state">
                        <option value="Alabama">Alabama</option>
                    </select>
                </div>
                <div class="city">
                    <span>Select city</span><br>
                    <select name="city">
                        <option value="NewYork">New York</option>
                    </select>
                </div>
                <div id="location-charges">
                    <span>Location charges</span><br>
                    <input type="number" name="location-charges">
                </div>-->
            </div>
            <div id="to" class="col-md-4">
                <h5>To</h5>
                <div class="line"></div>
                <div class="country">
                    <span>Select coutry</span>
                    <select name="country" class="right">
                    </select>
                </div>
                <div class="state">
                    <span>Select state</span>
                    <select name="state" class="right">
                        <option value="Alabama">Alabama</option>
                    </select>
                </div>
                <div class="city">
                    <span>Select city</span>
                    <select name="city" class="right">
                        <option value="NewYork">New York</option>
                    </select>
                </div>
                <div id="location-charges">
                    <span>Location charges</span>
                    <input type="number" class="right" name="location-charges">
                </div>
            </div>
            <div id="change" class="col-md-4">
                <h5>Constants</h5>
                <div class="line"></div>
                <div id="vat">
                    <span>VAT(%)</span>
                    <input type="number" class="right" name="vat" value="<?= json_decode(file_get_contents('constants.json'))->vat; ?>">
                </div>
                <div id="hazadous">
                    <span>Hazadous</span>
                    <input type="number" class="right" name="hazadous" value="<?= json_decode(file_get_contents('constants.json'))->hazadous; ?>">
                </div>
                <div class="style">
                    <span>1$ equals (NGN)</span>
                    <input type="number" class="right" name="exchange-ngn" value="<?= json_decode(file_get_contents('constants.json'))->exchange->ngn; ?>">
                </div>
                <div class="style">
                    <span>1$ equals (GBP)</span>
                    <input type="number" class="right" name="exchange-gbp" value="<?= json_decode(file_get_contents('constants.json'))->exchange->gbp; ?>">
                </div>
                <div class="style">
                    <span>Bacground color</span>
                    <input type="color" class="right" name="background-color" value="<?= json_decode(file_get_contents('constants.json'))->color->background ?>">
                </div>
                <div class="style">
                    <span>Cool color</span>
                    <input type="color" class="right" name="cool-color" value="<?= json_decode(file_get_contents('constants.json'))->color->cool ?>">
                </div>
                <div class="style">
                    <span>Inputs color</span>
                    <input type="color" class="right" name="inputs-color" value="<?= json_decode(file_get_contents('constants.json'))->color->inputs ?>">
                </div>
                <div class="style">
                    <span>Text color</span>
                    <input type="color" class="right" name="text-color" value="<?= json_decode(file_get_contents('constants.json'))->color->text ?>">
                </div>
            </div>
        </div>
    </div>
    <center><button class="get-quote" style="margin-top: 30px">
        Commit changes
    </button></center>
    <div id="toast"></div>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
    var countries = <?= file_get_contents('needed_countries.json'); ?>;
    var constants = <?= file_get_contents('constants.json'); ?>;
    function toast(text, type = 'alert', delay = 1000){
        $('#toast').text(text);
        $('#toast').attr({
            class: 'alert-' + type + ' alert show',
            role: 'alert'
        });
        setTimeout(function(){ $('#toast').removeClass('show') }, 3000);
    }
    $('button').click(function(){
        var from = {
            country: $('#from select[name="country"]').val(),
            region: $('#from select[name="state"]').val(),
            city: $('#from select[name="city"]').val(),
            // location_charges: $('#from input[name="location-charges"]').val()
        };
        var to = {
            country: $('#to select[name="country"]').val(),
            region: $('#to select[name="state"]').val(),
            city: $('#to select[name="city"]').val(),
            location_charges: $('#to input[name="location-charges"]').val()
        };
        var R = {
            from: from.country,
            to: to.country,
            standard: $('input[name="RStandard"]').val(),
            express: $('input[name="RExpress"]').val(),
            sea: $('input[name="RSea"]').val(),
            unit: $('select[name="unit"]').val()
        };
        var time = {
            standard: $('input[name="TStandard"]').val(),
            express: $('input[name="TExpress"]').val(),
            sea: $('input[name="TSea"]').val()
        };
        var color = {
            background: $('input[name="background-color"]').val(),
            cool: $('input[name="cool-color"]').val(),
            inputs: $('input[name="inputs-color"]').val(),
            text: $('input[name="text-color"]').val()
        };
        var exchange = {
            ngn: $('input[name="exchange-ngn"]').val(),
            gbp: $('input[name="exchange-gbp"]').val()
        };
        var vat = $('input[name="vat"]').val();
        var hazadous = $('input[name="hazadous"]').val();
        console.log({from, to, R, vat, time, hazadous, exchange});
        
        $.ajax('set-constants.php',
        {
            method: 'POST',
            data: {from, to, R, time, vat, hazadous, color, exchange}
        }).done(function(){
            toast('Done', 'success', 3000);
        })
    })
    function setStatesOrCities(jq_str, states_or_cities){
        replacement = (states_or_cities.map(function(state_or_city, i){
            if(i == 0){
                return '<option value="' + state_or_city + '" selected>' + state_or_city.replace(/(state)/i, '') + '<\/option>';
            }
            return '<option value="' + state_or_city + '">' + state_or_city.replace(/(state)/i, '') + '<\/option>';
        }).join('\n'));
        $(jq_str).html(replacement);
        $(jq_str).trigger('change');
    }
    $('#from select[name="country"]').change(function(){
        var code = $(this).val();
        var states = countries.find(function(country){
            return country.code == code;
        }).regions.map(function(region){return region.name});
        setStatesOrCities('#from select[name="state"]', states);
        if(code == 'ng'){
            $('#to select[name="country"]').html('<option value="us" selected>United States</option><option value="gb">UK</option>');
        } else if(code == 'us'){
            $('#to select[name="country"]').html('<option value="ng" selected>Nigeria</option>');
        } else if(code == 'gb'){
            $('#to select[name="country"]').html('<option value="ng" selected>Nigeria</option>');
        }
        $('#to select[name="country"]').trigger('change');
        var from = code;
        var to = $('#to select[name="country"]').val();
        if(constants.R[from + to]){
            $('input[name="RStandard"]').val(constants.R[from + to].standard ? constants.R[from + to].standard : 1);
            $('input[name="RExpress"]').val(constants.R[from + to].express ? constants.R[from + to].express : 1);
            $('input[name="RSea"]').val(constants.R[from + to].sea ? constants.R[from + to].sea : 1);
        } else {
            $('input[name="RStandard"]').val(1);
            $('input[name="RExpress"]').val(1);
            $('input[name="RSea"]').val(1);
        }
        if(constants.time[from + to]){
            $('input[name="TStandard"]').val(constants.time[from + to].standard ? constants.time[from + to].standard : '4-8');
            $('input[name="TExpress"]').val(constants.time[from + to].express ? constants.time[from + to].express : '2-4');
            $('input[name="TSea"]').val(constants.time[from + to].sea ? constants.time[from + to].sea : '5-8');
        } else {
            $('input[name="TStandard"]').val('4-8');
            $('input[name="TExpress"]').val('2-4');
            $('input[name="TSea"]').val('5-8');
        }
    });
    $('#to select[name="country"]').change(function(){
        console.log(constants)
        var code = $(this).val();
        var states = countries.find(function(country){
            return country.code == code;
        }).regions.map(function(region){return region.name});
        setStatesOrCities('#to select[name="state"]', states);
        var from = $('#from select[name="country"]').val();
        var to = code;
        if(constants.R[from + to]){
            $('input[name="RStandard"]').val(constants.R[from + to].standard ? constants.R[from + to].standard : 1);
            $('input[name="RExpress"]').val(constants.R[from + to].express ? constants.R[from + to].express : 1);
            $('input[name="RSea"]').val(constants.R[from + to].sea ? constants.R[from + to].sea : 1);
            $('select[name="unit"]').val(constants.R[from + to].unit);
        } else {
            $('input[name="RStandard"]').val(1);
            $('input[name="RExpress"]').val(1);
            $('input[name="RSea"]').val(1);
            $('select[name="unit"]').val('lbs');
        }
        if(constants.time[from + to]){
            $('input[name="TStandard"]').val(constants.time[from + to].standard ? constants.time[from + to].standard : '4-8');
            $('input[name="TExpress"]').val(constants.time[from + to].express ? constants.time[from + to].express : '2-4');
            $('input[name="TSea"]').val(constants.time[from + to].sea ? constants.time[from + to].sea : '5-8');
        } else {
            $('input[name="TStandard"]').val('4-8');
            $('input[name="TExpress"]').val('2-4');
            $('input[name="TSea"]').val('5-8');
        }
    });
    $('#from select[name="state"]').change(function(){
        var state = $(this).val();
        var code = $('#from select[name="country"]').val();
        var cities = ['All'].concat(countries.find(function(country){
            return country.code == code;
        }).regions.find(function(region){
            return region.name == state;
        }).cities);
        setStatesOrCities('#from select[name="city"]', cities);
        $('#from select[name="city"]').trigger('change');
    });
    $('#to select[name="state"]').change(function(){
        var state = $(this).val();
        var code = $('#to select[name="country"]').val();
        var cities = ['All'].concat(countries.find(function(country){
            return country.code == code;
        }).regions.find(function(region){
            return region.name == state;
        }).cities);
        setStatesOrCities('#to select[name="city"]', cities);
    });
    $('#to select[name="city"]').change(function(){
        var state = $('#to select[name="state"]').val();
        if(constants.location_charges[state]){
            var city = $(this).val();
            $('#to input[name="location-charges"]').val(constants.location_charges[state][city] ? constants.location_charges[state][city] : 0);
        } else {$('#to input[name="location-charges"]').val(0);}
    });
    $('input[name="background-color"]').change(function(){
        $('body').css('background', $(this).val());
    });
    $('input[name="inputs-color"]').change(function(){
        $('input, select, button').css('background', $(this).val());
    });
    $('input[name="text-color"]').change(function(){
        $('*').css('color', $(this).val());
    });
    $(document).ready(function(){
        var used_countries = [
            {name: "United States", code: "us"},
            {name: "Nigeria", code: "ng"},
            {name: "UK", code: "gb"}
        ].map(function(country, i){
            return '<option value="' + country.code + '">' + country.name + '</option>';
        });
        $('select[name="country"]').html(used_countries);
        $('#from select[name="country"]').val('us');
        $('#from select[name="country"]').trigger('change');
        $('body').css('background', constants.color.background);
        $('input, select, button').css('background', constants.color.inputs);
        $('*').css('color', constants.color.text);
        $('.card .card-header,a').css('background', constants.color.cool);
        $('#shipping-quote').css('background', constants.color.cool);
    })
</script>
</body>