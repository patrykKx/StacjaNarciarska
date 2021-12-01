$(document).ready(loadCurrentWeather());

function loadCurrentWeather() {
    $("#spinner").show();
    const apiKey = 'f96753ee20f647267d86a70f1c55987f';
    const latitude = '51.23'; //szerokosc
    const longitude = '22.55'; //dlugosc
    const latitude2 = '49.20'; //szerokosc
    const longitude2 = '20.03'; //dlugosc
    const apiCall = 'https://api.openweathermap.org/data/2.5/weather?lat='+latitude2+'&lon='+longitude2+'&appid='+apiKey+'&units=metric';
    
    fetch(apiCall)
    .then(res => res.json())
    .then(res => {
        getCurrentWeather(res);
    })
    .catch(err => {
        document.getElementById('errorLoadingWeather').innerHTML = 'Nie można załadować danych pogodowych. Spróbuj ponownie później.';
        $("#spinner").hide();
    });
}

function getCurrentWeather(res) {
    let weatherData = document.getElementById('weatherData');
    let precipation = 0;
    let rainPrecipation = 0;
    let snowPrecipation = 0;
    let precipationImage = '';
    
    if(res.rain !== undefined) {
        rainPrecipation += res['rain']['1h'];
        precipation += res['rain']['1h'];
    }
    if(res.snow !== undefined) {
        snowPrecipation += res['snow']['1h'];
        precipation += res['snow']['1h'];
    }

    if(rainPrecipation > 0 && snowPrecipation > 0) {
        precipationImage = '<img src="images/snowandrain.png">';
    }
    else if(rainPrecipation > 0) {
        precipationImage = '<img src="images/raindrop.png">';
    }
    else if(snowPrecipation > 0) {
        precipationImage = '<img src="images/snowflake.png">';
    }
    
    let currentTime = getCurrentTime(res.dt);
    let sunrise = getSunriseTime(res.sys.sunrise);
    let sunset = getSunsetTime(res.sys.sunset);

    let weatherDataHTML = '<div class="col-sm-6 py-sm-4 py-2">Data: <b>'+currentTime+'</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Temperatura: <b>'+res.main.temp+' °C</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Temp. odczuwalna: <b>'+res.main.feels_like+' °C</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Wiatr: <b>'+res.wind.speed+' m/s </b><img src="images/north.png" class="img-fluid" id="windArrow" alt="Arrow"></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Zachmurzenie: <b>'+res.clouds.all+'%</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Opady: <b>'+precipation+' mm/1h </b>'+precipationImage+'</div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Wilgotność: <b>'+res.main.humidity+'%</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Ciśnienie: <b>'+res.main.pressure+' hPa</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Wschód słońca: <b>'+sunrise+'</b></div>'+
                          '<div class="col-sm-6 py-sm-4 py-2">Zachód słońca: <b>'+sunset+'</b></div>';
    weatherData.innerHTML = weatherDataHTML;
    let windDirection = res.wind.deg + 180;
    $('#windArrow').css({'transform':'rotate('+windDirection+'deg)'});
    $("#spinner").hide();
}

function getCurrentTime(seconds) {
    let time = new Date(Date.UTC(1970, 0, 1));
    time.setUTCSeconds(seconds);
    let day = time.getDate();
    let month = time.getMonth()+1; //0 - styczeń
    let year = time.getFullYear();
    let hours = time.getHours();
    let minutes = time.getMinutes();
    let sec = time.getSeconds();
    
    if(day < 10) day = '0'+day;
    if(month < 10) month = '0'+month;
    if(hours < 10) hours = '0'+hours;
    if(minutes < 10) minutes = '0'+minutes;
    if(sec < 10) sec = '0'+sec;
    let currentTime = day+'-'+month+'-'+year+'&nbsp;&nbsp;&nbsp;&nbsp;'+hours+':'+minutes+':'+sec;
    return currentTime;
}

function getSunriseTime(seconds) {
    let time = new Date(Date.UTC(1970, 0, 1));
    time.setUTCSeconds(seconds);
    let hours = time.getHours();
    let minutes = time.getMinutes();
    if(hours < 10) hours = '0'+hours;
    if(minutes < 10) minutes = '0'+minutes;
    let sunrise = hours+':'+minutes;
    return sunrise;
}

function getSunsetTime(seconds) {
    let time = new Date(Date.UTC(1970, 0, 1));
    time.setUTCSeconds(seconds);
    let hours = time.getHours();
    let minutes = time.getMinutes();
    if(hours < 10) hours = '0'+hours;
    if(minutes < 10) minutes = '0'+minutes;
    let sunset = hours+':'+minutes;
    return sunset;
}