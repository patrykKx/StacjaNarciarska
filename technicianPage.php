<?php
require_once './classes/Form.php';
require_once './classes/Access.php';
session_start();
Access::isAccess("Techniczny");

Form::technicianForm();
if(isset($_SESSION['goToTechnicianPage'])) {
    ?>
    <script>
        $("#<?php echo $_SESSION['goToTechnicianPage'];?>").click();
    </script>
    <?php
    unset($_SESSION['goToTechnicianPage']);
}
