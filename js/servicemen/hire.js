var counterCheckbox = 0;

function allowListeningToEquipmentInputs() {
    $(".equipmentCheckbox").on("click", "input", function() {
        showOrHideArticle(this.value);
    });
    $("#addHireResetBTN").on("click", function() {
        $("article").attr("hidden", true);
        $("#mainEquipmentArticle").attr("hidden", false);
    });
    $("#equipmentHireData select").on("change", function() {
        getEquipmentNameAndSize(this.value);
    });
}

function showOrHideArticle(value) {
    if($('#'+value).is(":hidden")) {
        $('#'+value).attr("hidden", false);
        $('#addHireButtons').attr("hidden", false);
        counterCheckbox++;
        getEquipmentList(value);
    }
    else {
        $('#'+value+" input:text").val("");
        $('#'+value).attr("hidden", true);
        counterCheckbox--;
        if(counterCheckbox === 0) $('#addHireButtons').attr("hidden", true);
    }
}

function convertToPolishEquipmentName(value) {
    let correctValue = "";
    switch(value) {
        case "ski": correctValue = "Narty"; break;
        case "skiBoots": correctValue = "Buty narciarskie"; break;
        case "snowboard": correctValue = "Snowboard"; break;
        case "snowboardBoots": correctValue = "Buty snowboardowe"; break;
        case "helmet": correctValue = "Kask"; break;
    }
    return correctValue;
}

function convertToEnglishEquipmentName(value) {
    let correctValue = "";
    switch(value) {
        case "Narty": correctValue = "ski"; break;
        case "Buty narciarskie": correctValue = "skiBoots"; break;
        case "Snowboard": correctValue = "snowboard"; break;
        case "Buty snowboardowe": correctValue = "snowboardBoots"; break;
        case "Kask": correctValue = "helmet"; break;
    }
    return correctValue;
}

function getEquipmentList(value) {
    let correctValue = convertToPolishEquipmentName(value);
    $.post('controller.php', {
        submit: "getEquipmentToServiceman",
        type: correctValue
    }, 
    function(data, status) {
        putValuesToEquipmentIdInput(data, value);
        console.log(data);
    },
    "json"
    );
}

function putValuesToEquipmentIdInput(data, value) {
    let equipmentIdSelect = document.getElementById(value+"List");
    $("#"+value+"List").empty().append('<option selected disabled></option>');
    for (let i=0; i<data.length; i++){
        let option = document.createElement('option');
        option.value = data[i];
        option.innerHTML = data[i];
        equipmentIdSelect.appendChild(option);
    }
}

function getEquipmentNameAndSize(value) {
    $.post('controller.php', {
        submit: "getEquipmentNameAndSize",
        id: value
    }, 
    function(data, status) {
        console.log(data);
        if(data.name.length === 1) {
           let type = convertToEnglishEquipmentName(data.type[0]);
           $("#"+type+"ListName").val(data.name[0]);
           $("#"+type+"ListSize").val(data.size[0]); 
        }
    },
    "json"
    );
}

function getActiveHire() {
    $.post('controller.php', {
        submit: "getActiveHire"
    }, 
    function(data, status) {
        console.log(data);
        document.getElementById("activeHireContainer").innerHTML = getActiveHireHTML(data);
    },
    "json"
    );
}

function getActiveHireHTML(data) {
    let HTML = '';
    if(data.idHire.length === 0) {
        HTML += '<h5 class="text-danger py-2">Brak trwających wypożyczeń!</h5>';
    }
    else {
        HTML += '<div class="table-responsive-md"><table class="table"><thead><tr id="firstTr"><th scope="col">Login</th><th scope="col">Rozpoczęcie</th><th scope="col">Sprzęt</th><th scope="col">Akcja</th></tr></thead><tbody>';
        let tempIdHire = '';
        for(let i=0; i<data.idHire.length; i++) {
            if(data.idHire[i] === tempIdHire) continue;
            HTML += '<tr><td>'+data.login[i]+'</td><td>'+data.startTime[i].substring(0, 16)+'</td><td>'+getHireEquipment(data, i)+'</td><td>'+getActiveHireButton(data.idHire[i])+'</td></tr>';
            tempIdHire = data.idHire[i];
        }
        HTML += '</tbody></table></div>';
    }
    return HTML;
}

function getHireEquipment(data, i) {
    let idHire = data.idHire[i];
    let HTML = "";
    for(let j=0; j<data.idHire.length; j++) {
        if(data.idHire[j] === idHire) {
            HTML += data.idEquipment[j]+" ("+data.equipmentName[j]+")<br/>";
        }
    }
    return HTML;
}

function getActiveHireButton(idHire) {
    let HTML = '<form method="POST" action="controller.php"><input type="hidden" name="idHire" value="'+idHire+'"/><button type="submit" class="btn btn-info" name="submit" value="changeActiveHireStatus">Zwróć</button></form>';
    return HTML;
}