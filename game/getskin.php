<?php
/*----------------------------------------------------------------------------
    File: getskin.php
    Description: Serves up the skin files when a client requests one.
    
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

    require_once "../config.php";
    require_once "../scripts/functions.php";
    
    /* Query to select skin links for this particular player */
    $query = '
        Select  link
        From    `'.$MySQL['database'].'`.`ActiveSkin` AS ActiveSkin
                Left Join (`'.$MySQL['database'].'`.`Users` AS Users, `'.$MySQL['database'].'`.`Skins` AS Skins)
                On (ActiveSkin.userId = Users.id AND ActiveSkin.skinId = Skins.id)
        Where   Users.username = "'.mysql_real_escape_string($_GET['name']).'";
    ';
    
    $resource = runQuery($query);
    $record = mysql_fetch_assoc($resource);
    
    $file = $config['skinsWorkingLocation'].$record['link'];
    
    header('Content-Type: image/png');
    header('Content-Length:'.filesize($file));
    header('Content-Disposition: attachment;filename="'.$_GET['name'].'"');
    
    $fp=fopen($file,'r');
    fpassthru($fp);
    
    fclose($fp);
?>