<?php
require_once './classes/Form.php';
require_once './classes/Access.php';
session_start();
Access::isAccess("Użytkownik");

Form::userForm();
if(isset($_SESSION['goToUserPage'])) {
    ?>
    <script>
        $("#<?php echo $_SESSION['goToUserPage'];?>").click();
    </script>
    <?php
    unset($_SESSION['goToUserPage']);
}
