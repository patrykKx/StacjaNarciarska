function autoPrefixId() {
    let typeEquipment = document.getElementById('typeEquipment');
    let prefixId = document.getElementById('prefixIdEquipment');
    typeEquipment.addEventListener('change', function() {
    switch(typeEquipment.value) {
        case 'Narty': prefixId.value = 'N'; break;
        case 'Snowboard': prefixId.value = 'S'; break;
        case 'Buty narciarskie': prefixId.value = 'BN'; break;
        case 'Buty snowboardowe': prefixId.value = 'BS'; break;
        case 'Kask': prefixId.value = 'K'; break;
        default : prefixId.value = ""; break;
    }
    });
}

function clearPrefixId() {
    document.getElementById('resetEquipmentBTN').addEventListener('click', function() {
        document.getElementById('prefixIdEquipment').value = '';
    });    
}

function autoEquipmentList() {
    $("#equipmentTypeRadios").on("click", "input", function() {
        $("#equipmentListSpinner").show();
        getEquipmentList($(this)[0].value);
    });
}

function getEquipmentList(equipmentType) {
    $.post('controller.php', {
        submit: "showEquipment",
        equipmentType: equipmentType
        }, 
        function(data, status) {
            console.log(data);
            $("#equipmentListSpinner").hide();
            data = sortDataById(data);
            console.log(data);
            displayEquipmentList(data);
        },
        "json"
        );
}

function sortDataById(data) {
    console.log(data);
    let numberId = [];
    for(let i=0; i<data.ids.length; i++) {
        if(data.types[i] === "Narty" || data.types[i] === "Snowboard" || data.types[i] === "Kask") {
            numberId[i] = parseInt(data.ids[i].substring(1));
        }
        else if(data.types[i] === "Buty narciarskie" || data.types[i] === "Buty snowboardowe") {
            numberId[i] = parseInt(data.ids[i].substring(2));
        }
    }
    numberId = numberId.sort(function(a, b) {
        return a - b;
    });
    console.log(numberId);
    
    let newData = {
        "ids": [],
        "names": [],
        "sizes": [],
        "photoURLs": [],
        "types": []
    };
    let tempId; //Liczbowe id sprzętu
    for(let i=0; i<data.ids.length; i++) {
        if(data.types[i] === "Narty" || data.types[i] === "Snowboard" || data.types[i] === "Kask") {
            tempId = parseInt(data.ids[i].substring(1));
        }
        else if(data.types[i] === "Buty narciarskie" || data.types[i] === "Buty snowboardowe") {
            tempId = parseInt(data.ids[i].substring(2));
        }
        for(let j=0; j<numberId.length; j++) {
            if(tempId === numberId[j]) {
                newData.ids[j] = data.ids[i];
                newData.names[j] = data.names[i];
                newData.sizes[j] = data.sizes[i];
                newData.photoURLs[j] = data.photoURLs[i];
                newData.types[j] = data.types[i];
                break;
            }
        }
    }
    console.log(newData);
    return newData;

}

function displayEquipmentList(data) {
    let equipmentListHTML = '';
    if(data.ids.length === 0) {
        equipmentListHTML = '<h5 class="text-danger">Brak sprzętu!</h5>';
    }
    else {
        equipmentListHTML += '<div class="table-responsive-md"><table class="table">'+
                              '<thead><tr id="firstTr"><th scope="col">ID</th><th scope="col">Nazwa</th><th scope="col">Rozmiar</th><th scope="col">Zdjęcie</th><th scope="col">Akcja</th></tr></thead><tbody>';
        for(let i=0; i<data.ids.length; i++) {
            equipmentListHTML += getEquipmentRow(data, i);
        }
        equipmentListHTML += '</tbody></table></div>';
    }
    document.getElementById('equipmentList').innerHTML = equipmentListHTML;
}

function getEquipmentRow(data, i) {
    let param = data.ids[i];
    let param2 = data.types[i];
    let onClick = "setDeleteModalHTML('deleteEquipment','"+param+"','"+param2+"','Usunięcie sprzętu','Czy na pewno chcesz usunąć sprzęt z listy?')";
    let tableRow = '';
    tableRow += '<tr><td>'+data.ids[i]+'</td><td style="min-width:100px;">'+data.names[i]+'</td>';
    if(data.sizes[i] === null) tableRow += '<td> - </td>';
    else tableRow += '<td>'+data.sizes[i]+'</td>';
    if(data.photoURLs[i] === null) tableRow += '<td> - </td>';
    else tableRow += '<td class="w-25"><a href="'+data.photoURLs[i]+'" target="_blank"><img src="'+data.photoURLs[i]+'" class="img-fluid" style="max-height:40px;"></a></td>';
    tableRow += '<td><div class="row"><button class="btn btn-info col-12 col-md-5 mx-auto mb-2 mb-md-0" onClick="putValuestoEquipmentEdit(\''+data.ids[i]+'\')">Edytuj</button><button class="btn btn-danger col-12 col-md-5 mx-auto" data-toggle="modal" data-target="#deleteModal" onClick="'+onClick+'">Usuń</button></div></td>';
    return tableRow;
    //onClick="deleteEquipment('+"'"+data.ids[i]+"'"+','+"'"+data.types[i]+"'"+')" do buttona
}

function deleteEquipment(id, type) {
    $.post('controller.php', {
        submit: "deleteEquipment",
        equipmentId: id
        }, 
        function(data, status) {
            
        },
        "json"
    );    
    getEquipmentList(type);
    removeDeleteModalHTML();
}

function setDeleteModalHTML(myFunction, param, param2, title, text) {
    let HTML = '<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">'+
                    '<div class="modal-dialog">'+
                      '<div class="modal-content">'+
                        '<div class="modal-header">'+
                          '<h5 class="modal-title" id="deleteModalLabel">'+title+'</h5>'+
                          '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                          '</button>'+
                        '</div>'+
                        '<div class="modal-body">'+
                          text+
                        '</div>'+
                        '<div class="modal-footer">'+
                          '<button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>'+
                          '<button type="button" class="btn btn-danger" onClick="'+myFunction+'(\''+param+'\',\''+param2+'\')">Usuń</button>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
    document.getElementById("modalContainer").innerHTML = HTML;
}

function removeDeleteModalHTML() {
    $('#deleteModal').modal('hide');
    document.getElementById("modalContainer").innerHTML = " "; 
}

function putValuestoEquipmentEdit(id) {
    $.post('controller.php', {
        submit: "getEquipmentData",
        id: id
        }, 
        function(data, status) {
            console.log(data);
            if(data.length === 5) {
                $("#typeEquipment").val(data[1]);
                $("#nameEquipment").val(data[2]);
                $("#sizeEquipment").val(data[3]);
                $("#photoURL").val(data[4]);
                if(data[1] === "Narty" || data[1] === "Snowboard" || data[1] === "Kask") {
                    $("#prefixIdEquipment").val(data[0].substring(0, 1));
                    $("#idEquipment").val(data[0].substring(1));
                }
                else {
                    $("#prefixIdEquipment").val(data[0].substring(0, 2));
                    $("#idEquipment").val(data[0].substring(2));
                }
                let submitBTN = document.getElementById("submitEquipmentBTN");
                submitBTN.value = "editEquipment";
                submitBTN.textContent = "Edytuj";
                document.getElementById("firstBanner").innerHTML = "Edytuj sprzęt";
                $("#typeEquipment").prop('disabled', true);
                $.scrollTo($('#firstBanner').offset().top , 1000);
            }
        },
        "json"
    );    
}

function allowListeningToResetBTN() {
    document.getElementById("resetEquipmentBTN").addEventListener("click", function() {
        document.getElementById("firstBanner").innerHTML = "Dodaj nowy sprzęt";
        $("#equipmentInputs input").val();
        $("#typeEquipment").prop('disabled', false);
        let submitBTN = document.getElementById("submitEquipmentBTN");
        submitBTN.value = "addEquipment";
        submitBTN.textContent = "Potwierdź";
    });
}

function allowListeningToIdInput() {
    document.getElementById("allEquipmentId").addEventListener("change", function() {
        let prefix = document.getElementById("prefixIdEquipment");
        let id = document.getElementById("idEquipment");
        let type = document.getElementById("typeEquipment");
        $.post('controller.php', {
            submit: "getCorrectEquipmentId",
            prefix: prefix.value,
            id: id.value,
            type: type.value
            }, 
            function(data, status) {
                console.log(data);
                if(data == 1) {
                    document.getElementById("allEquipmentId").style.boxShadow = '0 0 0 3px green';
                }
                else {
                    document.getElementById("allEquipmentId").style.boxShadow = '0 0 0 3px red';
                }

            }
        );
    });
}