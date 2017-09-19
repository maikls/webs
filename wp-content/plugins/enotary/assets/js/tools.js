/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function toLog(txt) { console.log(txt); }


function is_int(value) {
  return !isNaN(value) && 
          parseInt(Number(value)) == value && 
          (value + "").replace(/ /g,'') !== "";
}


function is_empty(str) {
 if((str=="") || (str == undefined)) { return true; }
 str = str.trim();
 return (!str || 0 === str.length);
}	

function get_by_id(id) {
 return document.getElementById(id);   
}


function html_hf(id, value) {
  return "<input name=\""+ id + "\" type=\"hidden\" id=\""+ id +"\" value=\""+ encodeURI(value) +"\" />";
}
	
function html_tag(stag, value) {
 return "<" + stag + ">" + value + "</" + stag + ">";
}
 
function html_tag_ex(stag, value, id, classes) {
 return "<" + stag + " id='" + id + "'" + " class='" + classes + "'" + " >" + value + "</" + stag + ">";
} 
 
 function get_value(id, asValue) {
    var itemv = document.getElementById(id);
    if(itemv != null) { 
       if(asValue) { return itemv.value; }
       else { return itemv.innerHTML; }
    }
    else { toLog("Element not found: " + id); return ""; }
 }
 
 function set_value(id, value, asValue) { 
  if(id == '') { return false; }
	var itemv = document.getElementById(id);
	if(itemv != null) { 
	  if(asValue) {itemv.value = value;} 
	    else {itemv.innerHTML = value; } 
	}	 
 }
 
 
 function get_radio_value(name) {
  var radio = document.querySelector('input[name="' + name + '"]:checked');
  if(radio == null) { return ''; }
  else { return radio.value; }

  //  var radio = get_by_id(name); 
  //  if(radio == null) { return ''; }
  //   else { var item = return document.querySelector('input[name="' + name + '"]:checked').value; }
 }
 
 function toggle_class(id, className) {
    if (!id || !className){ return false;}
    var element = document.getElementById(id);
    if(element == null) { return false; }
	
    var classString = element.className, nameIndex = classString.indexOf(className);
    if (nameIndex == -1) { classString += ' ' + className;}
    else { classString = classString.substr(0, nameIndex) + classString.substr(nameIndex+className.length); }
    element.className = classString;
} 


function set_class(id, className, mode){
    if (!id || !className){ return false;}

    var element = get_by_id(id);
    if(element == null) { return false; }

    var classString = element.className, class_exists = (classString.indexOf(className) != -1);
    if(mode && (!class_exists))  { toggle_class(id, className); }
    if((!mode) && (class_exists))  { toggle_class(id, className); }
} 

function set_disable(id, mode) {
    var element = get_by_id(id);
    if(element == null) { return false; }	
    element.disabled = mode;
}


function set_visible(id, mode) {
    //console.log("set_visible: " + id + " - " + mode);  
    var element = get_by_id(id);
    if(element == null) {  return false; }	
    if(mode) { element.style.display = "block"; } 
     else { element.style.display = "none";  }
}