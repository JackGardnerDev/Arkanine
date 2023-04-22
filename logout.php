<!-- Logout Page -->

<!-- Will Take User to Login & Register Page -->
<!-- Either for not yet having signed in, or for signing out -->

<?php
session_start();
$_SESSION['user'] = null;
session_destroy();
header("Location: log-reg.php");
?>