function getServiceList() {
    $.post('controller.php', {
     submit: "getUserServiceList"
     }, 
     function(data, status) {
        console.log(data, status); 
        let serviceContainer = document.getElementById("serviceContainer");
        let HTML = '';
        let finishTime = "-";
        let cost = "-";
        if(data.startTime.length === 0) {
            HTML = '<h5 class="text-danger py-2">Brak serwisów sprzętu!</h5>';
        }
        else {
            HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Nazwa</th><th scope="col">Rodzaj serwisu</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th><th scope="col">Koszt</th><th scope="col">Status</th></tr></thead><tbody>';
            for(let i=0; i<data.startTime.length; i++) {
                let startTime = data.startTime[i].substring(0, 16);
                if(data.finishTime[i] !== null) {
                    finishTime = data.finishTime[i].substring(0, 16);
                }
                if(data.cost[i] !== null) {
                    cost = data.cost[i];
                }
                HTML += '<tr><td>'+data.name[i]+'</td><td>'+data.type[i]+'</td><td>'+startTime+'</td><td>'+finishTime+'</td><td>'+cost+'</td><td>'+data.status[i]+'</td></tr>';
            }
            HTML += '</tbody></table></div>';
        }
        serviceContainer.innerHTML = HTML;
     },
     "json"
    );
}

