// code.js -- POOP Group 13 -- Austin T

var urlBase = 'http://pooptopoos.com/';
var extension = "php";

var userId = 0;
var firstName = "";
var lastName = "";

var contactName = "";
var contactEmail = "";
var contactPhoneNumber = "";
var contactsList = [];

function doLogin()
{
   userId = 0;
	firstName = "";
	lastName = "";

	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;

	document.getElementById("loginResult").innerHTML = "";

	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Login.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try {
      //
   }
   catch(err) {
      document.getElementById("loginResult").innerHTML = err.message;
   }
}

function doLogout()
{
   userId = 0;
   firstName = "";
   lastName = "";

   //

   document.getElementById("loginName").value = "";
   document.getElementById("loginPassword").value = "";

   hideOrShow("loggedInDiv", false);
	hideOrShow("accessUIDiv", false);
	hideOrShow("loginDiv", true);
}

function hideOrShow(elementId, showState)
{
	var vis = "visible";
	var dis = "block";

	if (!showState)
	{
		vis = "hidden";
		dis = "none";
	}

	document.getElementById(elementId).style.visibility = vis;
	document.getElementById(elementId).style.display = dis;
}

//
