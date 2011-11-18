<?php
/*----------------------------------------------------------------------------
    File: getcloak.php
    Description: Serves up the cape files when a client requests one.
    
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
        Select  link, public
        From    `'.$MySQL['database'].'`.`ActiveCape` AS ActiveCape
                Left Join (`'.$MySQL['database'].'`.`Users` AS Users, `'.$MySQL['database'].'`.`Capes` AS Capes)
                On (ActiveCape.userId = Users.id AND ActiveCape.capeId = Capes.id)
        Where   Users.username = "'.mysql_real_escape_string($_GET['user']).'";
    ';
    
    $resource = runQuery($query);
    $record = mysql_fetch_assoc($resource);
    
    $file = $config['capesWorkingLocation'].$record['link'];
    
    header('Content-Type: image/png');
    header('Content-Length:'.filesize($file));
    header('Content-Disposition: attachment;filename="'.$_GET['user'].'"');
    
    $fp=fopen($file,'r');
    fpassthru($fp);
    
    fclose($fp);
?>