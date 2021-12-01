function prevYearHire() {
    let yearInput = document.getElementById("hireYearInput");
    let year = parseInt(yearInput.value) - 1;
    yearInput.value = year;
    getUserHireList(year);
}

function nextYearHire() {
    let yearInput = document.getElementById("hireYearInput");
    let year = parseInt(yearInput.value) + 1;
    yearInput.value = year;
    getUserHireList(year);
}

function getUserHireList(year) {
    $.post('controller.php', {
     submit: "getUserHireList",
     year: year
     }, 
     function(data, status) {
        console.log(data);
        let hireContainer = document.getElementById("hireContainer");
        let HTML = '';
        let finishTime = "-";
        let cost = "-";
        if(data.startTime.length === 0) {
            HTML = '<h5 class="text-danger py-2">Brak wypożyczeń sprzętu!</h5>';
        }
        else {
            HTML += '<div class="table-responsive-lg"><table class="table"><thead><tr id="firstTr"><th scope="col">Dzień</th><th scope="col">Rozpoczęcie</th><th scope="col">Zakończenie</th><th scope="col">Sprzęt</th><th scope="col">Zdjęcie</th><th scope="col">Koszt</th><th scope="col">Status</th></tr></thead><tbody>';
            let tempIdHire = '';
            for(let i=0; i<data.idHire.length; i++) {
                if(data.idHire[i] === tempIdHire) continue;
                if(data.finishTime[i] !== null) {
                    finishTime = data.finishTime[i].substring(11, 16);
                }
                if(data.cost[i] !== null) {
                    cost = data.cost[i];
                }
                HTML += '<tr><td>'+data.startTime[i].substring(0, 10)+'</td><td>'+data.startTime[i].substring(11, 16)+'</td><td>'+finishTime+'</td><td>'+getHireEquipment(data, i)+'</td><td>'+getHirePhotos(data, i)+'</td><td>'+cost+' zł</td><td>'+data.status[i]+'</td></tr>';
                tempIdHire = data.idHire[i];
            }
            HTML += '</tbody></table></div>';
        }
        hireContainer.innerHTML = HTML;
     },
     "json"
    );
}

function getHireEquipment(data, i) {
    let idHire = data.idHire[i];
    let HTML = "";
    let type = "";
    for(let j=0; j<data.idHire.length; j++) {
        if(data.idHire[j] === idHire) { 
            if(data.idEquipment[j].startsWith("BN") || data.idEquipment[j].startsWith("BS")) type = "Buty";
            else if(data.idEquipment[j].startsWith("N")) type = "Narty";
            else if(data.idEquipment[j].startsWith("S")) type = "Snowboard";
            else if(data.idEquipment[j].startsWith("K")) type = "Kask";
            HTML += type+" - "+data.equipmentName[j]+"<br/>";
        }
    }
    return HTML;
}

function getHirePhotos(data, i) {
    let idHire = data.idHire[i];
    let HTML = "";
    let type = "";
    for(let j=0; j<data.idHire.length; j++) {
        if(data.idHire[j] === idHire) {
            if(data.photoURL[j] !== null && data.photoURL[j] !== "") {
                HTML += '<div><a href="'+data.photoURL[j]+'" target="_blank"><img src="'+data.photoURL[j]+'" class="img-fluid" style="max-height:50px;"></a></div>';
            }      
        }
    }
    return HTML;
}