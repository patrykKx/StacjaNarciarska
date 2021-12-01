<?php
    require_once '../classes/Admin.php';
    session_start();
    $admin = new Admin();
    
    $text = ''; $color = '';
    if(isset($_SESSION['addWorkerSuccess'])) {
        if($_SESSION['addWorkerSuccess'] == true) {
            $text = 'Pomyślnie dodałeś pracownika!'; $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas dodania pracownika!'; $color = 'danger'; 
        }
        unset($_SESSION['addWorkerSuccess']);
    }  
    else if(isset($_SESSION['addWorkerValidation'])) {
        $text = $_SESSION['addWorkerValidation']; 
        $color = 'danger';
        unset($_SESSION['addWorkerValidation']);
    }  
    if($text != '' && $color != '') {
        echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
             '</h5>';
    }
?>

<section class="text-center">
    <h4>Dodaj nowego pracownika</h4>   
        <form method="POST" action="controller.php">
          <div class="form-group row pt-2 mb-2 mb-md-5">
              <div class="col-6 col-md-3 my-3 ">
                  <input type="text" class="form-control" id="firstnameW" name="firstname"  placeholder="Imię"
                         value="<?php 
                                if(isset($_SESSION['rememberFirstnameAddWorker'])) {
                                    echo $_SESSION['rememberFirstnameAddWorker']; 
                                    unset($_SESSION['rememberFirstnameAddWorker']); 
                                }
                                ?>"/>
              </div>
              <div class="col-6 col-md-3 my-3 ">
                  <input type="text" class="form-control "  name="surname" placeholder="Nazwisko"
                         value="<?php 
                                if(isset($_SESSION['rememberSurnameAddWorker'])) {
                                    echo $_SESSION['rememberSurnameAddWorker']; 
                                    unset($_SESSION['rememberSurnameAddWorker']); 
                                }
                                ?>"/>
              </div> 
              <div class="col-6 col-md-3 my-3 ">
                  <input type="email" class="form-control "  name="email" placeholder="Email"
                         value="<?php 
                                if(isset($_SESSION['rememberEmailAddWorker'])) {
                                    echo $_SESSION['rememberEmailAddWorker']; 
                                    unset($_SESSION['rememberEmailAddWorker']); 
                                }
                                ?>"/>
              </div>
              <div class="col-6 col-md-3 my-3 ">
                  <input type="text" class="form-control "  name="birthdate" placeholder="Data urodzenia" onfocus="(this.type='date')" onblur="(this.type='text')"
                         value="<?php 
                                if(isset($_SESSION['rememberBirthdateAddWorker'])) {
                                    echo $_SESSION['rememberBirthdateAddWorker']; 
                                    unset($_SESSION['rememberBirthdateAddWorker']); 
                                }
                                ?>"/>
              </div>
              <div class="col-6 col-md-3 my-3 ">
                  <select class="custom-select" name="role" id="workerRole">
                    <option value="" disabled selected>Stanowisko</option>  
                    <option value="Admin">Admin</option>
                    <option value="Serwisant">Serwisant</option>
                    <option value="Techniczny">Techniczny</option>
                    <option value="Instruktor">Instruktor</option>
                  </select>
                  <?php
                    if(isset($_SESSION['rememberRoleAddWorker'])) {
                        ?>
                        <script>
                            document.getElementById('workerRole').value = <?php echo '"'.$_SESSION['rememberRoleAddWorker'].'"'?>;
                        </script>
                        <?php
                        unset($_SESSION['rememberRoleAddWorker']);
                    }
                  ?>
                    <div class="pt-2" id="instructorTypeContainer" hidden="true">
                        <div class="custom-control custom-checkbox col-8 col-lg-4 text-left text-lg-center py-1">
                            <input type="checkbox" class="custom-control-input " id="checkboxSkiingInstructor" value="ski" name="checkboxSkiingInstructor">
                            <label class="custom-control-label" for="checkboxSkiingInstructor">Narciarstwo</label>
                        </div>
                        <div class="custom-control custom-checkbox col-6 col-lg-3 text-left text-lg-center py-1">
                            <input type="checkbox" class="custom-control-input " id="checkboxSnowboardInstructor" value="snowboard" name="checkboxSnowboardInstructor">
                            <label class="custom-control-label" for="checkboxSnowboardInstructor">Snowboard</label>
                        </div>
                    </div>  
              </div>
              <div class="col-6 col-md-3 my-3 ">
                  <input type="text" class="form-control "  name="login" placeholder="Login"
                         value="<?php 
                                if(isset($_SESSION['rememberLoginAddWorker'])) {
                                    echo $_SESSION['rememberLoginAddWorker']; 
                                    unset($_SESSION['rememberLoginAddWorker']); 
                                }
                                ?>"/>
              </div>
              <div class="col-6 col-md-3 my-3 ">
                  <input type="password" class="form-control "  name="pass" placeholder="Hasło"
                         value="<?php 
                                if(isset($_SESSION['rememberPassAddWorker'])) {
                                    echo $_SESSION['rememberPassAddWorker']; 
                                    unset($_SESSION['rememberPassAddWorker']); 
                                }
                                ?>"/>
              </div>
              <div class="col-6 col-md-3 my-3">
                  <input type="password" class="form-control "  name="pass2" placeholder="Powtórz hasło"
                         value="<?php 
                                if(isset($_SESSION['rememberPass2AddWorker'])) {
                                    echo $_SESSION['rememberPass2AddWorker']; 
                                    unset($_SESSION['rememberPass2AddWorker']); 
                                }
                                ?>"/>
              </div>
              <div class="col-12 row pt-4">
                <button type="submit" name="submit" value="addWorker" class="btn btn-success col-4 col-md-3 mx-auto ">Potwierdź</button>
                <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
            </div>   
          </div>
        </form>
</section>

<section class="text-center mt-4">
    <h4>Lista pracowników</h4>
    <div class="py-2" id="workersTypeRadios">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="" value="Admin">
            <label class="form-check-label" for="inlineRadio1">Admin</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="" value="Serwisant">
            <label class="form-check-label" for="inlineRadio2">Serwisant</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="" value="Techniczny">
            <label class="form-check-label" for="inlineRadio3">Techniczny</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="" value="Instruktor">
            <label class="form-check-label" for="inlineRadio3">Instruktor</label>
        </div>
    </div>
    <img src="images/snowspinner.gif" alt="alt" id="personListSpinner" style="display:none;position:absolute;left:48%;"/>
    
    <div class="py-3" id="workerList">
        
    </div>
</section>


