<?php
include "../www/db.php";
include "functions/modelLinePrepareInsert.php";
include "functions/addmodel.php";
include "functions/project.php";
include "functions/recyclepiece.php";
include "functions/usepiece.php";
include "functions/brickInProject.php";
include "functions/addpiece.php";
include "functions/addpiece2.php";
include "functions/addpiece3.php";
include "functions/addpiece4.php";
include "functions/addpiece5.php";
include "functions/replace_picture.php";
include "functions/list.php";
include "functions/brick.php";
include "functions/user.php";
include "functions/add_file.php";
include "functions/list_model.php";
include "functions/model.php";
include "functions/build.php";
include "functions/err_line.php";
include "functions/brick_no_picture.php";
include "functions/modelfile.php";
include "functions/myprojects.php";
include "functions/needed.php";
include "functions/search.php";
include "functions/menu.php";
include "functions/special.php";
include "functions/specialBricklink.php";
include "functions/specialBricklinkSell.php";
include "functions/specialBricklinkCancel.php";
include "functions/specialBricklinkVariables.php";
include "functions/design_without_bricklink.php";
include "functions/color_without_bricklink.php";
include "functions/specialBricklinkXML.php";
include "functions/specialBricklinkXMLupdate.php";
include "functions/deleteproject.php";
include "functions/maintanance.php";
include "functions/specialBricklinkOrder.php";
include "functions/merge.php";
include "functions/obsolete_bricklink_design.php";
include "functions/brick_no_price.php";
include "functions/marked_for_deletion.php";
include "functions/no_lotid.php";
include "functions/addpiecexml.php";
include "functions/color_ldraw.php";
if ($_GET['login'] == 'login')
{
	$_POST['username'] = strtolower($_POST['username']);
	$SQL = "SELECT id, pwd FROM lego.user WHERE \"user\" ILIKE '".$_POST['username']."' AND pwd = md5('".$_POST['password']."')";
	$user = pg_fetch_all(pg_query($db, $SQL));
	$user = $user[0];
	if ($user['id'] == 1)
	{
		setcookie('username', $_POST['username'], time()+60*60*24*365*5);
		setcookie('password', $user['pwd'], time()+60*60*24*365*5);
	}
}
if (!isset($_COOKIE['user']))
{
	if ($_COOKIE['username'] and $_COOKIE['password'])
	{
		$SQL = "SELECT id, pwd FROM lego.user WHERE \"user\" ILIKE '".$_COOKIE['username']."' AND pwd = '".$_COOKIE['password']."'";
		$user = pg_fetch_all(pg_query($db, $SQL));
		$user = $user[0];
		setcookie('user', $user['id'], 0);
		$SQL = "SELECT parameter, \"value\" FROM lego.user_parameters WHERE \"user\" = ".$user['id'];
		$parameters = pg_fetch_all(pg_query($db, $SQL));
		foreach ($parameters as $parameter)
		{
			setcookie($parameter['parameter'], $parameter['value'], 0);
		}
	}
}
if ($_GET['what'] != 'modelfile')
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo "<HTML>\n";
	echo "<HEAD>\n";
	if ($_GET['login'] == 'login' OR $_GET['login'] == 'logout' OR ((!$_COOKIE['user']) and ($_COOKIE['username'] and $_COOKIE['password'])))
	{
		$php_self = explode('/', $_SERVER['PHP_SELF']);
		echo "<meta http-equiv='Refresh' content='0; url=http://".$_SERVER['SERVER_NAME']."/".$php_self[1]."'>\n";
	}
	echo '    <meta http-equiv = "Content-Style-Type" content = "text/css"/>
    <link
      rel = "stylesheet"
      media = "all"
      type = "text/css"
      href = "cssmenu.css"/>';
	echo "</HEAD>\n";
	echo "<BODY>\n";
}
if ($_GET['what'] != 'modelfile')
{
	menu();
}
if (!isset($user['id']))
{
	if ($_COOKIE['username'] and $_COOKIE['password'])
	{
		$SQL = "SELECT id, pwd FROM lego.user WHERE \"user\" ILIKE '".$_COOKIE['username']."' AND pwd = '".$_COOKIE['password']."'";
		$user = pg_fetch_all(pg_query($db, $SQL));
		$user = $user[0];
	}
	elseif ($_GET['what'] != 'modelfile')
	{
		echo "<FORM ACTION = 'index.php?login=login' METHOD = 'POST'>\n";
		echo "User: <INPUT TYPE = 'text' NAME = 'username' ID = 'username'>\n";
		echo "Password: <INPUT TYPE = 'password' NAME = 'password' ID = 'password'>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'login'>\n";
		echo "</FORM>\n";
	}
}
if ($user['id'] == 1)
{
	if ($_GET['what'] != 'modelfile')
	{
		// echo '<A HREF = "index.php?what=list&amp;list=brick">List bricks</A><BR>';
		// echo '<A HREF = "index.php?what=list&amp;list=color">List colors</A><BR>';
		
		echo "<FORM ACTION = 'index.php?what=search' METHOD = 'POST'>\n";
		echo "<INPUT TYPE = 'text' NAME = 'search1' ID = 'search1'>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'search'><BR>\n";
		echo "</FORM>\n";
	
	
		/*$SQL = "SELECT (SELECT sum(dcu.used) FROM lego.design_color_user dcu WHERE \"user\" = ".$user['id'].") AS used, (SELECT sum(dcu.free) FROM lego.design_color_user dcu WHERE \"user\" = ".$user['id'].") AS free, (SELECT sum(dcu.free) + sum(dcu.used) FROM lego.design_color_user dcu WHERE \"user\" = ".$user['id'].") AS total";
		$lego = pg_fetch_all(pg_query($db, $SQL));
		echo "Used pieces: ".$lego[0]['used']."<BR>\n";
		echo "Free pieces: ".$lego[0]['free']."<BR>\n";
		echo "Total: ".$lego[0]['total']."<BR><BR>\n";*/
	}
	if (!$_GET['what'])
	{
		$what = "user";
	}
	if ($_GET['what'] == 'addpiece')
	{
		addpiece();
	}
	if ($_GET['what'] == 'addpiece2')
	{
		addpiece2();
	}
	if ($_GET['what'] == 'addpiece3')
	{
		addpiece3();
	}
	if ($_GET['what'] == 'addpiece4')
	{
		addpiece4();
	}
	if ($_GET['what'] == 'addpiece5')
	{
		addpiece5();
	}
	if ($_GET['what'] == 'replace_picture') /*replaces a brickpricture with a new one/*/
	{
		replace_picture();
	}
	if ($_GET['what'] == 'list')
	{
		lista();
	}
	if ($_GET['what'] == 'brick')
	{
		brick();
	}
	if ($_GET['what'] == 'user' OR $what == 'user')
	{
		user();
	}
	if ($_GET['what'] == 'addmodel')
	{
		addmodel();
	}
	if ($_GET['what'] == 'add_file')
	{
		add_file();
	}
	if ($_GET['what'] == 'list_model')
	{
		list_model();
	}
	if ($_GET['what'] == 'model')
	{
		model();
	}
	if ($_GET['what'] == 'build')
	{
		build();
	}
	if ($_GET['what'] == 'err_line')
	{
		err_line();
	}
	if ($_GET['what'] == 'brick_no_picture')
	{
		brick_no_picture();
	}
	if ($_GET['what'] == 'project')
	{
		project();
	}
	if ($_GET['what'] == 'recyclepiece')
	{
		recyclepiece();
	}
	if ($_GET['what'] == 'usepiece')
	{
		usepiece();
	}
	if ($_GET['what'] == 'myprojects')
	{
		myprojects();
	}
	if ($_GET['what'] == 'needed')
	{
		needed();
	}
	if ($_GET['what'] == 'search')
	{
		search();
	}
	if ($_GET['what'] == 'special')
	{
		special();
	}
	if ($_GET['what'] == 'specialBricklink')
	{
		specialBricklink();
	}
	if ($_GET['what'] == 'specialBricklinkSell')
	{
		specialBricklinkSell();
	}
	if ($_GET['what'] == 'specialBricklinkCancel')
	{
		specialBricklinkCancel();
	}
	if ($_GET['what'] == 'specialBricklinkVariables')
	{
		specialBricklinkVariables();
	}
	if ($_GET['what'] == 'design_without_bricklink')
	{
		design_without_bricklink();
	}
	if ($_GET['what'] == 'color_without_bricklink')
	{
		color_without_bricklink();
	}
	if ($_GET['what'] == 'specialBricklinkXML')
	{
		specialBricklinkXML();
	}
	if ($_GET['what'] == 'specialBricklinkXMLupdate')
	{
		specialBricklinkXMLupdate();
	}
	if ($_GET['what'] == 'deleteproject')
	{
		deleteproject();
	}
	if ($_GET['what'] == 'maintanance')
	{
		maintanance();
	}
	if ($_GET['what'] == 'specialBricklinkOrder')
	{
		specialBricklinkOrder();
	}
	if ($_GET['what'] == 'merge')
	{
		merge();
	}
	if ($_GET['what'] == 'obsolete_bricklink_design')
	{
		obsolete_bricklink_design();
	}
	if ($_GET['what'] == 'brick_no_price')
	{
		brick_no_price();
	}
	if ($_GET['what'] == 'marked_for_deletion')
	{
		marked_for_deletion();
	}
	if ($_GET['what'] == 'no_lotid')
	{
		no_lotid();
	}
	if ($_GET['what'] == 'addpiecexml')
	{
		addpiecexml();
	}
	if ($_GET['what'] == 'color_ldraw')
	{
		color_ldraw();
	}
}
if ($_GET['what'] == 'modelfile')
{
	modelfile();
}

if ($_GET['login'] == 'login')
{
	echo "</BODY>\n";
	echo "</HTML>";
}
?>