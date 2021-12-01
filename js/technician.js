document.getElementById('timetable').addEventListener('click', function() {
    $("#spinner").show();
    $.get('technician/timetable.php', {

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
    $.get('technician/statistic.php', {

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


