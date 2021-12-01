function dayClick(dayId) {
    $.post('controller.php', {
        submit: "showDayDetails",
        day: dayId
        }, 
        function(data, status) {
            displayDayDetails(data);
            let startHour = document.getElementById("startHour");
            let finishHour = document.getElementById("finishHour");
            let inputDay = document.getElementById("inputDay");
            document.getElementById("dayDescription").hidden = false;
            document.getElementById("day").innerHTML = "Dzień: <b>"+dayId+"</b>";
            if(data.isOpen === '1') {
                document.getElementById("isOpen").innerHTML = "Czy otwarte? <b style='color: green'>TAK</b>";
                startHour.hidden = false;
                finishHour.hidden = false;
                startHour.innerHTML = "Otwarcie: <b>"+data.startTime+"</b>";
                finishHour.innerHTML = "Zamknięcie <b>"+data.finishTime+"</b>";
                inputDay.value = dayId;
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

function displayDayDetails(data) {
    
}

function allowListeningToNumberInput() {
    let numberOfHours = document.getElementById("numberOfHours");
    let cost = document.getElementById("cost");
    numberOfHours.addEventListener("change", function () {
        $.post('controller.php', {
            submit: "getTicketCost",
            numberOfHours: numberOfHours.value
        }, 
        function(data, status) {
            cost.value = data;
        }
        ); 
    });
}

function getTicketList(year) {
    $.post('controller.php', {
        submit: "getTicketList",
        year: year
    }, 
        function(data, status) {
            let ticketsContainer = document.getElementById("ticketsContainer");
            let HTML = '';
            if(data.startTime.length === 0) {
                HTML = '<h5 class="text-danger py-2">Brak karnetów!</h5>';
            }
            else {
                HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Dzień</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th><th scope="col">Koszt</th><th scope="col">Status</th><th scope="col">Akcja</th></tr></thead><tbody>';
                for(let i=0; i<data.startTime.length; i++) {
                    let day = data.startTime[i].substring(0, 10);
                    let startTime = data.startTime[i].substring(11, 16);
                    let finishTime = data.finishTime[i].substring(11, 16);
                    HTML += '<tr><td>'+day+'</td><td>'+startTime+'</td><td>'+finishTime+'</td><td>'+data.cost[i]+' zł</td><td>'+data.status[i]+'</td><td>'+getTicketButtonHTML(data.status[i], data.id[i])+'</td></tr>';
                }
                HTML += '</tbody></table></div>';
            }
            ticketsContainer.innerHTML = HTML;
        },
        "json"
    ); 
}

function getTicketButtonHTML(status, id) {
    if(status === 'Nierozpoczęty') {
        return '<form method="POST" action="controller.php"><input type="hidden" name="ticketId" value="'+id+'"/><button type="submit" name="submit" value="cancelTicket" class="btn btn-outline-danger">Anuluj</button></form>';
    }
    else {
        return '-';
    }
}

function prevYear() {
    let yearInput = document.getElementById("ticketYearInput");
    let year = parseInt(yearInput.value) - 1;
    yearInput.value = year;
    getTicketList(year);
}

function nextYear() {
    let yearInput = document.getElementById("ticketYearInput");
    let year = parseInt(yearInput.value) + 1;
    yearInput.value = year;
    getTicketList(year);
}






