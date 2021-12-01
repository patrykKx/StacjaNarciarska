function dayClickTimetable(id) {
    console.log(id);
    $.post('controller.php', {
        submit: "getWorkerDayHighlights",
        date: id
        }, 
        function(data, status) {
            let HTML = '';
            HTML += '<h4><b>'+id+'</b></h4>';
            if(data.startTime.length === 0) {
                HTML += '<h4 class="text-danger pt-2">W tym dniu nie masz przydzielonego grafiku!</h4>';
            }
            else {
                HTML += '<div class="row"><h4 class="col-sm-6">Rozpoczęcie: <b>'+data.startTime[0].substring(11, 16)+'</b></h4>';
                HTML += '<h4 class="col-sm-6">Zakończenie: <b>'+data.finishTime[0].substring(11, 16)+'</b></div></h4>';
            }
            document.getElementById("timetableContainer").innerHTML = HTML;
            $.scrollTo($('#timetableContainer').offset().top, 1000);
        },
        "json"
    );
}

function getWorkerScheduleOnMap(year, month) {
    console.log("Worker "+year, month);
    $.post('controller.php', {
        submit: "getWorkerDays",
        year: year,
        month: month
        }, 
        function(data, status) {
            for(let i=0; i<data.startTime.length; i++) {
                if(data.startTime[i] !== null) {
                    let id = "'"+data.startTime[i].substring(0, 10)+"'";
                    document.getElementById(id).style.boxShadow = 'inset 0 0 0 5px #2c20bd';
                }
            }
        },
        "json"
    );
}
