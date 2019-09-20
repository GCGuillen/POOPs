// code.js -- POOP Group 13 -- Austin T

var urlBase = 'http://pooptopoos.com/';
var extension = "php";

var userId = 0;
var firstName = "";
var lastName = "";

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

   try
   {
      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);

      		userId = jsonObject.id;

      		if (userId < 1)
      		{
      			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
      			return;
      		}

      		firstName = jsonObject.firstName;
      		lastName = jsonObject.lastName;

      		document.getElementById("userName").innerHTML = firstName + " " + lastName;

      		document.getElementById("loginName").value = "";
      		document.getElementById("loginPassword").value = "";

      		hideOrShow("loggedInDiv", true);
      		hideOrShow("accessUIDiv", true);
      		hideOrShow("loginDiv", false);

      		xhr.send(jsonPayload);  // Move out of function to bottom of try block
         }
      }
   }
   catch(err)
   {
      document.getElementById("loginResult").innerHTML = err.message;
   }
}

function doLogout()
{
   userId = 0;
   firstName = "";
   lastName = "";

   document.getElementById("loginName").value = "";
   document.getElementById("loginPassword").value = "";
   document.getElementById("contactName").value = "";
   document.getElementById("contactEmail").value = "";
   document.getElementById("contactPhoneNumber").value = "";

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

function doRegistration()
{
   hideOrShow("loginDiv", false);
   hideOrShow("registerDiv", true);
}

function sumbitRegistration()
{
   //
}

function addContact()
{
   var cFName = document.getElementById("cFirstName").value;
   var cLName = document.getElementById("cLastName").value;
   var cPhoneNum = document.getElementById("cPhoneNumber").value;
   var cEmail = document.getElementById("cEmail").value;
   document.getElementById("contactAddResult").innerHTML = "";

   var jsonPayload = '{"cFirstName" : "' + cFName + '", "cLastName" : "' + cLName + '", "cPhoneNum" : "' + cPhoneNum + '", "cEmail" : "' + cEmail + '", "userId" : "' + userId + '"}';
   var url = urlBase + '/AddContact.' + extension;

   var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("contactAddResult").innerHTML = "Contact has been added";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("colorAddResult").innerHTML = err.message;
	}
}

function retrieveContacts()
{
   //
}

function searchContact()
{
   //
}

function deleteContact()
{
   //
}
