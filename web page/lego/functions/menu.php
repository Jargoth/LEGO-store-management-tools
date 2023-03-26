<?php
function menu()
{
	include "../www/db.php";
	
	echo "<div class = 'menuminwidth0'>\n";
	echo "<div class = 'menu'>\n";
	echo "<ul>\n";
	
	//BRICKS
	echo "<li>\n";
	echo "<a>\n";
	echo "<img src = 'images/movies.gif' alt = '- BRICKS -' title = '' height = '18' width = '112'/>\n";
	echo "<!--[if gt IE 6]><!--></a><!--<![endif]--><!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
	echo "<ul class = 'skinny'>\n";
  
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=addpiece'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Add Brick</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	}
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=addpiecexml'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Add XML</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	}
  
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=recyclepiece'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Recycle Brick</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	}
  
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=usepiece'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Use Brick</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	}
  
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=merge'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Merge Brick</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	}
	
	echo '
            </ul>
            <!--[if lte IE 6]></td></tr></table></a><![endif]-->
          </li>';
	
	//MODELS
	echo "<li>\n";
	echo "<a>\n";
	echo "<img src = 'images/movies.gif' alt = '- MODELS -' title = '' height = '18' width = '112'/>\n";
	echo "<!--[if gt IE 6]><!--></a><!--<![endif]--><!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
	echo "<ul class = 'skinny'>\n";
	
	echo "<li>\n";
	echo "<a>\n";
	echo "<span class = 'drop'>\n";
	echo "<span>List Models</span>&#187;\n";
	echo "</span>\n";
	echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
	echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
	echo "<ul>\n";
	if ($_COOKIE['user'])
	{
		echo "<li><a href = 'index.php?what=list_model&amp;my=1'>&nbsp;My Models</a></li>\n";
	}
	echo "<li><a href = 'index.php?what=list_model'>&nbsp;All Models</a></li>\n";
	echo "</ul>\n";
	echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
	echo "</li>\n";
  
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=addmodel'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Add Model</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	}
	
	echo '
            </ul>
            <!--[if lte IE 6]></td></tr></table></a><![endif]-->
          </li>';
	
	//PROJECTS
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a>\n";
		echo "<img src = 'images/movies.gif' alt = '- PROJECTS -' title = '' height = '18' width = '112'/>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]--><!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<ul class = 'skinny'>\n";
	
		echo "<li>\n";
		echo "<a href = 'index.php?what=myprojects'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>My Projects</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";

		echo "<li>\n";
		echo "<a href = 'index.php?what=needed'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Needed Bricks</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	
		echo '
            </ul>
            <!--[if lte IE 6]></td></tr></table></a><![endif]-->
          </li>';
	}
	
	//USER
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a>\n";
		echo "<img src = 'images/movies.gif' alt = '- USER -' title = '' height = '18' width = '112'/>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]--><!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<ul class = 'skinny'>\n";
	
		echo "<li>\n";
		echo "<a href = 'index.php?what=user'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>My Page</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	
		echo '
            </ul>
            <!--[if lte IE 6]></td></tr></table></a><![endif]-->
          </li>';
	}
	
	//MAINTENANCE
	if ($_COOKIE['user'] == 1)
	{
		echo "<li>\n";
		echo "<a href = 'index.php?what=maintanance'>\n";
		echo "<img src = 'images/movies.gif' alt = '- MAINTENANCE -' title = '' height = '18' width = '112'/></a>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]--><!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<ul class = 'skinny'>\n";
	
		
	
		echo '
            </ul>
            <!--[if lte IE 6]></td></tr></table></a><![endif]-->
          </li>';
	}
	
	//SPECIAL
	if ($_COOKIE['user'])
	{
		echo "<li>\n";
		echo "<a>\n";
		echo "<img src = 'images/movies.gif' alt = '- SPECIAL -' title = '' height = '18' width = '112'/>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]--><!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<ul class = 'skinny'>\n";
	
		if ($_COOKIE['bricklink'] == 'yes')
		{
			echo "<li>\n";
			echo "<a href = 'index.php?what=specialBricklink'>\n";
			echo "<span class = 'drop'>\n";
			echo "<span>BrickLink</span>\n";
			echo "</span>\n";
			echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
			echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
			echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
			echo "</li>\n";
		}
		
		echo "<li>\n";
		echo "<a href = 'index.php?what=special'>\n";
		echo "<span class = 'drop'>\n";
		echo "<span>Specials Signup</span>\n";
		echo "</span>\n";
		echo "<!--[if gt IE 6]><!--></a><!--<![endif]-->\n";
		echo "<!--[if lt IE 7]><table border='0' cellpadding='0' cellspacing='0'><tr><td><![endif]-->\n";
		echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
		echo "</li>\n";
	
		echo '
            </ul>
            <!--[if lte IE 6]></td></tr></table></a><![endif]-->
          </li>';
	}
		  
	//END
  echo '
        </ul>
        <!--[if lte IE 6]></td></tr></table></a><![endif]-->
      </div>
    </div>
	<div>';
}
?>