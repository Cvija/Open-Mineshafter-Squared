==============================================================
	Mineshafter Squared is a replacement for the 
	Minecraft Authentication, Skin, and Cape systems.
    Copyright (C) 2011  Ryan Sullivan
	
    Contributors: Ryan Sullivan
    Company: Kayotic Labs (www.kayoticlabs.com)
    Webapp: Mineshafter Squared (www.mineshaftersquared.com)
==============================================================

This is all assumed you have a webhost that offers 
the following features, or have installed them yourself
on a computer you wish to use as the authentication server.

1. Webserver (Apache has been tested, but it should work in others)
2. PHP
3. MySQL
4. PHPMyAdmin

Table of Contents
	I. Installation
		1. Database Setup
		2. Config Setup
		3. Upload Files
	II. Supported Web Browsers
		1. Full Support
		2. Semi-Support
	III. Contact and Other Stuff
		1. Contact
		2. Other Stuff
	
=====================
=  I. Installation  =
=====================
1. Database Setup
	Currently the provided SQL file assumes an empty database
	If you have tables in the database you are using that are
	named the same as any that are used.  They will be dropped
	and overridden.  You will loose data in that case.  An
	"Update" sql file is in the works.
	a. Log into PHPMyAdmin
	b. Create a new Database, name does not matter
	c. Click on Import at the top
	d. Select "Choose File" and navigate to the "setup" directory
	e. Use "setupNewDatabase.sql"
	f. Make sure Format is SQL otherwise defaults should be just fine

2. Config Setup
	a. Open up "config.php" found in the root directory of the
		authentication server.
	b. Change the following variables to use your MySQL login data.
		1. $MySQL['username'] to a MySQL user you have setup for the site.
		2. $MySQL['password'] to the password for that user.
		3. $MySQL['database'] to the name of the database you setup.
		
	If you are not changing the directory structure of the authentication server
	you do not need to edit anything else in this file.
	
	If you do want to change the directory structure, then read the comments 
	it should be self-explanatory.

3. Upload Files
	Use an FTP client such as FileZilla to upload everything 
		EXCLUDING:
		1. the setup directory (these files are not needed after initial setup)
		2. readme.txt (you can upload it if you want but there is no point)
		3. license.txt (this is for your records there is no need to upload it)

You are DONE! Use your favorite web browser to make sure the site works.  It should
look similar to http://www.mineshaftersquared.com/MSOpen

================================
=  II. Supported Web Browsers  =
================================
1. Full Support
	a. Chrome (All Versions)
	b. Firefox (Version 7 and up)
	c. Opera (Not Tested, but it should work)
		- If there are problems contact me. I will fully support this browser, I just have not 
		  done extensive testing

2. Semi-Support
	a. Internet Explorer (Version 9 and Up)
		- Functionality is supported, but website formatting is not.
		- Everything should work, it might just look different. (bad).

=================================
=  III. Contact and Other Stuff =
=================================
1. Contact
	a. Email: Ryan@kayoticlabs.com
	b. Website: RyanSullivan.org
	c. MineshafterSquared Website: MineshafterSquared.com
	d. GitHub: https://github.com/KayoticSully/Open-Mineshafter-Squared
2. Other Stuff
	I hope you find Open Mineshafter Squared useful and a pleasure to setup and run.  
	If you have any problems at all please contact me at the above address, or submit 
	an issue on GitHub.