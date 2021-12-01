document.getElementById('hire').addEventListener('click', function() {
    $("#spinner").show();
    $.get('servicemen/hire.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#hire").addClass('active');
        $("#mainSection").html(data);
        getUserLogins();
        allowListeningToEquipmentInputs();
        getActiveHire();
    }
    ); 
});

document.getElementById('service').addEventListener('click', function() {
    $("#spinner").show();
    $.get('servicemen/service.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#service").addClass('active');
        $("#mainSection").html(data);
        getUserLogins();
        allowListeningToServiceTypeInput();
        getServiceInProgress();
        getServiceReadyToPickUp();
    }
    ); 
});

document.getElementById('history').addEventListener('click', function() {
    $("#spinner").show();
    $.get('servicemen/history.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#history").addClass('active');
        $("#mainSection").html(data);
        putTodayDateToInput();
        loadServiceHeader(0);
        allowListeningToHireHistoryDateInput();
    }
    ); 
});

document.getElementById('timetable').addEventListener('click', function() {
    $("#spinner").show();
    $.get('servicemen/timetable.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#timetable").addClass('active');
        $("#mainSection").html(data);
        firstCalendarLoading();
    }
    ); 
});

document.getElementById('statistic').addEventListener('click', function() {
    $("#spinner").show();
    $.get('servicemen/statistic.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#statistic").addClass('active');
        $("#mainSection").html(data);
        makeDiagrams();
    }
    ); 
});

