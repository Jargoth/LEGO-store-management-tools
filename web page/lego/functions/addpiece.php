<?php
function addpiece()
{
	echo "<FORM ACTION = 'index.php?what=addpiece2' METHOD = 'POST'>\n";
	echo "Design Id: <INPUT TYPE = 'text' NAME = 'design' ID = 'design'>\n";
	echo "<INPUT TYPE = 'submit' NAME = 'submit_design' ID = 'submit_design' VALUE = 'search'><BR>\n";
	echo "</FORM>\n";
	echo "<FORM ACTION = 'index.php?what=addpiece2' METHOD = 'POST'>\n";
	echo "Description: <INPUT TYPE = 'text' NAME = 'description' ID = 'description'>\n";
	echo "<INPUT TYPE = 'submit' NAME = 'submit_description' ID = 'submit_description' VALUE = 'search'>\n";
	echo "</FORM>\n";
}
?>