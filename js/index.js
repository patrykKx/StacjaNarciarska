$(document).ready(getCurrentConditions());
$(document).ready(getCurrentTariff());
$(document).ready(firstCalendarLoading());

function getCurrentConditions() {
    $.post('controller.php', {
       submit: "showConditions"
    }, 
    function(data, status) {
        let conditionsHTML = '';
        let datetime;
        if(data === null) {
            conditionsHTML += '<h5 class="text-danger font-weight-bolder">Nie można wczytać aktualnych warunków. Spróbuj ponownie później.</h5>';
        }
        else { 
            datetime = data.updateDate.substring(0, data.updateDate.length - 3);
            conditionsHTML += '<div class="row pb-3" style="font-size: 120%">'+
                    '<div class="col-12 py-2">Data aktualizacji: <b>'+datetime+'</b></div>'+
                    '<div class="col-sm-6 py-2">Minimalna pokrywa: <b>'+data['minSnowCover']+' cm</b></div>'+
                    '<div class="col-sm-6 py-2">Maksymalna pokrywa: <b>'+data['maxSnowCover']+' cm</b></div>'+
                    '<div class="col-sm-6 py-2">Rodzaj śniegu: <b>'+data['snowType']+'</b></div>'+
                    '<div class="col-sm-6 py-2">Warunki: <b>'+data['conditions']+'</b></div>'+
                '</div>';
        if(data['description'] !== null) conditionsHTML += '<p class="col-sm-8 mx-auto p-2" style="font-size: 130%; border: black solid 1px">'+data['description']+'</p>';
        }
        document.getElementById('currentConditions').innerHTML = conditionsHTML;
    },
    "json"
    );
}

function getCurrentTariff() {
    console.log("Daj cennik");
    $.post('controller.php', {
       submit: "showTariff",
       page: "mainPage"
    }, 
    function(data, status) {
        let tariffHTML = '';
        if(data === null) {
           tariffHTML += '<h5 class="text-danger font-weight-bolder">Nie można wczytać cennika. Spróbuj ponownie później.</h5>';
        }
        else {
            tariffHTML += '<div class="table-responsive-md">'+
                    '<table class="table">'+
                            '<thead>'+
                                '<tr id="firstTr"><th scope="col">#</th><th scope="col">1 godzina</th><th scope="col">2 godziny</th><th scope="col">3 godziny</th><th scope="col">Cały dzień</th></tr>'+
                            '</thead>'+
                            '<tbody>'+
                            '<tr><td>Karnety</td><td>'+data['skipass_1h']+' zł</td><td>'+data['skipass_2h']+' zł</td><td>'+data['skipass_3h']+' zł</td><td>'+data['skipass_allDay']+' zł</td></tr>'+
                            '<tr><td>Wypożyczalnia (komplet)</td><td>'+data['setRental_1h']+' zł</td><td>'+data['setRental_2h']+' zł</td><td>'+data['setRental_3h']+' zł</td><td>'+data['setRental_allDay']+' zł</td></tr>'+
                            '<tr><td>Wypożyczalnia (jedna rzecz)</td><td>'+data['oneItemRental_1h']+' zł</td><td>'+data['oneItemRental_2h']+' zł</td><td>'+data['oneItemRental_3h']+' zł</td><td>'+data['oneItemRental_allDay']+' zł</td></tr>'+
                            '<tr><td>Lekcja z instruktorem *</td><td>'+data['lesson_1h']+' zł</td><td>'+data['lesson_2h']+' zł</td><td>'+data['lesson_3h']+' zł</td><td>-</td></tr>'+
                '</tbody></table></div>';
            tariffHTML += '<p class="text-left">* Cena za jedną osobę. Koszt kolejnych osób zmniejsza się o 5zł. W lekcji może brać maksymalnie 5 osób.</p>';    
            tariffHTML += '<div class="table-responsive-md">'+
                    '<table class="table">'+
                            '<thead>'+
                                '<tr id="firstTr"><th scope="col" colspan="2">Serwis</th></tr>'+
                            '</thead>'+
                            '<tbody>'+
                            '<tr><td class="col-8">Podstawowy - ostrzenie krawędzi nart lub snowboardu</td><td>'+data['smallService']+' zł</td></tr>'+
                            '<tr><td class="col-8">Kompleksowy - podstawowy serwis + usunięcie starego smaru i naniesienie nowego</td><td>'+data['mediumService']+' zł</td></tr>'+
                            '<tr><td class="col-8">Zaawansowany - średni serwis + uzupełnienie ubytków, wyrównanie ślizgów i polerowanie krawędzi</td><td>'+data['bigService']+' zł</td></tr>'+
                 '</tbody></table></div>'; 
        }   
        document.getElementById('currentTariff').innerHTML = tariffHTML;
    },
    "json"
    );
}

function dayClick(dayId) {
    $.post('controller.php', {
     submit: "showDayDetails",
     day: dayId
     }, 
     function(data, status) {
         let startHour = document.getElementById("startHour");
         let finishHour = document.getElementById("finishHour");
         document.getElementById("dayDescription").hidden = false;
         document.getElementById("day").innerHTML = "Dzień: <b>"+dayId+"</b>";
         if(data.isOpen === '1') {
             document.getElementById("isOpen").innerHTML = "Czy otwarte? <b style='color: green'>TAK</b>";
             startHour.hidden = false;
             finishHour.hidden = false;
             startHour.innerHTML = "Otwarcie: <b>"+data.startTime+"</b>";
             finishHour.innerHTML = "Zamknięcie <b>"+data.finishTime+"</b>";
         }
         else {
             document.getElementById("isOpen").innerHTML = "Czy otwarte? <b style='color: red'>NIE</b>";
             startHour.hidden = true;
             finishHour.hidden = true;
         }
     },
     "json"
    );
}