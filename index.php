<!----------------------------------------------------------------------------
    File: index.php
    Description: The homepage for Mineshafter Squared.
    
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
    
    buildHead('Home');
    ?>
    <div class="bodyText">
        <div class="section small dropshadow">
            <h2>
                1. Create Account
            </h2>
            <div class="content">
                <p>
                    Go to <a href="http://www.minecraft.net" target="_blank">Minecraft.net</a> and
                    register for a free account to try the game, or go to
                    <a href="http://www.minecraft.net/prepurchase.jsp" target="_blank">Minecraft Pre-purchase</a>
                    to get a premium account.
                </p>
                <p>
                    Even though this service allows you to play with
                    a free account, we highly suggest that you pay for the game.
                    <a id="premiumTab" href="#">Here's Why</a>.
                </p>
            </div>
        </div>
        <div class="section small dropshadow">
            <h2>
                2. Download Client
            </h2>
            <div class="content">
                <p>
                    Once you have your account then head to our <a href="downloads.php">Downloads</a>
                    page to get the client.  
                </p>
                <p>
                    The page will bring the download for your system to the top
                    and highlight it for you.
                </p>
            </div>
        </div>
        <div class="section small dropshadow">
            <h2>
                3. Play
            </h2>
            <div class="content">
                <p>
                    Once the client is downloaded and you have your account set up, just start the
                    client like any normal program. Log into Minecraft normally and you are all set!
                </p>
                <p>
                    You can also now start using our skin management system, and other advanced features
                    that we offer!
                </p>
            </div>
        </div>
        <div class="clear">
        </div>
        <div class="section">
            <h2>
                About Mineshafter Squared
            </h2>
            <div class="content">
                <p>
                    Welcome to [INSERT NAME HERE], A Mineshafter Squared authentication server!. This is a free and open version of Mineshafter (a free authentication alternative for Minecraft).
                </p>
                <p>
                    This service simply checks to make sure your Minecraft Account exists (to ensure that no one spoofs usernames) and then provides
                    authentication for free or premium accounts.  The service will auto-update your password if you change it on Minecraft.net. This is
                    not done creepily, its simply seemless. When you enter your new password, if it does not match the encrypted version we have on
                    file, it reaches out to see if the password has changed.  If it has, it encrypts the new password and logs you in.
                </p>
                <p>
                    Mineshafter Squared also provides skin and cape management.
                </p>
                <p>
                    If you have any further questions regarding Mineshafter Squared please visit the main website <a target="_blank" href="http://www.mineshaftersquared.com">MineshafterSquared.com</a>
                </p>
            </div>
        </div>
    </div>
    <?php
    buildFoot();
?>

