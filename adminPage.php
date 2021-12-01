<?php
require_once './classes/Form.php';
require_once './classes/Access.php';
session_start();
Access::isAccess("Admin");

Form::adminForm();
if(isset($_SESSION['goToAdminPage'])) {
    ?>
    <script>
        $("#<?php echo $_SESSION['goToAdminPage'];?>").click();
    </script>
    <?php
    unset($_SESSION['goToAdminPage']);
}