<!----------------------------------------------------------------------------
    File: capes.php
    Description: The cape management page.
    
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
        if(isset($_POST['capeForm'])){
            if($_FILES['fileUpload']['size'] < $config['maxFileUploadSize']){
                $fileInfo = pathinfo($_FILES['fileUpload']['name']);
                
                if(strtolower($fileInfo['extension']) == 'png'){
                    
                    $fileNameWithoutExt = basename($fileInfo['basename'], '.png')."_".$_SESSION['username'];
                    $cleanFileName = str_replace($config['sanitizeCharacters'], '', $fileNameWithoutExt);
                    $cleanFileName = str_replace(' ', '_', $cleanFileName)."." . $fileInfo['extension'];
                    $targetPath = $config['capesLocation'].$cleanFileName;
                    
                    if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetPath)){
                        $query='
                            Insert Into `'.$MySQL['database'].'`.`Capes`
                            (name, userId, description, link)
                            Values
                            ("'.$_POST['capeName'].'", "'.$_SESSION['userId'].'", "'.$_POST['capeDesc'].'", "'.basename($targetPath).'");
                        ';
                        
                        runQuery($query);
                        $capeId = mysql_insert_id($MySQL['link']);
                        
                        setActiveCape($capeId);
                    } else {
                        $error = "ERROR: Could not upload file";
                    }
                } else {
                    $error = "ERROR: Wrong file type";
                }
            }
        } else if(isset($_POST['delCape'])){
            deleteCape($_POST['capeId']);
        } else if(isset($_POST['setActive'])){
            setActiveCape($_POST['id']);
        }
        
        $capes = getCapes();
    }
        
    buildHead('Capes', array('lists.css'));
        
    if(isset($_SESSION['username'])){
        if(isset($error)){
            echo $error.'<br />';
        }
        ?>
        <div class="bodyWrapper">
            <div id="skinUploadBox">
                <form enctype="multipart/form-data" action="capes.php" method="POST">
                    <input type="hidden" name="capeForm" value="true" />
                    <span class="title" style="width:100px;">
                        Upload Cape:
                    </span>
                    <input name="fileUpload" type="file" style="background-color:#FBFBFB;" />
                    <br />
                    <br />
                    Cape Name: <input type="text" name="capeName" />
                    &nbsp;
                    Description: <input type="text" name="capeDesc" />
                    <input type="submit" value="Upload" />
                </form>
            </div>
            <?php
            if(count($capes) > 0){
            ?>
            <div id="ItemList">
                <h2>
                    Your Capes's
                </h2>
                <?php
                        foreach($capes as $cape){
                            $name = $cape['name'];
                            if(count_chars($cape['name']) >= 15){
                                $name = substr($name, 0, 15);
                            }
                            
                            $desc = $cape['description'];
                            if(count_chars($cape['description']) >= 30){
                                $desc = substr($desc, 0, 30);
                            }
                            
                            $activeCape = getActiveCape();
                            $classExtra = '';
                            if($cape['id'] == $activeCape['id']){
                                $classExtra = 'selected';
                            }
                            
                            ?>
                            <div class="item <?= $classExtra?>">
                                <div class="subItem">
                                    <img src="<?= $config['capesLocation'].$cape['link']?>" alt="<?=$cape['description']?>" class="subItem" />
                                    <div class="subItem text">
                                        <a href="javascript:setActive(<?= $cape['id'] ?>);" ><?= $name ?></a>
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
                                    <a href="javascript:deleteCape(<?= $cape['id'] ?>);">
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
        Please Login to manage your capes.
        <?php
    }
    
    buildFoot();
?>
<div class="hidden">
    <form action="" method="POST" id="delCapeForm" name="delCapeForm">
        <input type="hidden" name="delCape" value="true" />
        <input type="hidden" name="capeId" value="" />
    </form>
    
    <form action="" method="POST" id="setActiveForm" name="setActiveForm">
        <input type="hidden" name="setActive" value="true" />
        <input type="hidden" name="id" value="" />
    </form>
</div>