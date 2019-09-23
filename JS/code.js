// code.js -- POOP Group 13

var urlBase = 'http://pooptopoos.com/';
var extension = "php";

var userId = 0;
var contactId = 0;
var s = 0;

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

   try
   {
      // Send the json payload to the server
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            // Parse the response from the server
            var jsonObject = JSON.parse(xhr.responseText);

            // Set the userId and check to make sure it was changed, if so, print the error and return
      		userId = jsonObject.id;
      		if (userId < 1)
      		{
      			document.getElementById("loginResult").innerHTML = jsonObject.error;
      			return;
      		}

            // Reset the username and password
      		document.getElementById("uName").value = "";
      		document.getElementById("pWord").value = "";

            // Change the page from the login page
      		hideOrShow("contacts", true);
      		hideOrShow("login", false);
      		retrieveContacts();
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
   document.getElementById("searchContact").value = "";
   document.getElementById("cFirstName").value = "";
   document.getElementById("cLastName").value = "";
   document.getElementById("cEmail").value = "";
   document.getElementById("cPhoneNumber").value = "";

   // Go back to showing the login page
   //hideOrShow("contacts", false);
   //hideOrShow("login", true);

   location.href = 'index.html';
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
            // Parse the response from the server
            var jsonObject = JSON.parse(xhr.responseText);

            // Set the userId and check to make sure it was changed, if so, print error and return
            userId = jsonObject.id;
            if (userId < 1)
            {
               document.getElementById("loginResult").innerHTML = jsonObject.error;
               return;
            }

            // Reset the username and password
            document.getElementById("uName").value = "";
            document.getElementById("pWord").value = "";

            // Change the page from the login page
            hideOrShow("contacts", true);
            hideOrShow("login", false);
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
            var table = document.getElementById('gibberish');
            var tr = document.createElement("tr");

            tr.innerHTML = '<td>' + document.getElementById("cFirstName").value + '</td>' +
            '<td>' + document.getElementById("cLastName").value + '</td>' +
            '<td>' + document.getElementById("cEmail").value + '</td>' +
            '<td>' + document.getElementById("cPhoneNumber").value + '</td>';
            table.appendChild(tr);

            // Clear the add contact fields
            document.getElementById("cEmail").value = "";
            document.getElementById("cFirstName").value = "";
            document.getElementById("cLastName").value = "";
            document.getElementById("cPhoneNumber").value = "";
            document.getElementById("contactAddResult").innerHTML = "Contact has been added";
            // deleteTable();
            // retrieveContacts();
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}
}

function retrieveContacts()
{
//   document.getElementById("contactRetrieveResult").innerHTML = "";
   var jsonPayload = '{"userId" : "' + userId + '"}';
   var url = urlBase + 'api/retrieveContacts.' + extension;

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
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   document.getElementById("contactSearchResult").innerHTML = "No contacts were found.";
				   return;
			   }

            var i;
            for (i = 0; i < jsonObject.firstName.length; i++)
				{
               var table = document.getElementById('gibberish');
               var tr = document.createElement("tr");
               s = jsonObject.userId[i];

               tr.setAttribute("id", "insertedTable1"+s);
               tr.innerHTML = '<td>' + jsonObject.firstName[i] + '</td>' +
               '<td>' + jsonObject.lastName[i] + '</td>' +
               '<td>' + jsonObject.email[i] + '</td>' +
               '<td>' + jsonObject.phoneNumber[i] + '</td>';
               table.appendChild(tr);
			   }
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
}

function searchContact()
{
   var srch = document.getElementById("searchContact").value;
	document.getElementById("contactSearchResult").innerHTML = "";

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
			   document.getElementById("contactSearchResult").innerHTML = "Contact(s) found";

				var jsonObject = JSON.parse(xhr.responseText);
				if (jsonObject.error.length > 0)
				{
				   document.getElementById("contactSearchResult").innerHTML = "No contacts were found.";
				   return;
				}

            contactId = jsonObject.tableId;

				var i;
				for (i = 0; i < jsonObject.firstName.length; i++)
				{
               var table = document.getElementById('gibberish2');
               var tr = document.createElement("tr");
               s = jsonObject.userId[i];

               tr.setAttribute("id", "insertedTable2"+s);
               tr.innerHTML = '<td>' + jsonObject.firstName[i] + '</td>' +
               '<td>' + jsonObject.lastName[i] + '</td>' +
               '<td>' + jsonObject.email[i] + '</td>' +
               '<td>' + jsonObject.phoneNumber[i] + '</td>' +
               '<td><button type="button" class="button" onclick="deleteContact(' + (s) + ');">Delete</button></td>';

               table.appendChild(tr);
				}
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}

function deleteTable()
{
   //document.getElementById("gibberish").remove();
   var mainContactTable = document.getElementById("gibberish");
   mainContactTable.parentNode.removeChild(mainContactTable);
}


function deleteContact(id)
{
//   document.getElementById("contactDeleteResult").innerHTML = "";
   contactId = id;

   var jsonPayload = '{"tableId" : "' + contactId + '", "UserId" : "' + userId + '"}';
	var url = urlBase + 'api/deleteContact.' + extension;

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
            var searchContactTable = document.getElementById("insertedTable2"+id);
            searchContactTable.parentNode.removeChild(searchContactTable);

            // deleting current row in search contact result table
            //var mainContactTable = document.getElementById("insertedTable1"+id);
            //mainContactTable.parentNode.removeChild(mainContactTable);

            //document.getElementById("contactDeleteResult").innerHTML = "Contact has been deleted";
            //var jsonObject = JSON.parse(xhr.responseText);

            // Deleteing table
            mainContactTable = document.getElementById("gibberish");
            mainContactTable.parentNode.removeChild(mainContactTable);

            var mainContact = document.getElementById("contacts");
            // var table = document.getElementById('gibberish2');
            var table = document.createElement("table");
            // tr.setAttribute("id", "insertedTable2"+s);
            table.setAttribute("class", "contacts");
            table.setAttribute("id", "gibberish");
            table.setAttribute("border", "1");
            table.innerHTML = '<tr>' + '<th>FirstName</th>' + '<th>Last Name</th>' + '<th>E-Mail</th>' + '<th>Phone Number</th>'
            '</tr>';
            mainContact.appendChild(table);

            retrieveContacts();
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}
