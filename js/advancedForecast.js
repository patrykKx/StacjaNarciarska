$(document).ready(loadForecast());

function loadForecast() {
    const apiKey = 'f96753ee20f647267d86a70f1c55987f';
    const latitude = '51.23'; //szerokosc
    const longitude = '22.55'; //dlugosc
    const latitude2 = '49.20'; //szerokosc
    const longitude2 = '20.03'; //dlugosc
    const apiCall = 'https://api.openweathermap.org/data/2.5/forecast?lat='+latitude2+'&lon='+longitude2+'&appid='+apiKey+'&units=metric';
    
    fetch(apiCall)
    .then(res => res.json())
    .then(res => {
        console.log(res);
        getForecast(res);
    })
    .catch(err => {
        console.log(err);
        document.getElementById('errorLoadingWeather').innerHTML = 'Nie można załadować danych pogodowych. Spróbuj ponownie później.';
        
    });
}

function getForecast(res) {
    let forecastData = document.getElementById('forecastData');
    let forecastDataHTML = '';
    let timeHeader = '';
    let dayHeader = '';
    let hourHeader = '';
    let previousDayHeader = '';
    let dayNumber = 0;
  
    for(i=0; i<res.list.length-7; i++) {
        timeHeader = res.list[i].dt_txt;
        dayHeader = timeHeader.substring(0, 10);
        hourHeader = timeHeader.substring(11, 16);
        if(hourHeader === '03:00' || hourHeader === '09:00' || hourHeader === '15:00' || hourHeader === '21:00' ) continue;

        if(dayHeader !== previousDayHeader) {
            dayNumber++;
            previousDayHeader = dayHeader;

            forecastDataHTML += '<h4 class="pt-4">'+dayHeader+'</h4>';
            forecastDataHTML += '<div class="table-responsive-md">'+
                                    '<table class="table">'+
                                        '<thead>'+
                                            '<tr id="firstTr">'+
                                              '<th scope="col">#</th>'+
                                              '<th scope="col">00:00</th>'+
                                              '<th scope="col">06:00</th>'+
                                              '<th scope="col">12:00</th>'+
                                              '<th scope="col">18:00</th>'+
                                            '</tr>'+
                                        '</thead>';
            if(dayNumber === 1 && hourHeader === '06:00') {
                forecastDataHTML += '<tbody>'+
                                        '<tr><th scope="row">Temperatura</th>'+
                                            '<td></td><td>'+res.list[i].main.temp+'°C</td><td>'+res.list[i+2].main.temp+'°C</td><td>'+res.list[i+4].main.temp+'°C</td></tr>'+
                                        '<tr><th scope="row">Odczuwalna</th>'+
                                            '<td></td><td>'+res.list[i].main.feels_like+'°C</td><td>'+res.list[i+2].main.feels_like+'°C</td><td>'+res.list[i+4].main.feels_like+'°C</td></tr>'+
                                        '<tr><th scope="row">Wiatr</th>'+
                                            '<td></td><td>'+res.list[i].wind.speed+' m/s'+getWindArrow(i)+'</td><td>'+res.list[i+2].wind.speed+' m/s'+getWindArrow(i+2)+'</td><td>'+res.list[i+4].wind.speed+' m/s'+getWindArrow(i+4)+'</td></tr>'+
                                        '<tr><th scope="row">Opady</th>'+
                                            '<td></td><td>'+getPrecipation(res, i)+getPrecipationImage(res, i)+'</td><td>'+getPrecipation(res, i+2)+getPrecipationImage(res, i+2)+'</td><td>'+getPrecipation(res, i+4)+getPrecipationImage(res, i+4)+'</td></tr>'+
                                        '<tr><th scope="row">Zachmurzenie</th>'+
                                            '<td></td><td>'+res.list[i].clouds.all+' %</td><td>'+res.list[i+2].clouds.all+' %</td><td>'+res.list[i+4].clouds.all+' %</td></tr>'+
                                        '<tr><th scope="row">Wilgotność</th>'+
                                            '<td></td><td>'+res.list[i].main.humidity+' %</td><td>'+res.list[i+2].main.humidity+' %</td><td>'+res.list[i+4].main.humidity+' %</td></tr>'+
                                        '<tr><th scope="row">Ciśnienie</th>'+
                                            '<td></td><td>'+res.list[i].main.pressure+' hPa</td><td>'+res.list[i+2].main.pressure+' hPa</td><td>'+res.list[i+4].main.pressure+' hPa</td></tr>'+
                                    '</tbody></table></div>';
            }
            else if(dayNumber === 1 && hourHeader === '12:00') {
                forecastDataHTML += '<tbody>'+
                                        '<tr><th scope="row">Temperatura</th>'+
                                            '<td></td><td></td><td>'+res.list[i].main.temp+'°C</td><td>'+res.list[i+2].main.temp+'°C</td></tr>'+
                                        '<tr><th scope="row">Odczuwalna</th>'+
                                            '<td></td><td></td><td>'+res.list[i].main.feels_like+'°C</td><td>'+res.list[i+2].main.feels_like+'°C</td></tr>'+
                                        '<tr><th scope="row">Wiatr</th>'+
                                            '<td></td><td></td><td>'+res.list[i].wind.speed+' m/s'+getWindArrow(i)+'</td><td>'+res.list[i+2].wind.speed+' m/s'+getWindArrow(i+2)+'</td></tr>'+
                                        '<tr><th scope="row">Opady</th>'+
                                            '<td></td><td></td><td>'+getPrecipation(res, i)+getPrecipationImage(res, i)+'</td><td>'+getPrecipation(res, i+2)+getPrecipationImage(res, i+2)+'</td></tr>'+
                                        '<tr><th scope="row">Zachmurzenie</th>'+
                                            '<td></td><td></td><td>'+res.list[i].clouds.all+' %</td><td>'+res.list[i+2].clouds.all+' %</td></tr>'+
                                        '<tr><th scope="row">Wilgotność</th>'+
                                            '<td></td><td></td><td>'+res.list[i].main.humidity+' %</td><td>'+res.list[i+2].main.humidity+' %</td></tr>'+
                                        '<tr><th scope="row">Ciśnienie</th>'+
                                            '<td></td><td></td><td>'+res.list[i].main.pressure+' hPa</td><td>'+res.list[i+2].main.pressure+' hPa</td></tr>'+
                                    '</tbody></table></div>';
            }
            else if(dayNumber === 1 && hourHeader === '18:00') {
                forecastDataHTML += '<tbody>'+
                                        '<tr><th scope="row">Temperatura</th>'+
                                            '<td></td><td></td><td></td><td>'+res.list[i].main.temp+'°C</td></tr>'+
                                        '<tr><th scope="row">Odczuwalna</th>'+
                                            '<td></td><td></td><td></td><td>'+res.list[i].main.feels_like+'°C</td></tr>'+
                                        '<tr><th scope="row">Wiatr</th>'+
                                            '<td></td><td></td><td></td><td>'+res.list[i].wind.speed+' m/s'+getWindArrow(i)+'</td></tr>'+
                                        '<tr><th scope="row">Opady</th>'+
                                            '<td></td><td></td><td></td><td>'+getPrecipation(res, i)+getPrecipationImage(res, i)+'</td></tr>'+
                                        '<tr><th scope="row">Zachmurzenie</th>'+
                                            '<td></td><td></td><td></td><td>'+res.list[i].clouds.all+' %</td></tr>'+
                                        '<tr><th scope="row">Wilgotność</th>'+
                                            '<td></td><td></td><td></td><td>'+res.list[i].main.humidity+' %</td></tr>'+
                                        '<tr><th scope="row">Ciśnienie</th>'+
                                            '<td></td><td></td><td></td><td>'+res.list[i].main.pressure+' hPa</td></tr>'+
                                    '</tbody></table></div>';
            }
            else {
                forecastDataHTML += '<tbody>'+
                                        '<tr><th scope="row">Temperatura</th>'+
                                            '<td>'+res.list[i].main.temp+'°C</td><td>'+res.list[i+2].main.temp+'°C</td><td>'+res.list[i+4].main.temp+'°C</td><td>'+res.list[i+6].main.temp+'°C</td></tr>'+
                                        '<tr><th scope="row">Odczuwalna</th>'+
                                            '<td>'+res.list[i].main.feels_like+'°C</td><td>'+res.list[i+2].main.feels_like+'°C</td><td>'+res.list[i+4].main.feels_like+'°C</td><td>'+res.list[i+6].main.feels_like+'°C</td></tr>'+
                                        '<tr><th scope="row">Wiatr</th>'+
                                            '<td>'+res.list[i].wind.speed+' m/s'+getWindArrow(i)+'</td><td>'+res.list[i+2].wind.speed+' m/s'+getWindArrow(i+2)+'</td><td>'+res.list[i+4].wind.speed+' m/s'+getWindArrow(i+4)+'</td><td>'+res.list[i+6].wind.speed+' m/s'+getWindArrow(i+6)+'</td></tr>'+
                                        '<tr><th scope="row">Opady</th>'+
                                            '<td>'+getPrecipation(res, i)+getPrecipationImage(res, i)+'</td><td>'+getPrecipation(res, i+2)+getPrecipationImage(res, i+2)+'</td><td>'+getPrecipation(res, i+4)+getPrecipationImage(res, i+4)+'</td><td>'+getPrecipation(res, i+6)+getPrecipationImage(res, i+6)+'</td></tr>'+
                                        '<tr><th scope="row">Zachmurzenie</th>'+
                                            '<td>'+res.list[i].clouds.all+' %</td><td>'+res.list[i+2].clouds.all+' %</td><td>'+res.list[i+4].clouds.all+' %</td><td>'+res.list[i+6].clouds.all+' %</td></tr>'+
                                        '<tr><th scope="row">Wilgotność</th>'+
                                            '<td>'+res.list[i].main.humidity+' %</td><td>'+res.list[i+2].main.humidity+' %</td><td>'+res.list[i+4].main.humidity+' %</td><td>'+res.list[i+6].main.humidity+' %</td></tr>'+
                                        '<tr><th scope="row">Ciśnienie</th>'+
                                            '<td>'+res.list[i].main.pressure+' hPa</td><td>'+res.list[i+2].main.pressure+' hPa</td><td>'+res.list[i+4].main.pressure+' hPa</td><td>'+res.list[i+6].main.pressure+' hPa</td></tr>'+
                                    '</tbody></table></div>';
            }
            
        }                                     
    }
    forecastData.innerHTML = forecastDataHTML;
    windArrowAnimation(res);
}

function getPrecipation(res, i) {
    let precipation = 0;
    if(res.list[i].rain !== undefined) {
        precipation += res.list[i].rain['3h'];
    }
    if(res.list[i+1].rain !== undefined) {
        precipation += res.list[i+1].rain['3h'];
    }
    if(res.list[i].snow !== undefined) {
        precipation += res.list[i].snow['3h'];
    }
    if(res.list[i+1].snow !== undefined) {
        precipation += res.list[i+1].snow['3h'];
    }
    precipation = precipation.toFixed(2);
    return precipation+'mm';
}

function getPrecipationImage(res, i) {
    let rainPrecipation = 0;
    let snowPrecipation = 0;
    
    if(res.list[i].rain !== undefined) rainPrecipation += res.list[i].rain['3h'];
    if(res.list[i+1].rain !== undefined) rainPrecipation += res.list[i+1].rain['3h'];
    if(res.list[i].snow !== undefined) snowPrecipation += res.list[i].snow['3h'];
    if(res.list[i+1].snow !== undefined) snowPrecipation += res.list[i+1].snow['3h'];
    
    if(rainPrecipation > 0 && snowPrecipation > 0) return '<div><img src="images/snowandrain.png" style="max-height:30px;max-width:30px;"></div>';
    else if(rainPrecipation > 0) return '<div><img src="images/raindrop.png" style="max-height:30px;max-width:30px;"></div>';
    else if(snowPrecipation > 0) return '<div><img src="images/snowflake.png" style="max-height:30px;max-width:30px;"></div>';
    else return '';
} 

function getWindArrow(i) {
    return '<div><img src="images/north.png" class="img-fluid" id="windArrow'+i+'" alt="Arrow"></div>';
}

function windArrowAnimation(res) {
    let windDirection;
    for(a=0; a<res.list.length; a++) {
        windDirection = res.list[a].wind.deg + 180;
        $('#windArrow'+a).css({'transform':'rotate('+windDirection+'deg)'});
    }   
}