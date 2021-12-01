function getPersonList(personType) {
    $.post('controller.php', {
        submit: "showPersonList",
        personType: personType
        }, 
        function(data, status) {
            $("#personListSpinner").hide();
            displayPersonList(data);
        },
        "json"
    );
}

function autoWorkerList() {
    $("#workersTypeRadios").on("click", "input", function() {
        $("#personListSpinner").show();
        getPersonList($(this)[0].value);
    });
}

function displayPersonList(data) {
    let personListHTML = '';
    if(data.ids.length === 0) {
        personListHTML = '<h5 class="text-danger">Brak osób!</h5>';
    }
    else {
        personListHTML += '<div class="table-responsive-lg"><table class="table">'+
                              '<thead><tr id="firstTr"><th scope="col">Imię i nazwisko</th><th scope="col">Login</th><th scope="col">Data urodzenia</th><th scope="col">Utworzenie konta</th>'+instructorHead(data)+'<th scope="col">Akcja</th></tr></thead><tbody>';
        for(let i=0; i<data.ids.length; i++) {
            personListHTML += getPersonRow(data, i);
        }
        personListHTML += '</tbody></table></div>';
    }

    document.getElementById('workerList').innerHTML = personListHTML;
}

function instructorHead(data) {
    if(data.instructorType[0] === null) {
        return '';
    }
    else {
        return '<th scope="col">Sport</th>';
    }
}

function getPersonRow(data, i) {
    let param = data.ids[i];
    let param2 = data.roles[i];
    let onClick = "setDeleteModalHTML('deletePerson','"+param+"','"+param2+"','Usunięcie użytkownika','Czy na pewno chcesz usunąć użytkownika z listy?')";
    let tableRow = '';
    tableRow += '<tr><td>'+data.firstnames[i]+' '+data.surnames[i]+'</td>';
    tableRow += '<td>'+data.logins[i]+'</td>';
    tableRow += '<td>'+data.birthdates[i]+'</td>';
    tableRow += '<td>'+data.creationDates[i]+'</td>';
    if(param2 === "Instruktor") {
        tableRow += isInstructorRow(data.instructorType[i]);
    }   
    if(data.roles[i] === 'me') tableRow += '<td></td>';
    else tableRow += '<td><button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" onClick="'+onClick+'">Usuń</button></td>';
    return tableRow;
}

function isInstructorRow(data) {
    switch(data) {
        case "both": return '<td>'+"Narciarstwo<br/>Snowboard"+'</td>'; break;
        case "ski": return '<td>'+"Narciarstwo"+'</td>'; break;
        case "snowboard": return '<td>'+"Snowboard"+'</td>'; break;
        default: return ""; break;
    }
}

function deletePerson(id, type) {
    $.post('controller.php', {
        submit: "deletePerson",
        workerId: id
        }, 
        function(data, status) {
             
        },
        "json"
    );
    if(document.getElementById("userResult") !== null) {
        $("#userLoginInput").val("");
        $("#userInfo").html("");
    }
    else {
        getPersonList(type);  
    }
    removeDeleteModalHTML();
}

function allowListeningToWorkerRoleInput() {
    let workerRoleInput = document.getElementById("workerRole");
    let instructorTypeContainer = document.getElementById("instructorTypeContainer");
    workerRoleInput.addEventListener("change", function() {
       if(workerRoleInput.value === "Instruktor") {
           instructorTypeContainer.hidden = false;
       } 
       else {
           instructorTypeContainer.hidden = true;
       }
    });
}