<?php
/*----------------------------------------------------------------------------
    File: functions.php
    Description: has all the functions needed for Mineshafter Squared
    
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
 * runQuery(string $query [, string $returnType]) - Takes care of all database interactions.
 *
 * $query - a MySql query to use against the database
 * $returnType - defines the return type
 *      array - returns an array of records in array form
 *      assoc - returns an array of records in associative array form
 *      resource - returns the mysql resource
 *
 * Returns: an object of the specified return type containing the data returned
 * 	    from the query.
 **/
function runQuery($query, $returnType="resource") {
    global $MySQL;
    
    if (strlen($query) > 0) {
        $resource = mysql_query($query, $MySQL['link']) or die("Query: ".$query.", Error: ".mysql_error());
        
        $result = null;
        switch($returnType){
            case 'array':
                $result = array();
                while($record = mysql_fetch_assoc($resource)){
                    $result[] = $record;
                }
            break;
            
            case 'assoc':
                $result = array();
                while($record = mysql_fetch_assoc($resource)){
                    $result[] = $record;
                }
            break;
            
            case 'resource':
            default:
                $result = $resource;
        }
        
        return $result;
    } else {
        return null;
    }
}

/**
 * getDownloads(string $type) - Retrieves all downloads
 * $type - the type of downloads to get
 * 	client - get the game client downloads
 * 	main - get the servers and souce downloads
 * 	
 * Returns: an array containing the downloads data specified.
 **/
function getDownloads($type){
    global $MySQL;
    
    $query='
        Select      *
        From        `'.$MySQL['database'].'`.`Downloads`
        Where       type = "'.$type.'"
        Order By    id ASC;
    ';
    
    $resource = runQuery($query);
    $allDownloads = array();
    while($temp = mysql_fetch_assoc($resource)){
        array_push($allDownloads, $temp);
    }
    
    return $allDownloads;
}

/**
 * authenticateUser(string $name, string $pass) - Tests for valid username and password
 *
 * $name - the username to test
 * $pass - the password to test
 *
 * Returns: true if the user is valid; false if the user id invalid
 **/
function authenticateUser($name, $pass){
    global $MySQL;
    
    // for now sha256 hash but maybe use different
    $hashpass = hash("sha256", $pass);
    
    $query='
        Select  username
        From    `'.$MySQL['database'].'`.`Users`
        Where   username = "'.mysql_real_escape_string($name).'"
        And     password = "'.mysql_real_escape_string($hashpass).'"
    ';
    
    /* User Authenticated */
    if(mysql_num_rows(runQuery($query)) == 1){
        if(loginUser($name)){
            return true;
        }
    }
    /* User does not currently exist in local database */
    elseif(checkMinecraftForUser($name, $pass)){
	/* Add player to local database */
	if(createNewUser($name, $pass)){
            if(loginUser($name)){
                return true;
            }
	}
    }
    
    return false;
}


/**
 * checkMinecraftForUser(string $user, string $password) - Checks Minecraft.net for
 * valid user account
 *
 * $user - the username to test
 * $password - the password to test
 *
 * Returns: true if the user exists on Minecraft.net; false if the user does not exist on Minecraft.net
 **/
function checkMinecraftForUser($user, $password){
    global $config;
    $postParams = "user=".$user."&password=".$password."&version=".$config['authVersion'];
    $response = curlPost($config['authURL'], $postParams);
    
    $premiumRegex = "/\b[0-9]{13}\b:\b\w+\b:$user:[-]?\b[0-9]+\b/"; // add to database
    $notPremiumRegex = "/User not premium/"; // add to database
    
    if(preg_match($premiumRegex, $response) == 1){
        return true;
    } else if(preg_match($notPremiumRegex, $response) == 1){
        return true;
    } else {
        return false;
    }
}

/**
 * curlPost(string $url, string $parameters) - Posts data to a URL and
 * returns the response
 *
 * $url - the URL to Post to
 * $parameters - the data to pass along
 *
 * Returns: the page that is returned
 **/
function curlPost($url, $parameters){
    $churl = curl_init();
    curl_setopt($churl, CURLOPT_URL, $url."?".$parameters);
    curl_setopt($churl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($churl);
    curl_close($churl);
    return $response;
}

/**
 * loginUser(string $userIdentification) - Trys to log a user in given either
 * its username or its id number.
 *
 * $userIdentification - can be either the username or the id number
 *
 * Returns: true if login as successful; false if login was not successful
 **/
function loginUser($userIdentification){
    global $MySQL;
    $numericInput = false;
    
    $query = '
        Select 	username, id
        From    `'.$MySQL['database'].'`.`Users`
    ';
    
    if(is_numeric($userIdentification)){
        $query .= '
            Where	id = "'.mysql_real_escape_string($userIdentification).'";
        ';
        $numericInput = true;
    } else {
        $query .= '
            Where	username = "'.mysql_real_escape_string($userIdentification).'";
        ';
    }
    
    $record = mysql_fetch_assoc(runQuery($query));
    if($record != false){
        /* set session id */
        $_SESSION['sessId'] = session_id();
        
        /* set time */
        $_SESSION['time'] = time();
        
        /* set username */
        $_SESSION['username'] = $record['username'];
        
        /* set userid */
        $_SESSION['userId'] = $record['id'];
        
        /* If Login By username log the time of login
            else they are already logged in, no need to
            refresh */
        if(!$numericInput){
        /* Set Time */
            $query = '
                Update  `'.$MySQL['database'].'`.`Users`
                Set	    lastWebLogin = "'.$_SESSION['time'].'"
                Where   id = "'.$_SESSION['userId'].'"
            ';
            runQuery($query);
        }
        
        return true;
    } else {
        return false;
    }
}

/**
 * createNewUser(string $user, string $password) - adds a new user account to the
 * database
 *
 * $user - the username to create an account with
 * $password - the password to use for the account
 *
 * Returns: true if a new user was successfully created; false if a new user was not created.
 **/
function createNewUser($user, $password){
    global $config;
    global $MySQL;
    
    $query = '
	Select 	id
	From	`'.$MySQL['database'].'`.`Users`
	Where	username = "'.mysql_real_escape_string($user).'"
    ';
    
    $resource= runQuery($query);
    
    /* If User already exists update the password */
    if(mysql_num_rows($resource) == 1){
	$record = mysql_fetch_assoc($resource);
	$query = '
	    Update      `'.$MySQL['database'].'`.`Users`
	    Set		password = "'.hash("sha256", mysql_real_escape_string($password)).'"
	    Where	id = "'.$record['id'].'"
	';
	
	runQuery($query);
	
    } elseif(mysql_num_rows($resource) == 0){
	$query = '
	    Insert Into `'.$MySQL['database'].'`.`Users`
	    (username, password, createDate)
	    Values
	    ("'.mysql_real_escape_string($user).'", "'.hash("sha256", mysql_real_escape_string($password)).'", "'.time().'")
	';
	
	runQuery($query);
    }
    
    if(mysql_affected_rows($MySQL['link']) == 1){
        return true;
    } else {
        return false;
    }
}

/**
 * getSkins() - returns a list of skins for the current user
 *
 * Returns: an array of data related to the skins for the current user
 **/
function getSkins(){
    global $MySQL;
    
    if(isset($_SESSION['userId'])){
	$query = '
	    Select 	*
	    From	`'.$MySQL['database'].'`.`Skins`
	    Where	userId = "'.mysql_real_escape_string($_SESSION['userId']).'"
	    Order By	name ASC
	';
	
	$resource= runQuery($query);
	
	$skins = array();
	while($record = mysql_fetch_assoc($resource)){
	    $skins[] = $record;
	}
	
	return $skins;
    } else {
	return null;
    }
}

/**
 * getCapes() - returns a list of capes for the current user
 *
 * Returns: an array of data related to the capes for the current user
 **/
function getCapes(){
    global $MySQL;
    
    if(isset($_SESSION['userId'])){
	$query = '
	    Select 	*
	    From	`'.$MySQL['database'].'`.`Capes`
	    Where	userId = "'.mysql_real_escape_string($_SESSION['userId']).'"
	    Order By	name ASC
	';
	
	$resource= runQuery($query);
	
	$capes = array();
	while($record = mysql_fetch_assoc($resource)){
	    $capes[] = $record;
	}
	
	return $capes;
    } else {
	return null;
    }
}

/**
 * deleteSkin(int $skinId) - deletes the skin of the provided id
 * 			     as long as it belongs to the current user
 * $skinId - the id of the skin to delete
 *
 * Returns: true if delete was successful; false if delete was not successful
 **/
function deleteSkin($skinId){
    global $MySQL;
    global $config;
    
    
    $activeSkin = getActiveSkin();
    
    if($activeSkin['id'] == $skinId){
	resetUserSkin();
    }
    
    // Delete File
    $query='
	Select  link
	From `'.$MySQL['database'].'`.`Skins`
	Where   userId = "'.$_SESSION['userId'].'"
	And     id = "'.mysql_real_escape_string($skinId).'";
    ';
    $record = mysql_fetch_assoc(runQuery($query));
    
    $delete = true;
    if(file_exists($config['skinsLocation'].$record['link'])){
	$delete = unlink($config['skinsLocation'].$record['link']);
    }
    
    if($delete){
	// Delete Record
	$query='
	    Delete  From `'.$MySQL['database'].'`.`Skins`
	    Where   userId = "'.$_SESSION['userId'].'"
	    And     id = "'.mysql_real_escape_string($skinId).'";
	';
	runQuery($query);
	
	if(mysql_affected_rows($MySQL['link']) == 1){
	    return true;
	} else {
	    return false;
	}
    } else {
	return false;
    }
}

/**
 * deleteCape(int $capeId) - deletes the cape of the provided id
 * 			     as long as it belongs to the current user
 * $capeId - the id of the cape to delete
 *
 * Returns: true if delete was successful; false if delete was not successful
 **/
function deleteCape($capeId){
    global $MySQL;
    global $config;
    
    
    $activeSkin = getActiveCape();
    
    if($activeCape['id'] == $capeId){
	resetUserCape();
    }
    
    // Delete File
    $query='
	Select  link
	From `'.$MySQL['database'].'`.`Capes`
	Where   userId = "'.$_SESSION['userId'].'"
	And     id = "'.mysql_real_escape_string($capeId).'";
    ';
    $record = mysql_fetch_assoc(runQuery($query));
    
    $delete = true;
    if(file_exists($config['capesLocation'].$record['link'])){
	$delete = unlink($config['capesLocation'].$record['link']);
    }
    
    if($delete){
	// Delete Record
	$query='
	    Delete  From `'.$MySQL['database'].'`.`Capes`
	    Where   userId = "'.$_SESSION['userId'].'"
	    And     id = "'.mysql_real_escape_string($capeId).'";
	';
	runQuery($query);
	
	if(mysql_affected_rows($MySQL['link']) == 1){
	    return true;
	} else {
	    return false;
	}
    } else {
	return false;
    }
}

/**
 * getActiveSkin() - gets the current active skin for the current logged in user
 *
 * Returns: an array containing the data related to the current user's active skin.
 **/
function getActiveSkin(){
    global $MySQL;
    
    $query='
        Select      id, name, description, link, Skins.userId as userId
        From        `'.$MySQL['database'].'`.`ActiveSkin` AS ActiveSkin
        Left Join   (`'.$MySQL['database'].'`.`Skins` AS Skins)
        On          (ActiveSkin.skinId = Skins.id)
        Where       ActiveSkin.userId = "'.mysql_real_escape_string($_SESSION['userId']).'";
    ';
    
    $resource = runQuery($query);
    
    if(mysql_num_rows($resource) == 0){
        
        $query='
            Select      id, name, description, link
            From        `'.$MySQL['database'].'`.`Skins`
            Where       userId = "0";
        ';
        
        $resource = runQuery($query);
        
        if(mysql_num_rows($resource) == 1){
            $record = mysql_fetch_assoc($resource);
        } else {
            $record = array();
            $record['link'] = "skins/default.png";
        }
    } else {
        $record = mysql_fetch_assoc($resource);
    }
    
    return $record;
}

/**
 * getActiveCape() - gets the current active cape for the current logged in user
 *
 * Returns: an array containing the data related to the current user's active cape.
 **/
function getActiveCape(){
    global $MySQL;
    
    $query='
        Select      id, name, description, link, Capes.userId as userId
        From        `'.$MySQL['database'].'`.`ActiveCape` AS ActiveCape
        Left Join   (`'.$MySQL['database'].'`.`Capes` AS Capes)
        On          (ActiveCape.capeId = Capes.id)
        Where       ActiveCape.userId = "'.mysql_real_escape_string($_SESSION['userId']).'";
    ';
    
    $resource = runQuery($query);
    
    if(mysql_num_rows($resource) == 0){
        
        $query='
            Select      id, name, description, link
            From        `'.$MySQL['database'].'`.`Capes`
            Where       userId = "0";
        ';
        
        $resource = runQuery($query);
        
        if(mysql_num_rows($resource) == 1){
            $record = mysql_fetch_assoc($resource);
        }
    } else {
        $record = mysql_fetch_assoc($resource);
    }
    
    return $record;
}

/**
 * resetUserSkin() - resets the current user's skin to the default skin
 *
 * Returns: does not return anything
 **/
function resetUserSkin(){
    global $MySQL;
    
    $skin = getActiveSkin();
    
    if($skin['id'] != 1){
        $query='
            Update  `'.$MySQL['database'].'`.`ActiveSkin`
            Set     skinId = "1"
            Where   userId = "'.mysql_real_escape_string($_SESSION['userId']).'";
        ';
        
        runQuery($query);
        
        $affected = mysql_affected_rows($MySQL['link']);
        
        if($affected == 0){
            $query='
                Insert Into `'.$MySQL['database'].'`.`ActiveSkin`
                (userId, skinId)
                Values
                ("'.mysql_real_escape_string($_SESSION['userId']).'", "1");
            ';
            
            runQuery($query);
        }
    }
}

/**
 * resetUserCape() - remove's the users cape. Does not delete anything.
 *
 * Returns: does not return anything
 **/
function resetUserCape(){
    global $MySQL;
    
    $skin = getActiveCape();
    
    if($skin['id'] != 1){
        $query='
            Update  `'.$MySQL['database'].'`.`ActiveCape`
            Set     capeId = "1"
            Where   userId = "'.mysql_real_escape_string($_SESSION['userId']).'";
        ';
        
        runQuery($query);
        
        $affected = mysql_affected_rows($MySQL['link']);
        
        if($affected == 0){
            $query='
                Insert Into `'.$MySQL['database'].'`.`ActiveCape`
                (userId, capeId)
                Values
                ("'.mysql_real_escape_string($_SESSION['userId']).'", "1");
            ';
            
            runQuery($query);
        }
    }
}

/**
 * setActiveSkin(int $skinId) - sets the active skin of the current user to the specified skin id.
 * $skinId - the id of the skin to be set as the user's active skin.
 * 
 * Returns: does not return anything
 **/
function setActiveSkin($skinId){
    global $MySQL;
    
    if($skinId != null){
	$query='
	    Update  `'.$MySQL['database'].'`.`ActiveSkin`
	    Set     userId = "'.mysql_real_escape_string($_SESSION['userId']).'",
		    skinId = "'.mysql_real_escape_string($skinId).'";
	';
	runQuery($query);
	
	$affected = mysql_affected_rows($MySQL['link']);
	
	if($affected == 0){
	    $query='
		Insert Into `'.$MySQL['database'].'`.`ActiveSkin`
		(userId, skinId)
		Values
		("'.mysql_real_escape_string($_SESSION['userId']).'", "'.mysql_real_escape_string($skinId).'");
	    ';
	    runQuery($query);
	}
    }
}

/**
 * setActiveCape(int $capeId) - sets the active cape of the current user to the specified cape id.
 * $capeId - the id of the cape to be set as the user's active cape.
 * 
 * Returns: does not return anything
 **/
function setActiveCape($capeId){
    global $MySQL;
    
    if($capeId != null){
	$query='
	    Update  `'.$MySQL['database'].'`.`ActiveCape`
	    Set     userId = "'.$_SESSION['userId'].'",
		    capeId = "'.mysql_real_escape_string($capeId).'";
	';
	runQuery($query);
	
	$affected = mysql_affected_rows($MySQL['link']);
	
	if($affected == 0){
	    $query='
		Insert Into `'.$MySQL['database'].'`.`ActiveCape`
		(userId, capeId)
		Values
		("'.mysql_real_escape_string($_SESSION['userId']).'", "'.mysql_real_escape_string($capeId).'");
	    ';
	    runQuery($query);
	}
    }
}

/**
 * getVersion(string $type) - gets the latest version number of either the server or client.
 * $type - can be either 'server' or 'client'
 * 
 * Returns: the latest version number of the specified type
 **/
function getVersion($type){
    global $config;
    $query = '
        Select	value
        From	`'.$MySQL['database'].'`.`Data`
        Where	property = "'.mysql_real_escape_string($type).'-version"
    ';

    $record = mysql_fetch_assoc(runQuery($query));
    return $record['value'];
}

/**
 * checkForGameUpdate(string $currentBuild) - checks to see if there is a newer version of Minecraft.
 * $currentBuild - the clients current version number
 * 
 * Returns: a string with 'true:' or 'false:' followed by the latest game version number
 **/
function checkForGameUpdate($currentBuild){
    global $config;
    
    $query = '
        Select	property, value
        From	`'.$MySQL['database'].'`.`Data`
        Where	property = "latest-game-build"
	OR	property = "latest-game-version"
    ';
    
    $resource = runQuery($query);
    $needsUpdate = false;
    $latestVersion = 0;
    while($record = mysql_fetch_assoc($resource)){
	switch($record['property']){
	    case 'latest-game-build':
		if((int)$record['value'] > (int)$currentBuild){
		    $needsUpdate = true;
		}
	    break;
	    
	    case 'latest-game-version':
		$latestVersion = $record['value'];
	}
    }
    
    if($needsUpdate){
	return ":true:".$latestVersion;
    }
    
    return ":false:".$latestVersion;
}


/**
 * generateSessionId() - generates a unique sessionId
 * 
 * Returns: the sessionId
 **/
function generateSessionId(){
    global $config;
    // generate rand num
    srand(time());
    $randNum = rand(1000000000, 2147483647).rand(1000000000, 2147483647).rand(0,9);
    
    /* Check to see if it is un use */
    $query = '
        Select  session
        From    `'.$MySQL['database'].'`.`Users`
        Where   session = '.$randNum.'
    ';
    
    $resource = runQuery($query);
    if(mysql_num_rows($resource) == 0){
        return $randNum;
    } else {
        return generateSessionId();
    }
}

/**
 * getGameInfo() - returns the type of information asked for
 * $type - can be 'version' 'build' 'client' 'server'
 * 
 * Returns: the information asked for
 **/
function getGameInfo($type){
    global $config;
    
    switch($type){
	case 'version':
	    $query='
		Select  property, value
		From    `'.$MySQL['database'].'`.`Data`
		Where   property = "latest-game-version";
	    ';
	    $resource = mysql_fetch_assoc(runQuery($query));
	    return $resource['value'];
	break;
	
	case 'build':
	    $query='
		Select  property, value
		From    `'.$MySQL['database'].'`.`Data`
		Where   property = "latest-game-build";
	    ';
	    $resource = mysql_fetch_assoc(runQuery($query));
	    return $resource['value'];
	break;
    
        case 'client':
	    $query='
		Select  property, value
		From    `'.$MySQL['database'].'`.`Data`
		Where   property = "client-version";
	    ';
	    $resource = mysql_fetch_assoc(runQuery($query));
	    return $resource['value'];
	break;
    
        case 'server':
	    $query='
		Select  property, value
		From    `'.$MySQL['database'].'`.`Data`
		Where   property = "server-version";
	    ';
	    $resource = mysql_fetch_assoc(runQuery($query));
	    return $resource['value'];
	break;
	
	default:
	    $query='
		Select  property, value
		From    `'.$MySQL['database'].'`.`Data`
		Where   property = "latest-game-build"
		Or      property = "latest-game-version";
	    ';
	    $resource = runQuery($query);
	    $return = array();
	    while($record = mysql_fetch_assoc($resource)){
		switch($record['property']){
		    case 'latest-game-build':
			$return['build'] = $record['value'];
		    break;
		    
		    case 'latest-game-version':
			$return['version'] = $record['value'];
		    break;
		}
	    }
	    
	    return $return['build'].':'.$return['version'];
	break;
    }
}
?>