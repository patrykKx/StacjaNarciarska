<section class="pb-5 text-center bg-light">
    <h4>Lista wypożyczeń</h4>
    <div class="row pt-2">
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="prevDay()"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>
        <div class="col-6"><input type="date" class="form-control" id="hireDateInput"/></div>
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="nextDay()"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button></div>
    </div>
    
    <div id="hireHistoryContainer" class="pt-2"></div>
</section>

<section class="pb-5 text-center bg-light">
    <h4>Lista usług serwisowych</h4>
    <div class="pt-2" id="serviceHeader"></div>
    <div id="serviceHistory"></div>
</section>