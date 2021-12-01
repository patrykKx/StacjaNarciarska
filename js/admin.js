document.getElementById('station').addEventListener('click', function() {
    $("#spinner").show();
    $.get('admin/station.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#station").addClass('active');
        $("#mainSection").html(data);
        $("#mainSection").ready(getCurrentConditions());
        $("#calendarSection").ready(firstCalendarLoading());
        $("#calendarSection").ready(addListenerToOpeningSelect());
    }
    ); 
});

document.getElementById('workers').addEventListener('click', function() {
    $("#spinner").show();
    $.get('admin/workers.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#workers").addClass('active');
        $("#mainSection").html(data);
        autoWorkerList();
        allowListeningToWorkerRoleInput();
    }
    );
});

document.getElementById('timetable').addEventListener('click', function() {
    $("#spinner").show();
    $.get('admin/timetable.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#timetable").addClass('active');
        $("#mainSection").html(data);
        $("#calendarSection").ready(firstCalendarLoading());
        $("#addTimetable").ready(addTimetableLoading());
        
    }
    );
});

document.getElementById('users').addEventListener('click', function() {
    $("#spinner").show();
    $.get('admin/users.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#users").addClass('active');
        $("#mainSection").html(data);
        //getPersonList('Użytkownik');
        allowListeningToUserLoginInput();
    }
    );
});

document.getElementById('equipment').addEventListener('click', function() {
    $("#spinner").show();
    $.get('admin/equipment.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#equipment").addClass('active');
        $("#mainSection").html(data);
        autoPrefixId();
        clearPrefixId();
        autoEquipmentList();
        allowListeningToResetBTN();
        allowListeningToIdInput();
    }
    );
});

document.getElementById('statistic').addEventListener('click', function() {
    $("#spinner").show();
    $.get('admin/statistic.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#statistic").addClass('active');
        $("#mainSection").html(data);
        allowListeningToStatisticRadios();
    }
    );
});