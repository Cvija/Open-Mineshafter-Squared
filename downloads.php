<!----------------------------------------------------------------------------
    File: downloads.php
    Description: The page that offers downloads for Mineshafter Squared
    
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
    require_once "Browser.php";
    $Browser = new Browser();
    
    buildHead('Downloads', array('downloads.css'));
    ?>
    <div class="bodyWrapper">
        <div id="blockArea" class="clear listView">
            <div class="row">
                <div class="downloads column first">
                    <div class="block">
                        <h2>
                            Downloads
                        </h2>
                    </div>
                </div>
                <div class="description column">
                    <div class="block">
                        <h2>
                            Description
                        </h2>
                    </div>
                </div>
                <div class="size column">
                    <div class="block">
                        <h2>
                            Size
                        </h2>
                    </div>
                </div>
                <div class="link column last">
                    <div class="block">
                        <h2>
                            Link
                        </h2>
                    </div>
                </div>
                <div class="clear">
                </div>
            </div>
            <div class="row">
                <div class="content header center">
                    Client Downloads
                </div>
            </div>
            <?php
                $downloads = getDownloads('client');
                // put current OS on top
                $downloadType = null;
                switch($Browser->getPlatform()){
                    case Browser::PLATFORM_APPLE:
                        $downloadType = 'mac';
                    break;
                    case Browser::PLATFORM_WINDOWS:
                        $downloadType = 'windows';
                    break;
                    default:
                        $downloadType = 'client';
                    break;
                }
                
                $topDownload = array();
                foreach($downloads as $key => $value){
                    if(is_numeric(strpos(strtolower($value['name']), $downloadType))){
                        $topDownload['key'] = $key;
                        $topDownload['record'] = $value;
                    }
                }
                
                unset($downloads[$topDownload['key']]);
                array_unshift($downloads, $topDownload['record']);
                
                foreach($downloads as $key => $value){
                ?>
                    <div class="row <?php if($key == 0) echo "highlightDL"; ?>">
                        <div class="downloads column first">
                            <div class="content header">
                                <?php
                                echo $value['name'] . ' ' . $value['version'] ?>
                            </div>
                        </div>
                        <div class="description column">
                            <div class="content">
                                <?= $value['description']; ?>
                            </div>
                        </div>
                        <div class="size column">
                            <div class="content">
                                <?php
                                    $size = filesize($value['link'])/1024;
                                    $unit = "KB";
                                    if($size > 100){
                                        $size = $size / 1024;
                                        $unit = "MB";
                                    }
                                    echo number_format($size, 2).' '.$unit;
                                ?>
                            </div>
                        </div>
                        <div class="link column last">
                            <div class="content">
                                <?php
                                    $pathInfo = pathinfo($value['link']);
                                    $trackName = 'downloads/'.str_replace(" ", '', $pathInfo['filename']).'/'.str_replace('.', '-', $value['version']);
                                ?>
                                <a href="<?= $value['link']; ?>" onclick="javascript: _gaq.push(['_trackPageview', '<?= $trackName; ?>']);">
                                    Download
                                </a>
                            </div>
                        </div>
                        <div class="clear">
                        </div>
                    </div>
                    <?php
                }
                ?>
            <div class="row">
                <div class="content header center">
                    Server And Source Downloads
                </div>
            </div>
            <?php
                $downloads = getDownloads('main');
                foreach($downloads as $key => $value){
                ?>
                    <div class="row <?php if($key == count($downloads)-1) echo "last"; ?>">
                        <div class="downloads column first">
                            <div class="content header">
                                <?php
                                echo $value['name'] . ' ' . $value['version'] ?>
                            </div>
                        </div>
                        <div class="description column">
                            <div class="content">
                                <?= $value['description']; ?>
                            </div>
                        </div>
                        <div class="size column">
                            <div class="content">
                                <?php
                                if(trim($value['external']) == ''){
                                    $size = filesize($value['link'])/1024;
                                    $unit = "KB";
                                    if($size > 100){
                                        $size = $size / 1024;
                                        $unit = "MB";
                                    }
                                    echo number_format($size, 2).' '.$unit;
                                } else {
                                    echo $value['external'];
                                }
                                ?>
                            </div>
                        </div>
                        <div class="link column last">
                            <div class="content">
                                <?php
                                    $pathInfo = pathinfo($value['link']);
                                    $trackName = 'downloads/'.str_replace(" ", '', $pathInfo['filename']).'/'.str_replace('.', '-', $value['version']);
                                ?>
                                <a href="<?= $value['link']; ?>" onclick="javascript: _gaq.push(['_trackPageview', '<?= $trackName; ?>']);">
                                    Download
                                </a>
                            </div>
                        </div>
                        <div class="clear">
                        </div>
                    </div>
                    <?php
                }
                ?>
        </div>
    </div>
    <div class="smallText">
        * This file is hosted on <a href="https://github.com/KayoticSully/Open-Mineshafter-Squared">GitHub</a> so the file size is unknown.
    </div>
    <?php
    buildFoot();
?>