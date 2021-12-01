<section class="text-center pb-5">
    <h4>Statystyki</h4>
    
    <header class="text-center pt-3" id="statisticRadios">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="statisticType" id="stationRadio" value="Station">
            <label class="form-check-label" for="stationRadio">Stacja</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="statisticType" id="workerRadio" value="Worker">
            <label class="form-check-label" for="workerRadio">Pracownicy</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="statisticType" id="userRadio" value="User">
            <label class="form-check-label" for="userRadio">Użytkownicy</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="statisticType" id="offerRadio" value="Offer">
            <label class="form-check-label" for="offerRadio">Usługi</label>
        </div>
    </header>
    <div id="statisticContainer">
        <article>
            <h5 id="mostActiveUsersHeader"></h5>
            <div id="mostActiveUsers" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="openStationHeader"></h5>
            <div id="openStation" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="workerHoursHeader"></h5>
            <div id="workerHours" style="width: 100%;"></div>
        </article>

        <article class="row">
            <h5 id="instructorHoursHeader" class="col-12"></h5>
            <div class="col-12 pt-2" id="instructorHoursDate"></div>
            <div id="instructorHours" style="width: 100%"></div>
        </article>

        <article class="row">
            <h5 id="servicemanHoursHeader" class="col-12"></h5>
            <div class="col-12 pt-2" id="servicemanHoursDate"></div>
            <div id="servicemanHours" style="width: 100%;"></div>
        </article>

        <article class="row">
            <h5 id="technicianHoursHeader" class="col-12"></h5>
            <div class="col-12 pt-2" id="technicianHoursDate"></div>
            <div id="technicianHours" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="stationIncomeHeader"></h5>
            <div id="stationIncome" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="ticketHighlightsHeader"></h5>
            <div id="ticketHighlights" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="hireHighlightsHeader"></h5>
            <div id="hireHighlights" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="lessonHighlightsHeader"></h5>
            <div id="lessonHighlights" style="width: 100%;"></div>
        </article>

        <article>
            <h5 id="mostActiveInstructorsHeader"></h5>
            <div id="mostActiveInstructors" style="width: 100%;"></div>
        </article>
    </div>
</section>
