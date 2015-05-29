<?php
    ////// SETUP //////
    session_start();

    $_SESSION["username"] = null;
    $_SESSION["fname"] = null;
    $_SESSION["lname"] = null;
    $_SESSION["email"] = null;
    $_SESSION["rank"] = null;
    $_SESSION["date"] = null;
    $_SESSION["theme"] = null;

    echo "true";
?>