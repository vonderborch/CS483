<?php
	// for debugging
	print_r($_POST);
	
	////// vars //////
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$nname = $_POST['nname'];
	$email = $_POST['email'];
	$pwd = $_POST['pwd'];
	$cpwd = $_POST['cpwd'];
	$gender = $_POST['gender'];
	$news = $updates = null;
	try{ $news = $_POST['news']; }
	catch (Exception $e) { $news = null; }
	try{ $updates = $_POST['updates']; }
	catch (Exception $e) { $updates = null; }

	////// verification //////
	// clean names
	$pattern = "/[A-Za-z]+$/";
	if (preg_match($pattern, $fname) != 1 || strlen($fname) > 50)
	{
		header('Location: index.php?' . http_build_query(array("err" => "badfname")), true);
		exit;
	}
	if (preg_match($pattern, $lname) != 1 || strlen($lname) > 50)
	{
		header('Location: index.php?' . http_build_query(array("err" => "badlname")), true);
		exit;
	}
	if (strlen($nname) > 50)
	{
		header('Location: index.php?' . http_build_query(array("err" => "badnname")), true);
		exit;
	}
	// clean email
	$pattern = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
	if (preg_match($pattern, $email) != 1 || strlen($lname) > 50)
	{
		header('Location: index.php?' . http_build_query(array("err" => "bademail")), true);
		exit;
	}
	// clean password
	if ($pwd != $cpwd)
	{
		header('Location: index.php?' . http_build_query(array("err" => "badpwd")), true);
		exit;
	}
	if (strlen($pwd) > 50)
	{
		header('Location: index.php?' . http_build_query(array("err" => "badpwdb")), true);
		exit;
	}
	// clean gender
	if ($gender == "m")
	{
		$gender = "MALE";
	}
	else if ($gender == "f")
	{
		$gender = "FEMALE";
	}
	else
	{
		$gender = "OTHER";
	}
	
	////// database //////
	$udb = new mysqli('localhost', 'cpts483', 'cpts483', 'cpts483');
	
	if ($udb->connect_error)
	{
		die('Connect Error (' . $udb->connect_errno . ') ' . $udb->connect_error);
	}
	
	// insert into accounts table...
	if ($query = $udb->prepare("INSERT INTO accounts (email, first_name, last_name, nickname, password, gender) values (?, ?, ?, ?, ?, ?)"))
	{
		$query->bind_param('ssssss', $email, $fname, $lname, $nname, $pwd, $gender);
		
		if ($query->execute() == true)
		{
			// grab the id...
			$id = $udb->insert_id;
			$variable = 1;
			// insert news into account preferences table?
			if ($news != null)
			{
				if ($query = $udb->prepare("INSERT INTO account_preferences (account_id, preference_id) values (?, ?)"))
				{
					$variable = 1;
					$query->bind_param('ss', $id, $variable);
					
					if ($query->execute() == false)
					{
						printf("failed to add newsletter preference!\n");
					}
				}
				else
				{
					printf("Prepared Statement Error: %s\n", $udb->error);
				}
			}
			
			// insert updates into account preferences table?
			if ($updates != null)
			{
				if ($query = $udb->prepare("INSERT INTO account_preferences (account_id, preference_id) values (?, ?)"))
				{
					$variable = 2;
					$query->bind_param('ss', $id, $variable);
					
					if ($query->execute() == false)
					{
						printf("failed to add updates preference!\n");
					}
				}
				else
				{
					printf("Prepared Statement Error: %s\n", $udb->error);
				}
			}
		}
		else
		{
			printf("Couldn't create account! %s\n", $udb->error);
			header('Location: index.php?' . http_build_query(array("err" => "tryagain")), true);
			exit;
		}
	}
	else
	{
		printf("Prepared Statement Error: %s\n", $udb->error);
		header('Location: index.php?' . http_build_query(array("err" => "tryagain")), true);
		exit;
	}
	// close the database!
	mysqli_close($udb);
	header('Location: index.php?' . http_build_query(array("err" => "none")), true);
	exit;
?>