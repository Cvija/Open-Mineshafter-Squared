<?php
/*----------------------------------------------------------------------------
    File: siteBuilder.php
    Description: has functiond needed to build every page.
    
    Contributors: Ryan Sullivan
    Company: Kayotic Labs (www.kayoticlabs.com)
    Webapp: Mineshafter Squared (www.mineshaftersquared.com)
------------------------------------------------------------------------------
    Mineshafter Squared is a replacement for the Minecraft Authentication,
    Skin, and Cape systems.
    Copyright (C) 2011  Ryan Sullivan
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
-----------------------------------------------------------------------------*/

/**
 * buildHead(string $pageTitle [, array $cssFiles]) - builds the top of the site.
 *
 * $pageTitle - Title of the page.
 * $cssFiles - array of src's for css files to be included on the page.
 **/
function buildHead($pageTitle, $cssFiles=array()){
    ?>
    <!DOCTYPE html>

    <html>
    <head>
        <title>
            <?php
                echo $pageTitle;
            ?>
        </title>
        <!--CSS-->
        <link rel="stylesheet" type="text/css" href="css/appwide.css" />
        <?php
        /* Includes css files */
        foreach($cssFiles as $file){
            echo '<link rel="stylesheet" type="text/css" href="css/'.$file.'" />';
        }
        ?>
    </head>
    <body>
        <div id="AppWrapper">
	    <div id="siteTop">
		<div id="banner">
		    <?php
		    if(isset($_SESSION['userId'])){
			?>
			<div id="userName">
			    Welcome <?=$_SESSION['username']?>
			</div>
			<?php
		    }
		    ?>
		    <img src="images/MineshafterSquaredLogo.png" alt="Mineshafter Squared Banner" />
		</div>
		<?php
		if(!isset($_SESSION['userId'])){
		    ?>
		    <div id="logForm">
			    <div class="noteText">
				Sign in with your Minecraft Account to get started.
			    </div>
			    <form action="" method="POST" id="login" name="login">
				<input type="hidden" name="loginform" value="true" />
				<input type="text" name="username" placeholder="Username" /> &nbsp; <input type="password" name="password" placeholder="Password" />
				<a href="javascript:login();" id="loginLink">
				    <div id="loginButton">
					Login
				    </div>
				</a>
			    </form>
			<div class="clear"></div>
		    </div>
		    <?php
		}
		?>
		<div id="navBar" class="dropshadow">
		    <?php
		    if(isset($_SESSION['userId'])){
			$extraClass = 'loggedIn';
		    } else {
			$extraClass = '';
		    }
		    
		    $currentPage = basename($_SERVER['REQUEST_URI'], '.php');
		    ?>
		    <div class="navBarContainer <?=$extraClass?>">
			<a href="index.php">
			    <div class="menuItem  <?php if($currentPage == "index"){ echo "selected"; } ?>">
				Home
			    </div>
			</a>
			<a href="downloads.php">
			    <div class="menuItem <?php if($currentPage == "downloads"){ echo "selected"; } ?>">
			       Downloads
			    </div>
			</a>
			<a href="skins.php">
			    <div class="menuItem <?php if($currentPage == "skins"){ echo "selected"; } ?>">
				Skins
			    </div>
			</a>
			<a href="capes.php">
			    <div class="menuItem <?php if($currentPage == "capes"){ echo "selected"; } ?>">
				Capes
			    </div>
			</a>
			<?php
			if(isset($_SESSION['userId'])){
			    ?>
			    <a href="javascript:logout();">
				<div class="menuItem">
				    <form action="" method="POST" name="logout">
					<input type="hidden" name="logoutform" />
					    Logout
				    </form>
				</div>
			    </a>
			    <?php
			}
			?>
			<div class="clear">
			</div>
		    </div>
		</div>
	    </div>
	    <div id="siteMid">
    <?php
}

/**
 * buildFoot([array $jsFiles]) - builds the top of the site.
 *
 * $jsFiles - array of src's for javascript files to be included on the page.
 **/
function buildFoot($jsFiles=array()){
    ?>
	    </div>
        </div>
    </body>
    </html>
    <?php
    // include on every page
    echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>'."\n";
    echo '<script type="text/javascript" src="js/appwide.js"></script>'."\n";
    
    foreach($jsFiles as $file){
	echo '<script type="text/javascript" src="js/'.$file.'"></script>'."\n";
    }
}
?>