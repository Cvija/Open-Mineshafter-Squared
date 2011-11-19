<?php
/*----------------------------------------------------------------------------
    File: config.php
    Description: The configuration file for Mineshafter Squared
    
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
 * MySQL Configuration
 * 
 * Creates variable $MySQL to be used for all database connections.
 * Database queries can be made by using the runQuery() function found in
 * functions.php
 **/
$MySQL = array();
$MySQL['url']         = "localhost";
$MySQL['username']    = "kayoticl_Shafter"; // be secure, please do not use root
$MySQL['password']    = "Por2alNewShafter#!)";
$MySQL['database']    = "kayoticl_MineshafterSquaredBeta";

$MySQL['link']        = mysql_connect($MySQL['url'],
                                      $MySQL['username'],
                                      $MySQL['password']) or die("Could not connect to: ".$MySQL['url'].", Error: ".mysql_error());

/**
 * Defaults
 **/
$config['defaultSkinId'] = 1;

/**
 * Autentication Settings
 * 
 * Settings needed for authentication. In the future, most of these will be moved
 * into the database to make things easier.  Just so you are aware of that.
 **/
$config['authVersion'] = 13; // version, do not change this EVER!
$config['authURL'] = 'http://login.minecraft.net'; // the url to connect to Minecraft.net

/**
 * System Variables
 * 
 * Stuff the website needs to function and keep things secure.
 **/
$config['sanitizeCharacters'] = array('/', '\\', '-', '\'', '\"');
$config['skinsLocation'] = "game/skins/"; // where do you want all skins to be stored
$config['skinsWorkingLocation'] = "skins/"; // where are the skins stored in relation to getskin.php
$config['capesLocation'] = "game/capes/"; // where do you want all capes to be stored
$config['capesWorkingLocation'] = "capes/"; // where are the capes stored in relation to getcape.php
$config['maxFileUploadSize'] = 20480; // Maximum file upload size in bytes; 20480 = 20 Kb.
                                      // This is plenty for skin and cape textures.

?>