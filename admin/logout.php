<?php
    unset($_SESSION['user_name']);
    session_destroy();
    Header('Location:login');
?>