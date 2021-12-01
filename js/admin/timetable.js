function dayClickTimetable(dayId) {
    $.post('controller.php', {
        submit: "showDayDetails",
        day: dayId
        }, 
        function(data, status) {
            let isOpen = document.getElementById("isOpen");
            let startHour = document.getElementById("startHour");
            let finishHour = document.getElementById("finishHour");
            document.getElementById("dayDescription").hidden = false;
            document.getElementById("day").innerHTML = "Dzień: <b>"+dayId+"</b>";
            if(data.isOpen === '1') {
                isOpen.innerHTML = "Czy otwarte? <b style='color: green'>TAK</b>";
                startHour.hidden = false;
                finishHour.hidden = false;
                startHour.innerHTML = "Otwarcie: <b>"+data.startTime+"</b>";
                finishHour.innerHTML = "Zamknięcie <b>"+data.finishTime+"</b>";
            }
            else {
                isOpen.innerHTML = "Czy otwarte? <b style='color: red'>NIE</b>";
                startHour.hidden = true;
                finishHour.hidden = true;
            }
        },
        "json"
    );
    document.getElementById("addTimetable").hidden = false;
    document.getElementById("timetableTable").hidden = false;
    setDateInInput(dayId);
    getTimetable(dayId);
}

function setDateInInput(day) {
    let date = new Date(day);
    let dayNumber = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    let correctDate = date.getFullYear()+"-"+(month)+"-"+(dayNumber);
    $("#dayInput").val(correctDate);
}

function addTimetableLoading() {
    $("#workerTypeRadios").on("click", "input", function() { 
        getWorkersList($(this)[0].value);
    });
    document.getElementById("workerName").addEventListener("change", function() {
        $('#workerName').on('click', function() {
            let login = this.value.substring(this.value.indexOf("(") + 1, this.value.lastIndexOf(")"));
            document.getElementById("workerLogin").value = login;
        });
    });
}

function getWorkersList(role) {
    let addTimetableDetails = document.getElementById("addTimetableDetails");
    addTimetableDetails.hidden = false;
    $.post('controller.php', {
        submit: "showWorkersNames",
        role: role
        }, 
        function(data, status) {
            if(data.firstnames.length === 0) addTimetableDetails.innerHTML = '<h5 class="text-danger py-2">Brak pracowników!</h5>';
            else putValuesToInputs(data);
        },
        "json"
    );
}

function putValuesToInputs(data) {
    let nameSelect = document.getElementById('workerName');
    $("#workerName").empty().append('<option selected disabled></option>');
    for (let i=0; i<data.firstnames.length; i++){
        let option = document.createElement('option');
        option.value = data.surnames[i]+" "+data.firstnames[i]+" ("+data.logins[i]+") "+getInstructorSport(data.instructors[i]);
        option.innerHTML = data.surnames[i]+" "+data.firstnames[i]+" ("+data.logins[i]+") "+getInstructorSport(data.instructors[i]);
        nameSelect.appendChild(option);
    }
}

function getInstructorSport(sport) {
    switch(sport) {
        case "both": return " - Narciarstwo, Snowboard"; break;
        case "ski": return " - Narciarstwo"; break;
        case "snowboard": return " - Snowboard"; break;
        default: return ""; break;
    }
}

function getTimetable(day) {
    $.post('controller.php', {
        submit: "showDayTimetable",
        date: day
        }, 
        function(data, status) {
            let serviceman = {
                "id": [],
                "surname" : [],
                "firstname" : [],
                "login" : [],
                "startTime" : [],
                "finishTime" : []
            };
            let technicans = {
                "id": [],
                "surname" : [],
                "firstname" : [],
                "login" : [],
                "startTime" : [],
                "finishTime" : []
            };
            let instructors = {
                "id": [],
                "surname" : [],
                "firstname" : [],
                "login" : [],
                "startTime" : [],
                "finishTime" : [],
                "instructorType": []
            };
            for(let i=0; i<data.surnames.length; i++) {
                if(data.roles[i] === 'Serwisant') {
                    serviceman.id.push(data.ids[i]);
                    serviceman.surname.push(data.surnames[i]);
                    serviceman.firstname.push(data.firstnames[i]);
                    serviceman.login.push(data.logins[i]);
                    serviceman.startTime.push(data.startTimes[i]);
                    serviceman.finishTime.push(data.finishTimes[i]);
                }
                else if(data.roles[i] === 'Techniczny') {
                    technicans.id.push(data.ids[i]);
                    technicans.surname.push(data.surnames[i]);
                    technicans.firstname.push(data.firstnames[i]);
                    technicans.login.push(data.logins[i]);
                    technicans.startTime.push(data.startTimes[i]);
                    technicans.finishTime.push(data.finishTimes[i]);
                }
                else if(data.roles[i] === 'Instruktor') {
                    instructors.id.push(data.ids[i]);
                    instructors.surname.push(data.surnames[i]);
                    instructors.firstname.push(data.firstnames[i]);
                    instructors.login.push(data.logins[i]);
                    instructors.startTime.push(data.startTimes[i]);
                    instructors.finishTime.push(data.finishTimes[i]);
                    instructors.instructorType.push(data.instructorType[i]);
                }
            }
            displayTimetable(serviceman, "Serwisanci", day);
            displayTimetable(technicans, "Techniczni", day);
            displayTimetable(instructors, "Instruktorzy", day);
        },
        "json"
    );
}

function displayTimetable(workers, role, day) {
    day = "'"+day+"'";
    let HTML = '<h5 class="pt-3">'+role+'</h5>';
    if(workers.surname.length !== 0) {
        HTML += '<div class="table-responsive-md"><table class="table">'+
                    '<thead><tr id="firstTr"><th scope="col">Nazwisko i imię</th><th scope="col">Login</th><th scope="col">Start</th><th scope="col">Koniec</th>'+instructorTimetableTableHead(workers)+'<th scope="col">Akcja</th></tr></thead>'+
                    '<tbody>';
        for(let j=0; j<workers.surname.length; j++) {
            let param = workers.id[j];
            let param2 = day;
            let onClick = "setDeleteModalHTML('deleteWorkerFromTimetable','"+param+"',"+param2+",'Modyfikacja grafiku','Czy na pewno chcesz usunąć grafik użytkownika?')";
            HTML += '<tr><td>'+workers.surname[j]+' '+workers.firstname[j]+'</td><td>'+workers.login[j]+'</td><td>'+workers.startTime[j]+'</td><td>'+workers.finishTime[j]+'</td>'+instructorTimetableSports(workers, j)+'<td><button class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal" onClick="'+onClick+'">Usuń</button></td></tr>';
            //onClick="deleteWorkerFromTimetable('+workers.id[j]+','+day+')"
        }
        HTML += '</tbody></table></div>';
    }
    else {
        HTML += '<div class="text-danger">Brak pracowników w grafiku!</div>';
    }
    
    switch(role) {
        case "Serwisanci": document.getElementById("timetableServiceman").innerHTML = HTML; break;
        case "Techniczni": document.getElementById("timetableTechnicans").innerHTML = HTML; break;
        case "Instruktorzy": document.getElementById("timetableInstructors").innerHTML = HTML; break;
    }
}

function instructorTimetableTableHead(workers) {
    if(typeof workers.instructorType === 'undefined') return "";
    else return '<th scope="col">Sport</th>';
}

function instructorTimetableSports(workers, j) {
    if(typeof workers.instructorType === 'undefined') return "";
    else return isInstructorRow(workers.instructorType[j]);
}

function deleteWorkerFromTimetable(id, day) {
    let fullDate = new Date(day);
    let year = fullDate.getFullYear();
    let month = fullDate.getMonth()+1;
    $.post('controller.php', {
        submit: "deleteFromTimetable",
        id: id
        }, 
        function(data, status) {
            
        },
        "json"
    );
    $("td").css("boxShadow","none");
    getWorkerScheduleOnMap(year, month);
    getTimetable(day);
    removeDeleteModalHTML();
}

function getWorkerScheduleOnMap(year, month) {
    $.post('controller.php', {
        submit: "showWorkerDays",
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