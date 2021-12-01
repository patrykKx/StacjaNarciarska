function allowListeningToUserLoginInput() {
    $("#userLoginInput").on("input", function() {
        let login = $(this)[0].value;
        getMatchingLogins(login);
    });
}

function getMatchingLogins(login) {
    $.post('controller.php', {
        submit: "getMatchingLogins",
        login: login
        }, 
        function(data, status) {
            displaySearchUserList(data);
        },
        "json"
    );
}

function displaySearchUserList(data) {
    $("#userResult").html("");
    $("#userInfo").html("");
    for(let i=0; i<data.length; i++) {
        $("#userResult").append('<li class="list-group-item" id="'+data[i]+'">'+data[i]+'</li>');
    }
    $("#userResult li").on("click", function() {
        let login = $(this)[0].id;
        getInfoAboutUser(login);
        $("#userLoginInput").val(login);
        $("#userResult").html("");
    });
}

function getInfoAboutUser(login) {
    $.post('controller.php', {
        submit: "getInfoAboutUser",
        login: login
        }, 
        function(data, status) {
            console.log(data);
            displayUserInfo(data);
        },
        "json"
    );
}

function displayUserInfo(data) {
    if(data.login === null) $("#userInfo").html('<h4 class="text-danger py-2">Nie ma takiego użytkownika!</h4>');
    else {
        let HTML = '<div class="col-sm-6 py-2">Imię: <b>'+data.firstname+'</b></div>'+
                   '<div class="col-sm-6 py-2">Nazwisko: <b>'+data.surname+'</b></div>'+
                   '<div class="col-sm-6 py-2">Login: <b>'+data.login+'</b></div>'+
                   '<div class="col-sm-6 py-2">Email: <b>'+data.email+'</b></div>'+
                   '<div class="col-sm-6 py-2">Data urodzenia: <b>'+data.birthdate+'</b></div>'+
                   '<div class="col-sm-6 py-2">Utworzenie konta: <b>'+data.creationDate+'</b></div>'+
                   '<div class="col-sm-6 py-2">Ilość zakupionych karnetów: <b>'+data.ticket.uncancelled+'</b></div>'+
                   '<div class="col-sm-6 py-2">Ilość anulowanych karnetów: <b>'+data.ticket.cancelled+'</b></div>'+
                   '<div class="col-sm-6 py-2">Ilość zarezerwowanych lekcji: <b>'+data.lesson.uncancelled+'</b></div>'+
                   '<div class="col-sm-6 py-2">Ilość anulowanych lekcji: <b>'+data.lesson.cancelled+'</b></div>'+
                   '<div class="col-sm-6 py-2">Ilość wypożyczeń: <b>'+data.hire+'</b></div>'+
                   '<div class="col-sm-6 py-2">Ilość serwisów: <b>'+data.service+'</b></div>'+
                   '<div class="col-sm-6 py-2">Łączny koszt: <b>'+data.totalCost+'zł</b></div>'+
                   '<div class="col-sm-6 py-2">'+prepareDeleteUserButton(data.id)+'</div>';
        $("#userInfo").html(HTML);
    }
}

function prepareDeleteUserButton(userID) {
    let onClick = "setDeleteModalHTML('deletePerson','"+userID+"','Użytkownik','Usunięcie użytkownika','Czy na pewno chcesz usunąć użytkownika z listy?')";
    return '<button class="btn btn-danger col-6" data-toggle="modal" data-target="#deleteModal" onClick="'+onClick+'">Usuń użytkownika</button>';
}
    




