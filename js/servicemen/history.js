var numberOfMonth = 0;

function getServiceHistory(year, month) {
    $.post('controller.php', {
        submit: "getServiceHistory",
        month: month,
        year: year
        }, 
        function(data, status) {
            let HTML = '';
            if(data.login.length === 0) {
                HTML += '<h5 class="text-danger py-2">Brak zakończonych prac serwisowych!</h5>';
            }
            else {
                HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Login</th><th scope="col">Nazwa</th><th scope="col">Rodzaj</th><th scope="col">Koszt</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th></tr></thead><tbody>';
                for(let i=0; i<data.startTime.length; i++) {
                    HTML += '<tr><td>'+data.login[i]+'</td><td>'+data.name[i]+'</td><td>'+data.type[i]+'</td><td>'+data.cost[i]+'</td><td>'+data.startTime[i].substring(0, 16)+'</td><td>'+data.finishTime[i].substring(0, 16)+'</td></tr>';
                }
                HTML += '</tbody></table></div>';
            }
            document.getElementById("serviceHistory").innerHTML = HTML;
        },
        "json"
    );
}

function loadServiceHeader(numberOfMonth) {
    let serviceHeaderHTML = '';
    let monthHeader = getMonthAndYear(numberOfMonth);
    serviceHeaderHTML += '<div class="row pb-1">'+
                            '<div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onClick="previousMonth()"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>'+
                            '<div class="col-6" id="monthAndYear">'+monthHeader+'</div>'+
                            '<div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onClick="nextMonth()"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button>'+
                        '</div>';
    document.getElementById("serviceHeader").innerHTML = serviceHeaderHTML;
    getServiceHistory(getYear(numberOfMonth), getMonth(numberOfMonth));
}

function getMonthAndYear(numberOfMonth) {
    let months = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let month = date.getMonth();
    let year = date.getFullYear();
    return months[month]+" "+year;
}

function getYear(numberOfMonth) {
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let year = date.getFullYear();
    return year;
}

function getMonth(numberOfMonth) {
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let month = date.getMonth()+1;
    return month;
}

function previousMonth() {
    numberOfMonth--;
    loadServiceHeader(numberOfMonth);
}

function nextMonth() {
    numberOfMonth++;
    loadServiceHeader(numberOfMonth);
}

function putTodayDateToInput() {
    let date = getDate();
    $("#hireDateInput").val(date);
    getHireHistoryByDay(date);
}

function getDate(addDayNumber=0) {
    let tempDate;
    if(addDayNumber !== 0) {
        let dateFromInput = document.getElementById('hireDateInput').value;
        tempDate = new Date(dateFromInput);
    }
    else {
        tempDate = new Date();
    }
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate()+addDayNumber);
    let month = date.getMonth()+1;
    if(month < 10) month = "0"+month;
    let day = date.getDate();
    if(day < 10) day = "0"+day;
    return date.getFullYear()+"-"+month+"-"+day;
}

function getHireHistoryByDay(date) {
    $.post('controller.php', {
        submit: "getHireHistory",
        date: date
        }, 
        function(data, status) {
            document.getElementById("hireHistoryContainer").innerHTML = getHireHistoryHTML(data);
        },
        "json"
    );
}

function prevDay() {
    let date = getDate(-1);
    $("#hireDateInput").val(date);
    getHireHistoryByDay(date);
}

function nextDay() {
    let date = getDate(1);
    $("#hireDateInput").val(date);
    getHireHistoryByDay(date);
}

function allowListeningToHireHistoryDateInput() {
    document.getElementById("hireDateInput").addEventListener("change", function() {
        let date = document.getElementById('hireDateInput').value;
        getHireHistoryByDay(date);
    });
}

function getHireHistoryHTML(data) {
    let HTML = '';
    if(data.idHire.length === 0) {
        HTML += '<h5 class="text-danger py-2">Brak wypożyczeń w danym dniu!</h5>';
    }
    else {
        HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Login</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th><th scope="col">Sprzęt</th><th scope="col">Koszt</th></tr></thead><tbody>';
        let tempIdHire = '';
        for(let i=0; i<data.idHire.length; i++) {
            if(data.idHire[i] === tempIdHire) continue;
            HTML += '<tr><td>'+data.login[i]+'</td><td>'+data.startTime[i].substring(0, 16)+'</td><td>'+data.finishTime[i].substring(0, 16)+'</td><td>'+getHireEquipment(data, i)+'</td><td>'+data.cost[i]+' zł</td></tr>';
            tempIdHire = data.idHire[i];
        }
        HTML += '</tbody></table></div>';
    }
    return HTML;
}
