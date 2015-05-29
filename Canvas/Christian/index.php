<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Christian Webber: Games</title>
		
		<link rel="stylesheet" href="reset.css" type="text/css" />
	</head>

	<body>
		<div id="title"><h1>Welcome to Christian's Game Website</h1></div>
		
		<div id="content">Select a Game from below!<br>
		
		<?php
			if ($handle = opendir('.'))
			{
				$thelist = null;
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." && 
						$file != "index.php" && $file != "index.html")
					{
						$ext = pathinfo($file);
						if ($ext['extension'] == "html")
						{
							$thelist .= '<a href="'.$file.'">'.$ext['basename'].'</a><br>';
						}
					}
				}
				closedir($handle);
			}       
		?>
		<p><?=$thelist?></p>
		</div>
	</body>
</html>
