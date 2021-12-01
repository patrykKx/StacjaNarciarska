<section class="text-center bg-light pb-5">
    <h4>Twoje wypożyczenia sprzętu</h4>
    <div class="row py-3">
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="prevYearHire()"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>
        <div class="col-6"><input type="text" readonly="true" class="col-sm-6 mx-auto form-control text-center" id="hireYearInput"/></div>
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="nextYearHire()"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button></div>
    </div>
    <div class="pt-2" id="hireContainer"></div>
</section>

