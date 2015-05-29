<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Christian Webber: Homework 2</title>
		
		<link rel="stylesheet" href="reset.css" type="text/css" />
		<link rel="stylesheet" href="stylesheet.css" type="text/css" />
    </head>

    <body>
        <div id="title"><h1>Welcome to Christian's Homework 2 Website</h1></div>
		
		<div id="errors"><p>
		<?php
			$err=null;
			if (array_key_exists('err', $_GET))
			{
				$err = $_GET['err'];
				switch ($err)
				{
					case "none": printf("Account Created!"); break;
					case "badfname": printf("Invalid First Name!"); break;
					case "badlname": printf("Invalid Last Name!"); break;
					case "badnname": printf("Invalid Nickname!"); break;
					case "bademail": printf("Invalid Email!"); break;
					case "badpwd": printf("Passwords must match!"); break;
					case "badpwdb": printf("Password is too long!"); break;
					case "tryagain": printf("Couldn't create account, try again later!"); break;
					default: printf("Unspecified error: %s", $err); break;
				}
			}
		
		?></p></div>
		
		<div id="content">
			<form id="uac" method="post" action="createaccount.php">
				<div id="item">First Name: <input type="text" name="fname" maxlength=50 required=true title="May only contain letters." pattern="[A-Za-z]*"/></div><span id="err"><?php if ($err != null && $err == "badfname") print("Please enter a valid first name!"); ?></span><br>
				<div id="item">Last Name: <input type="text" name="lname" maxlength=50 required=true title="May only contain letters." pattern="[A-Za-z]*" /></div><span id="err"><?php if ($err != null && $err == "badlname") print("Please enter a valid last name!"); ?></span><br>
				<div id="item">Nickname: <input type="text" name="nname" maxlength=50 required=true /></div><span id="err"><?php if ($err != null && $err == "badnname") print("Please enter a valid nickname!"); ?></span><br>
				<div id="item">Email: <input type="email" name="email" maxlength=50 required=true /></div><span id="err"><?php if ($err != null && $err == "bademail") print("Please enter a valid email!"); ?></span><br>
				<div id="item">Password: <input type="password" name="pwd" maxlength=50 required=true /></div><span id="err"><?php if ($err != null && $err == "badpwdb") print("Please enter a valid password!"); ?></span><br>
				<div id="item">Confirm Password:<input type="password" name="cpwd" maxlength=50 required=true /></div><span id="err"><?php if ($err != null && $err == "badpwd") print("Passwords must match!"); ?></span><br>
				<div id="item">Gender: <span id="genderbender"><input type="radio" name="gender" value="m" required=true />Male | <input type="radio" name="gender" value="f" required=true />Female | <input type="radio" name="gender" value="o" required=true />Other</span></div><br>
				<div id="item">Preferences:</div><br>
				<div id="item"><input type="checkbox" name="news" value="newsyes" />Newsletter</div><br>
				<div id="item"><input type="checkbox" name="updates" value="updatesyes" />Receive Product Updates</div><br>
				
				<div id="item"><input type="submit" value="Create Account" /></div>
			</form>
		</div>
    </body>
</html>
