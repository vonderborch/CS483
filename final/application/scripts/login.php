<?php
    ////// SETUP //////
    session_start();

    // grab variables from posted data
    $user = $_POST['username'];
    $pass = $_POST['password'];
        
    ////// DATABASE //////
    $udb = new mysqli('localhost', 'cpts483', 'cpts483', 'blog');
    
    if ($udb->connect_error)
    {
        die('Connect Error (' . $udb->connect_errno . ') ' . $udb->connect_error);
    }

    $querystr = @"SELECT * FROM users WHERE username = '".$user."' AND password = '".$pass."'";
    if ($query = $udb->prepare($querystr))
    {
        if ($query->execute() === true)
        {
            $query->store_result();
            $numResults = $query->num_rows();
            
            $query->bind_result($id, $firstname, $lastname, $username, $email, $password, $theme, $rank, $date);
            $query->fetch();

            if ($numResults == 1)
            {
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["fname"] = $firstname;
                $_SESSION["lname"] = $lastname;
                $_SESSION["email"] = $email;
                $_SESSION["rank"] = $rank;
                $_SESSION["date"] = $date;
                $_SESSION["theme"] = $theme;

                echo "true";
            }
            else { echo "false"; }
        }
        else { echo "false"; }
    }
    else { echo "false"; }
?>