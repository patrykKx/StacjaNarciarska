function makeDiagrams() {
    getDataToUserActivity();
    getDataToUserSkipassHoursByMonths();
}

function getDataToUserActivity() {
    $.post('controller.php', {
       submit: "getTotalUserActivity"
    }, 
    function(data, status) {
        makeUserActivityDiagram(data);
    },
    "json"
    );
}

function makeUserActivityDiagram(queryResult) {
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Aktywność", "Ilość", {role: 'style'}],
                              ["Karnety", queryResult.skipass, 'gold'],
                              ["Wypożyczenia", queryResult.hire, 'lime'],
                              ["Serwis", queryResult.service, 'olive'],
                              ["Lekcje", queryResult.lesson, 'teal']);
    
    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
          hAxis: {title: 'Aktywność'},
          vAxis: {title: 'Całkowita ilość danej aktywności',
                  format: '#'},
          colors: ['gold'],
          height: 400
       };

       var chart = new google.visualization.ColumnChart(document.getElementById('userActivity'));
       
       if(queryResult.skipass == 0 && queryResult.lesson == 0 && queryResult.hire == 0 && queryResult.service == 0) {
           document.getElementById('userActivity').innerHTML = '<h5 class="text-danger">Nie masz jeszcze żadnej aktywności!</h5>';
       }
       else {
           chart.draw(data, options);
           
       }
    }
    
    $(window).resize(function(){
        drawChart();
    });
}

function getDataToUserSkipassHoursByMonths() {
    $.post('controller.php', {
       submit: "getUserSkipassHourByMonths"
    }, 
    function(data, status) {
        makeUserSkipassHoursDiagram(data);
    },
    "json"
    );
}

function makeUserSkipassHoursDiagram(queryResult) {
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Data", "Liczba godzin"]);
    for(let i=0; i<queryResult.date.length; i++) {
        arrayToVisualization.push([queryResult.date[i], parseInt(queryResult.sum[i])]);
    }
    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
          hAxis: {title: 'Liczba godzin',
                  format: '#'},
          vAxis: {title: 'Miesiąc'},
          height: 400

       };
        if(queryResult.date.length != 0) {
            var chart = new google.visualization.BarChart(document.getElementById('userSkipassHours'));
            chart.draw(data, options);
        }
        else {
            document.getElementById('userSkipassHours').innerHTML = '<h5 class="text-danger">Nie spędzałeś jeszcze czasu na stoku!</h5>';
        }
     
    }
    
    $(window).resize(function(){
        drawChart();
    });
}