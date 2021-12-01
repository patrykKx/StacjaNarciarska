function makeDiagrams() {
    getDataToWorkerHighlights();
    getDataToInstructorLesson();
}

function getDataToWorkerHighlights() {
    $.post('controller.php', {
       submit: "getWorkerHighlightsByMonths"
    }, 
    function(data, status) {
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
          colors: ['orange'],
          hAxis: {title: "Liczba godzin", format: "#"},
          vAxis: {title: "Miesiąc"}
       };

       var chart = new google.visualization.BarChart(document.getElementById('workerHighlights'));
       if(arrayToVisualization.length > 1) chart.draw(data, options);
       else document.getElementById('workerHighlights').innerHTML = '<h5 class="text-danger py-3">Nie przepracowałeś jeszcze żadnej godziny!</h5>';
    }
    
    $(window).resize(function(){
        drawChart();
    });
}

function getDataToInstructorLesson() {
    $.post('controller.php', {
       submit: "getInstructorLessonHighlights"
    }, 
    function(data, status) {
        makeInstructorLessonHighlightsDiagram(data);
    },
    "json"
    );
}

function makeInstructorLessonHighlightsDiagram(queryResult) {
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    let arrayToVisualization = [];
    arrayToVisualization.push(["Miesiąc", "Narciarstwo", "Snowboard"]);
    for(let i=0; i<Object.keys(queryResult.skiing).length; i++) {
        arrayToVisualization.push([Object.keys(queryResult.skiing)[i], queryResult.skiing[Object.keys(queryResult.skiing)[i]], queryResult.snowboard[Object.keys(queryResult.snowboard)[i]]]);
    }
    console.log(arrayToVisualization);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(arrayToVisualization);

        var options = {
            title: 'Liczba godzin udzielonych lekcji w poszczególnych miesiącach',
            legend: { position: 'top'},
            bar: { groupWidth: '50%' },
            isStacked: true,
            height: 400,
            hAxis: {title: "Liczba godzin", format: "#"},
            vAxis: {title: "Miesiąc"}
          };

       var chart = new google.visualization.BarChart(document.getElementById('instructorLessonHighlights'));
       if(arrayToVisualization.length > 1) chart.draw(data, options);
       else document.getElementById('instructorLessonHighlights').innerHTML = '<h5 class="text-danger py-3">Nie udzieliłeś jeszcze żadnej lekcji!</h5>';
    }
    
    $(window).resize(function(){
        drawChart();
    });
}