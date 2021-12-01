function makeDiagrams() {
    getDataToWorkerHighlights();
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
