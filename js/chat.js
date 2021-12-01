$("#peopleTypeRadios").on("click", "input", function() {
    getPeopleList($(this)[0].value);
    $("#recentlyPeople div").css("background-color", "#fafafa");
});

$("#searchPeopleInput").on("change", function() {
    document.getElementById("messageSubmitDiv").hidden = false;
    getMessages($(this)[0].value);
});

$("#sendMessageBTN").on("click", function() {
    let text = $("#messageTextarea").val();
    let receiver = document.getElementById("messageHeader").innerHTML;
    if(text.trim() != "" && receiver.trim() != "") {
        saveMessage(text, receiver);
        $("#messageTextarea").val("");
    }
});

$(document).ready(getRecentlyMessagePeople());
$(document).ready(setInterval(getRecentlyMessagePeople, 5000));

function getRecentlyMessagePeople() {
    $.post('controller.php', {
        submit: "getRecentlyMessagePeople"
        }, 
        function(data, status) {
            displayMessagePeople(data);
        },
        "json"
    );
}

$("#recentlyPeople").on("click", "div", function() {
    $("#searchPeopleInput").empty().append('<option selected disabled></option>');
    $("input:radio").prop('checked', false);
    $(this).css("border", "none");
    document.getElementById("messageSubmitDiv").hidden = false;
    getMessages(this.textContent);
});

function displayMessagePeople(data) {
    let recentlyPeople = document.getElementById("recentlyPeople");
    let loginHeader = document.getElementById("messageHeader");
    recentlyPeople.innerHTML = "";
    if(data.login.length !== 0) {
        for(let i=0; i<data.login.length; i++) {
            let newDiv = document.createElement("div");
            newDiv.classList.add("col-12");
            newDiv.classList.add("mb-1");
            newDiv.classList.add("d-flex");
            newDiv.classList.add("justify-content-center");
            newDiv.classList.add("recentlyPeopleDIV");
            //newDiv.style.height = "70px";
            //newDiv.style.backgroundColor = "#fafafa";
            newDiv.setAttribute("id", data.login[i]);
            newDiv.innerHTML = '<h5 class="my-auto">'+data.login[i]+'</h5>';
            if(data.unreadMessagesFromPerson[i] === true && loginHeader.innerHTML !== data.login[i]) newDiv.style.border = "thick solid #c22d19"; 
            recentlyPeople.appendChild(newDiv);     
        }
    } 
}

function getPeopleList(type) {
    $.post('controller.php', {
        submit: "getPersonListToChat",
        personType: type
        }, 
        function(data, status) {
            putValuesToSearchInput(data, type);
        },
        "json"
    );
}

function putValuesToSearchInput(data, peopleType) {
    let nameSelect = document.getElementById('searchPeopleInput');
    $("#searchPeopleInput").empty().append('<option selected disabled>Wybierz z listy</option>');
    for (let i=0; i<data.login.length; i++){
        let option = document.createElement('option');
        if(peopleType === "Użytkownik") {
            option.value = data.login[i];
            option.innerHTML = data.login[i];
        }
        else {
            option.value = data.login[i];
            option.innerHTML = data.surname[i]+" "+data.firstname[i]+" ("+data.login[i]+")";
        }
        nameSelect.appendChild(option);
    }
}

function getMessages(login) {
    $.post('controller.php', {
        submit: "getMessages",
        who: login
        }, 
        function(data, status) {
            displayMessages(data, login);
        },
        "json"
    );
}

function displayMessages(data, login) {
    let messagesField = document.getElementById("messagesField");
    let messageHeader = document.getElementById("messageHeader");
    messagesField.innerHTML = "";
    messageHeader.innerHTML = login;
    let HTML = "";
    let messageDate = "";
    if(data.content.length !== 0)  {
        for(let i=0; i<data.content.length; i++) {
            if(messageDate !== data.date[i].substring(0, 10)) {
                messageDate = data.date[i].substring(0, 10);
                HTML += '<div class="col-12 text-center pt-2 pb-1">'+messageDate+'</div>';
            }
            HTML += '<div class="col-12 px-0 pb-2"><p class="';
            if(data.amIsender[i] == false) HTML += 'float-left myMessage ml-1 rounded p-2 mb-0">';
            else HTML += 'float-right yourMessage mr-1 rounded p-2 mb-0">';
            HTML += data.content[i]+'</p></div>';  
        }
        messagesField.innerHTML = HTML; 
        messagesField.scrollTop = messagesField.scrollHeight;
    }
}

function saveMessage(text, receiver) {
    $.post('controller.php', {
        submit: "saveMessage",
        content: text,
        receiver: receiver
        }, 
        function(data, status) {
            getMessages(receiver);
            let recentlyPeople = document.getElementById("recentlyPeople");
            $("#"+receiver).remove();
            recentlyPeople.innerHTML = '<div class="col-12 mb-1 d-flex justify-content-center recentlyPeopleDIV" id="'+receiver+'"><h5 class="my-auto">'+receiver+'</h5></div>' + recentlyPeople.innerHTML;
        }
    );
}

$(document).ready(setInterval(refreshMessages, 5000));

function refreshMessages() {
    let person = document.getElementById("messageHeader").innerHTML;
    if(person.trim() != "") {
        getUnreadMessages(person);
    }
}

function getUnreadMessages(person) {
    $.post('controller.php', {
        submit: "getUnreadMessages",
        sender: person
        }, 
        function(data, status) {
            if(data.content.length > 0) displayMessageAfterListening(data);
        },
        "json"
    );
}

function displayMessageAfterListening(data) {
    for(let i=0; i<data.content.length; i++) {       
        let messagesField = document.getElementById("messagesField");
        let HTML = "";
        if(data.content.length !== 0)  {
            for(let i=0; i<data.content.length; i++) {  
                HTML += '<div class="col-12 px-0 pb-2"><p class="float-left myMessage ml-1 rounded p-2 mb-0">';
                HTML += data.content[i]+'</p></div>';  
            }
            messagesField.innerHTML += HTML; 
        }
    }
}