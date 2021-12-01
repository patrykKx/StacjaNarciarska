function getUserLogins() {
    $.post('controller.php', {
        submit: "showUserLogins"
        }, 
        function(data, status) {
            putValuesToUserLoginInput(data);
        },
        "json"
    );
}

function putValuesToUserLoginInput(data) {
    let userSelect = document.getElementById('userLogin');
    $("#userSelect").empty().append('<option selected disabled></option>');
    for (let i=0; i<data.length; i++){
        let option = document.createElement('option');
        option.value = data[i];
        option.innerHTML = data[i];
        userSelect.appendChild(option);
    }
}

function allowListeningToServiceTypeInput() {
    let serviceType = document.getElementById("serviceType");
    let cost = document.getElementById("serviceCost");
    serviceType.addEventListener("change", function() {
        $.post('controller.php', {
            submit: "getServiceCost",
            type: document.getElementById("serviceType").value
            }, 
            function(data, status) {
                cost.value = data;
            }
        );
    });
}

function getServiceInProgress() {
    $.post('controller.php', {
        submit: "getCurrentService",
        status: "W realizacji"
        }, 
        function(data, status) {
            let HTML = getServiceHTML(data); 
            document.getElementById("serviceInProgressContainer").innerHTML = HTML;
        },
        "json"
    );
}

function getServiceReadyToPickUp() {
    $.post('controller.php', {
        submit: "getCurrentService",
        status: "Gotowe do odebrania"
        }, 
        function(data, status) {
            document.getElementById("serviceReadyToPickUpContainer").innerHTML = getServiceHTML(data);
        },
        "json"
    );
}

function getServiceHTML(data) {
    let HTML = '';
    if(data.login.length === 0) {
        HTML += '<h5 class="text-danger py-2">Brak prac serwisowych w realizacji!</h5>';
    }
    else {
        HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Login</th><th scope="col">Nazwa</th><th scope="col">Rodzaj</th><th scope="col">Rozpoczęcie</th><th scope="col">Akcja</th></tr></thead><tbody>';
        for(let i=0; i<data.startTime.length; i++) {
            HTML += '<tr><td>'+data.login[i]+'</td><td>'+data.name[i]+'</td><td>'+data.type[i]+'</td><td>'+data.startTime[i].substring(0, 16)+'</td><td>'+getServiceButton(data.id[i], data.status[i])+'</td></tr>';
        }
        HTML += '</tbody></table></div>';
    }
    return HTML;
}

function getServiceButton(id, status) {
    let HTML = '<form method="POST" action="controller.php"><input type="hidden" name="idService" value="'+id+'"/>';
    if(status === "W realizacji") HTML += '<button type="submit" class="btn btn-primary" name="submit" value="changeServiceStatusToReadyToPickUp">Gotowe do odebrania</button>';
    else if(status === "Gotowe do odebrania") HTML += '<button type="submit" class="btn btn-info" name="submit" value="changeServiceStatusToPickedUp">Odebrano</button>';
    HTML += '</form>';
    return HTML;
}