<?php
/*----------------------------------------------------------------------------
    File: session.php
    Description: controls user sessions
    
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

if(file_exists("scripts/functions.php")){
    require_once "config.php";
    require_once "scripts/functions.php";
} else if(file_exists("functions.php")){
    require_once "../config.php";
    require_once "functions.php";
}

/* Start Logged Out */
$sessLoggedIn = false;

if(isset($MySQL)){
    /* Start the Session */
    session_start();
    
    /* If Logout */
    if(isset($_POST['logoutform'])){
        foreach($_SESSION as $key => $value){
            $_SESSION[$key] = null;
            unset($_SESSION[$key]);
        }
        session_destroy();
    }
    /*If Someone tries to login*/
    else if(isset($_POST['loginform'])){
        /* Check local database for username */
        if(! authenticateUser($_POST['username'], $_POST['password'])){
            $sessionError = "Bad Username Or Password";
        }
    }
}
?>