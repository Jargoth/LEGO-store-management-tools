<?php
function special()
{
	include "../www/db.php";
	if ($_COOKIE['user'])
	{
		if ($_POST['function'] == 'bricklink')
		{
			if ($_POST['how'] == 'out')
			{
				$SQL = "DELETE FROM lego.user_parameters WHERE \"user\" = ".$_COOKIE['user']." AND parameter = 'bricklink'";
				pg_query($db, $SQL);
				setcookie ("bricklink", "", time() - 3600);
			}
			if ($_POST['how'] == 'in')
			{
				$SQL = "SELECT \"value\" FROM lego.user_parameters WHERE parameter = 'bricklink' AND \"user\" = ".$_COOKIE['user'];
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$SQL = "UPDATE lego.user_parameters SET \"value\" = 'yes' WHERE parameter = 'bricklink' AND \"user\" = ".$_COOKIE['user'];
					pg_query($db, $SQL);
					setcookie("bricklink",'yes');
				}
				else
				{
					$SQL = "INSERT INTO lego.user_parameters (\"user\", parameter, \"value\") VALUES (".$_COOKIE['user'].", 'bricklink', 'yes')";
					pg_query($db, $SQL);
					setcookie("bricklink",'yes');
					$SQL = "SELECT \"value\" FROM lego.user_parameters WHERE parameter = 'bricklinkmax' AND \"user\" = ".$_COOKIE['user'];
					$res = pg_fetch_all(pg_query($db, $SQL));
					if (!$res)
					{
						$SQL = "INSERT INTO lego.user_parameters (\"user\", parameter, \"value\") VALUES (".$_COOKIE['user'].", 'bricklinkmax', '50')";
						pg_query($db, $SQL);
						setcookie("bricklinkmax",'50');
					}
					$SQL = "SELECT \"value\" FROM lego.user_parameters WHERE parameter = 'bricklinkmin' AND \"user\" = ".$_COOKIE['user'];
					$res = pg_fetch_all(pg_query($db, $SQL));
					if (!$res)
					{
						$SQL = "INSERT INTO lego.user_parameters (\"user\", parameter, \"value\") VALUES (".$_COOKIE['user'].", 'bricklinkmin', '10')";
						pg_query($db, $SQL);
						setcookie("bricklinkmin",'10');
					}
				}
			}
		}
		echo "Special is a sellection of optional functions thats very specific and don't fits everyone. Below is a list of the functions with a short description and the option to signup for them.";
		echo "<TABLE BORDER = 1>";
		
		//rubrik
		echo "<TR>";
		echo "<TD>";
		echo "signed up";
		echo "</TD>";
		echo "<TD>";
		echo "title";
		echo "</TD>";
		echo "<TD>";
		echo "description";
		echo "</TD>";
		echo "<TD>";
		echo "</TD>";
		echo "</TR>";
		
		//bricklink
		echo "<TR>";
		echo "<TD>";
		if ($_COOKIE['bricklink'] == 'yes')
		{
			echo "yes";
		}
		else
		{
			echo "no";
		}
		echo "</TD>";
		echo "<TD>";
		echo "BrickLink";
		echo "</TD>";
		echo "<TD>";
		echo "BrickLink helps you keep track on your sales on <A HREF = 'http://www.bricklink.com' TARGET = '_blank'>www.bricklink.com</A>.You can set two parameters that control how the function works.";
		echo "<UL>";
		echo "<LI>bricklinkmax: The function recommends you to sell bricks when you have more free bricks than bricklinkmax.</LI>";
		echo "<LI>bricklinkmin: You get a warning when you have less free bricks than bricklinkmin.</LI>";
		echo "</UL>";
		echo "</TD>";
		echo "<TD>";
		echo "<FORM ACTION = 'index.php?what=special' METHOD = 'POST'>";
		echo "<INPUT TYPE = 'hidden' NAME = 'function' ID = 'function' VALUE ='bricklink'>";
		if ($_COOKIE['bricklink'] == 'yes')
		{
			echo "<INPUT TYPE = 'hidden' NAME = 'how' ID = 'how' VALUE = 'out'>";
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'sign out'>";
		}
		else
		{
			echo "<INPUT TYPE = 'hidden' NAME = 'how' ID = 'how' VALUE = 'in'>";
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'sign in'>";
		}
		echo "</FORM>";
		echo "</TD>";
		echo "</TR>";
		echo "</TABLE>";
	}
}
?>