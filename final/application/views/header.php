<?php
    // Start the session
    if(!isset($_SESSION)) {
        session_start();
    }
    //$_SESSION["username"] = null;
    //$_SESSION["fname"] = null;
    //$_SESSION["lname"] = null;
    //$_SESSION["email"] = null;
    //$_SESSION["fname"] = null;
    //$_SESSION["user"] = "von";
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Christian's Blog</title>

    <!-- Import Blueprint CSS -->
    <link rel="stylesheet" href="<?php echo base_url().'application/styles/reset.css'; ?>" type="text/css" media="screen, projection, handheld">
    
    <link rel="stylesheet" href="<?php echo base_url().'application/styles/jquery/jquery-ui.css'; ?>" type="text/css" media="screen, projection, handheld">
    
    <link rel="stylesheet" href="<?php echo base_url().'application/styles/css/bootstrap.css'; ?>" type="text/css" media="screen, projection, handheld">

    <!-- desktop css -->
    <?php
        if(isset($_SESSION["username"])) {
            echo "<link rel='stylesheet' href='".base_url()."application/styles/".$_SESSION["theme"].".css' type='text/css' media='screen, projection, handheld'>";
        }
        else {
            echo "<link rel='stylesheet' href='".base_url()."application/styles/stylesheet.css' type='text/css' media='screen, projection, handheld'>";
        }
    ?>

    <script type="text/javascript" src="<?php echo base_url().'application/scripts/jquery-2.1.3.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'application/scripts/jquery-ui.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'application/scripts/jquery.debouncedresize.js'; ?>"></script>

    <script language="javascript">
        var baseurl = "<?php echo base_url() ?>";

        $(document).ready(function () {
            var dialogLogin, dialogCreate, formLogin, formCreate,
                // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
                emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
                nameRegex = /^[a-z]([0-9a-z_\s])+$/i,
                passRegex = /^([0-9a-zA-Z])+$/,
                name = $( "#username" ),
                password = $( "#password" ),
                namec = $( "#usernamec" ),
                email = $( "#email" ),
                passwordc = $( "#passwordc" ),
                fname = $( "#fname" ),
                lname = $( "#lname" ),
                allLoginFields = $( [] ).add( name ).add( password ),
                allCreateFields = $( [] ).add( namec ).add( fname ).add( lname ).add( email ).add( passwordc ),
                tips = $( ".validateTips" );

            //// HELPERS
            // updates helper text
            function UpdateTips( t ) {
                tips
                    .text( t )
                    .addClass( "ui-state-highlight" );
                setTimeout(function() {
                    tips.removeClass( "ui-state-highlight", 1500 );
                }, 500 );
            }
            // checks the length of a string
            function CheckLength( o, n, min, max ) {
                if ( o.val().length > max || o.val().length < min ) {
                    o.addClass( "ui-state-error" );
                    UpdateTips( "Length of " + n + " must be between " +
                    min + " and " + max + "." );
                    return false;
                } else {
                    return true;
                }
            }
            // checks a regular expression
            function CheckRegEx( o, regexp, n ) {
                if ( !( regexp.test( o.val() ) ) ) {
                    o.addClass( "ui-state-error" );
                    UpdateTips( n );
                    return false;
                } else {
                    return true;
                }
            }
            // posts an ajax request
            function PostRequest(strURL, box) {
                var xmlhttp;
                
                if (window.XMLHttpRequest) {
                    var xmlhttp = new XMLHttpRequest();
                }
                else if (window.ActiveXObject) {
                    var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.open('POST', strURL, true);
                xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4) {
                        UpdatePage(xmlhttp.responseText, box);
                    }
                }
                xmlhttp.send(strURL);
            }
            // updates the page in response to the changes
            function UpdatePage(str, box) {
                if (str == "true")
                {
                    UpdateTips("All form fields are required.");
                    if (box == "login") {
                        dialogLogin.dialog("close");
                    }
                    else if (box == "create") {
                        dialogCreate.dialog("close");
                    }
                    window.location.reload();
                }
                else
                {
                    UpdateTips("Database problems!");
                }
            }

            //// Login and Create User Account options
            // logs in a user
            function Login() {
                var valid = true;
                allLoginFields.removeClass("ui-state-error");
                
                valid = valid && CheckLength(name, "username", 3, 255);
                valid = valid && CheckLength(password, "password", 3, 255);
                
                valid = valid && CheckRegEx(name, nameRegex, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter.");
                valid = valid && CheckRegEx(password, passRegex, "Password field only allow : a-z 0-9");

                if (valid) {
                    var uname = name.val();
                    var upass = password.val();

                    var url = baseurl + "application/scripts/login.php?f=1&username=" + uname + "&password=" + upass;
                    PostRequest(url, "login");

                }

                return valid;
            }

            // creates a user
            function CreateUser() {
                var valid = true;
                allCreateFields.removeClass("ui-state-error");
                
                valid = valid && CheckLength(namec, "username", 3, 255);
                valid = valid && CheckLength(fname, "first name", 3, 255);
                valid = valid && CheckLength(lname, "last name", 3, 255);
                valid = valid && CheckLength(email, "email", 3, 255);
                valid = valid && CheckLength(passwordc, "password", 3, 255);
                
                valid = valid && CheckRegEx(namec, nameRegex, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter.");
                valid = valid && CheckRegEx(fname, nameRegex, "First Name may consist of a-z, 0-9, underscores, spaces and must begin with a letter.");
                valid = valid && CheckRegEx(lname, nameRegex, "Last Name may consist of a-z, 0-9, underscores, spaces and must begin with a letter.");
                valid = valid && CheckRegEx(email, emailRegex, "eg. name@email.com");
                valid = valid && CheckRegEx(passwordc, passRegex, "Password field only allow : a-z 0-9");

                if (valid) {
                    var uname = namec.val();
                    var upass = passwordc.val();
                    var ufname = fname.val();
                    var ulname = lname.val();
                    var uemail = email.val();

                    var url = baseurl + "application/scripts/create.php?f=1&username=" + uname + "&password=" + upass
                                       + "&fname=" + ufname + "&lname=" + ulname + "&email=" + uemail;
                    PostRequest(url, "create");
                }

                return valid;
            }

            //// SETUP DIALOG BOXES!
            dialogLogin = $("#dialog-login").dialog({
                autoOpen: false,
                height: 512,
                width: 512,
                modal: true,
                buttons: {
                    "Login": Login,
                    "Create Account": function() {
                        dialogLogin.dialog("close");
                        dialogCreate.dialog("open");
                    },
                    Cancel: function() {
                        UpdateTips("All form fields are required.");
                        dialogLogin.dialog("close");
                    }
                },
                close: function() {
                    formLogin[0].reset();
                    allLoginFields.removeClass("ui-state-error");
                }
            });
            dialogCreate = $("#dialog-create").dialog({
                autoOpen: false,
                height: 512,
                width: 512,
                modal: true,
                buttons: {
                    "Create Account": CreateUser,
                    Cancel: function() {
                        UpdateTips("All form fields are required.");
                        dialogCreate.dialog("close");
                    }
                },
                close: function() {
                    formCreate[0].reset();
                    allCreateFields.removeClass("ui-state-error");
                }
            });
            formLogin = dialogLogin.find("form").on("submit", function(event) {
                event.preventDefault();
                Login();
            });
            formCreate = dialogCreate.find("form").on("submit", function(event) {
                event.preventDefault();
                CreateUser();
            });
            $("#login_btn").button().on("click", function() {
                dialogLogin.dialog("open");
            });
            $("#create_btn").button().on("click", function() {
                dialogCreate.dialog("open");
            });
            $("#logout_btn").button().on("click", function() {
                var url = baseurl + "application/scripts/logout.php";
                PostRequest(url, "logoutbtn");
            });
            //// SETUP MENU GUI
            $("#home_btn").button().on("click", function() {
                window.location.href = "<?php echo base_url();?>";
                return false;
            });
            $("#post_btn").button().on("click", function() {
                window.location.href = "<?php echo base_url();?>index.php/Blog/ListPosts";
                return false;
            });
            $("#game_btn").button().on("click", function() {
                window.location.href = "<?php echo base_url();?>index.php/Blog/GameList";
                return false;
            });
            $("#admin_btn").button().on("click", function() {
                window.location.href = "<?php echo base_url();?>index.php/Admin/AdminIndex";
                return false;
            });
            $("#edit_btn").button().on("click", function() {
                var uid = '30';
                <?php
                    if (isset($_SESSION))
                    {
                        if (isset($_SESSION["id"]))
                        {
                            ?>uid = <?php echo $_SESSION["id"]; ?> ; <?php
                        }
                    }
                ?>
                window.location.href = "<?php echo base_url();?>index.php/Admin/EditUser/" + uid;
                return false;
            });
        });
    </script>
<head>
    
<body>
    <div id="dialog-login" title="Login" style="display: none">
        <p class="validateTips">All form fields are required.</p>

        <form>
            <fieldset>
                <label for="username">Username: </label>
                <input type="text" name="username" id="username" value="" class="text ui-widget-content ui-corner-all">
                </br>
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all">
                </br>

                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
            </fieldset>
        </form>
    </div>
    <div id="dialog-create" title="Create Account" style="display: none">
        <p class="validateTips">All form fields are required.</p>

        <form>
            <fieldset>
                <label for="usernamec">Username: </label>
                <input type="text" name="usernamec" id="usernamec" value="" class="text ui-widget-content ui-corner-all">
                </br>
                <label for="fname">First Name: </label>
                <input type="text" name="fname" id="fname" value="" class="text ui-widget-content ui-corner-all">
                </br>
                <label for="lname">Last Name: </label>
                <input type="text" name="lname" id="lname" value="" class="text ui-widget-content ui-corner-all">
                </br>
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">
                </br>
                <label for="passwordc">Password: </label>
                <input type="password" name="passwordc" id="passwordc" value="" class="text ui-widget-content ui-corner-all">
                </br>

                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
            </fieldset>
        </form>
    </div>

    <div id="header">

        <h1 id="header-title">CptS 483 Final</h1>
        <h4 id="header-subtitle">Christian Webber's Blog</h4>

        <h3 id="header-menu"><button id='home_btn'>Home</button>     <button id='post_btn'>Posts</button>      <button id='game_btn'>Games</button>      
        <?php 
        if (isset($_SESSION["username"]))
        {
            echo 'Hello '.$_SESSION["fname"];

            if ($_SESSION["rank"] < 3)
            {
                echo "     ";
                echo "<button id='admin_btn'>Administration</button>";
            }

            echo "     ";
            echo "<button id='edit_btn'>Account Settings</button>";

            echo "     <button id='logout_btn'>Logout</button>";
        }
        else
        {
            echo "<button id='login_btn'>Login</button>     <button id='create_btn'>Create Account</button>";
        }

        ?></h3>
    </div>
    <div id="body">
