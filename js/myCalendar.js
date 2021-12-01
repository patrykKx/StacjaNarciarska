var addToMonth = 0;

function loadCalendar(addToMonth) {
    document.getElementById('calendarHeader').innerHTML = loadCalendarHeader(addToMonth);
    document.getElementById('calendarBody').innerHTML = loadCalendarBody(addToMonth);
    getOpeningSchedule();
    allowHover();
}

function firstCalendarLoading() {
    addToMonth = 0;
    loadCalendar(addToMonth);
}

function loadCalendarHeader(addToMonth) {
    let calendarHeaderHTML = '';
    let monthHeader = getMonthAndYear(addToMonth);
    calendarHeaderHTML += '<div class="row pb-1">'+
                            '<div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onClick="leftArrowClick()"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>'+
                            '<div class="col-6" id="monthAndYear">'+monthHeader+'</div>'+
                            '<div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onClick="rightArrowClick()"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button></div>'+
                        '</div>';
    return calendarHeaderHTML;
}
function getMonthAndYear(addToMonth) {
    let months = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+addToMonth);
    let month = date.getMonth();
    let year = date.getFullYear();
    return months[month]+" "+year;
}

function loadCalendarBody(addToMonth) {
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+addToMonth);
    let month = date.getMonth();
    let year = date.getFullYear();
    let calendarBodyHTML = '';
    calendarBodyHTML += '<div class="table-responsive">'+
                            '<table class="table" id="myCalendar">'+
                            '<thead>'+
                            '<tr style="background-color: #fffdd0"><th>Nd</th><th>Pn</th><th>Wt</th><th>Śr</th><th>Cz</th><th>Pt</th><th>Sb</th></tr>'+
                            '</thead><tbody><tr>';
    for(let i=1; i<=parseInt(getDaysInMonth(month, year)); i++) {
        let d = new Date(year, month, i);
        let dayOfWeekNumber = d.getDay();

        if(i !== 1 && dayOfWeekNumber === 0) {
            calendarBodyHTML += '<tr>';
        }
        if(i === 1) {
            for(let j=0; j<dayOfWeekNumber; j++) {
                calendarBodyHTML += '<td style="background-color: whitesmoke"></td>';
            }
        }
        
        let dayId;
        if(i < 10 && month < 9) {
            dayId = "'"+year+"-0"+(month+1)+"-0"+i+"'";
        }
        else if(i < 10 && month >= 9){
            dayId = "'"+year+"-"+(month+1)+"-0"+i+"'";
        }
        else if(i >= 10 && month < 9){
            dayId = "'"+year+"-0"+(month+1)+"-"+i+"'";
        }
        else {
            dayId = "'"+year+"-"+(month+1)+"-"+i+"'";
        }
        
        if(document.getElementById('timetableContainer') !== null) {
            calendarBodyHTML += '<td><button type="button" class="col-12 btn-block py-2" id="'+dayId+'" onClick="dayClickTimetable('+dayId+')"><b>'+i+'</b></button></td>';
        }
        else if(document.getElementById('lessonContainer') !== null) {
            calendarBodyHTML += '<td><button type="button" class="col-12 btn-block py-2" id="'+dayId+'" onClick="dayClickLesson('+dayId+')"><b>'+i+'</b></button></td>';
        }
        else {
            calendarBodyHTML += '<td><button type="button" class="col-12 btn btn-block py-2" id="'+dayId+'" onClick="dayClick('+dayId+')"><b>'+i+'</b></button></td>';
        }
        
        if(dayOfWeekNumber === 6) {
            calendarBodyHTML += '</tr>';
        }
    }                                       
    calendarBodyHTML += '</tbody></table></div>';          
    return calendarBodyHTML;
}

function getDaysInMonth(month,year) {
// Here January is 0 based
//Day 0 is the last day in the previous month
    return new Date(year, month+1, 0).getDate();
};

function leftArrowClick() {
    addToMonth--;
    loadCalendar(addToMonth);
}

function rightArrowClick() {
    addToMonth++;
    loadCalendar(addToMonth);    
}

function allowHover() {
   $("td button").hover(
        function() {
            if(this.id !== '') {
                $(this).addClass("bg-info");
                $(this).css("cursor","pointer");
            }
        },
        function() {
            $(this).removeClass("bg-info");
            $(this).css("cursor","default");
        }
    );
};

function getOpeningSchedule() {
    let tempDate = new Date();
    let date = new Date(tempDate.getFullYear(), tempDate.getMonth()+addToMonth);
    let month = date.getMonth();
    let year = date.getFullYear();
    console.log(year+" "+month);
    $.post('controller.php', {
        submit: "showDays",
        year: year,
        month: month+1
        }, 
        function(data, status) {
            for(let i=0; i<data.length; i++) {
                if(data[i] !== null) {
                    let id = "'"+data[i]+"'";
                    document.getElementById(id).style.backgroundColor = 'green';
                }
            }
        },
        "json"
    );
    if(document.getElementById('timetableContainer') !== null) {
        getWorkerScheduleOnMap(year, month+1);
    }
    else if(document.getElementById('lessonContainer') !== null) {
        getInstructorScheduleOnMap(year, month+1);
    }
}

