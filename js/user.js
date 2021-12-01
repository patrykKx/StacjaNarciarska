document.getElementById('ticket').addEventListener('click', function() {
    $("#spinner").show();
    $.get('user/ticket.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#ticket").addClass('active');
        $("#mainSection").html(data);
        firstCalendarLoading();
        allowListeningToNumberInput();
        let date = new Date();
        document.getElementById("ticketYearInput").value = date.getFullYear();
        getTicketList(date.getFullYear());
    }
    ); 
});

document.getElementById('hire').addEventListener('click', function() {
    $("#spinner").show();
    $.get('user/hire.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#hire").addClass('active');
        $("#mainSection").html(data);
        let date = new Date();
        document.getElementById("hireYearInput").value = date.getFullYear();
        getUserHireList(date.getFullYear());
    }
    ); 
});

document.getElementById('lesson').addEventListener('click', function() {
    $("#spinner").show();
    $.get('user/lesson.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#lesson").addClass('active');
        $("#mainSection").html(data);
        firstCalendarLoading();
        allowListeningToLessonInputs();
        getUserLessons();
    }
    ); 
});

document.getElementById('service').addEventListener('click', function() {
    $("#spinner").show();
    $.get('user/service.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#service").addClass('active');
        $("#mainSection").html(data);
        getServiceList();
    }
    ); 
});

document.getElementById('statistic').addEventListener('click', function() {
    $("#spinner").show();
    $.get('user/statistic.php', {

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

