function getMonthAndYear(numberOfMonth) {
    let months = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let month = date.getMonth();
    let year = date.getFullYear();
    return months[month]+" "+year;
}

function putTodayDateToInput() {
    let date = getDate();
    $("#lessonDateInput").val(date);
    getLessonByDay(date);
}

function getDate(addDayNumber=0) {
    let tempDate;
    if(addDayNumber !== 0) {
        let dateFromInput = document.getElementById('lessonDateInput').value;
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

function getLessonByDay(date) {
    console.log(date);
    $.post('controller.php', {
        submit: "getInstructorLessonsByDayToInstructorPanel",
        date: date
        }, 
        function(data, status) {
            console.log(data);
            document.getElementById("instructorLessonContainer").innerHTML = displayLessons(data);
        },
        "json"
    );
}

function prevDay() {
    let date = getDate(-1);
    $("#lessonDateInput").val(date);
    getLessonByDay(date);
}

function nextDay() {
    let date = getDate(1);
    $("#lessonDateInput").val(date);
    getLessonByDay(date);
}

function allowListeningToLessonDateInput() {
    document.getElementById("lessonDateInput").addEventListener("change", function() {
        let date = document.getElementById('lessonDateInput').value;
        getLessonByDay(date);
    });
}

function displayLessons(data) {
    let HTML = '';
    if(data.startDate.length === 0) {
        HTML += '<h5 class="text-danger py-2">Brak lekcji w danym dniu!</h5>';
    }
    else {
        HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Dzień</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th><th scope="col">Użytkownik</th><th scope="col">Rodzaj</th><th scope="col">Uczestnicy</th><th scope="col">Akcja</th></tr></thead><tbody>';
        for(let i=0; i<data.startDate.length; i++) {
            HTML += '<tr><td>'+data.startDate[i].substring(0, 10)+'</td><td>'+data.startDate[i].substring(11, 16)+'</td><td>'+data.finishDate[i].substring(11, 16)+'</td><td>'+data.user[i]+'</td><td>'+data.type[i]+'</td><td>'+data.numberOfParticipants[i]+'</td><td>'+getLessonAction(data.idLesson[i], data.isCancelled[i], data.startDate[i])+'</td></tr>';
        }
        HTML += '</tbody></table></div>';
    }
    return HTML;
}

function getLessonAction(lessonID, isCancelled, startDate) {
    let HTML = ""; 
    let tomorrow = new Date(startDate); //Następny dzień po startDate
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0);
    tomorrow.setMinutes(0);
    tomorrow.setSeconds(0);
    let tomorrowOfLessonUNIX = tomorrow.getTime()/1000; //Czas w UNIX
    let now = new Date();
    let nowUNIX = now.getTime()/1000;

    if(isCancelled == "1") HTML += 'Odwołana';
    else if(isCancelled == "0" && (nowUNIX < tomorrowOfLessonUNIX)){
        let onClick = "setCancelLessonModalHTML('"+lessonID+"','Odwołanie lekcji','Czy na pewno chcesz odwołać lekcję?')";
        HTML += '<button class="btn btn-danger" data-toggle="modal" data-target="#changeStatusModal" onClick="'+onClick+'">Odwołaj</button>';
    }
    else {
        HTML += "-";
    }
    return HTML;
}

function setCancelLessonModalHTML(id, title, text) {
    let HTML = '<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">'+
                    '<div class="modal-dialog">'+
                      '<div class="modal-content">'+
                        '<div class="modal-header">'+
                          '<h5 class="modal-title" id="changeStatusModalLabel">'+title+'</h5>'+
                          '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                          '</button>'+
                        '</div>'+
                        '<div class="modal-body">'+
                          text+
                        '</div>'+
                        '<form method="POST" action="controller.php">'+
                            '<input type="hidden" name="idLesson" value="'+id+'"/>'+
                                '<div class="modal-footer">'+
                                    '<button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>'+
                                    '<button type="submit" class="btn btn-danger" name="submit" value="cancelLessonByInstructor">Odwołaj</button>'+
                                '</div>'+
                        '</form>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
    document.getElementById("modalContainer").innerHTML = HTML;
}


