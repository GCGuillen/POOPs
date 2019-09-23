// code.js -- POOP Group 13

var urlBase = 'http://pooptopoos.com/';
var extension = "php";

var userId = 0;
var contactId = 0;

function doLogin()
{
   // Set userId to 0 and loginResult to an empty string for error handling purposes
   userId = 0;
   document.getElementById("loginResult").innerHTML = "";

   // Get the username and password typed in by the user
	var uName = document.getElementById("uName").value;
	var passWord = document.getElementById("pWord").value;

   // Setup the json that will be sent to the server and the url
	var jsonPayload = JSON.stringify({login:uName, password:passWord});
	var url = urlBase + '/api/login.' + extension;

   // Prep for sending the json payload to the server
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	alert(uName + passWord);
   try
   {
      // Send the json payload to the server
      xhr.send(jsonPayload);
      //alert(xhr.responseText);
      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            // Parse the response from the server
            var jsonObject = JSON.parse(xhr.responseText);

            // Set the userId and check to make sure it was changed
      		userId = jsonObject.id;
      		if (userId < 1)  // if unchanged, print the error and return
      		{
      			document.getElementById("loginResult").innerHTML = jsonObject.error;
      			return;
      		}

            // Reset the username and password
      		document.getElementById("uName").value = "";
      		document.getElementById("pWord").value = "";

            // Change the page from the login page
      		hideOrShow("loggedInDiv", true);
      		hideOrShow("accessUIDiv", true);
      		hideOrShow("loginDiv", false);
            //location.href = 'contacts.html';
         }
      };

   }
   catch(err)
   {
      document.getElementById("loginResult").innerHTML = err.message;
   }
}

function doLogout()
{
   // Reset all vars
   userId = 0;
   document.getElementById("uName").value = "";
   document.getElementById("pWord").value = "";
   document.getElementById("cFirstName").value = "";
   document.getElementById("cLastName").value = "";
   document.getElementById("cEmail").value = "";
   document.getElementById("cPhoneNumber").value = "";

   // Go back to showing the login page
   hideOrShow("loggedInDiv", false);
	hideOrShow("accessUIDiv", false);
	hideOrShow("loginDiv", true);

   //location.href = 'index.html';
}

function hideOrShow(elementId, showState)
{
   // Handles what the HTML should show on the webpage
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
   // Set userId to 0 and loginResult to an empty string for error handling purposes
   userId = 0;
   document.getElementById("loginResult").innerHTML = "";

   // Get the username and password typed in by the user
	var uName = document.getElementById("uName").value;
	var passWord = document.getElementById("pWord").value;

   // Setup the json that will be sent to the server and the url
   var jsonPayload = JSON.stringify({login:uName, password:passWord});
	var url = urlBase + '/api/signup.' + extension;

   // Prep for sending the json payload to the server
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
      // Send the json payload to the server
	   xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
            alert(xhr.responseText);
            // Parse the response from the server
            var jsonObject = JSON.parse(xhr.responseText);

            // Set the userId and check to make sure it was changed
            userId = jsonObject.id;
            alert(userId);
            if (userId < 1)  // if unchanged, print the error and return
            {
               document.getElementById("loginResult").innerHTML = jsonObject.error;
               return;
            }

            // Reset the username and password
            document.getElementById("uName").value = "";
            document.getElementById("pWord").value = "";

            // Change the page from the login page
            hideOrShow("loggedInDiv", true);
            hideOrShow("accessUIDiv", true);
            hideOrShow("loginDiv", false);
            //location.href = 'contacts.html';
			}
		};

	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

function addContact()
{
   // Get the contact info from the HTML
   var cFName = document.getElementById("cFirstName").value;
   var cLName = document.getElementById("cLastName").value;
   var cPhoneNum = document.getElementById("cPhoneNumber").value;
   var cEmail = document.getElementById("cEmail").value;
   document.getElementById("contactAddResult").innerHTML = "";

   // Prepare to send the contact info to the server
   var jsonPayload = '{"cFirstName" : "' + cFName + '", "cLastName" : "' + cLName + '", "cPhoneNum" : "' + cPhoneNum + '", "cEmail" : "' + cEmail + '", "userId" : "' + userId + '"}';
   var url = urlBase + '/api/createContact.' + extension;
   alert(jsonPayload);
   // Create and open a connection to the server
   var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
	{
      // Send the json payload
      xhr.send(jsonPayload);

		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
            // Clear the add contact fields
         	document.getElementById("cEmail").value = "";
         	document.getElementById("cFirstName").value = "";
         	document.getElementById("cLastName").value = "";
         	document.getElementById("cPhoneNumber").value = "";
				document.getElementById("contactAddResult").innerHTML = "Contact has been added";
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}
}

function searchContact()
{
   alert(userId);
   var srch = document.getElementById("searchText").value;
	document.getElementById("contactSearchResult").innerHTML = "";

	var contactList = document.getElementById("contactList");
	contactList.innerHTML = "";

	var jsonPayload = '{"search" : "' + srch + '", "userId" : "' + userId + '"}';
	var url = urlBase + 'api/SearchContacts.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
      xhr.send(jsonPayload);

		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				hideOrShow("contactList", true);

				document.getElementById("contactSearchResult").innerHTML = "Contact(s) has been retrieved";
				var jsonObject = JSON.parse(xhr.responseText);
            contactId = jsonObject.tableId;
				alert(xhr.responseText);
				var i;
				for (i = 0; i < jsonObject.results.length; i++)
				{
					var opt = document.createElement("option");
					opt.text = jsonObject.results[i];
					opt.value = "";
					contactList.options.add(opt);
				}
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}

function deleteContact()
{

   document.getElementById("contactDeleteResult").innerHTML = "";

   var jsonPayload = '{"tableId" : "' + contactId + '", "userId" : "' + userId + '"}';
	var url = urlBase + 'api/DeleteContacts.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
      xhr.send(jsonPayload);

		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				hideOrShow("contactList", true);

				document.getElementById("contactDeleteResult").innerHTML = "Contact has been deleted";
				var jsonObject = JSON.parse(xhr.responseText);
				alert(xhr.responseText);
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}
