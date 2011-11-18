/*----------------------------------------------------------------------------
    File: appwide.js
    Description: The javascript needed on all pages 
    
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
$(document).ready(function(){
    $('#login input').die().live('keydown', loginKeyDown);
});

function logout(){
    document.logout.submit();
}

function login(){
    document.login.submit();
}

function loginKeyDown(event){
    if(event.keyCode == 13){
        login();
    }
}

function deleteSkin(idNum){
    $('#delSkinForm [name=skinId]').attr('value', idNum);
    document.delSkinForm.submit();
}

function deleteCape(idNum){
    $('#delCapeForm [name=capeId]').attr('value', idNum);
    document.delCapeForm.submit();
}

function setActive(idNum){
    $('#setActiveForm [name=id]').attr('value', idNum);
    document.setActiveForm.submit();
}