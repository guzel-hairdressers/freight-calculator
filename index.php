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
    <header>
        <center><h3>Header</h3></center>
    </header>
    <main>
        <center><h3>Pricing</h3></center>
        <div class="container">
            <div id="shipping-quote">
                <h5>Get shipping quote</h5>
                <div class="right">
                    <span>Currency</span>
                    <select name="currency">
                        <option value="USD" selected>USD - US dollar</option>
                        <option value="NGN">NGN - Naira</option>
                        <option value="GBP">GBP - British pound</option>
                    </select>
                </div>
            </div>
            <div id="info" style="margin: 0" class="row">
                <div id="from" class="col-md-4 package-info">
                    <h5>From</h5>
                    <div class="line"></div>
                    <div class="country">
                        <span>Select your coutry</span><br>
                        <select name="country">
                        </select>
                    </div>
                    <div class="state">
                        <span>Select your state</span><br>  
                        <select name="state">
                            <option value="Alabama">Alabama</option>
                        </select>
                    </div>
                    <div class="city">
                        <span>Select your city</span><br>
                        <select name="city">
                            <option value="NewYork">New York</option>
                        </select>
                    </div>
                </div>
                <div id="to" class="col-md-4 package-info">
                    <h5>To</h5>
                    <div class="line"></div>
                    <div class="country">
                        <span>Select your coutry</span><br>
                        <select name="country">
                        </select>
                    </div>
                    <div class="state">
                        <span>Select your state</span><br>  
                        <select name="state">
                            <option value="Alabama">Alabama</option>
                        </select>
                    </div>
                    <div class="city">
                        <span>Select your city</span><br>
                        <select name="city">
                            <option value="NewYork">New York</option>
                        </select>
                    </div>
                </div>
                <div id="package-dim" class="col-md-4 package-info">
                    <h5>Package dim(in)</h5>
                    <div class="line"></div>
                    <div id="dimensions" class="row">
                        <div class="col-md-4">
                            <span>Length:</span>
                            <input type="number" name="length">
                        </div>
                        <div class="col-md-4">
                            <span>Width:</span>
                            <input type="number" name="width">
                        </div>
                        <div class="col-md-4">
                            <span>Height:</span>
                            <input type="number" name="height">
                        </div>
                    </div>
                    <div id="unit">
                        <span>Select unit</span>
                        <select name="unit" class="right">
                            <option value="lbs/in" selected>lbs/in</option>
                            <option value="kg/cm">kg/cm</option>
                        </select>
                    </div>
                    <div id="weight">
                        <span>* Weight</span>
                        <input type="number" class="right" name="weight">
                    </div>
                    <div id="declared-value">
                        <span>Declared value</span>
                        <input type="number" class="right" name="declared-value">
                    </div>
                    <!--<center><button class="add-package" onclick="addPackage()">
                        Add package
                    </button></center>-->
                </div>
            </div>
        </div>
        <center><button class="get-quote" onclick="getQuote()">
            Get a Quote
        </button></center>
        <div class="container" style="margin-top:30px">
            <div class="card-deck">
                <div class="card hidden" id="standard" style="margin: 20px 10px; padding:0"></div>
                <div class="card hidden" id="hazadous" style="margin: 20px 10px; padding:0"></div>
                <div class="card hidden" id="express" style="margin: 20px 10px; padding:0"></div>
                <div class="card hidden" id="sea" style="margin: 20px 10px; padding:0"></div>
            </div>
        </div>
        <div id="toast"></div>
    </main>
</body>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
    var countries = <?= file_get_contents('needed_countries.json') ?>;
    var constants = <?= file_get_contents('constants.json') ?>;
    function toast(text, type = 'alert', delay = 1000){
        $('#toast').text(text);
        $('#toast').attr({
            class: 'alert-' + type + ' alert show',
            role: 'alert'
        });
        setTimeout(function(){ $('#toast').removeClass('show') }, 3000);
    }
    function addPackage(){
        var add = '<div class="col-md-8"></div><div id="package-dim" style="margin-top: 40px;" class="col-md-4">' + document.querySelector('#package-dim').innerHTML + '</div>';
        document.querySelector('#info').innerHTML += add;
    }
    function rounD(num){
        return Math.round(100 * num) / 100;
    }
    function dateToString(date){
        date = date.toUTCString().split(' ');
        return date.slice(0, 4).join(' ');
    }
    function getQuote(){
        var from = {
            country: $('#from select[name="country"]').val(),
            state: $('#from select[name="state"]').val(),
            city: $('#from select[name="city"]').val(),
        };
        var to = {
            country: $('#to select[name="country"]').val(),
            state: $('#to select[name="state"]').val(),
            city: $('#to select[name="city"]').val(),
        };
        var currency = $('select[name="currency"]').val();
        var unit = $('select[name="unit"]').val();

        var length = $('#dimensions input[name="length"]').val();
        var width = $('#dimensions input[name="width"]').val();
        var height = $('#dimensions input[name="height"]').val();
        if(!length || !width || !height){toast('Specify package dimensions', 'danger', 3000); return;}
        var weight = $('#package-dim input[name="weight"]').val();
        if(!weight){toast('Specify package weight', 'danger', 3000); return;}

        if(unit == 'kg/cm'){
            length *= .393701;
            width *= .393701;
            height *= .393701;
            weight *= 2.20462;
        }
        var volume = length * width * height;
        var dim = volume / 139 > weight ? volume / 139 : weight;
        declared_value = $('#package-dim input[name="declared-value"]').val();
        $.ajax('./get-quote.php',{
            method: 'POST',
            data: {
                from: from,
                to: to,
                currency: currency,
                dim: dim,
                declared_value: declared_value
            }
        }).done(function(data){
            var data = JSON.parse(data);
            var time = constants.time[from.country + to.country];
            var R = constants.R[from.country + to.country];
            var exchange = {
                ngn: constants.exchange.ngn,
                gbp: constants.exchange.gbp
            };
            if(R.unit == 'kg'){weight *= .45;}
            var stdD = new Date();
            var expD = new Date();
            var seaD = new Date();
            stdD.setDate(stdD.getDate() + parseInt(time.standard.split('-')[1]));
            expD.setDate(expD.getDate() + parseInt(time.express.split('-')[1]));
            seaD.setDate(seaD.getDate() + parseInt(time.sea.split('-')[1]) * 7);
            var standard = '<div class="card-header"><center>Standard Shipping</center></div><div class="card-body">    <center><h3 class="card-title">' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + rounD(currency == 'USD' ? data.standard : currency == 'NGN' ? data.standard * exchange.ngn : currency == 'GBP' ? data.standard * exchange.gbp : '') + '</h3>    <h6>' + time.standard + ' Business Days</h6></center>    <p class="card-text">Estimated Delivery: ' + dateToString(stdD) + '</p>    <p class="card-text">Your delivery cost is ' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + (currency == 'USD' ? R.standard : currency == 'NGN' ? rounD(R.standard * exchange.ngn) : currency == 'GBP' ? rounD(R.standard * exchange.gbp) : 0) + '/' + (R.unit == 'lbs' ? 'pound' : 'kilogram') + '</p></div><a href="#" class="btn btn-primary">Lower shipping cost</a>';
            var hazadous = '<div class="card-header"><center>Hazmat Shipping</center></div><div class="card-body">    <center><h3 class="card-title">' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + rounD(currency == 'USD' ? data.hazadous : currency == 'NGN' ? data.hazadous * exchange.ngn : currency == 'GBP' ? data.hazadous * exchange.gbp : '') + '</h3>    <h6>' + time.standard + ' Business Days</h6></center>    <p class="card-text">Estimated Delivery: ' + dateToString(stdD) + '</p>    <p class="card-text">Your delivery cost is ' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + (currency == 'USD' ? rounD(data.hazadous / weight / 1.05) : currency == 'NGN' ? rounD(data.hazadous / weight / 1.05 * exchange.ngn) : currency == 'GBP' ? rounD(data.hazadous / weight / 1.05 * exchange.gbp) : 0) + '/' + (R.unit == 'lbs' ? 'pound' : 'kilogram') + '</p></div><a href="#" class="btn btn-primary">Lower shipping cost</a>';
            var express = '<div class="card-header"><center>Express Shipping</center></div><div class="card-body">    <center><h3 class="card-title">' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + rounD(currency == 'USD' ? data.express : currency == 'NGN' ? data.express * exchange.ngn : currency == 'GBP' ? data.express * exchange.gbp : '') + '</h3>    <h6>' + time.express + ' Business Days</h6></center>    <p class="card-text">Estimated Delivery: ' + dateToString(expD) + '</p>    <p class="card-text">Your delivery cost is ' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + (currency == 'USD' ? R.express : currency == 'NGN' ? rounD(R.express * exchange.ngn) : currency == 'GBP' ? rounD(R.express * exchange.gbp) : 0) + '/' + (R.unit == 'lbs' ? 'pound' : 'kilogram') + '</p></div><a href="#" class="btn btn-primary">Lower shipping cost</a>';
            var sea = '<div class="card-header"><center>Sea Shipping</center></div><div class="card-body"><center><h3 class="card-title">' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + rounD(currency == 'USD' ? data.sea : currency == 'NGN' ? data.sea * exchange.ngn : currency == 'GBP' ? data.sea * exchange.gbp : '') + '</h3><h6>' + time.sea + ' Weeks</h6></center><p class="card-text">Estimated Delivery: ' + dateToString(seaD) + '</p><p class="card-text">Your delivery cost is ' + (currency == 'USD' ? '$' : currency == 'NGN' ? '₦' : currency == 'GBP' ? '£' : '') + (currency == 'USD' ? R.sea : currency == 'NGN' ? rounD(R.sea * exchange.ngn) : currency == 'GBP' ? rounD(R.sea * exchange.gbp) : 0) + '/' + (R.unit == 'lbs' ? 'pound' : 'kilogram') + '</p></div><a href="#" class="btn btn-primary">Lower shipping cost</a>';
            $('#standard').html(standard);
            $('#hazadous').html(hazadous);
            $('#express').html(express);
            $('#sea').html(sea);
            $('#standard').removeClass('hidden');
            $('#hazadous').removeClass('hidden');
            $('#express').removeClass('hidden');
            $('#sea').removeClass('hidden');
        });
    }
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
        
    });
    $('#to select[name="country"]').change(function(){
        var code = $(this).val();
        var states = countries.find(function(country){
            return country.code == code;
        }).regions.map(function(region){return region.name});
        setStatesOrCities('#to select[name="state"]', states);
    });
    $('#from select[name="state"]').change(function(){
        var state = $(this).val();
        var code = $('#from select[name="country"]').val();
        var cities = countries.find(function(country){
            return country.code == code;
        }).regions.find(function(region){
            return region.name == state;
        }).cities;
        setStatesOrCities('#from select[name="city"]', cities);
    });
    $('#to select[name="state"]').change(function(){
        var state = $(this).val();
        var code = $('#to select[name="country"]').val()
        var cities = countries.find(function(country){
            return country.code == code;
        }).regions.find(function(region){
            return region.name == state;
        }).cities;
        setStatesOrCities('#to select[name="city"]', cities);
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
        $('#info input, select').css('background', constants.color.inputs);
        $('#info *').css('color', constants.color.text);
        $('.card .card-header,a').css('background', constants.color.cool);
        $('#shipping-quote').css('background', constants.color.cool);
    })
</script>
</html>