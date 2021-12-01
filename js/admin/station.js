function getCurrentConditions() {
    $.post('controller.php', {
       submit: "showConditions"
    }, 
    function(data, status) {
        $('#minSnowCover').val(data.minSnowCover);
        $('#maxSnowCover').val(data.maxSnowCover);
        $('#snowType').val(data.snowType);
        $('#conditions').val(data.conditions);
        $('#description').val(data.description);
    },
    "json"
    );
}

function dayClick(dayId) {
    $.post('controller.php', {
        submit: "showDayDetails",
        day: dayId
        }, 
        function(data, status) {
            let openingHour = document.getElementById("openingHour");
            let closingHour = document.getElementById("closingHour");
            let hours = document.getElementById("hours");
            let selectIsOpen = document.getElementById("selectIsOpen");
            document.getElementById("dayFromCalendar").value = dayId;
            if(data.isOpen === '1') {
                selectIsOpen.value = "1";
                hours.hidden = false;
                openingHour.value = data.startTime;
                closingHour.value = data.finishTime;
            }
            else {
                selectIsOpen.value = "0";
                hours.hidden = true;
                openingHour.value = '';
                closingHour.value = '';
            }
        },
        "json"
    );
}

function addListenerToOpeningSelect() {
    let hours = document.getElementById("hours");
    let selectIsOpen = document.getElementById("selectIsOpen");
    selectIsOpen.addEventListener("change", function() {
        if(selectIsOpen.value === '1') {
            hours.hidden = false;
        } 
        else {
            hours.hidden = true;
        }
    });
}