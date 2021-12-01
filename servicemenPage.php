<?php
require_once './classes/Form.php';
require_once './classes/Access.php';
session_start();
Access::isAccess("Serwisant");

Form::servicemenForm();
if(isset($_SESSION['goToServicemenPage'])) {
    ?>
    <script>
        $("#<?php echo $_SESSION['goToServicemenPage'];?>").click();
    </script>
    <?php
    unset($_SESSION['goToServicemenPage']);
}

