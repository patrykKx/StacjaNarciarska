$(document).ready(countUnreadMessagesFromPeople());
$(document).ready(setInterval(countUnreadMessagesFromPeople, 5000));

function countUnreadMessagesFromPeople() {
    $.post('controller.php', {
        submit: "countUnreadMessagesFromPeople"
        }, 
        function(data, status) {
            displayMessagesCounter(data);
        }
    );
}

function displayMessagesCounter(data) {
    let counterContainer = document.getElementById("messagesCounterContainer");
    let messageLinkContainer = document.getElementById("messageLink");
    if(data == 0) {
        counterContainer.innerHTML = "";
        messageLinkContainer.classList.add("pr-2");
        messageLinkContainer.classList.remove("pr-0");
    }
    else {
        counterContainer.innerHTML = '<span class="text-light align-self-center px-2">'+data+'</span>';
        messageLinkContainer.classList.add("pr-0");
        messageLinkContainer.classList.remove("pr-2");
    }
}


