function dayClickLesson(id) {
    dayClick(id);
    getInstructorTimetable(id);
    getInstructorList(id);
}

function getInstructorTimetable(day) {
    $.post('controller.php', {
        submit: "getInstructorTimetableByDay",
        day: day
        }, 
        function(data, status) {
           displayInstructorValues(data, day);
        },
        "json"
    );
}

function displayInstructorValues(data, day) {
    let container = document.getElementById("instructorTimetableContainer");
    let HTML = "";
    if(data.login.length === 0) HTML = '<h4 class="text-danger font-weight-bolder">Brak instruktorów w wybranym dniu!</h4>';
    else {
        HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Instruktor</th><th scope="col">Narciarstwo</th><th scope="col">Snowboard</th><th scope="col">Obecność</th><th scope="col">Zarezerwowane lekcje</th></tr></thead><tbody>';
        for(let i=0; i<data.login.length; i++) {
            HTML += '<tr><td>'+data.firstname[i]+' '+data.surname[i]+' ('+data.login[i]+')</td><td>'+getYesOrNoImage(data.skiingInstructor[i])+'</td><td>'+getYesOrNoImage(data.snowboardInstructor[i])+'</td><td>'+data.startTimeWorking[i].substring(11, 16)+' - '+data.finishTimeWorking[i].substring(11, 16)+'</td><td id="lesson'+data.login[i]+'">'+getInstructorLessonsByDay(day, data.login[i])+'</td></tr>';
        }
        HTML += '</tbody></table></div>';
    }
    container.innerHTML = HTML;
}

function getYesOrNoImage(isInstructor) {
    if(isInstructor == 1) return '<img class="img-fluid" src="images/yes.jpg" alt="Yes"/>';
    else return '<img class="img-fluid" src="images/no.jpg" alt="No"/>';
}

function getInstructorLessonsByDay(day, instructorLogin) {  
    $.post('controller.php', {
        submit: "getInstructorLessonsByDay",
        day: day,
        login: instructorLogin
        }, 
        function(data, status) {
            let HTML = "";
            if(data.lessonStartTime.length === 0) {
                HTML += "-";
            }
            else {
                for(let k=0; k<data.lessonStartTime.length; k++) {
                    HTML += data.lessonStartTime[k].substring(11, 16)+" - "+data.lessonFinishTime[k].substring(11, 16)+"<br/>";
                }
            }
            document.getElementById("lesson"+instructorLogin).innerHTML = HTML;
        },
        "json"
    );
    return "";
}

function getInstructorScheduleOnMap(year, month) {
    $.post('controller.php', {
        submit: "showInstructorDays",
        year: year,
        month: month
        }, 
        function(data, status) {
            for(let i=0; i<data.length; i++) {
                if(data[i] !== null) {
                    let id = "'"+data[i]+"'";
                    document.getElementById(id).style.boxShadow = 'inset 0 0 0 5px #2c20bd';
                }
            }
        },
        "json"
    );
}

function allowListeningToLessonInputs() {
    document.getElementById("numberOfHours").addEventListener("change", function () {
        getLessonCost(); 
        
    });
    document.getElementById("numberOfParticipants").addEventListener("change", function () {
        getLessonCost(); 
    });
    $("#inputDay").on("change", function() {
        console.log(this.value);
        getInstructorList(this.value);
    });
    $("#instructorTypeRadios").on("click", "input", function() {
        getInstructorList();
    });
}

function getLessonCost() {
    let hours = document.getElementById("numberOfHours").value;
    let participants = document.getElementById("numberOfParticipants").value;
    let costInput = document.getElementById("cost");
    if((hours == 1 || hours == 2 || hours == 3) && (participants == 1 || participants == 2 || participants == 3 || participants == 4 || participants == 5)) {
        $.post('controller.php', {
            submit: "getLessonCost",
            hours: hours,
            participants: participants
            }, 
            function(data, status) {
                console.log(data);
                costInput.value = data;
            }
        );
    }
    else {
        costInput.value = "";
    }
}

function getInstructorList(dayId = "") {
    //Jeśli zmieniamy date za pomocą kalendarza lub inputa to podajemy date w argumencie, jesli zmieniamy typ sportu odczytujemy date za pomocą getEleById.value
    let type = "";
    let skiingRadio = document.getElementById("lessonSkiRadio");
    let snowboardRadio = document.getElementById("lessonSnowboardRadio");
    let day = "";
    if(dayId === "") day = document.getElementById("inputDay").value;
    else day = dayId;
    if(skiingRadio.checked) type = 'Narciarstwo';
    else if(snowboardRadio.checked) type = 'Snowboard';
    if(day !== "" && type !== "") {
        //Wyświetlamy instruktorów w danym dniu
        $.post('controller.php', {
            submit: "getInstructorList",
            type: type,
            day: day
            }, 
            function(data, status) {
                console.log(data);
                if(data.login.length === 0) $("#instructorSelect").empty();
                else putValuesToInstructorSelect(data);
            },
            "json"
        );
    }
    else {
        $("#instructorSelect").empty();
    }
}

function putValuesToInstructorSelect(data) {
    let nameSelect = document.getElementById('instructorSelect');
    $("#instructorSelect").empty().append('<option selected disabled>Wybierz instruktora</option>');
    for (let i=0; i<data.firstname.length; i++){
        let option = document.createElement('option');
        option.value = data.firstname[i]+" "+data.surname[i]+" ("+data.login[i]+")";
        option.innerHTML = data.firstname[i]+" "+data.surname[i]+" ("+data.login[i]+")";
        nameSelect.appendChild(option);
    }
}

function getUserLessons() {
    $.post('controller.php', {
        submit: "getUserLessons"
        }, 
        function(data, status) {
            console.log(data);
            document.getElementById("userLessonsContainer").innerHTML = displayUserLessons(data);
        },
        "json"
    );
}

function displayUserLessons(data) {
    let HTML = "";
    if(data.startDate.length === 0) {
        HTML += '<h5 class="text-danger py-2">Brak zarezerwowanych lekcji!</h5>';
    }
    else {
        HTML += '<div class="table-responsive-lg"><table class="table"><thead><tr id="firstTr"><th scope="col">Dzień</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th><th scope="col">Instruktor</th><th scope="col">Rodzaj</th><th scope="col">Uczestnicy</th><th scope="col">Koszt</th><th scope="col">Akcja</th></tr></thead><tbody>';
        for(let i=0; i<data.startDate.length; i++) {
            HTML += '<tr><td>'+data.startDate[i].substring(0, 10)+'</td><td>'+data.startDate[i].substring(11, 16)+'</td><td>'+data.finishDate[i].substring(11, 16)+'</td><td>'+data.instructor[i]+'</td><td>'+data.type[i]+'</td><td>'+data.numberOfParticipants[i]+'</td><td>'+data.cost[i]+' zł</td><td>'+getLessonAction(data.idLesson[i], data.isCancelled[i], data.startDate[i])+'</td></tr>';
        }
        HTML += '</tbody></table></div>';
    }
    return HTML;
}

function getLessonAction(idLesson, isCancelled, startDate) {
    let HTML = "";
    let objectStartDate = new Date(startDate);
    let objectNow = new Date();
    let startDateUNIX = objectStartDate.getTime()/1000; //Czas w UNIX
    let nowUNIX = objectNow.getTime()/1000;
    if(isCancelled == "1") HTML += 'Odwołana';
    else if(isCancelled == "0" && startDateUNIX > nowUNIX+3600){
        let onClick = "setCancelLessonModalHTML('"+idLesson+"','Odwołanie lekcji','Czy na pewno chcesz odwołać zamówioną lekcję?')";
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
                                    '<button type="submit" class="btn btn-danger" name="submit" value="cancelLessonByUser">Odwołaj</button>'+
                                '</div>'+
                        '</form>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
    document.getElementById("modalContainer").innerHTML = HTML;
}
