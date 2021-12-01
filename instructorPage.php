<?php
require_once './classes/Form.php';
require_once './classes/Access.php';
session_start();
Access::isAccess("Instruktor");

Form::instructorForm();
if(isset($_SESSION['goToInstructorPage'])) {
    ?>
    <script>
        $("#<?php echo $_SESSION['goToInstructorPage'];?>").click();
    </script>
    <?php
    unset($_SESSION['goToInstructorPage']);
}

