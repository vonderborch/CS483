<?php
    ////// SETUP //////
    session_start();

    // grab variables from posted data
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
        
    ////// DATABASE //////
    $udb = new mysqli('localhost', 'cpts483', 'cpts483', 'blog');
    
    if ($udb->connect_error)
    {
        die('Connect Error (' . $udb->connect_errno . ') ' . $udb->connect_error);
    }
    
    $querystr = @"INSERT INTO users (email, firstname, lastname, username, password) values ('".$email."', '".$fname."', '".$lname."', '".$user."', '".$pass."')";
    //$querystr = @"INSERT INTO users (email, firstname, lastname, username, password) values (?, ?, ?, ?, ?)";
    
    if ($query = $udb->prepare($querystr))
    {
        //$query->bind_parem('sssss', $fname, $lname, $user, $email, $pass);

        if ($query->execute() === true)
        {
            $querystr2 = @"SELECT * FROM users WHERE username = '".$user."' AND password = '".$pass."'";
            if ($query2 = $udb->prepare($querystr2))
            {
                if ($query2->execute() === true)
                {
                    $query2->store_result();
                    $numResults = $query2->num_rows();

                    $query2->bind_result($id, $firstname, $lastname, $username, $email, $password, $theme, $rank, $date);
                    $query2->fetch();
                    
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
                    else { echo "false4"; }
                }
                else { echo "false3"; }
            }
        }
        else { echo "false2"; }
    }
    else { echo "false1"; }
?>