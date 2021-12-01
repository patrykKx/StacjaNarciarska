<?php
    session_start();
    $text = ''; $color = '';
    if(isset($_SESSION['loginValidation'])) {
        $text = $_SESSION['loginValidation']; $color = 'danger';
        unset($_SESSION['loginValidation']);
    }
    else if(isset($_SESSION['serviceTypeValidation'])) {      
        $text = $_SESSION['serviceTypeValidation']; $color = 'danger';
        unset($_SESSION['serviceTypeValidation']);
    }
    else if(isset($_SESSION['serviceDescriptionValidation'])) {
        $text = $_SESSION['serviceDescriptionValidation']; $color = 'danger';
        unset($_SESSION['serviceDescriptionValidation']);
    }
    else if(isset($_SESSION['addService'])) {
        if($_SESSION['addService']) {
           $text = 'Pomyślnie dodałeś usługę serwisową!';
           $color = 'success';
        }
        else {
           $text = 'Wystąpił błąd podczas dodawania usługi serwisowej!';
           $color = 'danger';
        }
        unset($_SESSION['addService']);
    }
    else if(isset($_SESSION['changeServiceStatus'])) {
        if($_SESSION['changeServiceStatus']) {
           $text = 'Pomyślnie zmieniłeś status usługi serwisowej!';
           $color = 'success';
        }
        else {
           $text = 'Wystąpił błąd podczas zmiany statusu usługi serwisowej!';
           $color = 'danger';
        }
        unset($_SESSION['changeServiceStatus']);
    }
    if($text != '' && $color != '') {
        echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
             '</h5>';
    }
    if(isset($_SESSION['serviceDescription'])) {
    ?>
        <script>document.getElementById("serviceDescription").value = "<?php echo $_SESSION['serviceDescription'];?>";</script>
    <?php
        unset($_SESSION['serviceDescription']);
    }

?>

<section class="pb-5 text-center bg-light">
    <h4>Dodaj sprzęt do serwisu</h4>
    <form method="POST" action="controller.php">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <label for="userLogin">Użytkownik</label>
                    <select class="custom-select" name="userLogin" id="userLogin"> 
                        <option disabled selected></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="serviceType">Rodzaj serwisu</label>
                    <select class="custom-select" name="serviceType" id="serviceType"> 
                        <option disabled selected></option>
                        <option value="Podstawowy">Podstawowy</option>
                        <option value="Kompleksowy">Kompleksowy</option>
                        <option value="Zaawansowany">Zaawansowany</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="serviceCost">Koszt</label>
                    <input type="text" readonly class="form-control" id="serviceCost" name="serviceCost"/>
                </div>
                <div class="col-md-10 mt-md-3 mx-auto">
                    <label for="serviceDescription">Opis</label>
                    <textarea class="textarea form-control" id="serviceDescription" name="serviceDescription" rows="3"></textarea>
                </div>
                <div class="col-12 row pt-4">
                    <button type="submit" name="submit" value="addService" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
                    <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
                </div>
            </div>
        </div>
    </form>
</section>
        
<section class="text-center bg-light">
    <h4>Prace serwisowe w realizacji</h4>
    <div class="pb-3" id="serviceInProgressContainer"></div>
    <h4>Prace serwisowe gotowe do odebrania</h4>
    <div id="serviceReadyToPickUpContainer"></div>
</section>