<?php
/*----------------------------------------------------------------------------
    File: getversion.php
    Description: Provides authentication for user login to the game client.
    
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

require_once '../config.php';
require_once '../scripts/functions.php';

/* fix path to this file in client */
if((float)getVersion("client") == (float)$_GET['proxy']){
  if(authenticateUser($_POST['user'], $_POST['password'])){
    $sessid = generateSessionId();
    $query='
      Update  `'.$MySQL['database'].'`.`Users`
      Set     session = "'.$sessid.'",
	      lastGameLogin = "'.time().'"
      Where   username = "'.mysql_real_escape_string($_POST['user']).'"
    ';
    
    $success = runQuery($query);
    if($success){
      echo getGameInfo('build').':randomtoken:'.$_POST['user'].':'.$sessid;
    } else {
	echo "Error restart Minecraft";
    }
  } else {
    echo "Bad login";
  }
} else {
  echo "Update Proxy";
}
?>