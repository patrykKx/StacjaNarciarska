var instructorNumberOfMonth = 0;
var servicemanNumberOfMonth = 0;
var technicianNumberOfMonth = 0;

function allowListeningToStatisticRadios() {
    $("#statisticRadios").on("click", "input", function() {
        displayDiagrams($(this)[0].value);
    });
}

function displayDiagrams(type) {
    $("h5").html("");
    $("h5").removeClass("pt-5 pb-3");
    $("article div").html("");
    $("article div").removeClass("pt-2");
    if(type === "Station") {
        getDataToStationIncomeDiagram();
        getDataToStationOpenDiagram();
    }
    else if(type === "Worker") {
        getDataToWorkerHours();
        loadMonthHistoryHeader(0, "Instruktor");
        loadMonthHistoryHeader(0, "Serwisant");
        loadMonthHistoryHeader(0, "Techniczny");
        getDataToInstructorsDiagram();
        
    }
    else if(type === "User") {
        getDataToUserDiagram();
    }
    else if(type === "Offer") {
        getDataToTicketHighlights();
        getDataToHireHighlights();
        getDataToLessonHighlights();
    }
}

function getDataToUserDiagram() {
    $.post('controller.php', {
       submit: "getTotalUserCost"
    }, 
    function(data, status) {
        makeUserDiagram(data);
    },
    "json"
    );
}

function makeUserDiagram(queryResult) {
    $("#mostActiveUsersHeader").html("Najaktywniejsi użytkownicy").addClass("pt-5");
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Użytkownik", "Wydatki (zł)"]);
    for(let i=0; i<Object.keys(queryResult).length; i++) {
        arrayToVisualization.push([Object.keys(queryResult)[i], queryResult[Object.keys(queryResult)[i]]]);
    }
    
    function drawChart() {
      var data = google.visualization.arrayToDataTable(arrayToVisualization);

      var options = {
        hAxis: {title: 'Wydatki'},
        vAxis: {title: 'Użytkownik'},
        height: 400
     };
     
     var chart = new google.visualization.BarChart(document.getElementById('mostActiveUsers'));
     chart.draw(data, options);
    }
    
    
    $(window).resize(function(){
        if(document.getElementById("userRadio").checked) drawChart();
    });

}

function getDataToStationOpenDiagram() {
    $.post('controller.php', {
       submit: "getOpeningDayByMonths"
    }, 
    function(data, status) {
        makeStationOpenDiagram(data);
    },
    "json"
    );
}

function makeStationOpenDiagram(queryResult) {
    $("#openStationHeader").html("Ilość dni z otwartą stacją").addClass("pt-4");
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Miesiąc", "Ilość dni"]);
    for(let i=0; i<queryResult.date.length; i++) {
        arrayToVisualization.push([queryResult.date[i], parseInt(queryResult.countValue[i])]);
    }
    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
            hAxis: {title: 'Miesiąc'},
            vAxis: {ticks: [0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30]},
            colors: ['green'],
            height: 400
        };
        
        var chart = new google.visualization.ColumnChart(document.getElementById('openStation'));
        chart.draw(data, options);
    }   
    
    $(window).resize(function(){
        if(document.getElementById("stationRadio").checked) drawChart();
    });
}

function getDataToStationIncomeDiagram() {
    $.post('controller.php', {
       submit: "getStationIncomeByMonths"
    }, 
    function(data, status) {
        makeStationIncomeDiagram(data);
    },
    "json"
    );
}

function makeStationIncomeDiagram(queryResult) {
    $("#stationIncomeHeader").html("Przychody stacji").addClass("pt-5");
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Miesiąc", "Karnety", "Wypożyczenia", "Serwis", "Lekcje"]);
    for(let i=0; i<Object.keys(queryResult.skipassCost).length; i++) {
        arrayToVisualization.push([Object.keys(queryResult.skipassCost)[i], queryResult.skipassCost[Object.keys(queryResult.skipassCost)[i]], queryResult.hireCost[Object.keys(queryResult.hireCost)[i]], queryResult.serviceCost[Object.keys(queryResult.serviceCost)[i]], queryResult.lessonCost[Object.keys(queryResult.lessonCost)[i]]]);
    }
    console.log(arrayToVisualization);
    function drawChart() {
      var data = google.visualization.arrayToDataTable(arrayToVisualization);

      var options = {
        hAxis: {title: 'Przychód (zł)'},
        vAxis: {title: 'Miesiąc'},
        colors: ['red', 'orange', 'pink', 'skyblue'],
        seriesType: 'bars',
        series: {5: {type: 'line'}},
        height: 400
     };
     
     var chart = new google.visualization.ColumnChart(document.getElementById('stationIncome'));
     chart.draw(data, options);
    }
    
    $(window).resize(function(){
        if(document.getElementById("stationRadio").checked) drawChart();
    });
}

function getDataToWorkerHours() {
    $.post('controller.php', {
       submit: "getWorkerHoursByMonths"
    }, 
    function(data, status) {
        makeWorkerHoursDiagram(data);
    },
    "json"
    );
}

function makeWorkerHoursDiagram(queryResult) {
    $("#workerHoursHeader").html("Liczba przepracowanych godzin przez pracowników").addClass("pt-5");
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Miesiąc", "Instruktorzy", "Techniczni", "Serwisanci"]);
    for(let i=0; i<Object.keys(queryResult.instructor).length; i++) {
        arrayToVisualization.push([Object.keys(queryResult.instructor)[i], parseFloat(queryResult.instructor[Object.keys(queryResult.instructor)[i]]), parseFloat(queryResult.technician[Object.keys(queryResult.technician)[i]]), parseFloat(queryResult.serviceman[Object.keys(queryResult.serviceman)[i]])]);
    }
    function drawChart() {
      var data = google.visualization.arrayToDataTable(arrayToVisualization);

      var options = {
        vAxis: {title: 'Miesiąc'},
        hAxis: {title: 'Ilość przepracowanych godzin'},
        colors: ['red', 'blue', 'green'],
        height: 400
     };
     
     var chart = new google.visualization.BarChart(document.getElementById('workerHours'));
     chart.draw(data, options);
    }
    
    $(window).resize(function(){
        if(document.getElementById("workerRadio").checked) drawChart();
    });
}

function getDataToHireHighlights() {
    $.post('controller.php', {
       submit: "getHireHighlights"
    }, 
    function(data, status) {
        makeHireHighlightsDiagram(data);
    },
    "json"
    );
}

function makeHireHighlightsDiagram(queryResult) {
    $("#hireHighlightsHeader").html("Szczegóły wypożyczeń sprzętu").addClass("pt-5");
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Sprzęt", "Liczba wypożyczeń"],
                              ["Narty", queryResult.skis],
                              ["Buty narciarskie", queryResult.skiBoots],
                              ["Snowboard", queryResult.snowboard],
                              ["Buty snowboardowe", queryResult.snowboardBoots],
                              ["Kask", queryResult.helmet]);

    function drawChart() {
      var data = google.visualization.arrayToDataTable(arrayToVisualization);

      var options = {
        title: 'Wypożyczenie sprzętu',
        pieHole: 0.4,
        colors: ['red', 'orange', 'yellow', 'green', 'blue'],
        height: 400
     };
     
     var chart = new google.visualization.PieChart(document.getElementById('hireHighlights'));
     chart.draw(data, options);
    }
    
    $(window).resize(function(){
        if(document.getElementById("offerRadio").checked) drawChart();
    });
}

function getDataToTicketHighlights() {
    $.post('controller.php', {
       submit: "getTicketHighlights"
    }, 
    function(data, status) {
        console.log(data);
        makeTicketHighlightsDiagram(data);
    },
    "json"
    );
}

function makeTicketHighlightsDiagram(queryResult) {
    $("#ticketHighlightsHeader").html("Szczegóły sprzedaży karnetów").addClass("pt-5");;
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [["Rodzaj karnetu", "Liczba karnetów"],
                                ["1 godzina", queryResult.oneHour],
                                ["2 godziny", queryResult.twoHours],
                                ["3 godziny", queryResult.threeHours],
                                ["Cały dzień", queryResult.allDay]];
    function drawChart() {
      var data = google.visualization.arrayToDataTable(arrayToVisualization);

      var options = {
        hAxis: {title: 'Czas trwania'},
        vAxis: {title: 'Liczba karnetów'},
        colors: ['orange', 'blue'],
        height: 400
     };
     
     var chart = new google.visualization.ColumnChart(document.getElementById('ticketHighlights'));
     chart.draw(data, options);
    }
    
    $(window).resize(function(){
        if(document.getElementById("offerRadio").checked) drawChart();
    });
}

function getDataToLessonHighlights() {
    $.post('controller.php', {
       submit: "getLessonHighlights"
    }, 
    function(data, status) {
        makeLessonHighlightsDiagram(data);
    },
    "json"
    );
}

function makeLessonHighlightsDiagram(queryResult) {
    $("#lessonHighlightsHeader").html("Szczegóły lekcji").addClass("pt-5");
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Lekcja", "Rodzaj"],
                              ["Narciarstwo", queryResult.skiing],
                              ["Snowboard", queryResult.snowboard]);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
          title: 'Rodzaj lekcji',
          pieHole: 0.4,
          colors: ['lightblue', 'pink'],
          height: 400
        };

        var chart = new google.visualization.PieChart(document.getElementById('lessonHighlights'));
        chart.draw(data, options);
      }
    
    $(window).resize(function(){
        if(document.getElementById("offerRadio").checked) drawChart();
    });
}

//FUNKCJE DLA HISTORII MIESIECZNEJ PRACOWNIKÓW
function loadMonthHistoryHeader(numberOfMonth, workerType) {
    let monthHistoryHeaderHTML = '';
    let monthHeader = getMonthAndYear(numberOfMonth);

    monthHistoryHeaderHTML += '<div class="row pb-1">'+
                            '<div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onClick="previousMonth(\''+workerType+'\')"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>'+
                            '<div class="col-6" id="monthAndYear">'+monthHeader+'</div>'+
                            '<div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onClick="nextMonth(\''+workerType+'\')"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button>'+
                        '</div>';
    if(workerType === "Instruktor") document.getElementById("instructorHoursDate").innerHTML = monthHistoryHeaderHTML;
    else if(workerType === "Serwisant") document.getElementById("servicemanHoursDate").innerHTML = monthHistoryHeaderHTML;
    else if(workerType === "Techniczny") document.getElementById("technicianHoursDate").innerHTML = monthHistoryHeaderHTML;
    
    getMonthWorkerHistory(getYear(numberOfMonth), getMonth(numberOfMonth), workerType);
}

function getMonthAndYear(numberOfMonth) {
    let months = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let month = date.getMonth();
    let year = date.getFullYear();
    return months[month]+" "+year;
}

function getYear(numberOfMonth) {
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let year = date.getFullYear();
    return year;
}

function getMonth(numberOfMonth) {
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+numberOfMonth);
    let month = date.getMonth()+1;
    return month;
}

function previousMonth(workerType) {
    if(workerType === "Instruktor") {
        instructorNumberOfMonth--;
        loadMonthHistoryHeader(instructorNumberOfMonth, workerType);
    }
    else if(workerType === "Serwisant") {
        servicemanNumberOfMonth--;
        loadMonthHistoryHeader(servicemanNumberOfMonth, workerType);
    }
    else if(workerType === "Techniczny") {
        technicianNumberOfMonth--;
        loadMonthHistoryHeader(technicianNumberOfMonth, workerType);
    }
}

function nextMonth(workerType) {
    if(workerType === "Instruktor") {
        instructorNumberOfMonth++;
        loadMonthHistoryHeader(instructorNumberOfMonth, workerType);
    }
    else if(workerType === "Serwisant") {
        servicemanNumberOfMonth++;
        loadMonthHistoryHeader(servicemanNumberOfMonth, workerType);
    }
    else if(workerType === "Techniczny") {
        technicianNumberOfMonth++;
        loadMonthHistoryHeader(technicianNumberOfMonth, workerType);
    }
}

function getMonthWorkerHistory(year, month, workerType) {
    $.post('controller.php', {
       submit: "getWorkerHighlightsByMonthAndType",
       year: year,
       month: month,
       workerType: workerType
    }, 
    function(data, status) {
        makeMonthWorkerHistoryDiagram(data, workerType);
    },
    "json"
    );
}

function makeMonthWorkerHistoryDiagram(queryResult, workerType) {
    if(workerType === "Instruktor") {
        $('#instructorHoursHeader').html("Liczba przepracowanych godzin przez instruktorów w danym miesiącu").addClass("pt-5");
    }
    else if(workerType === "Serwisant") {
        $('#servicemanHoursHeader').html("Liczba przepracowanych godzin przez serwisantów w danym miesiącu").addClass("pt-5");
    }
    else if(workerType === "Techniczny") {
        $('#technicianHoursHeader').html("Liczba przepracowanych godzin przez technicznych w danym miesiącu").addClass("pt-5");
    }
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Pracownik", "Liczba godzin"]);
    for(let i=0; i<queryResult.name.length; i++) {
        arrayToVisualization.push([queryResult.name[i], queryResult.hours[i]]);
    }
    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
          vAxis: {title: 'Miesiąc'},
          hAxis: {title: 'Ilość przepracowanych godzin'},
          colors: ['red', 'blue', 'green'],
          height: 400
       };
       var chart;
       if(workerType === "Instruktor") chart = new google.visualization.BarChart(document.getElementById('instructorHours'));
       else if(workerType === "Serwisant") chart = new google.visualization.BarChart(document.getElementById('servicemanHours'));
       else if(workerType === "Techniczny") chart = new google.visualization.BarChart(document.getElementById('technicianHours'));
       
       if(arrayToVisualization.length > 1) chart.draw(data, options);
       else {
           let news = '<h5 class="text-danger pb-3">W tym miesiącu nie ma żadnych danych!</h5>';
           if(workerType === "Instruktor") document.getElementById('instructorHours').innerHTML = news;
           else if(workerType === "Serwisant") document.getElementById('servicemanHours').innerHTML = news;
           else if(workerType === "Techniczny") document.getElementById('technicianHours').innerHTML = news;
       }
    }
    
    $(window).resize(function(){
        if(document.getElementById("workerRadio").checked) drawChart();
    });
}

function getDataToInstructorsDiagram() {
    $.post('controller.php', {
       submit: "getMostActiveInstructors"
    }, 
    function(data, status) {
        makeMostActiveInstructorDiagram(data);
    },
    "json"
    );
}

function makeMostActiveInstructorDiagram(queryResult) {
    $("#mostActiveInstructorsHeader").html("Najaktywniejsi instruktorzy").addClass("pt-5");
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Instruktor", "Liczba godzin lekcji", "Liczba uczestników"]);
    for(let i=0; i<queryResult.instructor.length; i++) {
        arrayToVisualization.push([queryResult.instructor[i], queryResult.hours[i], queryResult.numberOfParticipants[i]]);
    }
    function drawChart() {
      var data = google.visualization.arrayToDataTable(arrayToVisualization);

      var options = {
        vAxis: {title: 'Instruktor'},
        colors: ['#93b3f7', '#fa87da'],
        seriesType: 'bars',
        series: {5: {type: 'line'}},
        height: 400
     };
     
     var chart = new google.visualization.BarChart(document.getElementById('mostActiveInstructors'));
     chart.draw(data, options);
    }
    
    $(window).resize(function(){
        if(document.getElementById("workerRadio").checked) drawChart();
    });
}