/*
// include the following in all files where u need to use it
// Copyright Quint Software Solutions Pvt. Ltd. 1999 - 2004. All rights reserved.
// Contact your lead programmer or Somnath Ukil for help.
<script language=javascript src=quint.js></script>
*/

/***** SAMPLE VALIDATION SCRIPT IN FORM
<script Language="JavaScript"><!--
function QssFormValidator(theForm) {
	if (!validateText(theForm.name, "Your Name", 50, 1, 0)) return (false);
	if (!validateText(theForm.address, "Your address", 100, 1, 0)) return (false);
	if (!validateText(theForm.age, "Your Age", 5, 0, 1)) return (false);
	if (!validateRadio(theForm.sex, "Your Sex")) return (false);
	if (!validateListBox(theForm.city, "Your City", 1)) return (false);
	return (true);
}
//--></script>

********* FORM onsubmit tag sample
onSubmit="return (QssFormValidator(this));"

*/

String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, "");
}
String.prototype.ltrim = function() {
    return this.replace(/^\s+/, "");
}
String.prototype.rtrim = function() {
    return this.replace(/\s+$/, "");
}
/*********
// example of using trim, ltrim, and rtrim
var myString = " hello my name is ";
alert("*" + myString.trim() + "*");
alert("*" + myString.ltrim() + "*");
alert("*" + myString.rtrim() + "*");
**********/

function ShowDiv(divid) {
    document.getElementById(divid).style.visibility = 'visible';
    document.getElementById(divid).style.height = 'auto';
}

function HideDiv(divid) {
    document.getElementById(divid).style.visibility = 'hidden';
    document.getElementById(divid).style.height = '0px';
}

function validateCheckBox_anycheck(formvar_name_prefix, coldesc, as_valid_values) {
    var total = 0;
    for (var i = 0; i < as_valid_values.length; i++) {
        if (getElementByName(formvar_prefix + as_valid_values[i]).checked) {
            total++;
        }
    }
    if (total == 0) {
        alert("Please select one of the \"" + coldesc + "\" options.");
        return false;
    }
    return true;
}

function validateListBox(formvar, coldesc, firstnotallowed) {
	if (formvar.selectedIndex < 0) {
		alert("Please select one of the \"" + coldesc + "\" options.");
		formvar.focus();
		return (false);
	}
	if (firstnotallowed == 1 && formvar.selectedIndex == 0) {
		alert("Please select one of the valid \"" + coldesc + "\" options.");
		formvar.focus();
		return (false);
	}
	return (true);
}

function validateRadio(formvar, coldesc) {
	var radioSelected = false;
	for (i = 0;  i < formvar.length;  i++) {
		if (formvar[i].checked)
			radioSelected = true;
	}
	if (!radioSelected) {
		alert("Please select one of the \"" + coldesc + "\" options.");
		return (false);
	}
	return (true);
}

function getRadioValue(formvar) {
    var radioVal = "";
    for (i = 0; i < formvar.length; i++) {
        if (formvar[i].checked)
            radioVal = formvar[i].value;
    }
    return (radioVal);
}

function validateText(formvar, coldesc, maxsize, checkstr, checkint, checkdate, checkemail) {
	if (formvar.value == "") {
		alert("Please enter a value for the \"" + coldesc + "\" field.");
		formvar.focus();
		return (false);
	}
	if (maxsize > 0 && formvar.value.length > maxsize) {
		alert("Please enter at most "+maxsize+" characters in the \"" + coldesc + "\" field.");
		formvar.focus();
		return (false);
	}
	if (checkstr == 1) {
		var checkOK = "\"'?/<>:0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzƒŠŒšœŸÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİŞßàáâãäåæçèéêëìíîïğñòóôõöøùúûüışÿ.,~`!#$%^&*()-_+=\\|][{} \t\r\n\f";
		var checkStr = formvar.value;
		var allValid = true;
		for (i = 0;  i < checkStr.length;  i++) {
			ch = checkStr.charAt(i);
			for (j = 0;  j < checkOK.length;  j++)
				if (ch == checkOK.charAt(j))
					break;
			if (j == checkOK.length) {
				allValid = false;
				break;
			}
		}
		if (!allValid) {
			alert("Please enter valid characters only in the \"" + coldesc + "\" field.");
			formvar.focus();
			return (false);
		}
	}
	if (checkint == 1) {
		var checkOK = "0123456789-.";
		var checkStr = formvar.value;
		var allValid = true;
		var decPoints = 0;
		var allNum = "";
		for (i = 0;  i < checkStr.length;  i++) {
			ch = checkStr.charAt(i);
			for (j = 0;  j < checkOK.length;  j++)
				if (ch == checkOK.charAt(j))
					break;
			if (j == checkOK.length) {
				allValid = false;
				break;
			}
		allNum += ch;
		}
		if (!allValid) {
			alert("Please enter only digit characters in the \"" + coldesc + "\" field.");
			formvar.focus();
			return (false);
		}
		
		var chkVal = allNum;
		var prsVal = parseInt(allNum);
		if (chkVal != "" && !(prsVal >= "0")) {
			alert("Please enter a value greater than or equal to \"0\" in the \"" + coldesc + "\" field.");
			formvar.focus();
			return (false);
		}
	}
	if (checkdate == 1) {
		if (isDate(formvar.value)==false){
			formvar.focus();
			return (false);
		}
	}
	if (checkemail == 1) {
		if (emailCheck(formvar.value) == false) {
			formvar.focus();
			return (false);
		}
	}
	return (true);
}

/**
 * DHTML date validation script. 
*/
// Declaring valid date character, minimum year and maximum year
var dtCh= "-";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31;
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30;}
		if (i==2) {this[i] = 29;}
   } 
   return this;
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12);
	var pos1=dtStr.indexOf(dtCh);
	var pos2=dtStr.indexOf(dtCh,pos1+1);

	// dd/mm/yyyy - India
	//var strDay=dtStr.substring(0,pos1);
	//var strMonth=dtStr.substring(pos1+1,pos2);
	// mm/dd/yyyy - US
	var strMonth=dtStr.substring(0,pos1);
	var strDay=dtStr.substring(pos1+1,pos2);

	var strYear=dtStr.substring(pos2+1);
	strYr=strYear;
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1);
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1);
	}
	month=parseInt(strMonth);
	day=parseInt(strDay);
	year=parseInt(strYr);
	if (pos1==-1 || pos2==-1){
		alert("The date format should be : mm/dd/yyyy");
		return false;
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Please enter a valid month");
		return false;
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day");
		return false;
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear);
		return false;
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date");
		return false;
	}
	return true;
}

/**********************************************************************/

function emailCheck (emailStr) {
	
	/* The following variable tells the rest of the function whether or not
	to verify that the address ends in a two-letter country or well-known
	TLD.  1 means check it, 0 means don't. */
	
	var checkTLD=1;
	
	/* The following is the list of known TLDs that an e-mail address must end with. */
	
	var knownDomsPat=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/;
	
	/* The following pattern is used to check if the entered e-mail address
	fits the user@domain format.  It also is used to separate the username
	from the domain. */
	
	var emailPat=/^(.+)@(.+)$/;
	
	/* The following string represents the pattern for matching all special
	characters.  We don't want to allow special characters in the address. 
	These characters include ( ) < > @ , ; : \ " . [ ] */
	
	var specialChars="\\(\\)><@,;:\\\\\\\"\\.\\[\\]";
	
	/* The following string represents the range of characters allowed in a 
	username or domainname.  It really states which chars aren't allowed.*/
	
	var validChars="\[^\\s" + specialChars + "\]";
	
	/* The following pattern applies if the "user" is a quoted string (in
	which case, there are no rules about which characters are allowed
	and which aren't; anything goes).  E.g. "jiminy cricket"@disney.com
	is a legal e-mail address. */
	
	var quotedUser="(\"[^\"]*\")";
	
	/* The following pattern applies for domains that are IP addresses,
	rather than symbolic names.  E.g. joe@[123.124.233.4] is a legal
	e-mail address. NOTE: The square brackets are required. */
	
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	
	/* The following string represents an atom (basically a series of non-special characters.) */
	
	var atom=validChars + '+';
	
	/* The following string represents one word in the typical username.
	For example, in john.doe@somewhere.com, john and doe are words.
	Basically, a word is either an atom or quoted string. */
	
	var word="(" + atom + "|" + quotedUser + ")";
	
	// The following pattern describes the structure of the user
	
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
	
	/* The following pattern describes the structure of a normal symbolic
	domain, as opposed to ipDomainPat, shown above. */
	
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
	
	/* Finally, let's start trying to figure out if the supplied address is valid. */
	
	/* Begin with the coarse pattern to simply break up user@domain into
	different pieces that are easy to analyze. */
	
	var matchArray=emailStr.match(emailPat);
	
	if (matchArray==null) {
	
	/* Too many/few @'s or something; basically, this address doesn't
	even fit the general mould of a valid e-mail address. */
	
		alert("Email address seems incorrect (check @ and .'s)");
		return false;
	}
	var user=matchArray[1];
	var domain=matchArray[2];
	
	// Start by checking that only basic ASCII characters are in the strings (0-127).
	
	for (i=0; i<user.length; i++) {
		if (user.charCodeAt(i)>127) {
			alert("Ths username contains invalid characters.");
			return false;
	   }
	}
	for (i=0; i<domain.length; i++) {
		if (domain.charCodeAt(i)>127) {
			alert("Ths domain name contains invalid characters.");
			return false;
	   }
	}
	
	// See if "user" is valid 
	
	if (user.match(userPat)==null) {
	
	// user is not valid
	
		alert("The username doesn't seem to be valid.");
		return false;
	}
	
	/* if the e-mail address is at an IP address (as opposed to a symbolic
	host name) make sure the IP address is valid. */
	
	var IPArray=domain.match(ipDomainPat);
	if (IPArray!=null) {
	
		// this is an IP address
	
		for (var i=1;i<=4;i++) {
			if (IPArray[i]>255) {
				alert("Destination IP address is invalid!");
				return false;
		   }
		}
		return true;
	}

	// Domain is symbolic name.  Check if it's valid.
	 
	var atomPat=new RegExp("^" + atom + "$");
	var domArr=domain.split(".");
	var len=domArr.length;
	for (i=0;i<len;i++) {
		if (domArr[i].search(atomPat)==-1) {
			alert("The domain name does not seem to be valid.");
			return false;
		}
	}
	
	/* domain name seems valid, but now make sure that it ends in a
	known top-level domain (like com, edu, gov) or a two-letter word,
	representing country (uk, nl), and that there's a hostname preceding 
	the domain or country. */
	
	if (checkTLD && domArr[domArr.length-1].length!=2 && 
	domArr[domArr.length-1].search(knownDomsPat)==-1) {
		alert("The address must end in a well-known domain or two letter " + "country.");
		return false;
	}
	
	// Make sure there's a host name preceding the domain.
	
	if (len<2) {
		alert("This address is missing a hostname!");
		return false;
	}
	
	// If we've gotten this far, everything's valid!
	return true;
}

// set the radio button with the given value as being checked
// do nothing if there are no radio buttons
// if the given value does not exist, all the radio buttons
// are reset to unchecked
function setRadioCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}

function popup_center(url, width, height) {
    var width = 710;
    var height = 500;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    var params = 'width=' + width + ', height=' + height;
    params += ', top=' + top + ', left=' + left;
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=yes';
    params += ', scrollbars=yes';
    params += ', status=no';
    params += ', toolbar=no';
    newwin = window.open(url, 'windowname5', params);
    if (window.focus) { newwin.focus() }
    return false;
}