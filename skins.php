<!----------------------------------------------------------------------------
    File: skins.php
    Description: The skin management page
    
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
------------------------------------------------------------------------------>
<?php
    require_once 'scripts/session.php';
    require_once 'scripts/siteBuilder.php';
    
    if(isset($_SESSION['username'])){
        if(isset($_POST['skinForm'])){
            if($_FILES['fileUpload']['size'] < $config['maxFileUploadSize']){
                $fileInfo = pathinfo($_FILES['fileUpload']['name']);
                
                if(strtolower($fileInfo['extension']) == 'png'){
                    
                    $fileNameWithoutExt = basename($fileInfo['basename'], '.png')."_".$_SESSION['username'];
                    $cleanFileName = str_replace($config['sanitizeCharacters'], '', $fileNameWithoutExt);
                    $cleanFileName = str_replace(' ', '_', $cleanFileName)."." . $fileInfo['extension'];
                    $targetPath = $config['skinsLocation'].$cleanFileName;
                    
                    if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetPath)){
                        $query='
                            Insert Into `'.$MySQL['database'].'`.`Skins`
                            (name, userId, description, link)
                            Values
                            ("'.$_POST['skinName'].'", "'.$_SESSION['userId'].'", "'.$_POST['skinDesc'].'", "'.basename($targetPath).'");
                        ';
                        
                        runQuery($query);
                        $skinId = mysql_insert_id($MySQL['link']);
                        
                        setActiveSkin($skinId);
                    } else {
                        $error = "ERROR: Could not upload file";
                    }
                } else {
                    $error = "ERROR: Wrong file type";
                }
            }
        } else if(isset($_POST['delSkin'])){
            deleteSkin($_POST['skinId']);
        } else if(isset($_POST['setActive'])){
            setActiveSkin($_POST['id']);
        }
        
        $skins = getSkins();
    }
        
    buildHead('Skins', array('lists.css'));
        
    if(isset($_SESSION['username'])){
        if(isset($error)){
            echo $error.'<br />';
        }
        ?>
        <div class="bodyWrapper">
            <div id="skinUploadBox">
                <form enctype="multipart/form-data" action="skins.php" method="POST">
                    <input type="hidden" name="skinForm" value="true" />
                    <span class="title" style="width:100px;">
                        Upload Skin:
                    </span>
                    <input name="fileUpload" type="file" style="background-color:#FBFBFB;" />
                    <br />
                    <br />
                    Skin Name: <input type="text" name="skinName" />
                    &nbsp;
                    Description: <input type="text" name="skinDesc" />
                    <input type="submit" value="Upload" />
                </form>
            </div>
            <?php
            if(count($skins) > 0){
            ?>
                <div id="ItemList">
                    <h2>
                        Your Skin's
                    </h2>
                    <?php
                        foreach($skins as $skin){
                            $name = $skin['name'];
                            if(count_chars($skin['name']) >= 15){
                                $name = substr($name, 0, 15);
                            }
                            
                            $desc = $skin['description'];
                            if(count_chars($skin['description']) >= 30){
                                $desc = substr($desc, 0, 30);
                            }
                            
                            $activeSkin = getActiveSkin();
                            $classExtra = '';
                            if($skin['id'] == $activeSkin['id']){
                                $classExtra = 'selected';
                            }
                            
                            ?>
                            <div class="item <?= $classExtra?>">
                                <div class="subItem">
                                    <img src="<?= $config['skinsLocation'].$skin['link']?>" alt="<?=$skin['description']?>" class="subItem" />
                                    <div class="subItem text">
                                        <a href="javascript:setActive(<?= $skin['id'] ?>);" ><?= $name ?></a>
                                    </div>
                                    <div class="subItem text">
                                        -
                                    </div>
                                    <div class="subItem text">
                                        <?= $desc ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="subItemRight text controls">
                                    <a href="javascript:deleteSkin(<?= $skin['id'] ?>);">
                                        delete
                                    </a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
        <?php
    } else {
        ?>
        Please Login to manage your skins.
        <?php
    }
    
    buildFoot();
?>
<div class="hidden">
    <form action="" method="POST" id="delSkinForm" name="delSkinForm">
        <input type="hidden" name="delSkin" value="true" />
        <input type="hidden" name="skinId" value="" />
    </form>
    
    <form action="" method="POST" id="setActiveForm" name="setActiveForm">
        <input type="hidden" name="setActive" value="true" />
        <input type="hidden" name="id" value="" />
    </form>
</div>