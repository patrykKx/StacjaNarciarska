document.getElementById('timetable').addEventListener('click', function() {
    $("#spinner").show();
    $.get('instructor/timetable.php', {

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

document.getElementById('lesson').addEventListener('click', function() {
    $("#spinner").show();
    $.get('instructor/lesson.php', {

    }, 
    function(data, status) {
        $("#spinner").hide();
        $("button").removeClass('active');
        $("#lesson").addClass('active');
        $("#mainSection").html(data);
        putTodayDateToInput();
        allowListeningToLessonDateInput();
    }
    ); 
});

document.getElementById('statistic').addEventListener('click', function() {
    $("#spinner").show();
    $.get('instructor/statistic.php', {

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