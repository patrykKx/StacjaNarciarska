function makeDiagrams() {
    getDataToHireHighlights();
    getDataToHireHighlightsByMonths();
    getDataToWorkerHighlights();
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
        title: 'Wypożyczenia w podziale na rodzaj sprzętu',
        pieHole: 0.4,
        colors: ['red', 'orange', 'yellow', 'green', 'blue']
     };
     
     var chart = new google.visualization.PieChart(document.getElementById('hireHighlights'));
     chart.draw(data, options);
    }
    
    $(window).resize(function(){
        drawChart();
    });
}

function getDataToHireHighlightsByMonths() {
    $.post('controller.php', {
       submit: "getHireHighlightsByMonths"
    }, 
    function(data, status) {
        makeHireHighlightsDiagramByMonths(data);
    },
    "json"
    );
}

function makeHireHighlightsDiagramByMonths(queryResult) {
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Miesiąc", "Narty", "Buty narciarskie", "Snowboard", "Buty snowboardowe", "Kaski"]);
    for(let i=0; i<Object.keys(queryResult.skis).length; i++) {
        arrayToVisualization.push([Object.keys(queryResult.skis)[i], parseInt(queryResult.skis[Object.keys(queryResult.skis)[i]]), parseInt(queryResult.skiBoots[Object.keys(queryResult.skiBoots)[i]]), parseInt(queryResult.snowboard[Object.keys(queryResult.snowboard)[i]]), parseInt(queryResult.snowboardBoots[Object.keys(queryResult.snowboardBoots)[i]]), parseInt(queryResult.helmet[Object.keys(queryResult.helmet)[i]])]);
    }

    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
          title: 'Wypożyczenie sprzętu w poszczególnych miesiącach',
          pieHole: 0.4,
          colors: ['red', 'orange', 'yellow', 'green', 'skyblue'],
          hAxis: {format: "#"}
       };

       var chart = new google.visualization.BarChart(document.getElementById('hireHighlightsByMonths'));
       chart.draw(data, options);
    }
    
    $(window).resize(function(){
        drawChart();
    });
}


function getDataToWorkerHighlights() {
    $.post('controller.php', {
       submit: "getWorkerHighlightsByMonths"
    }, 
    function(data, status) {
        console.log(data);
        makeWorkerHighlightsDiagram(data);
    },
    "json"
    );
}

function makeWorkerHighlightsDiagram(queryResult) {
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Miesiąc", "Liczba godzin"]);
    for(let i=0; i<queryResult.date.length; i++) {
        arrayToVisualization.push([queryResult.date[i], queryResult.hours[i]]);
    }

    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
          title: 'Liczba przepracowanych godzin w poszczególnych miesiącach',
          colors: ['skyblue'],
          hAxis: {format: "#"}
       };

       var chart = new google.visualization.BarChart(document.getElementById('workerHighlights'));
       if(arrayToVisualization.length > 1) chart.draw(data, options);
       else document.getElementById('workerHighlights').innerHTML = '<h5 class="text-danger py-3">Nie przepracowałeś jeszcze żadnej godziny!</h5>';
    }
    
    $(window).resize(function(){
        drawChart();
    });
}