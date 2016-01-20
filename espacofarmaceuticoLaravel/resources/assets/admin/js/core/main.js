var path = "";

/**
 * Sets the event handlers on the login page
*/
function setLoginPage()
{
	//if enter is pressed when input is in focus 
		//submit correct form and info
	$("input, select").keypress(function(e){
		if(e.which == 13)
		{
			var input = $(this);
			if(input.hasClass("login"))
			{
				clickLogin();
			}
			if(input.hasClass("register"))
			{
				clickRegister();
			}
			//if(input.hasClass("company"))
			//{
			//	clickRegisterCompany();
			//}
			return false;
		}
	});
	
	//if cookie[email] put email in the box
	//$(".login[type='email']").val(getCookie("email"));
	
	//stop the dotted box appearing on the tab 
	$("#loginTabs li a").focus(function(){
		$(this).blur();
		//clear all inputs except email if there is a cookie
		$("input").val("");
		$("select").prop("selected", "null");
		$(".login[type='email']").val(getCookie("email"));
		//clear all alerts
		$(".alert").remove();
	});
	
	//If link is pressed open tab
	$(".linkToTab").click(function(e){
		e.preventDefault();
		var ref = $(this).attr("href");
		$("#loginTabs a[href='"+ref+"']").tab("show");
	});
	
	//populateCountry("select.country");
	//$("select.country").change(function(){
	//	populateTimezone("select.timezone", $(this).val());
	//});
	
	//company name fiel remove disabled after company code > 4 chars entered. 
	//ajax search for company code and return ID NOT NAME
	//autocomplete once type
	
	//ADD THIS FOR FANCYNESS LATER MAYBE
	/*$("#companyCode").keyup(function(){
		var code = $(this).val();
		if(code.length > 4)
		{
			//open company name
			$("#company").prop("disabled",false);
		}
		else
		{
			$("#company").prop("disabled", true);
		}
	});*/
	$("#company").keyup(function(){
		var c = $(this).val();
		if(c.length > 2)
		{						
			//search for company code
			searchCode("#company", "#companyId", $("#companyCode").val(), $(this).val());	
		}						
	});
	
	//if the login modal is closed change to login tab
	$("#loginModal").on("hidden.bs.modal", function(){
		$("#loginModal .modal-body").html("");
		$("#loginTabs a[href='#login']").tab("show");
	});
	
	$("input, select").change(function(){
		var alert = $(this).next();
		if(alert.hasClass("input-alerts"))
		{
			alert.remove();
		}
	});
	
	/*$("#companyCode").tooltip({
		title : "Six character code provided by the company administrator",
		trigger : "hover"
	});*/
	
}

/**
* Determines what is shown in the nav bar
* int userId : user's id
* int entityId : a preferred entityId
*/
function populateCommunitiesNav(userId, entityId)
{
	var data = new FormData();
	data.append("function", "findEntityDetails");
	data.append("userId", userId);
	data.append("entityId", entityId);
	data.append("timestamp", new Date().getTime()/1000);
	$.ajax({
		url: path+"include/function.php",
		data: data,
		contentType: false, 
		processData: false,
		type: "POST"
	}).done(function(d){
		var tenants = JSON.parse(d);
		if(tenants.length > 0)
		{			
			var dropdown = "";
			var selectedCommunity = false;
			if(tenants.length == 1)
			{
				tenants[0].selected = true;
			}
			
			var accessCount = 0;
			//user has tenants
			//add these to drop down
			for(var i = 0; i < tenants.length; i++)
			{
				var t = tenants[i];
				var id = t.id;
				var name = t.name;
				var db = t.db;
				var accessEnd = t.accessEnd;
				
				var access = true;
				var disabled = "";
				if(accessEnd != null)
				{
					//console.log(typeof accessEnd);
					//if end of access is before today disable the link and put date
					//var accessDate = new Date(accessEnd.replace(/-/g, "/"));
					accessEndStr = timestampToDate(accessEnd, "d-m-Y");
					//get time in seconds
					var today = new Date().getTime()/1000;
					
					if(accessEnd < today)
					{
						//this doesn't do anything yet
						//maybe show on the list... 
						//console.log("less than today");
						name += "End of access: "+accessEnd;
						disabled = "disabled";
						access = false;
						accessCount--;
					}
				}
				
				//if they have access to this tenant show it in the list
				if(access == true) //&& t.accessLevel < 3
				{
					//if there is only one company show this as selected
					//find slected company
					if(t.selected)
					{
						selectedCommunity = true;
						//show on top
						$("#selectedLink").html(name+"<span class='caret'></span>");
						//show tenant links
						$(".tenantLinks").removeClass("hide");
					}
					else
					{
						//add to dropdown
						dropdown += "<li><a href='#' onclick='changeEntity("+id+")'><span class='glyphicon glyphicon-chevron-right'></span>&nbsp;"+name+"</a></li>\n";	
						
					}
				}
				accessCount++;
			}
			if(selectedCommunity == false)
			{				
				$("#selectedLink").html("Select Entity <span class='caret'></span>");
			}
			
			$("#selectedLink").attr("href","#");
			$("#selectedLink").attr("data-toggle","dropdown");
			$("#selectedLink").dropdown();
			
			//if the amount of links availiable is more then one 
			if(accessCount > 1)
			{
				dropdown += "<li class='divider'></li>";
			}
				
			dropdown +=	"<!--<li><a href='#'><span class='glyphicon glyphicon-plus'></span>&nbsp;Associate New Entity</a></li>\n-->"
						+"<li><a href='/entity/'><span class='glyphicon glyphicon-cog'></span>&nbsp;Entity Control</a></li>\n";
			$("#tenantDropdown .dropdown-menu").html(dropdown);
		}
		else
		{
			//no tenants.
			//ask to register
			$("#selectedLink").html("Associate Entity");
			$("#selectedLink").attr("href","/entity/");
		}
	});
}

/**
* When user selects to change their entity
* Send request to ajax to change the sessions
* go to dash
* int id = entity Id
*/
function changeEntity(id)
{
	//get time
	var time = new Date().getTime()/1000;
	console.log(time);
	var data = new FormData();

	data.append("function", "changeEntity");
	data.append("entityId", id);
	data.append("timestamp", time);
	////define data
	$.ajax({
		url:path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{
			console.log("go to dash");
			//go to dash.php
			location.href="/dash/";
		}
		else
		{
			alert("You are not part of this entity. Please try again.");
		}
	});
}


/**
* When user logs in
*/
function clickLogin()
{

	var elem = $("input.login:visible");
	validate("login", elem, function(errs){
		if(Object.keys(errs).length > 0)
		{
			for(x in errs)
			{
				$(x).parent().addClass("has-error");
				var msg = errs[x];
				var str = "";
				//when there is more then one error for that input show them under each other.
				for(m in msg)
				{
					str += msg[m]+" <br/>";
				}
				$(x+":visible").after("<div class='alert alert-danger input-alerts'>"+str+"</div>");
				//do something with msg;
			}
		}
		else
		{
			var data = new FormData();
			data.append("function","login");
			data.append("timestamp", new Date().getTime()/1000);
			if(elem)
			{
				elem.each(function(){
					var id = $(this).attr("id");
					var val = $(this).val();
					data.append(id, val);
				});
			}
			
			$.ajax({
				url: path+"include/function.php",
				type: "POST",
				data: data,
				processData: false,
				contentType: false	
			}).done(function(d){
				console.log(d);
                d = JSON.parse(d);
				console.log(d);
				if(d.success == true)
				{	
					//console.log(d.uri);
					location.href=d.uri;
					//console.log("success");
				}
				else
				{
					//alert(da.msg);
					//$("#alertFormRow").html("<div class='alert alert-block alert-danger input-alerts'>"+da.msg+"</div>");
					var errs = d.errs;
					for(e in errs)
					{
						var str = "";
						for(m in errs[e])
						{
							str += errs[e][m];
						}
						$(e+":visible").after("<div class='alert alert-danger input-alerts'>"+str+"</div>");
					}
				}
			});			
		}
	});
}

function clickRegister()
{
	//remove alerts
	$("#register .alert").remove();
	var elem = $("input.register, select.register");
	validate("registerUser", elem ,function(errs){
		console.log(errs);
		//if any errors sent back change input colour
		//show msg
		if(Object.keys(errs).length > 0)
		{
			for(x in errs)
			{
				$(x+".register").parent().addClass("has-error");
				var msg = errs[x];
				var str = "";
				//when there is more then one error for that input show them under each other.
				for(m in msg)
				{
					str += msg[m]+" <br/>";
				}
				$(x+":visible").after("<div class='alert alert-danger input-alerts'>"+str+"</div>");
			}
		}
		else
		{
			//if data is ok 
			//create user
			var data = new FormData();
			data.append("function", "register");
						
			if(elem)
			{
				elem.each(function(){
					//don't add the password confirmation
					if($(this).attr("id") != "passwordConfirm")
					{
						data.append($(this).attr("id"), $(this).val());
					}					
				});
			}
			
			$.ajax({
				url: path+"include/function.php",
				data: data,
				type: "POST",
				processData: false,
				contentType: false
			}).done(function(d){
				console.log(d);
				var arr = JSON.parse(d);
				console.log(arr);
				if(arr.success)
				{
					$("#loginModal .modal-body").html("<h3>Welcome to SafetyBeat</h3><br/>Please login");
					$("#loginModal").modal();
					$("#email.login").val(arr.email);
				}
				else
				{
					$("#register .form-group:eq(0)").before("<div class='alert alert-block alert-danger'>"+arr.msg+"</div>");
				}
			});
		}
	});

	
}

function createEntity(mapObj)
{	
	var createSite = false;
	var markers = mapObj.markers;
    var first = mapObj.first;
	var data = new FormData();
	data.append("function", "createEntity");
	var elem = $("#create input");
	validate("createEntity", elem, function(errs){
		//if any errors sent back change the input
		//else
		//send the data to the server
		if(Object.keys(errs).length > 0)
		{
			for(x in errs)
			{
				$(x+".company").parent().addClass("has-error");
				var msg = errs[x];
				//do something with msg;
				var str = "";
				//when there is more then one error for that input show them under each other.
				for(m in msg)
				{
					str += msg[m]+" <br/>";
				}
				$(x+":visible").after("<div class='alert alert-danger input-alerts'>"+str+"</div>");
			}
		}
		else
		{
			elem.each(function(){
				var id = $(this).attr("id");
				if(!id.match(/^lat$|^lng$|^radius$/))
				{
					//add user entered data
					data.append($(this).attr("id"), $(this).val());
				}
			});
			
			if($("#newEntityShowMap").prop("checked"))
			{
				createSite = true;
				//add overlay info for radius / latlng
				//to add to site
				data.append("radius", mapObj.overlays[0].getRadius());
				data.append("lat", mapObj.siteMarkers[0].getPosition().lat());
				data.append("lng", mapObj.siteMarkers[0].getPosition().lng());
				
				data.append("siteName", $("#newEntityName").val()+" Head Office");
				
				//address break up
				var address_types = {
					street_number: "short_name",
					route: "long_name", //street name
					locality: "long_name", //suburb
					administrative_area_level_1: "short_name", //state
					country: "long_name", 
					postal_code: "short_name"
				};
				
				var components = findAddressComponents(address_types, mapObj.markers[0])
				for(c in components)
				{
					console.log(c);
					data.append(c, components[c]);
				}
			}
			
			/**GET TIME AND TIMEZONE*/
			var time = new Date().getTime()/1000;
			var timezone = jstz.determine().name();
			
			
			data.append("timestamp", time);
			data.append("timezone", timezone);
			data.append("createSite", createSite);
			
			$.ajax({				
				url: path+"include/function.php",
				data: data,
				type: "POST",
				processData: false, 
				contentType: false
			}).done(function(d){
				//entity created
				console.log(d);
				var s = JSON.parse(d);

				if(s.success)
				{	
					var entId = s.entityId;
					//close modal
					$("#create").modal("hide");
                    if(first == true){
                        window.location='/entity-settings-step-two/'+entId;
                        //toEntitySettings(entId);
                    }
					//reload list
					createAssociatedTable(userId, entityId);		
					//update the dropdown in the header
					$("#tenantDropdown li.divider").before(
						"<li><a onclick='changeEntity("+entId+")' href='#'><span class='glyphicon glyphicon-chevron-right'></span>&nbsp;"+$("#newEntityName").val()+"</a></li>"
					);
					if(s.site.success)
					{
						//site is created	
						
					}
					else
					{
						//show error
					}
					
					//show enities code in a modal
					var createdStr = "Entity "+$("#newEntityName").val()+" has been created.<br/>"+
									"The association code is <strong>"+s.code+"</strong>";
					$("#showCode #modal-code").html(createdStr);
					$("#showCode").modal("show");
				}
				else
				{
					//show error
				}
			});
		}
	
	});
}

/**
 * Associate an entity
 */
function assocEntity()
{
	var newAssoc = true;
	var data = new FormData();
	data.append("function", "assocEntity");
	var elem = $("#assoc input");
	validate("assoc", elem, function(errs){
		console.log(errs);
		if(Object.keys(errs).length > 0)
		{
			//there are errors
			for(x in errs)
			{
				$(x+".company").parent().addClass("has-error");
				var msg = errs[x];
				//do something with msg;
				var str = "";
				//when there is more then one error for that input show them under each other.
				for(m in msg)
				{
					str += msg[m]+" <br/>";
				}
				$(x+":visible").after("<div class='alert alert-danger input-alerts'>"+str+"</div>");
			}
		}
		else
		{
			var entityCode = $("#assocEntityCode").val();
			data.append("userId", userId);
			data.append("entityCode", entityCode);
			data.append("accessLevel", 3);
			data.append("accessEnd", null);
			data.append("timestamp", new Date().getTime()*1000);
			
			//no errors; continue
			$.ajax({
				url: path+"include/function.php",
				data: data,
				type: "POST",
				contentType: false,
				processData: false				
			}).done(function(d){				
				d = JSON.parse(d);
				console.log(d);
				if(d.success)
				{
					//entity added
					createAssociatedTable(userId, d.entity.entityId);
					//close modal
					$("#assoc").modal("hide");
					
					//repopulate nav?
					
				}
				else
				{
					//error
					//$("#assocEntityCode").parent().addClass("has-error");
					$("#assocEntityCode").after("<div class='alert alert-danger input-alerts'>"+d.msg+"</div>")
				}
			});
		}
	});
}

/**
* Unnassociate the entity with the currentuser
* on community control
*/
function unassocEntity(entityId, entityName, userId)
{
	var c = confirm("Are you sure you want to unassociate yourself from "+entityName+"?");
	
	var data = new FormData();
	data.append("function", "unassocEntity");
	data.append("userId", userId);
	data.append("entityId", entityId);
	data.append("timestamp", new Date().getTime()/1000);	
	
	if(c)
	{
	
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			processData: false,
			contentType: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				//if
				//redraw table
				createAssociatedTable(userId, entityId);
			}
			else
			{
			
			}
		});
	}
}

function unassociateUser(userId, userName, currUser)
{
	var c = confirm("Are you sure you want to remove "+userName+"'s access?");
	
	var data = new FormData();
	data.append("function", "unassocEntity");
	data.append("userId", userId);
	//data.append("entityId", entityId);
	data.append("timestamp", new Date().getTime()/1000);
	if(c)
	{
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST",
			processData: false, 
			contentType: false
		}).done(function(d){
			d = JSON.parse(d);
			console.log(d);
			if(d.success)
			{
				//redraw table
				createUserTable(null, d.currUser);
				
			}
			else
			{
				//error
			}
		});
	}}

function clickAccessModal(sessionAccess, userAccess, entityId)
{
	$("[name='accessEntityId']").val(entityId);
	$("[name='accessEntityLevel']").val(userAccess);
	
	var accessLevels = entityAccessList();
	var accessSelect = "";
	
	console.log(" session accessLevel "+sessionAccess);
	console.log("user accessLevel "+userAccess)
		
	for(var i = 0; i < accessLevels.length; i++)
	{
		//accessLevel key = i
		//access val = i+1
		var num = i+1;
		if(num >= sessionAccess)
		{
			var checked = "";
			//if this users value is at this level keep it checked
			if(num == userAccess)
			{
				checked = "checked";
			}
			accessSelect += "<div class='radio'>"
						+	"<label>"
						+	  "<input type='radio' name='accessLevel' value='"+num+"' "+checked+">"+accessLevels[i]
						+	"</label>"
						 +"</div>";
		}
	}
	
	$("#accessModalForm .checkboxes").html(accessSelect);
	
	$("#entityAccessModal").modal();

}

function clickEntityCodeModal(id)
{
	console.log(id);
	var data = new FormData();
	data.append("entityId", id);
	data.append("function", "findEntityCode");
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			$("#showCode .modal-header").html("<h3>Entity Code</h3>");
			$("#showCode #modal-code").html("<h4>The entity code for "+d.name+" is:<br/><strong>"+d.entityCode+"</strong></h4>");
			$("#showCode").modal();
		}
		else
		{
			//error
		}
	});
}

function clickSaveAccess()
{
	var entity = $("[name='accessEntityId']").val();
	//current level
	var accessEntityLevel = $("[name='accessEntityLevel']").val();
	//new level
	var newLevel = $("[name='accessLevel']:checked").val();
	var page = $("[name='page']").val();
	var user = $("[name='userId']").val();
	
	var data = new FormData();
	data.append("function", "updateAccessLevel");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("entityId", entity);
	data.append("accessLevel", newLevel);
	data.append("userId", user);
	//data.append("page", page);
	
	if(newLevel > accessEntityLevel && page == "profile")
	{
		var c = confirm("Are you sure you want to lower your entity access level?");
	}
	else if(page == "profile")
	{
		var c = confirm("Are you sure you want to change your entity access level?");
	}
	else
	{
		var name = $("#firstName").val();
		var c = confirm("Are you sure you want to change this entity access level for "+name+"?");
	}
	
	if(c)
	{
		//need to work out how to 
		//get the current users session access
			//if they are changing from profile get all types
		//get edited users access
	
		//save the access
		$.ajax({
			url : path+"include/function.php",
			data: data,
			type: "POST",
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			//get the logged in users access
			//var sessAccess = $("[data-entity='"+d.entity+"'] .sessionAccess").val();
			
			if(d.success)
			{
				$(".alert-row").html("<div class='alert alert-block alert-success text-center'>Entity Access Updated</div>");
				//change the button
				var accessList = entityAccessList();
				//access key is access -1
				//THIS ONLY WORKS WHEN CHANGING SOMEONE ELSE'S ACCESS. ON PROFILE NEED SOME OTHER WAY TO GET SESSION
				var accessBtn = "<button type='button' class='btn btn-block btn-default' data-toggle='modal' onclick='clickAccessModal("+d.sessionAccess+","+d.access+", "+entity+")'>"+accessList[d.access-1]+"</button>";
				
				$("[data-entity='"+d.entity+"'] .accessBtn").html(accessBtn);
				//var sessAccess = $("[data-entity='"+d.entity+"'] .sessionAccess").val(d.access);
			}
			else
			{
				//show error
				$(".alert-row").html("<div class='alert alert-block alert-danger text-center'>"+d.msg+"</div>");
			}
			
			alertTimeout($(".alert-row .alert"), 3000);
			
			$("#entityAccessModal").modal("hide");
		});
	}
}

function validateRegisterUser(elem, errs)
{
	elem.each(function(){
			var val = $(this).val();
			var id = $(this).attr("id");
			console.log(id+": "+val);
			if(val == "" || val == null)
			{				
				errs["#"+id+".register"] = ["Required"];
			}
			else
			{
				if(id == "firstName" || id == "lastName")
				{
					var nameErrs = [];
					//no numbers
					if(val.match(/[0-9]/g))
					{
						nameErrs.push("Name cannot contain numbers");
					}
					
					if(nameErrs.length > 0)
					{
						errs["#"+id+".register"] = nameErrs;
					}
				}
				if(id == "email")
				{
					//check for @blah.com
					if(!(val.match(/^\w+(\.?\w+)*@\w+\.\w+(\.\w+)*$/)))
					{
						errs["#email.register"] = ["Email is not valid"];
					}
				}
				
				if(id == "phone")
				{
					var phoneErrs = [];
					if(!val.match(/[\+0-9\- ]+/))
					{
						phoneErrs.push("Phone number is invalid");
					}
					
					//if any errors, set them to the object
					if(phoneErrs.length > 0)
					{						
						errs["#phone.register"] = phoneErrs;
					}
				}
				
				if(id == "password")
				{
					var passwordErrs = [];
														
					
					//check the length
					if(val.length < 6 || val.length > 12)
					{
						passwordErrs.push("Password must be between 6 and 12 characters");
					}
					
					if(!val.match(/^[a-zA-Z0-9\.]*$/))
					{
						passwordErrs.push("Password cannot contain special characters");
					}
					
					//check it has at least one number
					if(!val.match("[0-9]"))
					{
						passwordErrs.push("Password much contain at least one number");
					}
					
					//check for match of email address or email provider
					var emailArr = $("#email.register").val().match(/([\w\.\-]+)@(\w+)(?:\.\w+)*/);
					if(emailArr)
					{
						var email = emailArr[1].replace(/([\.-])/, "$1?");
						var afterEmail = emailArr[2];
						regex = new RegExp("("+email+"|"+afterEmail+")");
						if(val.match(regex))
						{
							passwordErrs.push("Password cannot match email address");
						}
					}
					
					//check passwords match
					var confirm = $("#passwordConfirm.register").val();
					if(val != confirm)
					{
						passwordErrs.push("Passwords do not match");
					}
					
					if(passwordErrs.length > 0)
					{
						errs["#"+id+".register"] = passwordErrs;
					}
				}
			}
		});
		
	return errs;
}

/**
* Validation for entity creation
*/
function validateCreateEntity(elem, errs)
{
	elem.each(function(){
		var val = $(this).val();
		var id = $(this).attr("id");
		console.log(id+": "+val);
		if((id == "newEntityName" || id == "newEntityAddress") && val == "")
		{
			errs["#"+id] = ["required"];
		}
		else
		{
			
		}
	});
	
	return errs;
}
/**
* validation for entity association
*/
function validateAssocEntity(elem, errs)
{
	elem.each(function(){
		var val = $(this).val();
		var id = $(this).attr("id");
		if((id == "assocEntityName" || id == "assocEntityCode") && val == "" )
		{
			errs["#"+id] = ["required"];
		}
		else
		{
			if(id == "assocEntityId" && val == "null")
			{
				//the code hasn't been found
				errs["#assocEntityName"] = ["Code and entity name do not match"];
			}
		}
	});
	
	return errs;
}

/*
 * Validation for question edit/create
 */
function validateQuestion(elem, errs)
{
	
	var topLevel = false;
	var siteSpecific = false;
	var hasInterval = false;
	//validate the stuff
	elem.each(function(){
	
		var name = $(this).attr("name");
		var val = $(this).val();
		
		if(name == "questionText")
		{
			if(val == "")
			{
				errs[name] = ["No question entered"];
			}
		}		
		if(name == "topLevel")
		{	
			if(this.checked == true)
			{
				topLevel = true;
			}
		}	
		if(name == "hasInterval")
		{
			hasInterval = this.checked;
			//check topLevel
			if(topLevel == false)
			{
				if(hasInterval)
				{
					errs[name] = ["Follow up question can not have an interval"];
				}
			}
		}		
		if(name == "intervalTime")
		{
			if(topLevel == true && hasInterval == true)
			{
				if(val == "")
				{
					errs[name] = ["Interval time is empty"];
				}
			}
		}
		if(name == "siteSpecific")
		{
			
			if(this.checked == true)
			{
				siteSpecific = true;
			}
		}	
		if(name == "siteSpecId")
		{
			if(siteSpecific == true)
			{
				if(val == "null")
				{
					errs[name] = ["No site selected"];
				}
			}
		}
	});	
	
	return errs;
}

/**
 * Validates a site's information and reutrns any errors
 */
function validateSite(elem, errs)
{
	var blackspot = false;
	var safezone = false;
	elem.each(function(){
		var name = $(this).attr("name");
		var val = $(this).val().trim();
		var placeholder = $(this).attr("placeholder");
		
		console.log(name+" : "+val);
		if(name == "siteName" && val == "")
		{
			errs[name] = [placeholder+" can not be blank"];
		}
		else if((name == "lat" || name == "lng" || name == "radius") && val == "")
		{
			errs["search"] = ["Search a location to drop a marker"];
		}
		else if(name == "street_name" && val == "")
		{
			errs[name] = [placeholder+" can not be blank"];
		}
		else if(name == "gid" && val != "")
		{
			if(!val.match(/^https:\/\/docs\.google\.com\/.+\/spreadsheets\/d\/.+\//i))
			{
				errs[name] = ["Please input a valid Google Drive link or leave blank"];
			}
			else
			{
				//errs[name] = ["valid"];
			}
		}
		else if(name == "blackspot" && blackspot == false)
		{
			if($(this).parent().hasClass("active"))
			{
			
				blackspot = true;
			}
		}
		else if(name == "safezone" && safezone == false)
		{
			if($(this).parent().hasClass("active"))
			{
				safezone = true;
			}
		}	
		
		/*else if(name == "pnum")
		{
			if(!val.match(/^(sp)?[0-9]{4,7}$/i))
			{
				if(val.match(/^p[0-9]*$/i))
				{
					errs["pnum"] = ["Project Number no longer contains the letter 'P'. Please remove this to continue"];
				}
				else
				{
					errs["pnum"] = ["Project Number is invalid<br/>"];
				}
			}
		}*/
		
	});
	
	if(blackspot == false)
	{
		errs["blackspot"] = ["No blackspot value"];
	}
	if(safezone == false)
	{
		errs["safezone"] = ["No safezone value"];
	}
	console.log(errs);
	return errs;
}

/**
 * validates the emergencyContact modal and reutrns the errors
 */
function validateCustomEmergencyContactModal(elem, errs)
{
	elem.each(function(){
		var id = $(this).attr("id");
		var val = $(this).val().trim();
		if(val == "")
		{
			errs[id] = ["This input is required"];
		}
		else
		{
			if(id == "emergencyAddress")
			{
				//rules for address
			}
			else if(id == "emergencyPhone")
			{
				//rules for phone number
				if(!val.match(/^[0-9\+\- \[\]\{\}]+$/))
				{
					errs[id] = ["Phone number contains invalid characters"];
				}
			}
		}
		
	});
	return errs;
}



/**
 * Validates user information for profile and user pages
 */
function validateUser(elem, errs)
{
	elem.each(function(){
		var val = $(this).val().trim();
		var name = $(this).attr("name");
		var placeholder = $(this).attr("placeholder");
		
		console.log(name+" : "+val);
		if(name == "firstName" || name == "lastName")
		{
			if(val == "")
			{
				errs[name] = [placeholder+" is required."];
			}
			else if(val.match(/[0-9]+/g))
			{
				errs[name] = [placeholder+" can not contain numbers."];
			}
		}
		else if(name == "email")
		{
			//check for @blah.com
			if(!val.match(/^\w+(\.?\w+)*@\w+\.\w+(\.\w+)*$/))
			{
				errs[name] = [placeholder+" is not valid"];
			}
		}
		else if(name == "mobile")
		{
			if(!val.match(/[\+0-9\- ]+/))
			{
				errs[name] = [placeholder+" is not valid"];
			}
		}	
		// else if(name == "profilePhoto")
		// {
			// var photos = $("#profilePhotoInput input:file")[0].files;
			// if(photos.length > 0)
			// {
				// var img = photos[0];
				// if(!img.type.match(/image\/.*/))
				// {
					// console.log("no match");
					// errs["profilePhotoInput"] = ["Profile image must be an image type"];
				// }
			// }			
		// }
	});	
		
	return errs;
}
/*
 * Validate permissions
 */
function validatePermissions(elem, errs)
{
	console.log("validate permissions")
	elem.each(function(){
		var name = $(this).attr("name");
		var val = $(this).val().trim();
		if(name == "groupName")
		{
			if(val == "")
			{
				errs[name] = ["Group Name can not be blank"];
			}
			//else if(val.match())
			//{
			
			//}
		}	
	});
	return errs;
}

/**
 * validate entity settings
 */
function validateEntitySettings(elem, errs)
{
	elem.each(function(){
		var name = $(this).attr("name");
		var val = $(this).val().trim();
		
		//head office
		//emergecny number
		
		//account manager check box 
		//if off set val to null
		//entity manager check box
		//if off set val to null
		
		//check each other notifications are int and in order
		
		
	});
	return errs;
}


/**
* Validate data from the elements and perform a function with the results
*
* String type : type of validation needed to perform
* Jquery Obj elem : Array of objects returned by Jquery select
* function callback : function to perform after validation
*/
function validate(type, elem, callback)
{
	$(".input-alerts").remove();
	var errs = {};
	switch(type)
	{
		case "login":
			elem.each(function(){
			var val = $(this).val();
			var id = $(this).attr("id");
			if(val == "" || val == null)
			{
				errs["#"+id+".login"] = ["Required"];
			}
			});
			break;
		case "registerUser":
			errs = validateRegisterUser(elem, errs);
			break;
		case "creatEntity":
			errs = validateCreateEntity(elem, errs);
			break;
		case "assoc":
			errs = validateAssocEntity(elem, errs);
			break;
		case "question":
			errs = validateQuestion(elem, errs);
			break;
		case "site":
			errs = validateSite(elem, errs);
			break;
		case "user":
			errs = validateUser(elem, errs);
			break;
		case "permissions":
			errs = validatePermissions(elem, errs);
			break;
		case "contact":
			errs = validateContact(elem, errs);
			break;
		case "entitySettings":
			errs = validateEntitySettings(elem, errs);
			break;
		case "customEmergencyContact":
			break;
			errs = validateCustomEmergencyContactModal(elem, errs);
		case "leave":
			elem.each(function(){
				if($(this).attr("id") == "desc")
				{
					if($(this).val() == "")
					{
						errs["#desc"] = ["Required"];
					}
				}
			});
			break;
		default:
			console.log("No function to validate "+type);
	}
	callback(errs);
}

			
function populateCountry(div)
{
	var txt = "<option value='null'>Choose Country</option>";
	var data = new FormData();
	data.append("function","findCountries");

	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){	
		var arr = JSON.parse(d);
		var msg = arr.msg;
		var c = arr.countries;
		
		for(var i = 0; i < c.length; i++)
		{
			txt += "<option value="+c[i].id+">"+c[i].country+"</option>";
		}
		
		//fill all country dropdown
		$(div).html(txt);
	});
	
}

function populateTimezone(div, country)
{
	var txt = "<option value='null'>Choose Timezone</option>";
	var data = new FormData();
	data.append("function", "findTimezone");
	data.append("country", country);
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false,
		processData: false
	}).done(function(d){
		var arr = JSON.parse(d);
		var msg = arr.msg;
		var s = arr.timezones;
		
		for(var i = 0; i < s.length; i++)
		{
			txt += "<option value="+s[i].id+">"+s[i].timezone+"</option>";
		}
		
		$(div+":visible").html(txt);
		$(div+":visible").prop("disabled", false);
	});
}


function getProjectNumberDropdown(elem)
{
	var data = new FormData();
	data.append("function", "getProjectNumber");
	data.append("timestamp", new Date().getTime()/1000);
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		var txt = "<option value=''>No Project Number</option>";
		if(d.success)
		{			
			if(d.projectNumbers.length > 0)
			{
				for(var i = 0; i < d.projectNumbers.length; i++)
				{
					txt += "<option value='"+d.projectNumbers[i].projectStatusId+"'>"+d.projectNumbers[i].pnum+"</option>";
				}
			}	
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-block alert-danger text-center'>"+d.msg+"</div>");
		}
		
		elem.html(txt);
	});
}

/**
* set the siteView page
* find users that are active at this site and show them on the map
* mapObj { map: google.maps.Map, 
*			markers: [],
*			overlays: [google.maps.Circle], 
*			googleMarkers: [google.maps.Markers]
* }
* siteId: id of the site being requested
*/
function getSiteInfoView(siteId)
{	
	//this function checks for their access
	var data = new FormData();
	data.append("function", "findSites");
	data.append("siteId", siteId);
	data.append("timestamp", new Date().getTime()/1000);
	//do I need anything else?
	//put it here and then request through php
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST",
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{
			if(d.sites.length > 0)
			{						
				$("#siteInfo").removeClass("hide");
				var site = d.sites[0];
				$("#name").val(site.name);
				if(site.isSpecial)
				{
					$(".special").removeClass("hidden");
					$("#pnum").val(site.pnum);
					
					//check for valid
					var plValid = "ok";
					var plColour = "success";
					var plnote = "This Points List is working correctly";
					if(site.pointsListValid == true)
					{
						plValid = "ok";
						plColour = "success";
						plnote = "This Points List is working correctly";
					}
					else
					{
						plValid = "remove";
						plColour = "danger";
						plnote = "This Points List has had issues updating.<br/>Please review<br/>Tip: Check share permissions for alerton.com.au";
					}
					
					
					var tooltip = false;
					var linkTxt = "";
					if(site.pointsListId != null && site.pointsListId != "")
					{
						//break up the id
						linkTxt = "<br/>"
									+"<div class='col-md-11 col-xs-10'><button type='button' class='btn btn-link pointsListBtn btn-block' onclick='window.open(\"https://docs.google.com/a/alerton.com.au/spreadsheets/d/"+site.pointsListId+"\")'>"+site.pointsListId+"</button></div>"
									+"<div class='col-md-1 col-xs-2'><button type='button' class='btn btn-"+plColour+" btn-block' ><span class='glyphicon glyphicon-"+plValid+"'></span></button></div>";
						
						tooltip = true;
					}
					else
					{
						linkTxt = "<br/><div class='col-xs-12 text-center'><h4>No Points List Saved</h4></div>";
					}
					
					$(".pointsList div").html(linkTxt);
					//if there is need for a tooltip add it
					if(tooltip == true)
					{
						$(".pointsList div:first").tooltip({
							title: plnote,
							html: true
						});
					}
					var budget = (parseInt(site.budget) < 0) ? 0 : site.budget;
					var completion = (parseInt(site.completion) < 0) ? 0 : site.completion;
					if(completion != null)
					{						
						
						new JustGage({
							id: "fuel-guage-completion", 
							value: completion,
							min: 0,
							max: 100, 
							title: "Work Remaining",
							label: "%",
							levelColors: ["#428bca"],
							startAnimationTime: 4000,
							startAnimationType: "bounce"
						});	 		
					}
					
					if(budget != null)
					{
						
						new JustGage({
							id: "fuel-guage-budget", 
							value: budget,
							min: 0,
							max: 100, 
							title: "Fuel Remaining",
							label: "%",
							levelColors: ["#d9534f","#f0ad4e","#5cb85c"],
							startAnimationTime: 4000,
							startAnimationType: "bounce"
						});
					}
					
				}
				
				//put all address components together
				$("#address").val(site.stNumber+" "+site.stName+", "+site.suburb);
				$("#state").val(site.state);
				$("#country").val(site.country);
				$("#postCode").val(site.postcode);
				//set safezone and blackspot
				$("[name='safezone'][value='"+site.safeZone+"']").parent().addClass("active");
				$("[name='blackspot'][value='"+site.blackspot+"']").parent().addClass("active");
				
				//disable other button
				$("#blackspot label, #safezone label").each(function(){	
					if(!$(this).hasClass("active"))
					{
						$(this).attr("disabled", "disabled");
					}
				});
				
				//set emergency contacts
				setSiteEmergencyTable(site.siteId, $("#emergencyContacts"), false);
				
				//add this site to the markers
				//mapObj.markers = [site];
				
				//show map if not set
				if(mapObj.map == null)
				{
					mapObj.map = showViewSiteMap("viewSiteMap"); //site object sets the position and zoom
				}
				//mapObj.map.setCenter(google.maps.LatLng(site.lat, site.lng));
				mapObj = setSiteMarkerOverlay(mapObj, site);
				//set the boundaries
				console.log(mapObj);
				var bounds = mapObj.overlays[0].getBounds();
				mapObj.map.fitBounds(bounds);
				
				setSiteCodeTable(site, $("#currentCodes"), $("#inactiveCodes"));
				
				//find site postition
				//set marker and overlay
				
				var data = new FormData();
				data.append("function", "findCheckins");
				data.append("siteId", siteId);
				var timestamp = new Date().getTime()/1000;
				data.append("timestamp", timestamp);
				//find users at this site
				$.ajax({
					url: path+"include/function.php",
					data: data,
					type: "POST",
					processData: false,
					contentType: false
				}).done(function(d){
					d = JSON.parse(d);
					console.log(d);
					//mapObj = setUserMarkers(mapObj, d.checkinData);
					var userMarkers = [];
					
					var str = "";
					if(d.checkinData.length > 0)
					{
						var checkinCount = 0;
						var countStr = "";
						for(var i = 0; i < d.checkinData.length; i++)
						{
							var checkin = d.checkinData[i];
							//set this up with little men like the dashboard
							checkinCount++;
							if(checkin.checkinTypeId < 3 || checkin.checkinTypeId == 10)
							{
								var status = checkin.status;
								if(checkin.status.indexOf([1,2,7,8]) != -1)
								{
									//user is ok
									str += "<p class='text-success'>"+checkin.firstName+" "+checkin.lastName+"</p>";
								}
								else if(checkin.status.indexOf([3,4]) != -1)
								{
									//user could in danger
									str += "<p class='text-warning'>"+checkin.firstName+" "+checkin.lastName+"</p>";
								}
								else
								{
									//user is in danger
									str += "<p class='text-warning'>"+checkin.firstName+" "+checkin.lastName+"</p>";
								}						
							}
							else
							{
								//find those checked out less than an hour ago	
								str += "<p class='text-muted'>"+checkin.firstName+" "+checkin.lastName+"</p>";
							}	
							
							//find random lat/lng within site radius and send this to the usermarkers						
							//bounds is a rectangle so marker could show outside the circle
							//console.log(bounds);
							
							//USE CIRCLE OVERLAY BOUNDS TO CALC RANDOM POSITION WITHIN RADIUS
							// var ne = bounds.getNorthEast();
							// var sw = bounds.getSouthWest();
							// console.log(ne);
							// console.log(sw);
							// var latMax = ne.lat();
							// var latMin = sw.lat();
							// var lat = (Math.random() * (latMax - latMin) + latMin).toFixed(10);
							
							// var lngMin = ne.lng();
							// var lngMax = sw.lng();
							// var lng = (Math.random() * (lngMax - lngMin) + lngMin).toFixed(10);
							
							//PUTS PIN IN RANDOM PLACE WITHOUT 0.0001 lat/lng FROM SITE PIN
							var latMax = parseFloat(checkin.siteLat) +	0.0001;
							var latMin = parseFloat(checkin.siteLat) -	0.0001;
							var lngMax = parseFloat(checkin.siteLng) +	0.0001;
							var lngMin = parseFloat(checkin.siteLng) -	0.0001;
						
							var lat = (Math.random() * (latMax - latMin) + latMin).toFixed(6);
							var lng = (Math.random() * (lngMax - lngMin) + lngMin).toFixed(6);
												
							userMarkers.push({
								lat: lat,
								lng: lng, 
								isPulse: false,
								userEntityId: checkin.userEntityId,
								siteEntityId: checkin.siteEntityId,
								status: checkin.status,
								userId: checkin.userId, 
								firstName: checkin.firstName,
								lastName: checkin.lastName,
								userEntityName: checkin.userEntityName,		
								checkinTypeId: checkin.checkinTypeId
							});						
						}

						countStr += "<h4>"+checkinCount;
						countStr += (checkinCount == 1) ? " user " : " users ";
						countStr += "currently at this site</h4>"
						str = countStr + str;
						
					}
					else
					{
						str = "<h4>No Current Check in Data for This Site</h4>";
					}
					$("#checkinDataRow").html(str);
					
					//make userMarkers
					console.log(userMarkers);
					mapObj = setUserMarkers(mapObj, userMarkers);
				});
			}
			else
			{
				$("#alert-row").html("<div class='alert alert-"+d.class+" text-center'>"+d.msg+"</div>");
				$("#siteInfo").addClass("hide");
			}
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-"+d.class+" text-center'>"+d.msg+"</div>");
			$("#siteInfo").addClass("hide");
		}
		
		$("[data-toggle]").tooltip();
	});
}
/**
 * Get the inital view of the edit site page
 * create the autocomplete text box 
 * mapObj : {
 * 		map
 *		markers:
 * 		overlays:
 *		siteMarkers:
 * 		userMarkers:
 *		infowindow :
 * }
 *int siteId : sites Id
 * action : create/edit
 */
function getEditSite(siteId, action)
{		
	//set the autocomplete
	editSiteAutocomplete(mapObj, "search");

	var data = new FormData();
	data.append("function", "findSites");
	data.append("siteId", siteId);
	data.append("action", action);
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{					
			var action = d.action;
			
			if(action == "create")
			{
				//do nothing?
				//change some inputs?
			}
			else
			{				
				if(d.sites.length > 0)
				{	
					var site = d.sites[0];
					//site exists	
					//show the site codes
					$("#siteCodes").removeClass("hidden");						
					$(".siteCodes").removeClass("hidden");
					
					
					$("#siteCodeCheck").change(function(){
						
						if($(this).prop("checked"))
						{	
							$("#siteCodeTime").val("").prop("disabled", true);
						}
						else
						{
							$("#siteCodeTime").prop("disabled", false);
						}
					});
					var today = timestampToDate(new Date().getTime()/1000, "d/m/Y");
					
					$("#siteCodeTime").datepicker({
						format: "dd/mm/yyyy",
						startDate: today
					});	
					
					//populate fields
					$("#siteName").val(site.name);
					if(site.isSpecial)
					{
						//$(".special").removeClass("hidden");
						$("#pnum").val(site.projectStatusId);
						(site.pointsListId != null && site.pointsListId != "") ? $("#gid").val("https://docs.google.com/a/alerton.com.au/spreadsheets/d/"+site.pointsListId+"/") : $("#gid").val(""); 
						//(site.pointsListValid) ? $("#plValid").addClass("btn-succes").
						
						//set the popover for points list info
						// $(".pointsList").popover({
							// trigger: "hover", 
							 // placement: "top", 
							// html: true,
							// content: "Copy URL while viewing file in Google Drive<br/><br/>Eg: https://docs.google.com/a/spreadsheets/d/1234/"
					// //		selector: ".pointsList"
						// });
						
					
					}
					$("#streetNumber").val(site.stNumber);
					$("#streetName").val(site.stName);
					$("#suburb").val(site.suburb);
					$("#state").val(site.state);
					$("#country").val(site.country);
					$("#postCode").val(site.postcode);
					
					//Reset the active
					$("#safezone label").removeClass("active");
					$("#blackspot label").removeClass("active");
											
					$("#safezone [value='"+site.safeZone+"']").parent().addClass("active");
					$("#blackspot [value='"+site.blackspot+"']").parent().addClass("active");
					
					$("#radius").val(site.radius);
					$("#lat").val(site.lat);
					$("#lng").val(site.lng);
					
					//set the edit map
					mapObj = setSiteMarkerOverlay(mapObj, site);
					console.log(mapObj);
					//set the marker and overlay to editable
					mapObj.siteMarkers[0].setDraggable(true);
					mapObj.overlays[0].setEditable(true);
					//set the bounds to the overlay
					mapObj.map.fitBounds(mapObj.overlays[0].getBounds());
					//mapObj.overlays.setDraggable(true);
					//set the dragtrigger
					markerTriggerCreate(mapObj.siteMarkers[0], mapObj.overlays[0], $("#lat"), $("#lng"), $("#radius"));
				
					setSiteCodeTable(site, $("#currentCodes"), $("#inactiveCodes"));
					
					$(".emergencyNumbers").removeClass("hidden");
					setSiteEmergencyTable(siteId, $("#currentEmergency"), true);					
				}
			}
			
			//on both create and edit
			if(d.isSpecial)
			{
				$(".special").removeClass("hidden");
				
				//set the popover for points list info
				$(".pointsList").popover({
					trigger: "hover", 
					placement: "top", 
					html: true,
					content: "Copy URL while viewing file in Google Drive<br/><br/>Eg: https://docs.google.com/a/spreadsheets/d/1234/"
			//		selector: ".pointsList"
				});
			}
			
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-"+d.class+" text-center'>"+d.msg+"</div>");
			$("#siteInfo").addClass("hide");
		}
		
	});
	
	//set the map 
	if(mapObj.map == null)
	{
		mapObj.map = showEditSiteMap("editSiteMap");
	}
}



function clickAddSiteCode(siteId)
{
	//check the site id?
	
	//add the form
	
}

function addSiteCode(siteId)
{
	var siteCodeTimestamp = null;
	//if never is checked or site code time is "" set to null
	var siteCodeTime = ($("#siteCodeTime").val() == "" || $("#siteCodeCheck").prop("checked") == true) ? null : $("#siteCodeTime").val();
	//find timestamp
	console.log(siteCodeTime);
	if(siteCodeTime != null)
	{
		var dateParts = siteCodeTime.match(/(\d+)\/(\d+)\/(\d+)/);
		console.log(dateParts);
		if(dateParts != null) //real date
		{
			var siteCodeDate = new Date(dateParts[3], parseInt(dateParts[2],10)-1, dateParts[1]);
			siteCodeTimestamp = siteCodeDate.getTime()/1000;
			console.log(siteCodeTimestamp);
		}
		else
		{
			//not a real date
		}
	}
	else
	{
		//error
	}
	
	var data = new FormData();
	data.append("function", "addSiteCode");
	data.append("siteCodeTime", siteCodeTimestamp);
	data.append("siteId", siteId);
	data.append("timestamp", new Date().getTime()/1000);
	data.append("timezone", jstz.determine().name());
	
	$.ajax({
	 url: path+"include/function.php",
	 data: data,
	 type: "POST",
	 contentType: false, 
	 processData: false
	}).done(function(d){
		 d = JSON.parse(d);
		
		 if(d.success)
		 {		
			var accessEnd = (d.code.accessEnd == null || d.code.accessEnd == "null") ? "infinite" : timestampToDate(d.code.accessEnd, "d/m/Y");
			
			
			//if success add it to the current codes
			$("#currentCodes tr:first").after("<tr>"
											+"<td>"+d.code.code+"</td>"
											+"<td>"+accessEnd+"</td>"
											+"<td>No Current Users</td>"
											+"</tr>");
			
			//if alert remove it 
			if($("#currentCodes tr:first .alert").length > 0)
			{
				$("#currentCodes tr:first").remove();
			}
		
			$("#addSiteCodeModal").modal("hide");
			//reset the date 
			$("#addSiteCodeModal input:checkbox").prop("checked", true);
			$("#addSiteCodeModal input:text").val("").prop("disabled", true);
		}
		 else
		 {
			console.log(d.msg);
		
		 }	
	});
}


/**
* Find the info for the user view
* mapObj { map: google.maps.Map, 
*			markers: [],
*			overlays: [google.maps.Circle], 
*			googleMarkers: [google.maps.Markers]
* }
* int userId : user being looked up
**/
function getUserInfoView(userId)
{
	var data = new FormData();
	data.append("function", "findUsers");
	data.append("userId", userId);
	data.append("timestamp", new Date().getTime()/1000);			
	
	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
        console.log(d);
		d = JSON.parse(d);
		if(d.success)
		{				
			if(d.users.length > 0)
			{
				//get the access list
				var accessList = entityAccessList();
				$("#userInfo").removeClass("hide");
				var user = d.users[0];
				$("#firstName").val(user.firstName);
				$("#lastName").val(user.lastName);
				$("#email").val(user.email);
				$("#mobile").val(user.mobile);
				
				$("#profilePhoto").attr("src", path+"include/userImg.php?userId="+userId);					
				createUserConnectionBtnsFor(userId, null, encodeURI(user.firstName), function(res){						
					//if they are admin OR they have a connection and they follow me
					if(res.requestByAccess < 2 || (res.hasConnection == true && res.followsMe == true))
					{
						$(".restricted").removeClass("hidden");
						//show activity
						//show emergency contacs
						//show user access details
						$("#generalGroup").val(accessList[res.requestToAccess-1]);
						$("#permissionGroup").val(user.groupName);
						showUserViewActivity(mapObj, userId);
						setUserEmergencyTable(userId, $("#emergencyContacts"), false);
					}
					
					//set the buttons as active / not active
					 if(res.followsMe == true)
					 {
						//$("#leader_btns .followsMe").parent().addClass("active");
						//add the onclick function
					 }
					 if(res.leadsMe == true)
					 {
						//$("#leader_btns .leadsMe").parent().addClass("active");
						//add the onclick function
					 }
				});		
			}
			else
			{
				$("#alert-row").html("<div class='alert alert-warning text-center'>"+d.msg+"</div>");
				$("#siteInfo").addClass("hide");
			}
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-"+d.class+" text-center'>"+d.msg+"</div>");
			$("#siteInfo").addClass("hide");
		}
	});
}	

/**
 * Sets the buttons on the user profile for if the logged in user follows or leads the user they're viewing 
 * Sets the connectionId for each to remove it and the pending state
 * Sets the view connections button 
 * Determines whether the map should be shown
 */
function createUserConnectionBtnsFor(requestTo, requestBy, name, callback)
{
	
	var data = new FormData();
	data.append("function", "getConnectionsByUsers");
	(requestBy != null) ? data.append("requestBy", requestBy) : false;
	data.append("requestTo", requestTo);
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url : path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType : false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			//request will always return these as long as both users have access to this entity
			var requestByAccess = d.requestByAccess;
			var requestToAccess = d.requestToAccess;
			var requestById = d.requestById;
			var leadsMe = false;
			var leadsMePend = false;
			var followsMe = false;
			var followsMePend = false;
			var hasConnection = false;
			var onlyMember = true;
			
			//view connections btn
			var connectionsBtn = "<a type='button' class='btn btn-primary btn-block' href='/connections/"+requestTo+"/'>View Connections</a>";
			//add and remove btns
			var addBtn = "<div class='col-xs-12'><button type='button' class='btn btn-success btn-block' onclick=\"loadConnectionModal("+requestById+", "+requestTo+", '"+decodeURI(name)+"')\">Add Connection</button></div>";
			//user removes connection from my-connections
			var removeConnectionBtn = "<div class='col-xs-12'><button type='button' class='btn btn-info btn-block' onClick=\"alert('To remove or cancel a member connections use the My Connections page')\">Remove All Connections</button></div>";
			
			//draw the buttons
			var btn = "";
			
			//lead and follow buttons
			var leadsMeBtn = "<div class='col-xs-12'><button class='btn btn-default btn-block leadsMe' type='button' onClick=\"requestConnectionFromUserView("+requestTo+", "+requestById+", "+requestById+", '"+encodeURI(name)+"', $(this))\"><i class='glyphicon glyphicon-plus'></i>&nbsp;Leads Me</button></div>";
			var followsMeBtn = "<div class='col-xs-12'><button class='btn btn-default btn-block followsMe' type='button' onClick=\"requestConnectionFromUserView("+requestById+", "+requestTo+", "+requestById+", '"+encodeURI(name)+"', $(this))\"><i class='glyphicon glyphicon-plus'></i>&nbsp;Follows Me</button></div>";
			var leadsMePendBtn = "";
			var followsMePendBtn = "";
			//this connection decides what other information you can see of the user too
			//find my connection with this user (do I lead them?)
			for(var i = 0; i < d.connections.length; i++)
			{
				var connection = d.connections[i];				
				//active permissions
				if(connection.active == 1)
				{
					//if one is active then there's a connection
					hasConnection = true;
					//leader/follower connections
					if(connection.permission == 2)
					{
						onlyMember = false;
						if(requestTo == connection.leaderId)
						{
							leadsMe = true;
							leadsMeBtn = "<div class='col-xs-12'>"
											+"<button class='btn btn-default leadsMe btn-block active' type='button' onClick=\"removeConnectionFromUserView("+connection.reportToId+", '"+encodeURI(name)+"', "+connection.leaderId+", $(this))\">"
												+"<i class='glyphicon glyphicon-remove'></i>&nbsp;Leads Me"
											+"</button>"
										+"</div>";
						}
						else if(requestTo == connection.followerId)
						{
							followsMe = true;
							followsMeBtn = "<div class='col-xs-12'>"
												+"<button class='btn btn-default btn-block followsMe active' type='button' onClick=\"removeConnectionFromUserView("+connection.reportToId+", '"+encodeURI(name)+"', "+connection.followerId+", $(this))\">"
													+"<i class='glyphicon glyphicon-remove'></i>&nbsp;Follows Me"
												+"</button>"
											+"</div>";
						}
					}
					else if(connection.permission == 1) //active member connection show the option buttons
					{
						onlyMember = false;
					}
				}
				else if(connection.active == 2) //pending connections
				{
					//pending leader/followe permissions
					if(connection.permission == 2) //there are pending connections for higher so show the buttons
					{
						onlyMember = false;
						if(requestTo == connection.leaderId)
						{
							leadsMePend = true;
							leadsMePendBtn += "<div class='col-xs-12'>"
												+"<button class='btn btn-default leadsMe btn-block active' type='button' onClick=\"cancelPendingConnectionfromUserView("+connection.reportToId+", '"+encodeURI(name)+"', "+connection.leaderId+", $(this))\">"
													+"<i class='fa fa-question-circle'></i>&nbsp;Leads Me"
												+"</button>"
											+"</div>";
						}
						else if(requestTo == connection.followerId)
						{
							followsMePend = true;
							followsMePendBtn += "<div class='col-xs-12'>"
												+"<button class='btn btn-default followsMe btn-block active' type='button' onClick=\"cancelPendingConnectionfromUserView("+connection.reportToId+", '"+encodeURI(name)+"', "+connection.followerId+", $(this))\">"
													+"<i class='fa fa-question-circle'></i>&nbsp;Follows Me"
												+"</button>"
											+"</div>";
						}				
					}
					else if(connection.permission == 1)
					{
						//pending member connection
						//show the remove button
					}						
				}
			}
			
			if(d.connections.length > 0) //there are connections show the buttons
			{		
				hasConnection = true;
				if(onlyMember == false) //there is an active connection
				{					
					//create buttons to show based on entity permissions						
					if(requestToAccess < 3 && requestByAccess < 3) //both have full access
					{
						btn += (leadsMePend == true) ?  leadsMePendBtn : leadsMeBtn;
					//	btn += "<br/>";
						btn += (followsMePend == true) ? followsMePendBtn : followsMeBtn;
					}	
					else if(requestByAccess == 3 && requestToAccess < 3) //logged in user is leader but other is not
					{					
						btn += (leadsMePend == true) ?  leadsMePendBtn : leadsMeBtn;
					}
					else if(requestByAccess < 3 && requestToAccess == 3)//logged in user is not leader, other is
					{
						btn += (followsMePend == true) ? followsMePendBtn : followsMeBtn;
					}
					else if(requestToAccess == 3 && requestByAccess == 3)// both users are regular users
					{
						btn += removeConnectionBtn;
					}
				}			
				else
				{
					//only a member connection is found, show remove button
					btn += removeConnectionBtn;
					
				}
			}
			else //add connection button
			{
				hasConnection = false;
				btn += addBtn;
			}
			
			//console.log(btn);
			
			$("#leader_btns").html(btn);
			
			
			//set whether the connection buttons exists
			//if(hasConnection == true || requestByAccess == 1) -- PUT THIS WHEN CONNECTION CONTROL FOR OTHERS IS AVAILABLE
			if(hasConnection == true)
			{
				$("#connectionBtn").html(connectionsBtn);
			}
			
			//send back a result
			callback({
				requestToAccess : requestToAccess, 
				requestByAccess : requestByAccess, 
				leadsMe : leadsMe, 
				followsMe : followsMe, 
				hasConnection : hasConnection				
			});		
		}
		else
		{
			//console.log(d.msg);
			$(".alert-row").text(d.msg).removeClass("hidden");
		}
		
	});
}

function showUserViewActivity(mapObj, userId)
{
	//set the map
	if(mapObj.map == null)
	{
		mapObj.map = showUserMap("viewUserMap");
	}			
	
	//set the user's position and the site they are on
	var data = new FormData();
	data.append("function", "findLastUserActivity");
	data.append("userId", userId);
	data.append("timestamp", new Date().getTime()/1000);
	data.append("limit", 10);
	
	//find the site this user is on
	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		processData: false,
		contentType: false
	}).done(function(d){
		d = JSON.parse(d);	
		if(d.success)
		{
			if(d.userActivity.length > 0)
			{
				var pulses = [];
				var userActivityStr = "";
				var lastActivity = null;
				//this user's checkin data
				for(var i = 0; i < d.userActivity.length; i++)
				{
					var s = d.userActivity[i];
					
					//get date information
					var date = s.dateObj.month.substr(0,3)+" "+s.dateObj.mday;
					if(!s.dateObj.year.match(new Date().getFullYear()))
					{
						date = s.dateObj.mday+"/"+s.dateObj.mon+"/"+s.dateObj.yday;
					}
					var time = addZero(s.dateObj.hours)+" : "+addZero(s.dateObj.minutes);
					var timezone = s.timezone;
					var slash = timezone.lastIndexOf("/")+1;
					timezone = timezone.substr(slash).replace("_", " ");
					
					var siteName = s.name;
					if(s.isPulse == true)
					{
						siteName = "<i class='fa fa-spinner fa-spin'></i>";
						//add to pulse
						pulses.push(s);
					}
					
					
					userActivityStr += "<div class='col-xs-12' data-id='"+s.checkinId+"'>"
									+"<div class='col-xs-4 suburb'>"+siteName+"</div>"
									+"<div class='col-xs-3'>"+s.checkinType+"</div>"
									+"<div class='col-xs-2'>"+date+"</div>"
									+"<div class='col-xs-3'>"+time+"  "+timezone+"</div>"
									+"</div>";
						
					//sitename action date time timezone

					//lastActivity = s;						
				}
				
				$("#lastActivityRow").html(userActivityStr);
				//add the suburb
				for(var i = 0; i < pulses.length; i++)
				{
					var checkin = pulses[i];
					locationForDash(checkin, function(l, id){
						$("[data-id='"+id+"'] .suburb").html(l);
					});
				}
				
				lastActivity = d.userActivity[0];
				if(lastActivity != null) // the last activity entry has been set
				{
					//show this on the map
					mapObj = trackUserMap(mapObj, [lastActivity], userId, d.userStatus);					
				}
			}
			else
			{
				//no activity
				$("#lastActivityRow").html("<div class='alert alert-warning alert-block'>This user has no activity</div>");
			}
		}
		else
		{
			
		}
	});	
	
	setTimeout(function(){
		showUserViewActivity(mapObj, userId);
		//mapObj = null;
		userId = null;
	}, 15000);
	
}
/*
 *  Get the user information for the edit users page
 * int userId : id of the user to get the information for
 */
function getEditUser(userId)
{
	var data = new FormData();
	data.append("function", "findUsers");
	data.append("accessOnly", true);
	data.append("timestamp", new Date().getTime()/1000);
	data.append("userId", userId);
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		
		if(d.success)
		{
			if(d.users.length > 0)
			{
				var user = d.users[0];
				$("#entityId").val(d.entityId);
				$("#userId").val(user.userId);
				$("#firstName").val(user.firstName);
				$("#lastName").val(user.lastName);
				$("#email").val(user.email);
				$("#mobile").val(user.mobile);
				//$("#accessVal").val(user.accessLevel);
				//var sessionAccess = $("#sessionAccess").val();
				
				$(".profilePhoto").attr("src", path+"include/userImg.php?userId="+user.userId);
				var jcrop_api = null;
				setProfilePhotoUpdater(jcrop_api);
				
				var accessDrop = "";
				var accessList = entityAccessList();
				console.log(user.accessLevel);
				if(user.accessLevel)
				{
					if(user.accessLevel && d.accessLevel <= parseInt(user.accessLevel))
					{
						//console.log("my access is more");						
						//key is accessval -1
						var accessBtn = "<button type='button' class='btn btn-block btn-default' data-toggle='modal' onclick='clickAccessModal("+d.accessLevel+", "+user.accessLevel+", "+d.entityId+")'>"+accessList[user.accessLevel-1]+"</button>";
						$("#accessLevel").html(accessBtn);
					}
					else
					{
						//console.log("their access is more");
						//check this again in php send 
						//accessDrop = "<input type='text' class='form-control' id='accessLevelList' value='"+accessList[user.accessLevel]+"' disabled>"
						//			+ "<input type='hidden' name='accessLevel' value='"+user.accessLevel+"'>";	
						accessBtn = "<button type='button' class='btn btn-block btn-default' disabled>"+accessList[user.accessLevel-1]+"</button>";
						$("#accessLevel").html(accessBtn);
					}
				}
				
				setUserEmergencyTable(user.userId, $("#emergencyContacts"), true);
				
				//set the permissions dropdown their session access shouldn't affect this
				console.log(user.groupId);
				$("#permissionsGroupSelect").val(user.groupId);
							
				
				//create the entity array
				//entities = Array({
					// entityId : d.entityId, 
					// name : user.entityName, 
					// leaders : user.leaders
				// });
				
				//if there are leaders
				// if(user.leaders.length > 0)
				// {
					// //create the table in view mode
					// drawLeadersTable($("#userLeadersTable"), entities, "view", "user");
				// }	
				
				
			}					
			else
			{
			
			}
		}
		else
		{
			//error
		}
		
		//return entities;
	});
}

function clickPermissionModal(currGroup)
{
	

}



/**
 * Gets a geolocation result based on a lat/lng
 * runs a function using this result and the status
 */
function geoloc(lat, lng, callback)
{
	//console.log("geocode");
	var count = 0;
	var geocoder = new google.maps.Geocoder()
	geocoder.geocode({"latLng": new google.maps.LatLng(lat, lng)
	}, function(result, status){
		console.log(status);
		count++;
		if(status == "OK" || status == "OVER_QUERY_LIMIT")
		{
			callback(result, status);
		}
		else if(count < 4)
		{
				setTimeout(function(){
					geoloc(lat, lng, callback);
					lat = null;
					lng = null;
					callback = null;
				}, 5000);
		}
		else
		{
			callback(result, status);
		}
	});
	
	//console.log(geocoder);
}

/**
* Set the map for the entity
* Sets user markers on the map with the user info
**/
function getDashCheckinMap()
{
	var data = new FormData();
	data.append("function", "findCheckins");
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST",
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		//console.log(d);
		if(d.success)
		{
			var checkinData = d.checkinData;
			//start the map 
			if(mapObj.map == null)
			{
				console.log("show map");
				mapObj.map = showDashMap("dashMap", checkinData);	
				console.log(mapObj);
			}
			else
			{					
				//unset all the markers
				mapObj.userMarkers = unsetMarkerSet(mapObj.userMarkers);
				//unset clusters
				//mapObj.userClusters = unsetClusters(mapObj.userClusters);
				mapObj.userClusters.clearMarkers();
			}
				
			//set the markers
			mapObj = setUserMarkers(mapObj, checkinData, d.entityId);
			//Do I want users and sites?
			//just users?
			//only my users?
			//make a dropdown
		}
		else
		{
		}
		
		setTimeout(function(){
			getDashCheckinMap();
		}, 60000);
	});
	
	return mapObj;
}


/**
 *Finds a location to fill in on the dash for pulse entries.
 */
function locationForDash(d, callback)
{
	var l;
	geoloc(d.lat, d.lng, function(result, status){
		var locality = null;
		var adminArea = null;
		var postcode = null;
		
		if(status == "OK")
		{
			for(var i = 0; i < result.length; i++)
			{
				var r = result[i];
				var components = r.address_components;
				for(var x = 0; x < components.length; x++)
				{
					var c = components[x];
					var type = c.types[0];
					if(type == "locality" && locality == null)
					{
						locality = c["long_name"];
					}
					if(c.types[0] == "administrative_area_level_1" && adminArea == null)
					{
						adminArea = c["short_name"];
					}	
					if(c.types[0] == "postal_code" && postcode == null)
					{
						postcode = c["short_name"];
					}
					
					if(adminArea != null && locality != null && postcode != null)
					{
						break;
					}
				}
				
				break;
			}
			
			l = locality+" "+adminArea+", "+postcode;
		}
		else
		{
			l = parseFloat(d.lat).toFixed(3)+","+parseFloat(d.lng).toFixed(3);
			
		}
		callback(l, d.checkinId);
	});
}

/**
* Draws the leaders table
* jQuery obj table = element to populate
* entities : [{	
*					entityId, 
*					entityName, 
*					leaders [userId, firstName, lastName]
*			 }]
*  String mode : mode to set up, view or edit
**/
function drawLeadersTable(table, entities, mode, page)
{
	var userId = table.attr("data-user");
	if(mode == "view")
	{
		//add the default 
		$("#following .leaderInput").removeClass("hide");
		var leaderTxt = "";
		if(entities.length > 0)
		{			
			for(var x = 0; x < entities.length; x++)
			{
				//for each entity
				//if there is a leader
				if(entities[x].leaders.length > 0)
				{
					//remove the default
					$("#following .leaderInput").addClass("hide");
					
					//write heading
					leaderTxt += "<tr><th class='text-center'>"+entities[x].name+"</th></tr>";
					
					for(var i = 0; i < entities[x].leaders.length; i++)
					{					
						var leader = entities[x].leaders[i];
						//for each leader
						//show
						leaderTxt += "<tr><td>"+leader.firstName+" "+leader.lastName+"</td></tr>";
					}
				}
			}
			table.html(leaderTxt);
		}
	}
	else if(mode == "edit")
	{
		console.log("Edit");
		var leaderTxt = "";
		if(entities.length > 0)
		{
			console.log(entities);
			//for each entity
			for(var x = 0; x < entities.length; x++)
			{	
				var entityId = entities[x].entityId;
				console.log(entityId);
				//show header
				leaderTxt += "<tr><th colspan='2' class='text-center'>"+entities[x].name+"</th></tr>";
				
				for(var i = 0; i < entities[x].leaders.length; i++)
				{
					//first name will break with apostrophe
					var leader = entities[x].leaders[i];
					leaderTxt += "<tr class='leaderRow'>"
								+	"<td class='col-md-2'><button type='button' class='btn btn-danger btn-block removeLeader' data-entity='"+entityId+"' onclick='removeLeader("+userId+", "+leader.userId+", "+entityId+", \""+leader.firstName+"\", $(\"#"+table.attr("id")+"\")"+",\""+page+"\")'><span class='glyphicons glyphicon glyphicon-minus'></span></button></td>"
								+ 	"<td>"+leader.firstName+" "+leader.lastName+"</td>"
								+"</tr>";
				}
				leaderTxt += "<tr>"
							+	"<td class='col-md-2'><button type='button' class='btn btn-success btn-block leaderControl add' onclick='setLeaderBtn($(this),\"add\")' data-entity='"+entityId+"' data-user='"+userId+"'><span class='glyphicon glyphicon-plus'></span></button></td>"
							+	"<td></td>"
							+"</tr>";
			}
		}
		else
		{
			//show no entities
		}
		
		table.html(leaderTxt);
	}
}

/**
 * User clicks the cancel button on the leaders table
 * Retirn table to view mode
 * jquery Obj  table : table being changed
 * str page : page 
 */
function cancelLeaders(table, page)
{
	var c = confirm("Are you sure you want to cancel your changes to Leaders?");
	if(c)
	{
		console.log("view mode");
		$("#following .editMode").addClass("hidden");
		$("#following .viewMode").removeClass("hidden");
		drawLeadersTable(table, entities, "view", page);
	}
}
/*
 * Put the leaders table into edit mode and redraw the table in edit mode
 */
function editLeaders(table, page)
{
	console.log(entities);
	$("#following .viewMode").addClass("hidden");
	$("#following .editMode").removeClass("hidden");
	//redraw table
	drawLeadersTable(table, entities, "edit", page);
}

/**
 * Saves the table of leaders as it is
 */
function saveLeaders(table, page)
{
	var c = confirm("Are you sure you want to save your changes to Leaders?");
	if(c)
	{
		$("#following .editMode").addClass("hidden");
		$("#following .viewMode").removeClass("hidden");
		
		var leaders = Array();
		
		//get all added leaders
		$(".leaderControl.remove").parent().next().children("select").each(function(){
			var entity = $(this).attr("data-entity");
			var val = $(this).val();
			var name = $(this).text();
			//validate
			
			
			//put them in an array
			leaders.push({"entity": entity, "leader" : val});
		});
	
		var data = new FormData();
		data.append("function", "addLeaders");
		data.append("follower", table.attr("data-user"));
		data.append("leaders", JSON.stringify(leaders));
		data.append("timestamp", new Date().getTime()/1000);
		data.append("page", page);
		
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			processData: false, 
			contentType: false
		}).done(function(d){
						
			d = JSON.parse(d);
			entities = d.entities;
			drawLeadersTable(table, entities, "view", page);
		});
	}
}

/**
 * Removes a leader from the leaders table
 * Prompts to remove and then deletes from ajax
 */
function removeLeader(follower, leader, entity, name, table, page)
{
	var str = "Are you sure you want to remove "+name+" as a team leader?";
	if(page == "profile")
	{
		str = "Are you sure you want to remove "+name+" as your team leader?";
	}
	var c = confirm(str);
	if(c)
	{
		
		var data = new FormData();
		data.append("function", "removeLeader");
		data.append("follower", follower);
		data.append("leader", leader);
		data.append("entityId", entity);
		data.append("timestamp", new Date().getTime()/1000);
		
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			processData: false,
			contentType: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				//remove this leader from the entities object;					
				for(var i = 0; i < entities.length; i++)
				{
					//if the entity matches the entity deleting
					if(entities[i].entityId == entity)
					{							
						//search through the leaders
						for(var x = 0; x < entities[i].leaders.length; x++)
						{
							//if the leaders id matches the leader
							if(entities[i].leaders[x].userId == leader)
							{
								//remove this leader from the entity info
								entities[i].leaders.splice(x,1);
								break;
							}
						}
					}
				}
				
				drawLeadersTable(table, entities, "edit", page);
			}
			else
			{
				$("#alert-row").before().html("<span class='alerts alert-error'>"+d.msg+"</span>");
			}
		});
	}		
}

/**
 * Saves the user data
 * str page : The user page being updated
 */
function saveUser(page)
{
	var userId = $("#userId").val();
	var t = new Date().getTime()/1000;
	var data = new FormData();
	data.append("function", "saveUser");
	data.append("timestamp", t);
	
	//get all the inputs
	$("#userInfo input, #userInfo select").each(function(){
		var name = $(this).attr("name");
		var val = $(this).val();
		data.append(name, val);
	});
	
	data.append("profilePhoto", $("#userInfo #profilePhotoInput input:file")[0].files[0]);
	
	$.ajax({
		url : path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{
			console.log(page);
			if(page == "user")
			{	
				location.href="/users/";
			}
			else if(page == "profile")
			{
				//show success
				$("#alert-row").html("<div class='alert alert-block alert-success text-center'>Profile Updated</div>");
				alertTimeout($(".alert-success"), 3000);	
								
				//go to view mode
				$("#userInfo input").prop("disabled", true);
				$("#following .editMode").addClass("hidden");
				$("#following .viewMode").addClass("hidden");
				$(".btns.editMode").addClass("hidden");
				$(".btns.viewMode").removeClass("hidden");
				
				//update the img usr ?t to remove the cache
				$(".profilePhoto").attr("src", path+"include/userImg.php?userId="+userId+"&"+t);				
			}
		}
		else
		{
			//show error
			$("#alert-row").html("<div class='alert alert-block alert-danger input-alerts text-center'>"+d.msg+"</div>");
			//alertTimeout($(".alert"), 3000);
		}
	});
}

/**
 * User clicks to save the user profile
 * str page : type of page being saved (user/profile)
 */
function clickSaveUser(page)
{
	var name = $("#firstName").val();					
	//validate and show errors
	validate("user", $("#userInfo input"), function(errs){
		if(Object.keys(errs).length > 0)
		{
			for(name in errs)
			{
				//addclass has-error
				var msg = errs[name];
				var str = "";
				for(m in msg)
				{
					str+= msg[m]+" <br/>";
				}
				
				$("[name='"+name+"']").after("<div class='alert alert-danger input-alerts'>"+str+"<div>");				
			}
		}
		else
		{
			//confirm user wants to save
			var str = " your profile";
			if(page == "user")
			{
				str = name;
			}
			else if(page == "profile")
			{
				if($("#accessVal").val() < $("#accessLevel").val())
				{
					str += " \nNote: You are changing your entity access level.";
				}
			}
			var c = confirm("Are you sure you want to save changes to "+str);
			if(c)
			{
				//save the page
				saveUser(page);
			}
		}
	});
}
/**
 * User clicks the save button on the edit user page
 */
function clickCancelSaveUser(page)
{
	var name = $("#firstName").val();
	var c = confirm("Are you sure you want to cancel changes to "+name);
	if(c)
	{
			//go back
		location.href="/users/";
			
	}
	
}


/**
* user clicks the cancel team leader edit button
* redraw that table
**/
/*
function cancelLeadersProfile()
{
	var c = confirm("Are you sure you want to cancel your changes?");
	if(c)
	{
		$(".editMode").addClass("hide");
		$(".viewMode").removeClass("hide");
		drawLeadersTable($("#profileLeadersTable"), entities, "view");
	}
}*/

/**
* User clicks the edit eams leaders button on their profile
* redraw the table
**/
/*
function editLeadersProfile()
{	
	//remove the "no leaders" input
	//change edit button
	$(".viewMode").addClass("hide");
	$(".editMode").removeClass("hide");
	//redraw table
	drawLeadersTable($("#profileLeadersTable"), entities, "edit");
}
*/
/**
* User clicks the save team leaders button
* 
**/
/*
function saveLeadersProfile()
{
	var c = confirm("Are you sure you want to save your changes?");
	if(c)
	{
		$(".editMode").addClass("hide");
		$(".viewMode").removeClass("hide");
		
		var leaders = [];
		
		//get all added leaders
		$(".leaderControl.remove").parent().next().children("select").each(function(){
			var entity = $(this).attr("data-entity");
			var val = $(this).val();
			var name = $(this).text();
			//validate
			
			
			//put them in an array
			leaders.push({"entity": entity, "leader" : val});
		});
	
		var data = new FormData();
		data.append("function", "addLeaders");
		data.append("follower", $("#profileLeadersTable").attr("data-user"));
		data.append("leaders", JSON.stringify(leaders));
		data.append("timestamp", new Date().getTime()/1000);
		
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			processData: false, 
			contentType: false
		}).done(function(d){
			d = JSON.parse(d);
			entities = d.entities;
			drawLeadersTable($("#profileLeadersTable"), entities, "view");
		});
	}
}*/
/*
function removeLeaderProfile(follower, leader, entity, name)
{
	var c = confirm("Are you sure you want to remove "+name+" as your team leader?");
	if(c)
	{
		
		var data = new FormData();
		data.append("function", "removeLeader");
		data.append("follower", follower);
		data.append("leader", leader);
		data.append("entityId", entity);
		data.append("timestamp", new Date().getTime()/1000);
		
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			processData: false,
			contentType: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				//remove this leader from the entities object;					
				for(var i = 0; i < entities.length; i++)
				{
					//if the entity matches the entity deleting
					if(entities[i].entityId == entity)
					{							
						//search through the leaders
						for(var x = 0; x < entities[i].leaders.length; x++)
						{
							//if the leaders id matches the leader
							if(entities[i].leaders[x].userId == leader)
							{
								//remove this leader from the entity info
								entities[i].leaders.splice(x,1);
								break;
							}
						}
					}
				}
				
				drawLeadersTable($("#profileLeadersTable"), entities, "edit", follower);
			}
			else
			{
				$("#alert-row").before().html("<span class='alerts alert-error'>"+d.msg+"</span>");
			}
		});
	}		
}*/

/**
* Sets the view for the profile
* Adds the user information
* Shows all their team leaders from each entity
* Allows them to edit their team leaders
**/
function setViewProfile(callback)
{
	var jcrop_api = null;
	
	var data = new FormData();
	data.append("function", "getProfile");
	var timestamp = new Date().getTime()/1000;
	data.append("timestamp", timestamp);
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		processData: false, 
		contentType: false
	}).done(function(d){
		d = JSON.parse(d);
		
		//set the entities
		entities = d.user.entities;
		drawLeadersTable($("#profileLeadersTable"), entities, "view", "pofile");
		
		console.log(d);
		$("#userId").val(d.user.userId);
		$("#firstName").val(d.user.firstName);
		$("#lastName").val(d.user.lastName);
		$("#email").val(d.user.email);
		$("#mobile").val(d.user.mobile);
		$(".profilePhoto").attr("src", path+"include/userImg.php?userId="+d.user.userId);	
		
		$("#profileFileInput").fileinput({
			"name": "profilePhoto"
		});
		
		//set the jcrop and photo uploader onchange stuff
		setProfilePhotoUpdater(jcrop_api);	
		console.log("load table");
		setUserEmergencyTable(d.user.userId, $("#emergencyContacts"), false);
		callback();
	});
}

/**
 * Set the onchange events for the photo input and start the jcrop plugin
 */
function setProfilePhotoUpdater(jcrop_api)
{
	console.log("Set");
	$("#profilePhotoInput").on("change.bs.fileinput", function(){	
		$(".image-alert").remove();
		console.log($(this).find("input:file")[0].files[0]);
		var file = $(this).find("input:file")[0].files[0]
		var type = file.type;
		var size = file.size;
		
		if(!type.match(/^image\/.*$/))
		{	
			$(this).append("<div class='alert alert-danger alert-block text-center image-alert'>Must be Image</div>");
		}
		else
		{
		
			console.log($("#photoPreview").parent());
			//move the picker to the side
			$("#photoPreview").parent().removeClass("col-xs-offset-5");
			//show preview
			$("#preview").parent().removeClass("hidden");
			
			//if jcrop 
			if(jcrop_api == null)
			{					
				$("#photoPreview").Jcrop({
						aspectRatio: 1,
						boxWidth: 300, 
						boxHeight: 300,
						//maxSize: [2000,2000],
						minSize: [10,10],
						onSelect: updatePreview,
						onChange: updatePreview,
						allowSelect: false //don't close the select
					}, function(){
					jcrop_api = this;
				});
			}						
			//preview the uploading image in jcrop and select it
			previewInJcrop($(this).find("input:file")[0], jcrop_api, function(){
				var b = jcrop_api.getBounds();
				
				//remove 1% to avoid index error
				var w = b[0]-(b[0]*0.01);
				var h = b[1]-(b[1]*0.01);
				
				//set max size to stop user creating index error
				jcrop_api.setOptions({maxSize : [w,h]});
				
				//find the smaller side and resize to make a square
				(w > h) ? w = h : h = w;
				
				//select the full image (as a square)
				jcrop_api.setSelect([0,0, w, h]);
			});			
		}			
	});
	
	$("#profilePhotoInput").on("clear.bs.fileinput", function(){
		//move back to the center
		$("#photoPreview").parent().addClass("col-xs-offset-5");
		//remove the thumbnail preview
		$("#preview").parent().addClass("hidden");
		if(jcrop_api != null)
		{
			jcrop_api.destroy();
			jcrop_api = null;
		}
	});
}

/**
 * Updates a jcrop preview
 */
function updatePreview(c)
{

	// console.log("cx "+c.x);
	// console.log("cy "+c.y);
	// console.log("cw "+c.w);
	// console.log("ch "+c.h);
	if(parseInt(c.w) > 0)
	{
		
		var imgObj = $(".jcrop-holder img")[0];
		var canvas = $("#preview")[0];
		var context = canvas.getContext("2d");
		
		// console.log("canvas w "+canvas.width);
		// console.log("canvas h "+canvas.height);
		context.drawImage(imgObj, c.x, c.y, c.w, c.h, 0, 0, canvas.width-1, canvas.height-1);
		//add dimensions to form
		$("#x").val(c.x);
		$("#y").val(c.y);
		$("#w").val(c.w);
		$("#h").val(c.h);
	}
}

/**
 * Uses the uploaded image from input and adds it to the jcrop field
 */
function previewInJcrop(input, jcrop_api, callback)
{
	var data = "";
	if(input.files && input.files[0])
	{		
		var reader = new FileReader();
		reader.onload = function(e)
		{
			//set the jcrop image as the result and then run callback
			//IMPORTANT TO RUN AS CALLBACK
			//IMAGE WON'T BE UPDATED IF FUNCTION RUN OUTSIDE
			jcrop_api.setImage(e.target.result, callback);
			
		}
		reader.readAsDataURL(input.files[0]);
	}	
}


/**
 * User clicks the edit profile button
 */ 
function clickEditProfile()
{
	//change elements from view mode to edit mode
	$("#following .viewMode").removeClass("hidden");
	$(".btns.editMode").removeClass("hidden");
	$(".btns.viewMode").addClass("hidden");
	
	$("#userInfo input:not([name='email'])").prop("disabled", false);
	
	console.log("click edit");
	var id = $("#userId").val();
	setUserEmergencyTable(id, $("#emergencyContacts"), true);
}

/**
 * User clicks the cancel edit profile button
 */
function clickCancelEditProfile()
{	
	if(!$("#following .editMode").hasClass("hidden"))
	{
		alert("Save changes to the Team Leader table and try again");
	}
	else
	{
		//confirm to cancel
		var c = confirm("Are you sure you want to cancel changes to your profile?");
		if(c)
		{
			//change the elements from edit mode to view mode
			$("#following .viewMode").addClass("hidden");
			$(".btns.editMode").addClass("hidden");
			$(".btns.viewMode").removeClass("hidden");
			$("#userInfo input").prop("disabled", true);
		}
	}
}

/**
* Sets the buttons for the leaders table on the profile page
* Jquery Obj btn  : The button that was clicked
* string mode : the mode that is being loaded
**/
function setLeaderBtn(btn,mode)
{
	if(mode == "add")
	{	
		//don't change anything just add an extra row with a dropdown
		
		var entityId = btn.attr("data-entity");
		var userId = btn.attr("data-user");
		
		var data = new FormData();
		data.append("function", "findLeaders");
		data.append("entityId", entityId);
		data.append("timestamp", new Date().getTime()/1000);
		data.append("userId", userId);
					
		//add the dropdown
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			contentType: false,
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			console.log(d);
			var newRow = "<tr>"
					+"<td class='col-md-2'><button type='button' class='btn btn-danger btn-block leaderControl remove' onclick='setLeaderBtn($(this),\"remove\")' data-entity='"+entityId+"' data-user='"+userId+"'>"
					+"<span class='glyphicon glyphicon-minus'></span>"
					+"</td>"
					+"<td>";	
			if(d.success)
			{
				 newRow += "<select class='form-control' data-entity='"+entityId+"'>";
				for(var i = 0; i < d.leaders.length; i++)
				{
					var leader = d.leaders[i];
					newRow += "<option value='"+leader.userId+"'>"+leader.firstName+" "+leader.lastName+"</option>";						
				}
				newRow += "</select>";
			}
			else
			{
				//do nothing
				newRow += "<span class='alert alert-block'>"+msg+"</span>";
			}
			
			newRow += "</td></tr>";
			
			//add the row above the add row
			$(".leaderControl.add[data-entity='"+entityId+"']").parent().parent().before(newRow);
		});
		
	}
	else if(mode == "remove")
	{
		//remove this row
		btn.parent().parent().remove();
	}
}

/**
 * Get the default question data and draw the panels
 * draw new row for each site specific options
 * make the default questions sortable
**/
function viewQuestionData()
{
	//console.log("view question data");
	var data = new FormData();
	data.append("function", "getQuestions");
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			if(d.questions.length > 0)
			{
				//add the first key (default site)
				var sites = {0:[]};
				//sort the questions
				for(var i = 0; i < d.questions.length; i++)
				{
					var q = d.questions[i];
					if(q.siteId == null)
					{
						//add to default
						sites[0].push(q);
					}
					else
					{
						if(sites[q.siteId] == null)
						{
							sites[q.siteId] = [];
						}
						sites[q.siteId].push(q);
					}
				}
			
				//create default panel opened and rest closed				
				//console.log(sites);				
				for(siteId in sites)
				{
					var siteName = "";
					var panel = "";
					if(siteId == 0)
					{
						siteName = "Default questions";
						var optionsList = "";
						var questionRow = "";
						var questions = sites[siteId];
						
						var questionHtml = createSiteRowQuestions(questions);
						optionsList = questionHtml.optList;
						questionRow = questionHtml.questionRow;
						
						//panel header
						panel += "<div class='panel panel-default' data-site='"+siteId+"'>"
								+	"<div class='panel-heading'>"		
								+		"<div class='btn-group pull-right'>"
								+			"<button type='button' class='btn btn-danger' onclick='clickCancelQuestionFlow("+siteId+", \""+siteName+"\")'>Cancel</button>"
								+			"<button type='button' class='btn btn-success' onclick='clickSaveQuestionFlow("+siteId+")'>Save</button>"
								+		"</div>"
								+		"<br/>"
								+		"<h4 class='panel-title questionPanelTitle'>"
								+			"<a class='panelSiteName' data-toggle='collapse' data-parent='#siteQuestionPanel' href='#site"+siteId+"_questions'>Default Questions</a>"
								+		"</h4>"
								+	"</div>"
						//panel content
								+	"<div id='site"+siteId+"_questions' class='panel-collapse collapse in questionPanel' data-site='"+siteId+"' data-toggle='false'>"
								+		"<div class='panel-body'>"
								+			"<!-- TABLES -->"
								+			"<div class='col-md-8'>"
						//main table
								+				"<table class='table table-bordered questionTable col-md-12' data-site='"+siteId+"'>"
								+					"<tr>"
								+						"<th class='col-md-1'></th>"
								+						"<th class='col-md-3'>Question</th>"
								+						"<th class='col-md-4'>Child Yes</th>"
								+						"<th class='col-md-4'>Child No</th>"
								+					"</tr>"
								+					questionRow
								+					"<tr class='addQuestionRow'>"
								+						"<td><button type='button' class='btn btn-success btn-block' onclick='addQuestionRow($(this))'><span class='glyphicon glyphicon-plus'></span></button></td>"
								+						"<td colspan='4'></td>"
								+					"</tr>"
								+				"</table>"
						//end main table
								+			"</div>"
								+			"<div class='col-md-4'>"
						//options table
								+				"<table class='col-md-12 optionsTable' data-site='"+siteId+"'>"
								+					"<tr><th>Options</th></tr>"
								+					"<tr class='optionsListRow'>"
								+						"<td class='optionsListCell'>"
								+							"<ul class='optionsList canSort' data-site="+siteId+">"
								+								optionsList
								+							"</ul>"
								+						"</td>"
								+					"</tr>"
								+				"</table>"
						//end options table
								+			"</div>"
								+		"</div>"
								+	"</div>"
								+"</div>";
					}
					else
					{
						siteName = sites[siteId][0].siteName;
						//panel header
						panel += "<div class='panel panel-default' data-site='"+siteId+"'>"
								+	"<div class='panel-heading'>"
								+		"<div class='btn-group pull-right hide'>"
								+			"<button type='button' class='btn btn-danger' onclick='clickCancelQuestionFlow("+siteId+", \""+siteName+"\")'>Cancel</button>"
								+			"<button type='button' class='btn btn-success' onclick='clickSaveQuestionFlow("+siteId+")'>Save</button>"
								+		"</div>"
								+		"<br/>"
								+		"<h4 class='panel-title questionPanelTitle'>"
								+			"<a class='panelSiteName' data-toggle='collapse' data-parent='#siteQuestionPanel' href='#site"+siteId+"_questions'>"+siteName+"</a>"
								+		"</h4>"
								+	"</div>"
								//panel content
								+	"<div id='site"+siteId+"_questions' class='panel-collapse collapse questionPanel' data-site='"+siteId+"'>"
								+		"<div class='panel-body'>"
								+			"<!-- TABLES -->"
								+		"</div>"
								+	"</div>"
								+"</div>";
					}
					
					$("#siteQuestionPanel").append(panel);
					//make sortable only siteId = 0
					makeQuestionsSortable(0);
				}
				
				/**
				 * COLLAPSABLE FUNCTIONS
				**/
				$("#siteQuestionPanel").on("hide.bs.collapse", function(e){
					siteQuestionPanelHide(e);
				});
				
				$("#siteQuestionPanel").on("hidden.bs.collapse", function(e){
					var panelId = e.target.getAttribute("id");
					//wipe the panel body after it closes
					$("#"+panelId).find(".panel-body").empty();
					//console.log(e);
					//remove buttons
					$("#"+panelId).prev().find(".btn-group").addClass("hide");
				});
				
				//on open
				$("#siteQuestionPanel").on("show.bs.collapse", function(e){
					siteQuestionPanelShow(e)
				});	
			}
			
		}
		else
		{
			//error
			console.log(d.msg);
		}
	});
}	




/**
 * create the site row questions on the question control page
 * creates a table with remove and add buttons and the current set questions
 * creates a list of options that can be moved into the table
 * obj questions : array of questions
 * return {
 * "questionRow" : each question in a new row for the main table
 * "optList" : each question in  new li for the options list
 * }
**/
function createSiteRowQuestions(questions)
{
	var questionRow = "";
	var optionsList = "";
	//show this on hover
	var hoverFunctions = "onmouseenter='addQEditOnMouseOver(this)' onmouseleave='removeQEditOnMouseOut(this)'";
				

	for(var i = 0; i < questions.length; i++)
	{
		var q = questions[i];
		var qTxt = q.question;
		var qId = q.questionId;
		var siteId = q.siteId;
		if(siteId == null)
		{
			siteId = 0;
		}
		
		var alertClass = "alert-info";
		if(q.isParent == true || q.isParent == "true")
		{
			alertClass = "alert-success";
		}
		
		//show in the table if has children or is a parent
		if(q.childYes > 0 || q.childNo > 0 || q.isParent == "true" || q.isParent == true)
		{
			var childYes = "";
			var childNo = "";
			var yesClass  = "alert-info";
			var noClass = "alert-info";
			
			if(q.yIsParent == true)
			{
				yesClass = "alert-success";
			}
			if(q.nIsParent == true)
			{
				noClass = "alert-success";
			}
			
			
			//if child yes
			if(q.childYes > 0)
			{
				childYes = "<li class='alert alert-block "+yesClass+"' data-question='"+q.childYes+"' "+hoverFunctions+">"+q.yQuestion+"</li>";
			}
			//if child no
			if(q.childNo > 0)
			{
				childNo = "<li class='alert alert-block "+noClass+"' data-question='"+q.childNo+"' "+hoverFunctions+">"+q.nQuestion+"</li>";
			}
			
			questionRow += "<tr class='questionRow' data-question='"+qId+"'>"
						+"<td><button class='btn btn-danger btn-block' type='button' onclick='removeQuestionRow(this)'><span class='glyphicon glyphicon-trash'></span></button></td>"
						+"<td class='questionCell canSort' data-site='"+siteId+"'><li class='alert alert-block "+alertClass+"' data-question='"+qId+"' "+hoverFunctions+">"+qTxt+"</li></td>"									
						+"<td class='childOption yes canSort' data-site='"+siteId+"' "+hoverFunctions+">"+childYes+"</td>"
						+"<td class='childOption no canSort' data-site='"+siteId+"' "+hoverFunctions+">"+childNo+"</td>"
						+"</tr>"
		}
		
		//put it in the options
		optionsList += "<li class='alert alert-block "+alertClass+"' data-question='"+qId+"' "+hoverFunctions+">"+qTxt+"</li>";
			
		
	}
	
	return {"questionRow": questionRow, "optList": optionsList};
}

/**
 * saves the site's panel flow
 * gets all the rows in this site and saves the question and child questions
 * runs a callback (d.success, d.msg)
**/
function saveQuestionPanel(siteId, callback)
{
	var siteName = $("[data-site='"+siteId+"'] .panel-heading .panelSiteName").text();

	var c = confirm("Would you like to save the question changes to "+siteName);
	var success = false;
	var msg = "";
	if(c)
	{
		success = true;
		var questionsToEdit = [];
		//get all the values
		$(".questionTable[data-site='"+siteId+"'] .questionRow").each(function(){
			console.log($(this).find(".questionCell"));
			childYes = 0;
			childNo = 0;
			
			var questionCell = $(this).find(".questionCell li");				
			var childYesCell = $(this).find(".childOption.yes li");
			var childNoCell = $(this).find(".childOption.no li");
			if(questionCell.length > 0)
			{
				questionId = questionCell[0].getAttribute("data-question");
				if(childYesCell.length > 0)
				{
					childYes = childYesCell[0].getAttribute("data-question");
				}
				if(childNoCell.length > 0)
				{
					childNo = childNoCell[0].getAttribute("data-question");
				}
			}			
		
			questionsToEdit.push({
				"questionId" : questionId,
				"childYes" : childYes, 
				"childNo" : childNo
			});
		});
		//do the sql to save
		var data = new FormData();
		data.append("function", "saveQuestionFlow");
		data.append("timestamp", new Date().getTime()/1000);
		data.append("questionsToEdit", JSON.stringify(questionsToEdit));
		data.append("siteId", siteId);
		
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST",
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			//console.log(d);
			
			callback(d.success, d.msg, siteId);
		});
	}
	
}


/**
* Make the sortables on the questions table
* Allow interaction by site
*/
function makeQuestionsSortable(siteId)
{
	//active the sortables
	$("[data-site='"+siteId+"'] .canSort").sortable({
		connectWith: "[data-site='"+siteId+"'] .canSort",
		stop: function(e, ui)
		{
			//this questions id
			var q = ui.item.attr("data-question");																						
			var parent = ui.item.parent();							
			//parent q id
			var parentQ = parent.parent().find(".questionCell").find("li").attr("data-question");
			//child q ids
			var childY = parent.parent().find(".childOption.yes").find("li").attr("data-question");
			var childN = parent.parent().find(".childOption.no").find("li").attr("data-question");
			
			//stop loops
			//if child question and parent question match
			//remove the last placed one		
			if(parent.hasClass("questionCell"))
			{
				if(childY == q || childN == q)
				{
					//remove the new item
					ui.item.detach();
				}
			}
			else if(parent.hasClass("childOption"))
			{
				if(parentQ == q)
				{
					ui.item.detach();
				}
			}							
			
			//get this items site
			var s = parent.attr("data-site");
			//for each item in the list
			$(".optionsList[data-site='"+s+"'] li").each(function(){	
				//if this question is already in the list
				if($(this).attr("data-question") == q)
				{
					//remove this element
					$(this).detach();
				}
			});
			
			//re-add the element
			ui.item.clone().appendTo(".optionsList[data-site='"+s+"']");
		},
		update: function(e, ui)
		{				
		
			var questionId = ui.item.attr("data-question");
			var parent = ui.item.parent();
			var siteId = parent.attr("data-site");
			var siteOptionList = $(".optionsListCell [data-site='"+siteId+"']");
			
			if(parent != null)
			{
				//if its in the table
				if(parent.hasClass("childOption") || parent.hasClass("questionCell"))
				{
					//if the parent has another item in it
					if(parent.children("li").length > 0)
					{
						//remove old item
						parent.children("li").detach();
						parent.append(ui.item);
					}
				}
				
				if(parent.hasClass("questionCell"))
				{
					//reset alert colours
					$(".alert-warning").removeClass("alert-warning").addClass("alert-info");										
							
					//if there is more then one copy of this question in the questions cell
					//there was already this question here
					var questionCell = $(".questionCell[data-site='"+siteId+"'] [data-question='"+questionId+"']");
					if(questionCell.length > 1)
					{											
						//remove the new copy of it
						ui.item.detach();
						siteOptionList.append(ui.item);
						
						//put some user feedback
						//alert("Question is already set");
						questionCell = $(".questionCell[data-site='"+siteId+"'] [data-question='"+questionId+"']");
						
						var hasClass = "";
						if(questionCell.hasClass("alert-info"))
						{
							hasClass = "alert-info";
						}
						else if(questionCell.hasClass("alert-success"))
						{
							hasClass = "alert-success";
						}
						//highlight the item
						questionCell.removeClass(hasClass);
						questionCell.addClass("alert-warning");
						setTimeout(function(){
							questionCell.removeClass("alert-warning").addClass(hasClass);												
						}, 2000);
					}										
				}
			}
		}
	}).disableSelection();
}


/**
 * Add the edit button to the question option on mouse over
**/
function addQEditOnMouseOver(elem)
{
	//if the question is is in the options list
	if($(elem).parent().hasClass("optionsList"))
	{
		//remove the edit button
		$("li[data-question]").find("button").remove();
		//add the edit button
		var id = $(elem).attr("data-question");
		$(elem).append("<button class='close' type='button' onclick='editQuestion("+id+")'><span class='glyphicon glyphicon-wrench'></span></button>");						
	}
}

/**
 * remove all edit buttons when mouse out
**/
function removeQEditOnMouseOut(elem)
{
	//remove the edit button
	$("li[data-question]").find("button").remove();
}	

/**
 * writes the questions modal for this questions
 * makes a request for the questions data according to the id
 * shows the checkboxes and inputs with loaded data if is exists
**/
function editQuestion(id)
{
		
	var modalBody = "";
	//if its a new question set the id to -1
	//-1 in the query will return no results ever
	if(id == null)
	{
		id = -1;
	}
	
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "getQuestions");
	data.append("questionId", id);
	
	//get question data
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			var qId = null;
			var ques = "";
			var canProceed = false;
			var note = "";
			var interval = ""; 
			var safeAnswer = "";	
			var isParent = false;
			var siteSpecific = false;
			var siteId = null;
			var siteName = "";
			
			var safeAnswerY = "";
			var safeAnswerN = "";
			var safeAnswerB = "";
			var topLevel = "";
			var canProceedVal = "";
			var siteSpecVal = "";
			var intervalVal = "";
			
			var intervalCheckbox = "disabled";
			var intervalTxt = "disabled";				
			var siteTxt = "disabled";
			
			var type = "new";
		
			if(d.questions.length == 1)
			{
				
				var q = d.questions[0];
				qId = q.questionId;
				ques = q.question;
				canProceed = q.canProceed;
				note = q.note;					
				isParent = q.isParent;
				safeAnswer = q.safeAnswer;
				interval = q.intervalTime;
				siteId = q.siteId;
				type = "edit";			
				
				
				if(safeAnswer == 1)
				{
					safeAnswerY = "checked";
				}
				else if(safeAnswer == 0)
				{
					safeAnswerN = "checked";
				}
				else if(safeAnswer == 2)
				{
					safeAnswerB = "checked";
				}
				
				if(isParent == 1)
				{
					topLevel = "checked";
					intervalCheckbox = "";
					intervalTxt = "";
				}
				if(canProceed == 1)
				{
					canProceedVal = "checked";
				}
				if(siteId != null)
				{
					siteSpecVal = "checked";
					siteName = q.siteName;
					siteTxt = "";
				}
				
				if(interval != null)
				{
					intervalVal = "checked";
					//interval = q.intervalTime/(60*60*24); //convert to days
					intervalTxt = "";
				}
				else
				{
					interval = "";
					intervalTxt = "disabled";
				}
				
			}
			
			modalBody =	"<form role='form' class='form-horizontal' id='questionModalForm'>"
					+ 		"<input type='hidden' name='questionId' id='questionId' value='"+qId+"'>"
					+		"<div class='form-group'>"
					+ 			"<label for='questionText' class='control-label col-md-3'>Question</label>"
					+ 			"<div class='col-md-9'>"
					+				"<input type='text' class='form-control' id='questionText' name='questionText' value='"+ques+"'>"
					+			"</div>"
					+		"</div>"
					+ 		"<div class='form-group'>"
					+			"<label for='questionNote' class='control-label col-md-3'>Note</label>"
					+			"<div class='col-md-9'>"
					+				"<textarea class='form-control' id='questionNote' name='questionNote'>"+note+"</textarea>"
					+			"</div>"
					+		"</div>"
					+		"<div class='form-group'>"
					+			"<label class='control-label col-md-3'>Top Level Question</label>"
					+			"<div class='col-md-9'>"
					+				"<label class='checkbox-inline'>"
					+					"<input type='checkbox' name='topLevel' id='topLevel' "+topLevel+">&nbsp;"
					+				"</label>"
					+			"</div>"
					+		"</div>"
					+ 		"<div class='form-group'>"
					+			"<label for='questionSafeAnswer' class='control-label col-md-3'>Safe Answer</label>"
					+			"<div class='col-md-4'>"
					+				"<label class='radio-inline'>"
					+					"<input type='radio' name='safeAnswer' "+safeAnswerY+" value='1'>Yes"
					+				"</label>"
					+				"<label class='radio-inline'>"
					+					"<input type='radio' name='safeAnswer' "+safeAnswerN+" value='0'>No"
					+				"</label>"
					+				"<label class='radio-inline'>"
					+					"<input type='radio' name='safeAnswer' "+safeAnswerB+" value='2'>Both"
					+				"</label>"
					+			"</div>"	
					+		"</div>"
					+		"<div class='form-group'>"						
					+			"<label for='questionsProceed' class='control-label col-md-3'>Can Proceed</label>"
					+			"<div class='col-md-1' id='questionsProceed'>"
					+				"<label class='checkbox-inline'>"
					+					"<input type='checkbox' name='canProceed' "+canProceedVal+">&nbsp;"
					+				"</label>"
					+			"</div>"		
					+		"</div>"
					+		"<div class='form-group' id='intervalRow'>"													
					+			"<label class='control-label col-md-3'>Question interval</label>"
					+			"<div class='col-md-1'>"
					+				"<label class='checkbox-inline'>"
					+					"<input type='checkbox' name='hasInterval' "+intervalVal+" "+intervalCheckbox+">&nbsp;"
					+				"</label>"
					+			"</div>"
					+			"<div class='col-md-2'>"
					+				"<input type='text' class='form-control' name='intervalTime' id='intervalTime' value='"+interval+"' "+intervalTxt+">"
					+			"</div>"
					+			"<div class='col-md-4'>"
					+				"<select class='form-control col-md-2' name='intervalType' id='intervalType' "+intervalTxt+">"
					+					"<option value='hours'>hours</option>"
					+					"<option value='days'>days</option>"
					+					"<option value='months'>months</option>"
					+				"</select>"
					+			"</div>"
					+		"</div>"
					+		"<div class='form-group' id='siteRow'>"
					+			"<label class='control-label col-md-3'>Site specific?</label>"
					+			"<div class='col-md-1'>"
					+				"<label class='checkbox-inline'>"
					+					"<input type='checkbox' name='siteSpecific' "+siteSpecVal+">&nbsp;"
					+				"</label>"
					+			"</div>"
					+			"<div class='col-md-8'>"
					+				"<input type='text' class='form-control typeahead' data-provide='typeahead' autocomplete='off' id='siteSpecName' value='"+siteName+"' placeholder='Search Site Name' "+siteTxt+">"
					+				"<input type='hidden' id='siteSpecId' name='siteSpecId' value='"+siteId+"'>"
					+			"</div>"
					+		"</div>"
					+	"</form>";
			//onkeyup='searchSiteName($(this), $(\"#siteSpecId\"))'
			
			
			
			//set the modal body
			$("#questionModal .modal-body").html(modalBody);
			//if its a new question don't show the delete button
			if(id != -1)
			{
				$("#questionModal #deleteQuestionBtn").removeClass("hide");
			}
			else
			{
				$("#questionModal #deleteQuestionBtn").addClass("hide");
			}
			
			//set which interval we're looking at
		
			var interval = q.intervalTime;
			if(interval > 0) //if interval is set
			{
				if(interval < 24*60*60)
				{
					$("#intervalType").val("hours");
					$("#intervalTime").val(Math.floor(interval));
				}
				else if(interval < 30*24*60*60)
				{
					$("#intervalType").val("days");
					$("#intervalTime").val(Math.floor(interval/(24*60*60)));
				}
				else
				{
					$("#intervalType").val("months");
					$("#intervalTime").val(Math.floor(interval/(30*24*60*60)));
				}
			}
						
			$("#questionModal").modal();
			
			setSiteTypeAhead();
			
			//set control ui changes
			//checkboxes enable input etc
			var frm = $("#questionModalForm");
			//if top level changes
			frm.find("#topLevel").change(function(){
				if(this.checked)
				{					
					//allow interval
					$("#intervalRow :checkbox").prop("disabled", false);
				}				
				else
				{
					//uncheck interval
					$("#intervalRow input:checkbox").prop("checked", false);
					//disable input
					$("#intervalRow [name]").prop("disabled", true);
				}
			});
			
			//if interval changes
			frm.find("#intervalRow :checkbox").change(function(){
				var intervalElem = $("#intervalRow select, #intervalRow :text");
				if(this.checked)
				{
					//allow interval
					intervalElem.prop("disabled", false);
				}
				else
				{
					//disable interval
					intervalElem.prop("disabled", true);
				}
			});
			
			//if site spec changes
			frm.find("#siteRow :checkbox").change(function(){
				if(this.checked)
				{
					//allow site
					$("#siteRow :text").prop("disabled", false);
				}
				else
				{
					//disable site
					$("#siteRow :text").prop("disabled", true).val("");
					//remove any site that exists
					$("#siteRow :hidden").val("null");
				}
			});
		}
		else
		{
			console.log(d.msg);
		}
	});		
}

/**
 * User clicks the delete button on the modal
 * Prompts user to delete the question
 * If yes it gets deleted, requests the delete and removes the question
 * where it appears
**/
function clickDeleteQuestion(form)
{
	console.log($(form));
	var id = $(form).find("#questionId").val();
	var question = $(form).find("#questionText").val();
	var qIds = [id];
	console.log(qIds);
	
	var c = confirm("Are you sure you want to delete this question: "+question);
	var data = new FormData();
	data.append("function", "removeQuestions");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("questionIds", JSON.stringify(qIds));
	
	$.ajax({
		url : path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			if(d.questionIds.length > 0)
			{
				for(var i = 0; i < d.questionIds.length; i++)
				{
					var qId = d.questionIds[i];
					//these questions were deleted
					console.log("DELETED : "+d.questionIds[i]);
					//remove this question row
					$(".questionRow[data-question='"+qId+"']").remove();
					//remove this option from the list
					$(".optionsList [data-question='"+qId+"']").remove();
				}
			}
			
			if(d.failed.length > 0)
			{
				//some failed
				for(var i = 0; i < failed.length; i++)
				{
					console.log("FAILED : "+d.failed[i]);
				}
			}
			
			//close modal
			$("#questionModal").modal("hide");
		}
		else
		{
			//show error
			console.log(d.msg);
			
			//all failed or another error
		}
	});
}

/**
 * User hits the save button in the modal
 * Sends a request to save it
 * if it saves it updates the table and option list depending on 
 * Parent status and question id
 */
function saveQuestion(id)
{
	var data = new FormData();
	data.append("function", "saveQuestion");
	data.append("timestamp", new Date().getTime()/1000);
	//get all the values in the form
	$(id+" [name]:not(:radio), "+id+" [name]:radio:checked").each(function(){		
		var name = $(this).attr("name");
		var val = false;
		//if checkbox set as true/false
		if($(this).attr("type") == "checkbox")
		{
			val = this.checked;
		}
		else
		{				
			val = $(this).val();
		}									
		data.append(name, val);
	});	
	
	
	//send to db
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);	
		//reutrn succ/false
		if(d.success)
		{
			//close modal
			//clear modal
			$("#questionModal").modal("hide");
			
			//get some values
			var siteId = d.question.siteId;
			var siteName = d.question.siteName;
			var qId = d.question.questionId;
			var isParent = d.question.isParent;
			
			//get the question html
			var questionHTML = createSiteRowQuestions([d.question]);
			var questionRow = questionHTML.questionRow;
			var optList = questionHTML.optList;			
			
			if(siteId == "null")
			{
				siteId = 0;
				d.question.siteId = 0;
			}
			
			//find if this question exists
			var questionRowExist = $(".questionRow[data-question='"+qId+"'], .optionsList [data-question='"+qId+"']");
					
			//if this question was edited replace it, otherwise add it.
			//question wont exist if the tab is closed so dont' have to check for it
			//even if the site specific question is removed keep the panel
			if(questionRowExist.length > 0)
			{
				//remove any that exist
				questionRowExist.remove();
				//$(".optionsList [data-question='"+qId+"']").remove();
			}
			
			//check if the siteId exists already
			var siteExists = $("#site"+siteId+"_questions");
			if(siteExists.length == 0)
			{
				//add this site panel
				panel = "<div class='panel panel-default' data-site='"+siteId+"'>"
					+	"<div class='panel-heading questionPanelTitle'>"
					+		"<div class='btn-group pull-right'>"
					+	 		"<button type='button' class='btn btn-danger' onclick='clickCancelQuestionFlow("+siteId+", \""+siteName+"\")'>Cancel</button>"
					+			"<button type='button' class='btn btn-success' onclick='clickSaveQuestionFlow("+siteId+")'>Save</button>"
					+		"</div>"
					+		"<br/>"
					+		"<h4 class='panel-title'>"
					+			"<a class='panelSiteName' data-toggle='collapse' data-parent='#siteQuestionPanel' href='#site"+siteId+"_questions'>"+siteName+"</a>"
					+		"</h4>"
					+	"</div>"
					//panel content
					+	"<div id='site"+siteId+"_questions' class='panel-collapse collapse questionPanel' data-site='"+siteId+"'>"
					+		"<div class='panel-body'>"
					+			"<!-- TABLES -->"
					+		"</div>"
					+	"</div>"
					+"</div>";
				$("#siteQuestionPanel").append(panel);
			}
			
			//else
			//{
				//new just add					
				if(isParent == "true")
				{
					$("#site"+siteId+"_questions .questionTable .addQuestionRow").before(questionRow);
				}
				$("#site"+siteId+"_questions .optionsList").append(optList);
			//}			
			makeQuestionsSortable(siteId);
		}
		else
		{
		}
	
		
	});
}

/**
 * Get the saved questions and populate the correct site panel
 * make the items sortable
 */
function getQuestionPanelBody(siteId)
{
	if(siteId == 0)
	{
		siteId = null;
	}
	//populate the panel-body of this site
	var data = new FormData();
	data.append("function", "getQuestions");
	data.append("siteId", siteId);
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		//write the panel body
		if(d.success)
		{
			var questions = d.questions;
			//reset the siteid again
			if(siteId == null)
			{
				siteId = 0;
			}
			
			var questionHtml = createSiteRowQuestions(questions);
			var optionsList = questionHtml.optList;
			var questionRow = questionHtml.questionRow;
			
			var panelBody ="<div class='col-md-8'>"							//main table
						+	"<table class='table table-bordered questionTable' data-site='"+siteId+"'>"
						+		"<tr>"
						+			"<th class='col-md-1'></th>"
						+			"<th class='col-md-3'>Question</th>"
						+			"<th class='col-md-4'>Child Yes</th>"
						+			"<th class='col-md-4'>Child No</th>"
						+		"</tr>"
						+		questionRow
						+		"<tr class='addQuestionRow'>"
						+			"<td><button type='button' class='btn btn-success btn-block' onclick='addQuestionRow($(this))'><span class='glyphicon glyphicon-plus'></span></button></td>"
						+			"<td colspan='4'></td>"
						+		"</tr>"
						+	"</table>"
				//end main table
						+	"</div>"
						+	"<div class='col-md-4'>"
				//options table
						+	"<table class='col-md-12 optionsTable' data-site='"+siteId+"'>"
						+		"<tr><th>Options</th></tr>"
						+		"<tr class='optionsListRow'>"
						+			"<td class='optionsListCell'>"
						+				"<ul class='optionsList canSort' data-site="+siteId+">"
						+					optionsList
						+				"</ul>"
						+			"</td>"
						+		"</tr>"
						+	"</table>"
				//end options table
						+"	</div>";
			
			$(".questionPanel[data-site='"+siteId+"'] .panel-body").html(panelBody);
			
			//make sortable	
			makeQuestionsSortable(siteId);
			
			//show the save and cancel buttons				
			$("[data-site='"+siteId+"'] .panel-heading .btn-group").removeClass("hide");
		}
		else
		{
			console.log("ERROR: "+d.msg);
		}
	});
}
	
/**
 *$("#siteQuestionPanel").on('show.bs.collapse');
 * gets this questions data
 * puts it in the panel-body with the table and list
**/
function siteQuestionPanelShow(e)
{
	var siteId = e.target.getAttribute("data-site");
	getQuestionPanelBody(siteId)
}

/**
 * Click a cancel button on a question panel.
 * Confirm with use to cancel
 * Get the saved questions
 */
function clickCancelQuestionFlow(siteId, siteName)
{	
	var c = confirm("Are you sure you want to cancel the changes for site "+siteName);
	if(c)
	{
		//reload the data
		getQuestionPanelBody(siteId);
	}
}

/**
 * when user clicks the save button on the top of the collapsible
*/
function clickSaveQuestionFlow(siteId)
{
	//confirm
	saveQuestionPanel(siteId, function(){
		//do nothing
	});	
}

/**
 * $("#siteQuestionPanel").on("hide.bs.collapse")
 * when the hide event fires if its default site dont clost
 * else save the panel and hide if theres no error
 */
function siteQuestionPanelHide(e)
{
	var siteId = e.target.getAttribute("data-site");
	var targetId = e.target.getAttribute("id");
	
	//stop the default panel from closing
	if(siteId == 0)
	{
		console.log("stop close");
		//don't close
		e.preventDefault();
	}
	else
	{
		saveQuestionPanel(siteId, function(success, msg, siteId){								
			
			if(success)
			{				
				//close

			}
			else
			{
				e.preventDefault();
				alert("THER WAS AN ERROR" + msg);
			}		
		});
	}

}


/**
 * adds a row to the questions table
 * draws it above the current add row
 * sets the data-question id to -1 not sure if that's needed anymore
**/
function addQuestionRow(btn)
{
	//show this on hover
	var hoverFunctions = "onmouseenter='addQEditOnMouseOver(this)' onmouseleave='removeQEditOnMouseOut(this)'";

	var row = btn.parent().parent();
	//table
	var siteId = row.parent().parent().attr("data-site");
	row.before("<tr class='questionRow' data-question='-1'>"
				+"<td><button class='btn btn-danger btn-block' type='button' onclick='removeQuestionRow(this)'><span class='glyphicon glyphicon-trash'></span></button></td>"
				+"<td class='questionCell canSort' data-site='"+siteId+"'></td>"
				+"<td class='childOption yes canSort' data-site='"+siteId+"' "+hoverFunctions+"></td>"
				+"<td class='childOption no canSort' data-site='"+siteId+"' "+hoverFunctions+"></td>"
				+"</tr>");
	//make sortable
	makeQuestionsSortable(siteId);
}

/**
 * removes the row selected
**/
function removeQuestionRow(btn)
{
	$(btn).parent().parent().remove();
}

/**
 * User clicks the save button on the question modal
 * Send the input data to be validated
 * If errors show alert boxes
 * If valid send to ajax
 * int id  : Id of the question or null if a new question
 */
function clickSaveQuestion(id)
{
	//clear all alerts
	$(".question-alert").remove();

	var elem = $(id+" [name]:not(:radio), "+id+" [name]:radio:checked");

	validate("question", elem, function(errs){
		console.log(errs);
		//if any errors
		if(Object.keys(errs).length > 0)
		{
			for(x in errs)
			{
				//addclass has-error
				var msg = errs[x];
				var str = "";
				for(m in msg)
				{
					str+= msg[m]+" <br/>";
				}
				console.log("name "+x);
				//draw the alert
				$("#questionModalForm [name='"+x+"']").parent().append("<div class='alert alert-danger question-alert'>"+str+"</div>");
			}
		}
		else
		{
			saveQuestion(id);
		}
	
	});		
}

function setSiteTypeAhead()
{	
	var source = function(data){
		return function(query, process){
			//console.log(query);
			objects = [];
			map = {};
			
			//regex case irrelvant to query
			var substrRegex = new RegExp(query, "i");
			
			//console.log(data);
			$.each(data, function(i, object){
				if(substrRegex.test(object.name)){
					//console.log(i);
					//console.log(object.name);
					map[object.name] = object;
					objects.push({name : object.name});
				}
			});
			//console.log(map);
			//console.log(objects);
			
			//process the matches
			process(objects);
		};
	};

	var sites = [];
	
	var data = new FormData();
	//data.append("entityId", entityId);
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findSites");
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data,
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		//console.log(d);
		for(var i = 0; i < d.sites.length; i++)
		{
			sites.push({name: d.sites[i].name, id: d.sites[i].siteId});
		}
		
		//console.log(sites);
		
		$("#siteSpecName.typeahead").typeahead({
			hint: true, 
			highlight: true, 
			minLength: 1
		},
		{
			name: "sites",
			displayKey: "name",
			source: source(sites),
			updater: function(item){
				console.log(item);
				//console.log(map[item]);
			}
		});
		
		$("#siteSpecName.typeahead").on("typeahead:selected typeahead:autocompleted", function(e,item) {
			//find the object for this text
			var obj = map[item.name];
			//set the value of the hidden field to this id
			$("#siteSpecId").val(obj.id); 
			$(this).blur();
		});
		$("#siteSpecName.typeahead").keyup(function(){
			console.log("changed");
			$("#siteSpecId").val("");
		});
		
	});
		
}

/**
 * Sets a typeahead using a dataset for certain inputs
 * dataSet = [
 *  {id: int, name: string}
 *]
 * inputs = [jqueryObj]
 *]
 */
function setTypeAhead(dataset, inputs)
{
	var source = function(data){
		return function(query, process){
			//console.log(query);
			objects = [];
			map = {};
			
			//regex case irrelvant to query
			var substrRegex = new RegExp(query, "i");
			
			//console.log(data);
			$.each(data, function(i, object){
				if(substrRegex.test(object.name)){
					//console.log(i);
					//console.log(object.name);
					map[object.name] = object;
					objects.push({name : object.name});
				}
			});
			//console.log(map);
			//console.log(objects);
			
			//process the matches
			process(objects);
		};
	};
		//console.log(sites);
	for(var i = 0; i < inputs.length; i++)
	{
		var input = inputs[i];
				
		input.typeahead({
			hint: true, 
			highlight: true, 
			minLength: 1
		},
		{
			name: "data",
			displayKey: "name",
			source: source(dataset),
			updater: function(item){
				console.log(item);
				//console.log(map[item]);
			}
		});
	
	}
	
	//set a data-hidden attribute to the input box to use this
	$(".typeahead").on("typeahead:selected typeahead:autocompleted", function(e,item) {
		//find the object for this text
		var obj = map[item.name];
		var hidden = $($(this).attr("data-hidden"));
		//set the value of the hidden field to this id
		hidden.val(obj.id); 
		$(this).blur();
	});
	$(".typeahead").keyup(function(){
		var hidden = $($(this).attr("data-hidden"));
		
		hidden.val("");
	});	
}


/**
 * searches for a site name
 * jquery obj textInput : the input the user is typing into
 * jquery obj intInput : the hidden input to put the reutrned id into
 * 
**/
function searchSiteName(textInput, intInput)
{

	var val = textInput.val();
	//clear the int input
	intInput.val("null");
	if(val.length > 2)
	{
		var data = new FormData();
		data.append("function", "searchSiteName");
		data.append("search", val);
		data.append("timestamp", new Date().getTime()/1000);
		
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST",
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				if(d.siteId != null)
				{
					textInput.val(d.siteName);
					intInput.val(d.siteId);
					textInput.selectRange(val.length, d.siteName.length);
				}
				else
				{
					intInput.val("null");
				}
			}
			else
			{
				//error;
			}
		});		
	}
}

/**
 * User clicks the save button
 * takes all the inputs and validates them
 * If they are valid sends them to the save function
 */
function clickSaveSite(siteName)
{
	siteName = siteName.val();
			
	//validate
	validate("site", $("#siteInfo input"), function(errs){
		if(Object.keys(errs).length > 0)
		{
			for(name in errs)
			{
				//addclass has-error
				var msg = errs[name];
				var str = "";
				for(m in msg)
				{
					str+= msg[m]+" <br/>";
				}
				
				$("[name='"+name+"']").after("<div class='alert alert-danger input-alerts'>"+str+"<div>");				
			}
		}
		else
		{
			var c = confirm("Are you sure you want to save site "+siteName)
			if(c)
			{
				//continue to save
				saveSite();
			}
		}
	});	
}


/**
 * Saves the site
 * takes all the inputs, sends them to the php
 * On success sends to the siteCenter
 * On fail shows errors
 */
function saveSite()
{
	var data = new FormData();
	data.append("function","registerSite");
	data.append("timestamp", new Date().getTime()/1000);
	
	//get all inputs, and the active radio button
	$("#siteInfo input:not(:radio), #siteInfo label.btn.active :radio, #siteInfo select").each(function(){
		var name = $(this).attr("name");
		var val = $(this).val();
		
		data.append(name, val);
		console.log(name+": "+val);
	});
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{
			location.href="/sites/";
		}
		else
		{
			//errrorrs
			$("#alert-row").html("<div class='alert alert-block alert-danger text-center'>"+d.msg+"</div>");
		}
	});
}

/**
 * Delete a site
 * prompts the user to delete and shows a success or error
 * if successful removes them from the table
 */
function deleteSite(id)
{
	var name = $("[data-site='"+id+"'] .siteName").text();
	var c = confirm("Are you sure you want to delete site "+name);
	if(c)
	{
		var data = new FormData();
		data.append("function","deleteSites");
		data.append("timestamp", new Date().getTime()/1000);
		data.append("siteIds", JSON.stringify([id]));
		//delete site
		$.ajax({
			url: path+"include/function.php",
			data: data,
			type: "POST",
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			console.log(d);
			
			if(d.success)
			{
				//show success
				$("#alert-row").html("<div class='alert alert-success alert-block text-center'>Site Deleted</div>");
				//removeRow
				$("[data-site='"+d.deleted[0]+"']").remove();
			}
			else
			{
				//show error
				console.log(d.msg);
				$("#alert-row").html("<span class='alert alert-error alert-block text-center'>"+d.msg+"</span>");
				
			}
			
			alertTimeout($(".alert"), 3000);
		});
	}
	else
	{
		//do nothing
	}
	
}

/**
 * User cancels site save
 * prompt them to cancel and return to site control
 */
function clickCancelSaveSite(nameInput)
{
	var name = nameInput.val();
	var c = confirm("Are you sure you want to cancel your changes for "+name);
	if(c)
	{
		location.href="/sites/";
	}
}

/**
 * Updates the group member
 * Changes their buttons to suit where they are 
 * int userId : user being changed
 * int currentGroup : the group they're in
 * int newGroup : the group to change them to
 * int selectedGroup : the group that is on this page
 */
function updateGroupMember(userId, currentGroup, newGroup, selectedGroup)
{
	//confirm it first
	var str = "Are you sure you want to ";
	if(newGroup == selectedGroup)
	{
		str += "add this person to this team?";
	}
	else
	{
		str += "remove this person from this team?";
	}
	//if(confirm(str))
	//{	
		var data = new FormData();
		data.append("function", "updateGroupMember");
		data.append("timestamp", new Date().getTime()/1000);
		data.append("userId", userId);
		data.append("newGroup", newGroup);
		data.append("currentGroup", currentGroup);
		
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST",
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);	
			console.log(d);
			if(d.success)
			{
				//find the member count
				var count = $(".memberCount").attr("data-count");
				$("#alert-row").html("<div class='alert alert-success alert-block text-center'>Group Members updated</div>");
				//if members has been added to this group change buttons to remove style
				var currBtn = "btn-success";
				var newBtn = "btn-danger";
				var currIcon = "glyphicon-plus";
				var newIcon = "glyphicon-minus";
				var func = "updateGroupMember("+userId+", "+newGroup+", "+d.defaultId+", "+newGroup+")";
				//var disabled = "";
				
				if(newGroup != selectedGroup) //if the new group is the selected group
				{					
					//remove from this group
					
					//remove from count
					count--;
					//change it to an add button
					currBtn = "btn-danger";
					newBtn = "btn-success";
					currIcon = "glyphicon-minus";
					newIcon = "glyphicon-plus";
					func = "updateGroupMember("+userId+", "+newGroup+", "+selectedGroup+", "+selectedGroup+")";					
				}
				else if(d.defaultId == selectedGroup) //if new group is not selected and is default
				{
					//add to count
					count++;
					func = "";
					//disabled = "disabled";
					$("[data-user='"+userId+"'] .updateBtn").attr("disabled", "");
				}
				else //new group is not selected and is not default
				{
					//add to count
					count++;
				}
				
				//console.log("disabled "+disabled);
				
				//re-run the search? 
				//just update this person?
				$("[data-user='"+userId+"'] .currentGroup").text(d.groupName);
				//change the button graphic and the function
				$("[data-user='"+userId+"'] .updateBtn").removeClass(currBtn).addClass(newBtn).attr("onClick", func);
				//change the icon
				$("[data-user='"+userId+"'] .updateBtn .glyphicon").removeClass(currIcon).addClass(newIcon);
				
				//update count numbers
				$(".memberCount").attr("data-count",count);
				var members = " members";
				if(count == 1)
				{
					members = " member";
				}
				$(".memberCount").text(count+members);
				
				alertTimeout($("#alert-row .alert"), 3000);
			}
			else
			{
				$("#alert-row").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
			}				
		});
//	}
	
}

/** 
 * User clicks the save permissions button
 * Saves all the permissions for each page
 */
function savePermissions(groupId)
{
	var groupName = $("#groupName").val();
	if(groupName != "")
	{
		str = "Are you sure you want to save changes to the permissions "+groupName+"?";
		var regexp = new RegExp("^"+$(".header.groupName").text()+"$");
		//If new name and old name don't match change the confirmation details
		if(!groupName.match(regexp))
		{
			str ="Are you sure you want to save all your changes to the details of "+groupName+"?";
		}
		var c = confirm(str);
		if(c)
		{
			//validate the groupName 
			validate("permissions", $("#groupName"), function(errs){
				//if errs
				if(Object.keys(errs).length > 0)
				{
					for(name in errs)
					{
						//addclass has-error
						var msg = errs[name];
						var str = "";
						for(m in msg)
						{
							str+= msg[m]+" <br/>";
						}
						
						$("[name='"+name+"']").after("<div class='alert alert-danger input-alerts'>"+str+"<div>");				
					}
				}
				else
				{
					
					//else
					console.log(groupId);
									
					var pages = Array();
				
					$("[data-page]").each(function(){
						console.log($(this).find("[name='canView']"));
						var pageId = $(this).attr("data-page");
					
						var canView = $(this).find("[name='canView']").prop("checked");
						var canEdit = $(this).find("[name='canEdit']").prop("checked");
						var canDelete = $(this).find("[name='canDelete']").prop("checked");
						var canViewRestricted = $(this).find("[name='canViewRes']").prop("checked");
						
						pages.push({
							"pageId" : pageId, 
							"canView" : canView,
							"canEdit" : canEdit, 
							"canDelete" : canDelete, 
							"canViewRestricted" : canViewRestricted
						});
					});
					
					var data = new FormData();
					data.append("function", "savePermissions");
					//if group id exists send it, otherwise set to null
					(groupId != undefined) ?data.append("groupId", groupId) :false;
					data.append("groupName", groupName);
					data.append("timestamp", new Date().getTime()/1000);
					data.append("pages", JSON.stringify(pages));
					
					$.ajax({
						url:path+"include/function.php",
						data: data, 
						type: "POST",
						contentType: false, 
						processData: false
					}).done(function(d){
						d = JSON.parse(d);
						console.log(d);
						if(d.success)
						{
							//success
							
							//return to previous page
							location.href="/permissions/";
						}
						else
						{
							$("#alert-error").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
							//an error happened
						}
					});
				}
			});	
		}
	}
	else
	{
		$("[name='groupName']").after("<div class='alert alert-danger input-alerts'>Group Name can not be blank<div>");				
		alertTimeout($("[name='groupName']"), 3000);
	}
}

function cancelPermissions()
{
	var name = $(".groupName").text();
	(confirm("Are you sure you want to cancel all your changes to "+name)) ? location.href="/permissions/" : false;
}

function removePermissionGroup(groupId)
{
	console.log("remove "+groupId);
	var groupName = $("[data-groupId="+groupId+"] .groupName").text();
	var c = confirm("Are you sure you want to remove the group "+groupName);
	var data = new FormData();
	data.append("function", "removePermissionGroup");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("groupId", groupId);
	if(c)
	{
		$.ajax({
			url: path+"include/function.php",
			type: "POST",
			data: data,
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			console.log(d);
			var msg = "";
			if(d.success)
			{
				getPermissionsGroups();
				msg = groupName+" Removed";
			}
			else
			{
				msg = d.msg;
				
			}
			
			$("#alert-row").html("<div class='alert alert-block alert-danger text-center'>"+msg+"</div>");
			alertTimeout($(".alert"), 3000);
		});
	}
	else
	{
	
	}
}

/**
 * Sets the group dropdown
 * selector is the selector of the div that is being occupied by this dropdown
**/
function setGroupsDropdown(selector)
{
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findGroups");
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{		
			var groupSelect = "";
			var disabled = "";
			if(parseInt(permissions.canEdit) == false)
			{
				//add row as non editable
				disabled = "disabled";					
			}			
			
			groupSelect += "<select class='form-control' id='permissionsGroupSelect' name='permissionGroup' "+disabled+">";
			for(var i = 0; i < d.groups.length; i++)
			{
				var group = d.groups[i];
				groupSelect += "<option value='"+group.groupId+"'>"+group.groupName+"</option>";
			}
			
			groupSelect += "</select>";
			
			$(selector).html(groupSelect);
		}
		else
		{
			//there was an error
			console.log(d.msg);
		}
	});

}

/** 
 *Creates the dropdown for the dashboard
 *Only show if there is an entity not equal to the one they are logged in as
 */
function getDashboardEntityDropdown(d)
{
	//empty the search bar
	//var search = $("#search").val("");
	
	//run checkinQuery
	// var data = new FormData();
	// data.append("function", "findCheckins");
	// data.append("timestamp", new Date().getTime()/1000);
	// data.append("search", $("#search").val());
	
	// $.ajax({
		// url: path+"include/function.php",
		// data: data, 
		// type: "POST", 
		// processData: false, 
		// contentType: false
	// }).done(function(d){
		// d = JSON.parse(d);
		// if(d.success)
		// {
			var currEntity = d.entityId;
			var entityList = "<option value=''>All entities</option>"
							+"<option value='"+currEntity+"'>Current Entity</option>";
			for(var i = 0; i < d.checkinData.length; i++)
			{
				var userEntityId = d.checkinData[i].userEntityId;
				var userEntityName = d.checkinData[i].userEntityName;
				var siteEntityId = d.checkinData[i].siteEntityId;
				var siteEntityName = d.checkinData[i].siteEntityName;
				if(currEntity != userEntityId)
				{
					//add to the dropdown
					entityList += "<option value='"+userEntityId+"'>"+userEntityName+"</option>";
					
					$(".dashDropdown").removeClass("hidden");
					//unhide the dropdown
				}
				else if(currEntity != siteEntityId && siteEntityId != null)
				{
					//add to the dropdown
					entityList += "<option value='"+siteEntityId+"'>"+siteEntityName+"</option>";
					
					$(".dashDropdown").removeClass("hidden");
					//unhide the dropdown
				}
				
				$("#entityChoice").html(entityList);
			}
		// }
	// });
}

/**
 * Ads a leader/follower connection
 */
function addFollower(leaderId, followerId, entityId)
{
	var data = new FormData();
	data.append("function", "addFollowers");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("leaderId", leaderId);
	data.append("followerIds", JSON.stringify([followerId]));
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data,
		processData: false, 
		contentType: false
	}).done(function(d){
		d = JSON.parse(d);
		//if success draw table again
		if(d.success)
		{
			searchTeamMembers($("#search"), leaderId);
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-error alert-block text-center'>"+d.msg+"</div>");
		}
	});
}

/**
 * Remove a leader/follower entry
 */
function removeFollower(leaderId, followerId, entityId)
{
	var data = new FormData();
	data.append("function", "removeFollowers");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("leaderId", leaderId);
	data.append("followerIds", JSON.stringify([followerId]));
	data.append("entityId", entityId);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data:data, 
		processData: false, 
		contentType: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		//if success draw table again
		if(d.success)
		{
			searchTeamMembers($("#search"), leaderId);
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-error alert-block text-center'>"+d.msg+"</div>");
		}
	});
}

/**
 * Sets the entity admin
 * gets the data for the typaheads and initiates them
 */
function setEntityAdmin()
{
    var data = new FormData();
    data.append("timestamp", new Date().getTime()/1000);
    data.append("function", "findUsers");
    data.append("accessOnly", true);

    $.ajax({
        url: path+"include/function.php",
        type: "POST",
        data: data,
        contentType: false,
        processData: false
    }).done(function(d){
        d = JSON.parse(d);
        if(d.success)
        {
            var users = [];
            for(var i = 0; i < d.users.length; i++)
            {
                users.push({name: d.users[i].firstName+" "+d.users[i].lastName, id: d.users[i].userId});
                var user = d.users[i];
            }

            var inputs = [
                $("#entityManager.typeahead"),
                $("#accountManager.typeahead"),
                $("#entityAdmin.typeahead")
            ];
            setTypeAhead(users, inputs);
        }
        else
        {
            //error getting admins
        }
    });
}

/**
 * Sets the entity managers
 * gets the data for the typaheads and initiates them
 */
function setEntityManagers()
{
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findUsers");
	data.append("accessOnly", true);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);			
		if(d.success)
		{
			var users = [];
			for(var i = 0; i < d.users.length; i++)
			{
				users.push({name: d.users[i].firstName+" "+d.users[i].lastName, id: d.users[i].userId});
				var user = d.users[i];
			}
			
			var inputs = [
				$("#entityManager.typeahead"),
				$("#accountManager.typeahead"),
                $("#entityAdmin.typeahead")
			];
			setTypeAhead(users, inputs);
		}
		else
		{
			//error getting admins
		}
	});
}


/**
 *Sets the slider for the notification settings
 *Ensures that each notification handle doesn't go out of the required range
 */
function setEntitySettingSliders()
{
	var colours = ["bg-success", "bg-info", "bg-primary", "bg-warning", "bg-danger"];
	var tips = ["To User", "To Direct Team Leader", "To Site Safety Manager", "To Entity Safety Manager", "To Entity Administrator"];

	/**
	 * Funtion to complete on stop
	 * Checks for the current value and the handles around it and moves the handle accordingly
	 */
	var doUpdate = function(event, ui)
	{
		//get the handle id
		var handleId = $(ui.handle).attr("data-handle");
		var handleVal = ui.value;
		var newVal = handleVal;
		//if its not the first handle
		if(handleId > 0)
		{				
			var prevVal = ui.values[handleId-1];
			//if the new value is less that the previous handle
			if(prevVal >= handleVal)
			{
				//move the handle forward
				newVal = prevVal+0.5;
			}
		}
		
		//if its not the last handle
		if(handleId < ui.values.length - 1)
		{
			var nextVal = ui.values[1+parseInt(handleId)];				
			//if the new values is more then the next handle
			if(nextVal <= handleVal)
			{
				//move the handle back
				newVal = nextVal - 0.5;
			}
		}
		
		//update the input
		$(".noteInput[data-handle='"+handleId+"']").val(newVal);
		//remove highlight
		$("input[data-handle='"+handleId+"']").parent().removeClass("has-success");
		//update the handle position
		$("#slider").slider("values", handleId, newVal);
	}
	
	/**
	 * Sets the input boxes on slide
	 */
	function setSliderInputVals(event, ui)
	{
		var handle = $(ui.handle).attr("data-handle");
		var val = ui.value;
		
		$(".noteInput[data-handle='"+handle+"']").val(val);
	}
	
	/**
	 * On slider start
	 */
	function startSlider(event, ui)
	{
		//highlight the input that corresponds with this handle
		var handle = $(ui.handle).attr("data-handle");
		$("input[data-handle='"+handle+"']").parent().addClass("has-success");
	}
	
	$("#slider").slider({
		start: startSlider,
		stop: doUpdate,
		slide: setSliderInputVals,
		min: 0.5,
		max: 10, 
		step: 0.5,
		values: [2, 2.5, 3, 3.5, 4]
	});
	
	$("#slider span").each(function(i){
		//add data-handle attr
		$(this).attr("data-handle", i).css("background", "").addClass(colours[i]);
		//add tooltip
		$(this).attr("data-toggle", "tooltip").attr("data-placement", "top").attr("title",tips[i]);
		$("[data-toggle='tooltip']").tooltip();
		
		$(".slider-labels label[data-handle='"+i+"']").text(tips[i]);
	});
}

/**
 * Sets all the data for the entity settings page 
 */
function setEntitySettings()
{		
	setEntitySettingSliders();
	setEntityManagers();
    //setEntityAdmin();
	
	//set the google autocomplete for head office?
    $("#setAccountManager").change(function(){
		if($(this).prop("checked") == true)
		{
			$("#accountManager").prop("disabled", false);
		}
		else
		{				
			$("#accountManager").prop("disabled", true);
			$("#accountManager").val("");
			$("#accountManagerId").val("");
		}
	});
	$("#setEntityManager").change(function(){			
		if($(this).prop("checked") == true)
		{
			$("#entityManager").prop("disabled", false);
		}
		else
		{				
			$("#entityManager").prop("disabled", true);
			$("#entityManager").val("");
			$("#entityManagerId").val("");
		}
	});
	
	new google.maps.places.Autocomplete(
		document.getElementById("headOffice"), {
			types: ["geocode"]
	});
			
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "getEntitySettings");
	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false,
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		//console.log(d);
		if(d.success)
		{
			var permissions = d.permissions;
			
			var setting = d.settings; console.log(setting);
			$("#entityName").val(setting.name);
			$("#headOffice").val(setting.headOffice); 
			$("#emergency").val(setting.emergencyNumber);
			$("#entityCode").val(setting.code);
			$("#note1").val(setting.notify1); //set textBox
			$("#note2").val(setting.notify2); //set textBox
			$("#note3").val(setting.notify3); //set textBox
			$("#note4").val(setting.notify4); //set textBox
			$("#note5").val(setting.notify5); //set textBox
			
			//set the slider
			var notifyArr = [setting.notify1,setting.notify2,setting.notify3,setting.notify4,setting.notify5];
			$("#slider").slider("values", notifyArr);

            //set the managers
            if(setting.adminId != null)
            {
                $("#entityAdmin").val(setting.adminFirstName+" "+setting.adminLastName);
                $("#adminId").val(setting.adminId);
            }
            //set the managers
			if(setting.safetyAccountManager != null)
			{
				//click the checkbox
				$("#setAccountManager").prop("checked", true);
				//undisable the input
				$("#accountManager").prop("disabled", false);
				$("#accountManager").val(setting.accountFirstName+" "+setting.accountLastName);
				$("#accountManagerId").val(setting.safetyAccountManager);
			}
			if(setting.safetyEntityManager != null)
			{			
				//click the checkbox
				$("#setEntityManager").prop("checked", true);
				//undisable the input
				$("#entityManager").prop("disabled", false);
				$("#entityManager").val(setting.entityFirstName+" "+setting.entityLastName);
				$("#entityManagerId").val(setting.safetyEntityManager);
			}
			
			
			//package
			$("#packageName").val(setting.pkgName);
			if(setting.autoUpgrade == true)
			{
				$("#autoUpgrade").prop("checked", true);
			}
			if(setting.special == true)
			{
				$("#special").prop("checked", true);
			}
			
			//if they don't have permissions to edit make all editable field uneditable
			if(permissions.canEdit == false)
			{
				$("input").prop("disabled", true);
				$("#generateCodeBtn").addClass("hidden").attr("onclick", "");
				$("#slider").slider("disable");
			}
		}
		else
		{
			//error	
		}
	});
}

/**
 * User clicks the cancel button on the entity screen
 */
function clickCancelSaveEntity()
{
	var c = confirm("Are you sure you want to cancel changes to the Entity Settings?");
	if(c)
	{
		location.href="entity";
	}	
}

/** 
 * User clicks the save entity button
 */
function clickSaveEntity(entityId, first)
{
	/*var c = confirm("Are you sure you want to update the Entity Settings?");
	if(c)
	{*/
		var elem = $("input");
		validate("entitySettings", elem, function(errs){
			if(Object.keys(errs).length > 0)
			{
				for(name in errs)
				{
					//addclass has-error
					var msg = errs[name];
					var str = "";
					for(m in msg)
					{
						str+= msg[m]+" <br/>";
					}
					
					$("[name='"+name+"']").after("<div class='alert alert-danger input-alerts'>"+str+"<div>");				
				}
			}
			else
			{
				var data = new FormData();
				data.append("function", "saveEntitySettings");
				data.append("timestamp", new Date().getTime()/1000);
			
				elem.each(function(){
					var name = $(this).attr("name");
					var val = $(this).val();
					
					if($(this).attr("type") == "checkbox")
					{
						val = $(this).prop("checked");							
					}
					
					data.append(name, val);
				
					console.log(name);
					console.log(val);
				});
				
				$.ajax({
					url: path+"include/function.php",
					type: "POST",
					data: data, 
					contentType: false, 
					processData: false
				}).done(function(d){
					console.log(d);
					d = JSON.parse(d);
					if(d.success)
					{
                        if(first == true){
                            //entity updated
                            location.href="/plans";
							//location.href="/plans/"+entityId;
							//location.href="/send-invites/"+entityId;
                        }else{
                            //entity updated
							location.href="/plans";
							//location.href="/entity/";
                        }
					}
					else
					{
						$("#alert-row").html("<div class='alert alert-danger text-center'>"+d.msg+"</div>");
						alertTimeout($("#alert-row .alert"), 3000);
					}
				});
				
			}
		});	
	//}
}


/**
 *Generates a new entity code and displays it
 */
function generateEntityCode(btn)
{	
	var c = confirm("Are you sure you want to generate a new Entity Code? This will write over the current one");
	if(c)
	{
		var data = new FormData();
		data.append("timestamp", new Date().getTime()/1000);
		data.append("timezone", jstz.determine().name());
		data.append("function", "generateEntityCode");
		
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST", 
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			
			if(d.success) //code updated successfully
			{
				//var code = d.code;
				$("#entityCode").val(d.code);
				//show green banner
				$(btn).removeClass("btn-default").addClass("btn-success");
				setTimeout(function(){
					$(btn).removeClass("btn-success").addClass("btn-default");
				}, 2000);
			}
			else
			{
				//something happened
			}
		});
	}
	else
	{
		//cancelled
	}
}


/***
* This searches for the company code and the entered company name and 
* autocompletes if there is a correct answer. It also populates the entity id
* if one is found.
*
* String input : id of input that user is typing into
* String idinput : id of the input used to store the entityid 
* String code : the code the user has entered
* String entity : the string the user has currently entered for the company name
**/
function searchCode(input, idinput, code, entity)
{	
	var data = new FormData();
	data.append("function", "searchCode");
	data.append("code",code);
	data.append("entity",entity);
	//if ajaxObj has a request, stop it
	ajaxObj = $.ajax({
		url: path+"include/function.php",
		type: "POST",
		data: data,
		contentType: false, 
		processData: false					
	}).done(function(d){
		var arr = JSON.parse(d);
		if(arr.id != null)
		{
			$(input).val(arr.name);
			$(idinput).val(arr.id)
			$(input).selectRange(entity.length, arr.name.length);
		}
		else
		{
			$(idinput).val('null');
		}		
	});
}

/**
* Select a text input range and highlight it
*
* int start : start position
* int end : end position
*/
$.fn.selectRange = function(start, end)
{
	if(!end) 
	{
		end = start;
	}
	
	return this.each(function(){
		if(this.setSelectionRange)
		{
			this.focus();
            this.setSelectionRange(start, end);
		}
		else if(this.createTextRange)
		{
			var range = input.createTextRange();
			range.collapse(true);
			range.moveEnd("character", end);
			range.moveStart("character", start);
			range.select();
		}
	});
}




/**
* Show alert box and log user out
* Removes their sessions data if they accept
*/
function logout()
{
	var x = confirm("Are you sure you want to log out of SafetyBeat?");
	if(x == true)
	{	
		var fd = new FormData();
		fd.append("function", "logout");
		$.ajax({
			url: path+"include/function.php",
			data: fd,
			type: "POST",
			processData: false,
			contentType: false			
		}).done(function(d){
			//d = JSON.parse(d);
			//console.log(d);
			location.href="/";
		});
	}
}

function getCookie(cname)
{
	var name = cname +="=";
	var ca = document.cookie.split(";");
	for(var i = 0; i < ca.length; i++)
	{
		var c = ca[i];
		while(c.charAt(0) == " ")
		{
			c = c.substring(1);
		}
		//if name exists in arr
		if(c.indexOf(name) != -1)
		{
			//encode the string and replace "+" with " "
			var encoded = c.substring(name.length, c.length).replace(/\+/g, "%20");
			//return the value decoded
			return decodeURIComponent(encoded);
		}
	}
	return "";
}

/**
 *Takes the current url and splits it at the /
 * Returns an array with the url parts
 */
function getURL()
{
	var full = document.URL;
	var arr = full.split("/");
	return arr;
}


function formatDate(date, format)
{
	var d = "";
	if(format == "dd-mm-yyyy")
	{
		d = date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
	}
	if(format == "dd-mm-yy")
	{
		d = date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear().substr(2);
	}
	return d;
}

/**
 * Takes a timestamp and returns it formatted to the designated format
 * int timestamp : timestamp to convert
 * str format : the way to format
 */
function timestampToDate(timestamp, format)
{
	var d = "";
	var date = new Date(timestamp*1000);
	if(format == "d-m-Y")
	{
		d = addZero(date.getDate())+"-"+addZero((date.getMonth()+1))+"-"+date.getFullYear();
	}
	else if(format == "d-m-Y h:i")
	{
		d = addZero(date.getDate())+"-"+addZero((date.getMonth()+1))+"-"+date.getFullYear()
			+" "+addZero(date.getHours())+":"+addZero(date.getMinutes());
	}
	else if(format == "d/m/Y")
	{
		d = addZero(date.getDate())+"/"+addZero((date.getMonth()+1))+"/"+date.getFullYear();
	}
	else
	{
		console.log("format doesn't exist "+format);
	}
	console.log(d);
	return d;
}

/**
 *Adds a zero to the front of an int
 */
function addZero(i)
{
	i = parseInt(i);
	if(i < 10)
	{
		i = "0"+i;
	}
	return i;
}

/**
 * sets a timeout for the alert
 * Jquery obj : alert
 * time : time to show it for
 */
function alertTimeout(alert, time)
{
	setTimeout(function(){
		alert.remove();
	}, time);
}

/**
 * Show the alert row with a message inside and the correct colouring
 */
function showAlertRow(elem, success, msg)
{
	var addClass = "alert-danger";
	var removeClass = "alert-success";
	if(success === true)
	{
		addClass = "alert-success";
		removeClass = "alert-danger";
	}
	
	elem.text(msg)
		.addClass(addClass)
		.removeClass(removeClass)
		.removeClass("hidden");
		
	hideAlertRow(elem, 3000);
}

/**
 * Hides the alert row after a certain amount of time
 */
function hideAlertRow(elem, time)
{
	setTimeout(function(){
		elem.addClass("hidden");
	}, time);
}

/**
 * Get the enetity access levels in order
 */
function entityAccessList()
{
	var list = [
		"Administrator",
		"Leader",
		"User"
	];
	return list;
}

/**
 *Gets the browsers location and performs a function on success or fail
 */
function getBrowserLocation(success, fail)
{
	if(navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(function(pos){
			success(pos);
		}, function(error){			
			console.log(error);
			var msg = geoError(error);
			fail(msg);
		});
	}
	else
	{
		var msg = geoError({"code":"BROWSER"});
		fail(msg);
	}
}

/**
 * The error codes for a naviagtor.geolocation error
 */
function geoError(error)
{
	var msg = "";
	switch(error.code)
	{
		case error.PERMISSION_DENIED:
			msg = "User denied the request for Geolocation.";
			break;
		case error.POSITION_UNAVAILABLE:
			msg = "Location information is unavailable.";
			break;
		case error.TIMEOUT:
			msg = "The request to get user location timed out.";
			break;
		case error.UNKNOWN_ERROR:
			msg = "An unknown error occurred.";
			break;
		case error.BROWSER:
			msg = "Browser doesn't support Geolocation.";
			break;
	}
	
	return msg;
}


function getSlug(slug, table, callback)
{	
	var data = new FormData();
	data.append("slug", slug);
	data.append("table", table);
	$.ajax({
		url: path+"include/slug.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			callback(d.id);
		}		
		else
		{
			//error
		}
	});	
}

function toEntitySettings(entityId)
{
	var data = new FormData();
	data.append("function", "changeEntity");
	data.append("entityId", entityId);
	data.append("timestamp", new Date().getTime()/1000);
	$.ajax({
		url: path+"include/function.php",
		type: "POST",
		data: data,
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			location.href="/entity-settings/";
		}
		else
		{
			$("#alert-row").html("<div class='alert alert-danger'>"+msg+"</div>");
			alertTimeout($("#alert-row .alert"), 3000);
		}
	});
}

/**
 *Clears the search input box on editMyTeam.php 
 *Runs the search and creates the table again
 */
function clearEditTeamSearch(userId)
{
	$("#search").val("");
	searchTeamMembers($("#search"), userId);
}
/**
 *Clears the search input box on dash.php
 *Runs the search and creates the dash again
 */
function clearDashSearch()
{
	$("#search").val("");
	getDashboard();
}

/**
 * Clears the search input box on userControl.php
 * Runs the search and creates the table again
 */
function clearUserTableSearch()
{
	$("#search").val("");
	createUserTable(null);
}
/**
 * Clears the search input box on siteControl.php
 * Runs the search and creates the table again
 */
function clearSiteTableSearch()
{
	$("#search").val("");
	createSiteTable(null);
}

/**
 * Clears the search input box on prontoData.php
 * Runs the search and creates the table again
 */
function clearProntoSearch()
{
	$("#search").val("");
	getProntoData();
}

/** 
 * Reusable function for clearing search bars
 * best used for simple table/searches 
 */
function clearSearch(input, callback)
{
	input.val("");
	callback();
}



/**
 * Manual refresh of pronto and google drive data
 * bool google : Refresh points list too? 
 * bool table : Refresh the prontoData table?
 */
function refreshPronto(google, table)
{
	//show loading modal
	$("#loadingModal").modal({
		keyboard: false, 
		backdrop: "static"
	}).modal("show");

	$.ajax({
		url: "http://safetybeat.com/cron/pronto.php"
	}).done(function(){
		console.log("pronto updated");
		
		//run the points list
		if(google)
		{
			$.ajax({
				url: "http://safetybeat.com/cron/pointsList.php"
			}).done(function(){
				console.log("google updated");
				//load the table
				if(table)
				{
					getProntoData();
					$("#loadingModal").modal("hide");
				}
			});
		}
		else
		{
			//load the table
			getProntoData();
			$("#loadingModal").modal("hide");
		}				
	
	});		
	
	
}


/**
 * Create the notification center and set the overall settings
 */
function setupNotificationcenter()
{
	var types = [{
		type: "alert",
		img: "fa fa-bullhorn",
		imgtype: "class",
		bgcolor: "#1E90FF", 
		color: "#FFFFFF"
	},
	{
		type: "warning",
		img: "glyphicon glyphicon-ok",
		imgtype: "class",
		bgcolor: "#F5DEB3", 
		color: "#000000"
	},
	{
		type: "danger",
		img: "glyphicon glyphicon-fire",
		imgtype: "class",
		bgcolor: "#EB5D49", 
		color: "#FFFFFF"
	}];
	
	var default_notifs = [{
			type: 'alert', // define the type
			values: [{
				text: {
					text: 'A Default',
					title: 'Testing some notes'
				},
				time: new Date().getTime()/1000,
				"new": true,
				callback: function(notif) {
					console.log(notif);
					//$.notificationcenter.alert(notif.text, notif.type);
				}
			}]
	}];


	//start the notification panel
	$("body").notificationcenter({
		body_element: "#wrapper",
		toggle_button: "#notificationcentericon",
		add_panel: true,
		notification_offset:50,
		display_time: 5000,
		types: types,
		type_max_display: 5,
		truncate_message: 0,
		header_output: '{icon} {type} {count}',
		counter: true,
		default_notifs: default_notifs,
		faye: false,
		ajax: false,
		ajax_checkTime: 5000,
		alert_hidden: true,
		alert_hidden_sound: "./sounds/Tink",
		store_callback: function(notifs){
			console.log(notifs);
		}
	});
}

/**
 * Sends back the userMarker img path depending on checkinType, userstatus, entity etc
 */
function createUserMarkerImg(checkinTypeId, status, isPulse, alt, little)
{
	var colour = "_grey";
	var action = "_checkout";	
	var other = "";
	var imgStr = path+"img/pin/pin"+action+colour+other+".png"; //default
	
	
	//CHECKIN TYPES
	//1 = CHECK IN
	//2 = REPORT IN
	//3 = CHECK OUT
	//14 = CHECK OUT
	//15 = CHECK OUT
	//4 - 13 = PULSE
	switch(parseInt(checkinTypeId))
	{
		case 1:
			action = "_checkin";				
			break;
		case 2:
			action = "_reportin";
			break;
		case 3:
		case 14:
		case 15:
			action = "_checkout";
			break;
		default:
			action = "_pulse";
			break;
	}
	
	//STATUS KEYS
	//0 - OUT AND FINISHED FOR THE DAY
	//1 - ACTIVE
	//2 - REMINDER OF LATE TO REPORT    (NOTIFY USER ONLY)
	//3 - AFTER WAITING TIME      (NOTIFY USER AND TEAM LEADER)
	//4 - AFTER TEAM LEADER NOTIFIED    (NOTIFY USER AND SAFETY ENTITY)
	//5 - AFTER SAFETY ENTITY NOTIFIED   (NOTIFY USER AND SAFETY ACCOUNT)
	//6 - AFTER SAFETY ACCOUNT NOTIFIED  (NOTIFY USER AND ADMIN)
	//7 - USER SAFE, PENDING REPORT    (NOTIFY USER ONLY)
	//8 - USER PULSE, PENDING REPORT    (NOTIFY USER ONLY WITH OPTION TO SAY HE FINISH WORKING)
	switch(parseInt(status))
	{
		case 1:
		case 2:
		case 7:
		case 8:
			colour = "_green";
			break;
		case 0:
			colour = "_grey";
			break;
		case 3:
		case 4:		
			colour = "_yellow";
			break;
		case 5:
		case 6: 
			colour = "_red";
			break;
		default:
			colour = "_grey";
			break;
	}
	
	if(alt == true) //alternate entity
	{
		other = "_other";	
	}
	
	var lit = "";
	if(little && little == true)
	{
		lit = "little/";
	}
	imgStr = path+"img/pin/"+lit+"pin"+action+colour+other+".png"; //updated
	
	
	return imgStr;
}

//// SITE EMERGENCY CONTACTS ////
/**
 * Use the current lat/lng and radius to find the closest emergency services
 * Display these and allow the user to scroll through them and choose the ones they 
 * want to save
 */
function refreshEmergencyNumbers()
{
	var siteLat = $("#lat").val();
	var siteLng = $("#lng").val();
	var radius = $("#radius").val();
	var resultList = [];
	
	//get all types of emergency numbers from db
	var em = new FormData();
	em.append("function","getEmergencyTypes");
	em.append("timestamp", new Date().getTime()/1000);
	em.append("refTypeId", 2);
	$.ajax({
		url: path+"include/function.php",
		data: em, 
		type: "POST", 
		contentType: false, 
		processData: false
	}).done(function(d){
		var emergencyTypes = JSON.parse(d);
		var types = [];
		for(var i = 0; i < emergencyTypes.length; i++)
		{
			if(emergencyTypes[i].googleName != "" && emergencyTypes[i].googleName != null)
			{
				types.push(emergencyTypes[i].googleName);
			}
		}
	
		if(siteLat != 0 && siteLng != 0)
		{
			var request = {
				location: new google.maps.LatLng(siteLat, siteLng),
				radius: Math.ceil(parseInt(radius)+800), 
				types: types,
				rankby: "distance"
			};
		
			//use this lat/lng to find google places
			var service = new google.maps.places.PlacesService(document.getElementById("mapsResponse"));
			service.nearbySearch(request, function(results, status){
				
				if(status == google.maps.places.PlacesServiceStatus.OK){
					console.log(status);		
					//show results and set the back and forward buttons
					
					//if less then 10 results show them and set the data number to this
					if(results.length < 10)
					{
						displayEmergencyResults(0, results.length-1, results, emergencyTypes);
					}
					else //else show, set data number to 9 and enable the foward button
					{
						displayEmergencyResults(0, 9, results, emergencyTypes);						
						$("#forwardEmergency").prop("disabled", false);
					}
					//set the data-number
					$("#mapsResponse").attr("data-page", 1);
					
					//set the forward and back buttons depending on this result
					$("#forwardEmergency").click(function(){
						findEmergencyNumber('forward', results, emergencyTypes);
					});
					$("#backwardEmergency").click(function(){
						findEmergencyNumber('back', results, emergencyTypes);
					});
					
					//return the whole set
					return results;					
				}				
			});			
		}
	});	
}

/**
 * Show the results in the direction wanted
 */
function findEmergencyNumber(direction, e, emergencyTypes)
{
	var page = parseInt($("#mapsResponse").attr("data-page"));
	console.log("page "+page);
	if(direction == "forward")
	{
		page++;
	}
	else if(direction == "back")
	{
		page--;
	}
	var to = page*10-1;
	var from = to-9;
	
	
	displayEmergencyResults(from, to, e, emergencyTypes);	
	$("#mapsResponse").attr("data-page", page);			
	
	//set the buttons
	
	if(page == 1) //first page and ten results only
	{
		if(e.length-1 <= 10) //ten results only, all displayed
		{
			//keep both disabled
			$("#forwardEmergency").prop("disabled", true);
			$("#backwardEmergency").prop("disabled", true);
		}
		else //no back results
		{
			//show only forward
			$("#forwardEmergency").prop("disabled", false);
			$("#backwardEmergency").prop("disabled", true);
		}
	}
	else //any other page
	{			
		if(to < e.length-1) //there are forward and back results
		{
			$("#forwardEmergency").prop("disabled", false);
			$("#backwardEmergency").prop("disabled", false);
		}
		else //there are no further results, 
		{
			//disable the forward button
			$("#forwardEmergency").prop("disabled", true);
			$("#backwardEmergency").prop("disabled", false);
		}
	}		
}

/**
 * Launches and sets the emergency contact modal
 */
function launchEmergencyContactModal()
{
	//remove any alerts
	$("#addCustomEmergencyModal .alert").remove();
	
	//get emergencyType
	var emergencyType  = $("#customEmergencyContact .emergencySelect option:selected").text();
	var emergencyTypeId = $("#customEmergencyContact .emergencySelect").val();
	//get emergency name
	var emergencyName = $("#customEmergencyName").val();
	
	//add to fields in the modal
	$("#emergencyName").val(emergencyName);
	$("#emergencyType").text(emergencyType);
	$("#emergencyTypeId").val(emergencyTypeId);
	
	//open modal
	$("#addCustomEmergencyModal").modal();
}

/**
 * Displays the requested results in a table with the select box of emergency types
 * from - to will determine which results are shown
 */
function displayEmergencyResults(from, to, dataSet, emergencyTypes)
{
	//console.log(from+" - "+to);
	//remove other popovers
	$("[data-row]").popover("destroy");
	
	var popovers = [];
	//make select box for emergency type
	var emergencySelect = "<select class='emergencySelect form-control'>"
	for(em in emergencyTypes)
	{
		emergencySelect += "<option value='"+emergencyTypes[em].emergencyTypeId+"'>"+emergencyTypes[em].name+"</option>"
	}							
	emergencySelect += "</select>";
	
	
	var table = "<table class='table table-bordered'>"
				+ "<tr id='customEmergencyContact'>"
					+"<td class='col-xs-2'><button type='button' class='btn btn-success btn-block' onclick=\"launchEmergencyContactModal()\"><i class='glyphicon glyphicon-plus'></i></button></td>"
					+"<td class='col-xs-3'>"+emergencySelect+"</td>"
					+"<td><input type='text' class='form-control' id='customEmergencyName' placeholder='Emergency Name'></td>"
				+ "</tr>";
	//have one they can add their own
	if(dataSet.length == 0)
	{
		
	}
	else
	{
		for(var i = from; i <= to; i++)
		{
			//console.log(i);
			if(i < dataSet.length)
			{				
				//show in table
				var r = dataSet[i];
				
				//console.log(emergencyTypes);
				var emergencyTypeId = 0;
			
				//find the emergency type that is refers to and get the emergencyTypeId
				for(x = 0; x < r.types.length; x++)
				{
					var type = r.types[x];
					for(em in emergencyTypes)
					{
						console.log(emergencyTypes[em]);
						if(emergencyTypes[em].googleName == type)
						{
							emergencyTypeId = emergencyTypes[em].emergencyTypeId;
							break;
						}
					}
					break;
				}
				console.log("id "+emergencyTypeId);
				
				table+= "<tr data-popoverId='"+i+"' data-row='"+i+"'>"							
						+"<td class='col-xs-2'><button type='button' class='btn btn-success btn-block' onclick=\"addGoogleSiteEmergencyContact("+i+", '"+r.place_id+"', '"+encodeURI(r.name)+"')\"><i class='glyphicon glyphicon-plus'></i></button></td>"
						+"<td class='col-xs-3'>"+emergencySelect+"</td>"
						+"<td>"+r.name+"</td>"
						+"</tr>";
						
				var u = (typeof r.photos != "undefined") ? r.photos[0].getUrl({'maxWidth': 400, 'maxHeight': 400}) : "";
				var c = "<div class='row'>"
						+"<div class='col-xs-12'>"
							+"<p>"+r.vicinity+"</p>"
						+"</div>"
						+"</div>";
				if(u != "")
				{
					c += "<div class='row'>"
							+"<div class='col-xs-6 col-xs-offset-3'>"
								+"<img src='"+u+"' class='img-responsive img-thumbnail emergency-img'>"
							+"</div>"
						+"</div><br/>";
				}		
				
				popovers.push({
					"id" : i,
					"title" : r.name,
					"content" : c,
					"emergencyType" : emergencyTypeId
				});
			}
		}
	}
	table += "</table>";
	$("#mapsResponse").html(table);
	
	for(var i = 0; i < popovers.length; i++)
	{
		$("[data-row='"+popovers[i].id+"']").popover({
			container: "body",
			title: popovers[i].title,
			content: popovers[i].content,
			trigger: "hover",
			placement: "right",
			html: true
		});
		
		//set the emergency Type select box
		//console.log($("[data-row='"+popovers[i].id+"'] .emergencySelect"));
		$("[data-row='"+popovers[i].id+"'] .emergencySelect").val(popovers[i].emergencyType);
	}
	
}

/**
 *Adds the emergency contact based on the google detail response
 * int row_id : the id of the row to get the information
 * string place_id : google id associated with this place
 * string name : The name of this contact
 */
function addGoogleSiteEmergencyContact(row_id, place_id, name)
{
	//get the row id
	var siteId = $("#siteId").val();
	var emergencyType = $("[data-row='"+row_id+"'] .emergencySelect").val();
	
	var request = {
		placeId: place_id			
	}
	//get the google results and post to php 
	var service = new google.maps.places.PlacesService(document.getElementById("detailsResponse"));
	service.getDetails(request, function(place, status){
		if(status == google.maps.places.PlacesServiceStatus.OK)
		{
			var data = new FormData();
			data.append("function", "saveEmergencyContact");
			data.append("emergencyTypeId", emergencyType);
			data.append("refTypeId", 2);
			data.append("refId", siteId);
			data.append("phone", place.international_phone_number);
			data.append("address", place.formatted_address);
			data.append("name", place.name);
			data.append("timestamp", new Date().getTime()/1000);

			$.ajax({
				url: path+"include/function.php",
				data: data, 
				type: "POST",
				contentType: false, 
				processData: false
			}).done(function(d){
				d = JSON.parse(d);
				if(d.success) //saved
				{
					//move this row up and change the button to a remove button with the id form my db
					var newId = d.newId;
					setSiteEmergencyTable(siteId, $("#currentEmergency"), true);
					$("[data-row='"+row_id+"']").hide();
				}
			});				
		}
	});
	
	
}


/** 
 * When user clicks to add this contact validate the modal and either show alerts or add the data
 */
function addCustomSiteEmergencyContact()
{
	//go through all the inputs and collect the info 
	var siteId = $("#siteId").val();
				
	addCustomEmergencyContact(siteId, 2, function(success){
		if(success == true) //saved
		{		
			//reload emergency table
			setSiteEmergencyTable(siteId, $("#currentEmergency"), true);
			//close modal
			$("#addCustomEmergencyModal").modal("hide");					
			//clear inputs
			$("#addCustomEmergencyModal input").val("");
			$("#customEmergencyContact input").val("");
			$("#customEmergencyContact select").val(1);
		}
	});			
}

/** 
 * When user clicks to add this contact validate the modal and either show alerts or add the data
 */
function addCustomUserEmergencyContact()
{
	//go through all the inputs and collect the info 
	var userId = $("#userId").val();				
	addCustomEmergencyContact(userId, 1, function(success){
		if(success == true) //saved
		{		
			//reload emergency table
			setUserEmergencyTable(userId, $("#emergencyContacts"), true);
			//close modal
			$("#addCustomEmergencyModal").modal("hide");					
			//clear inputs
			$("#addCustomEmergencyModal input").val("");
			$("#customEmergencyContact input").val("");
			$("#customEmergencyContact select").val(2);
		}
	});			
}

//NEED ANOTHER FOR PROFILE (DO I USE NULL? OR ADD ONE FOR EACH ENTITY?)
function addCustomProfileEmergencyContact()
{
	
}

/** 
 * Generic function for custom contact. 
 * validates the info
 * Takes all the info and performs callback in $.ajax.done()
 */
function addCustomEmergencyContact(refId, refTypeId, callback)
{
	var name = $("#emergencyName").val();
	var emergencyTypeId = $("#customEmergencyContact .emergencySelect").val();
	var address = $("#emergencyAddress").val();
	var phone = $("#emergencyPhone").val();
	
	//confirm it
	//validate the inputs
	validate("customEmergencyContact", $("#addCustomEmergencyModal input"), function(errs){
		console.log(errs);
		if(Object.keys(errs).length > 0)
		{
			for(id in errs)
			{
				//addclass has-error
				var msg = errs[id];
				var str = "";
				for(m in msg)
				{
					str+= msg[m]+" <br/>";
				}
				
				$("#"+id).after("<div class='alert alert-danger input-alerts'>"+str+"<div>");				
			}
		}
		else
		{			
			var c = confirm("Are you sure you want to save emergency contact "+name)
			if(c)
			{
				//continue to save
				
	
				//save it to db
				var data = new FormData();
				data.append("function", "saveEmergencyContact");
				data.append("emergencyTypeId", emergencyTypeId);
				data.append("refTypeId", refTypeId);
				data.append("refId", refId);
				data.append("phone", phone);
				data.append("address", address);
				data.append("name", name);
				data.append("timestamp", new Date().getTime()/1000);
				data.append("entityId", false);
				
				$.ajax({
					url: path+"include/function.php",
					data: data, 
					type: "POST",
					contentType: false, 
					processData: false
				}).done(function(d){
					d = JSON.parse(d);
					callback(d.success);
				});	
			}
		}
	});
}

/**
 * Remove the emergency contact from the table and set the active status to 0
 */
function removeEmergencyContact(id, name, refId)
{
	//confirm delete
	var c = confirm("Are you sure you want to remove "+decodeURI(name)+" as an emergency contact?");
	if(c)
	{
		//set as active = 0 in database
		var data = new FormData();
		data.append("function", "removeEmergencyContact");
		data.append("emergencyId", id);
		data.append("timestamp", new Date().getTime()/1000);
		
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST", 
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				//remove row
				$("[data-emergencyId='"+id+"']").remove();
			}
		});
	}
	
}

/** 
 * Sets all the details for the user-map page including the control buttons
 */
function setUserMapPage(userId)
{
	var userData = new FormData();
	userData.append("timestamp", new Date().getTime()/1000);
	userData.append("function", "findUsers");
	userData.append("userId", userId);

	//get name
	$.ajax({
		url: path+"include/function.php",
		data: userData, 
		type: "POST", 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			var user = d.users[0];
			$(".page-header .userName").text(user.firstName+" "+user.lastName);
		}
	});
	
	//var moment = moment();
	
	var startTime = moment().startOf("day").format("X");
	var endTime = moment().endOf("day").format("X");
	var todayStr = moment().format("ddd DD/MM/YYYY");
	
	// var date = new Date();
	// var dayName = date.toString().slice(0,3);
	// var day = date.getDate();
	// var month = date.getMonth();
	// var year = date.getFullYear();
	
	//var startTime = new Date(year, month, day, 0, 0, 0, 0).getTime()/1000;
	//var endTime = new Date(year, month, day, 11,59,59,999).getTime()/1000;
	
	$("#buttonRow .btn-forward").prop("disabled", true);
	//initilaize with breadcrumb
	setDailyActivityTrail(userId, startTime, endTime, true);
	//var today = dayName+" "+addZero(day)+"/"+addZero(month+1)+"/"+year;
	$("#dateDisplay input").val(todayStr);
	//set the controls
	$("#dateDisplay input").datepicker({
		format: "D dd/mm/yyyy",
		todayBtn: true,
		endDate: todayStr		
	}).on("changeDate", function(e){
		STARTTIME = e.date.getTime()/1000;
		ENDTIME = STARTTIME+(23*59*59);
		
		//if its a breadcrumb
		if($(".pageHeader.siteContent").hasClass("hidden"))
		{
			//get the new date and set the map for this
			setDailyActivityTrail(userId, STARTTIME, ENDTIME, true);	
		}
		else
		{
			setDailyActivityTrail(userId, STARTTIME, ENDTIME, false);
		}
		
		//close date pickers
		$(this).datepicker("hide");
		
		//if endTime >= now disable forward button
		if(ENDTIME >= new Date().getTime()/1000)
		{
			$("#buttonRow .btn-forward").prop("disabled", true);
		}
		else
		{
			$("#buttonRow .btn-forward").prop("disabled", false);
		}
	});
	
	$("#buttonRow .btn-back").click(function(){
		//get the current date displyed
		var date = $("#dateDisplay input").datepicker("getDate");
		date.setDate(date.getDate()-1);
		//go one day back
		$("#dateDisplay input").datepicker("setDate", date);			
	});
	$("#buttonRow .btn-forward").click(function(){
		//get the current date displyed
		var date = $("#dateDisplay input").datepicker("getDate");
		date.setDate(date.getDate()+1);
		//go one day forward
		$("#dateDisplay input").datepicker("setDate", date);		
	});
	
}

/**
 * Toggles the user map page between the breadcrumb trail and the site trail
 */
function toggleSiteMap(userId)
{
	if($(".pageHeader.siteContent").hasClass("hidden") == true){
		//breadcrumb is showing
		//show site
		//hide breadcrumb
		$(".breadcrumbContent").addClass("hidden");
		$(".siteContent").removeClass("hidden");
	
		//run function for dailyActivity
		startTime = $("#dateDisplay input").datepicker("getDate").getTime()/1000;
		endTime = startTime+(23*59*59);		
		
		//get the new date and set the map for this
		setDailyActivityTrail(userId, startTime, endTime, false);	
	
		
	}
	else
	{
		$(".siteContent").addClass("hidden");
		$(".breadcrumbContent").removeClass("hidden");
		
		startTime = $("#dateDisplay input").datepicker("getDate").getTime()/1000;
		endTime = startTime+(23*59*59);		
		//run function for dailyactivity
		setDailyActivityTrail(userId, startTime, endTime, true);
	}
}

/**
 * Load the connection control page
 * Set the carousel and the slide event
 */
function loadConnectionControlPage(userId, reqUserId)
{
	//get name of connection
	
	//load the carousel in a paused state
	$("#connectionCarousel").carousel({"interval":false});	
	setCurrentConnectionTable(reqUserId);		
	
	//set the onNext for the carousel
	$("#connectionCarousel").on("slide.bs.carousel", function(){
		var itemId = $($(this).find(".active")).attr("id"); 
		if(itemId == "currentConnections")
		{
			setAddConnectionTable(userId, reqUserId);
			$(".carouselControls button").html("<i class='glyphicon glyphicon-link'></i>&nbsp;Current Connections");
			$(".carouselControls .carouselHeader").text("Add New Connections");
		}
		else if(itemId == "addConnections")
		{
			setCurrentConnectionTable(reqUserId);
			$(".carouselControls button").html("<i class='glyphicon glyphicon-plus'></i>&nbsp;Add Connections");
			$(".carouselControls .carouselHeader").text("Current Connections");
		}
	});
		
}

/**
 * Load the connection modal with the correct buttons
 */
function loadConnectionModal(requestById,requestToId, name)
{	
	//find all the connections for these two users
	var data = new FormData();
	data.append("function", "getConnectionsByUsers");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("requestBy", requestById);
	data.append("requestTo", requestToId);
	$.ajax({
		url : path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{
			//requestTo...
			var isLeader = false;
			var isFollower = false;
			var requestByAccess = d.requestByAccess;
			var requestToAccess = d.requestToAccess;
							
			//dont know if I need to have this extra. Only if I want to do the change buttons
			for(var i = 0; i < d.connections.length; i++)
			{
				var connection = d.connections[i];
				if(requestTo == connection.leaderId)
				{
					isLeader = true;
				}
				else if(requestTo == connection.followerId)
				{
					isFollower = true;
				}
			}
			
			//if isLeader && pending = 2
			//if isFollower && pending = 2
				//show these buttons with pending ?
			
			
			
			//draw the modal contents using the users accessLevels
			//draw the buttons
			var memberBtn = "<div class='btn-group' role='group'><button type='button' class='btn btn-default' onclick='addConnectionByModal("+requestById+", "+requestToId+", 1, "+requestById+")'>Same Level</button></div>";
			var leadsMeBtn = "<div class='btn-group' role='group'><button type='button' class='btn btn-default' onclick='addConnectionByModal("+requestToId+", "+requestById+", 2, "+requestById+")'>Leads Me</button></div>";
			var followsMeBtn = "<div class='btn-group' role='group'><button type='button' class='btn btn-default' onclick='addConnectionByModal("+requestById+", "+requestToId+", 2, "+requestById+")'>Follows Me</button></div>";
			
			var btns = "";
			if(requestToAccess < 3 && requestByAccess < 3) //both have full access
			{
				//show all buttons
				btns = memberBtn+leadsMeBtn+followsMeBtn;
			}	
			else if(requestByAccess == 3 && requestToAccess < 3)
			{
				//show member and leads me
				btns = memberBtn+leadsMeBtn;
			}
			else if(requestByAccess < 3 && requestToAccess == 3)
			{
				//show member and follows me
				btns = memberBtn+followsMeBtn;
			}
			else if(requestToAccess == 3 && requestByAccess == 3)
			{
				//show member
				btn = memberBtn;
			}
			
			var modalButtons = "<div class='btn-group btn-group-justified' role='group' aria-label='conection options'>"+btns+"</div>";
			
			$("#connectionModal #connectionPromptText").text("What is your connection with "+decodeURI(name)+"?");
			$("#connectionModal #connectionModalButtons").html(modalButtons);
			$("#connectionModal").modal();
		}
		else
		{
			console.log(d.msg);
		}
	});
			
}

/** 
 * Use the modal to add a connection
 */
function addConnectionByModal(leaderId, followerId, permission, requestBy)
{
	var c = confirm("Are you sure you want to request this connection?");
	if(c)
	{
		requestConnection(leaderId, followerId, permission, requestBy, function(d){
			//alert("connection added");
			//reload the table
			var itemId = $("#connectionCarousel .active").attr("id");
			loadConnectionTables(itemId);
			
			var requestTo = (requestBy != leaderId) ? leaderId : followerId;
			//create the connection btns if on user page
			createUserConnectionBtnsFor(requestTo, null, encodeURI(name), function(res){
				//do nothing
			});
			
			//close modal and replace with "pending" if higher request, keep "change" if lower request
			//close modal
			$("#connectionModal").modal("hide");
		});
	}
}

/** 
 * Remove connection from my-connections
 */
function removeConnection(connectionId, name)
{
	var c = confirm("Are you sure you want to remove your connection to "+decodeURI(name)+"?");
	if(c)
	{
		deactivateConnection(connectionId, function(d){
			//reload the table
			var itemId = $("#connectionCarousel .active").attr("id");
			loadConnectionTables(itemId);
		});
	}
}

function removeConnectionFromCurrentConnections(connectionId, name)
{
	var c = confirm("Are you sure you want to remove this connection to "+decodeURI(name)+"?");
	if(c)
	{
		decreaseConnection(connectionId, function(d){
			//reload the table
			var itemId = $("#connectionCarousel .active").attr("id");
			loadConnectionTables(itemId);
		});
	}
}

/**
 * Remove connection from user view page
 */
function removeConnectionFromUserView(connectionId, name, uId, elem)
{
	var c = confirm("Are you sure you want to remove this connection to "+decodeURI(name)+"?");
	if(c)
	{
		decreaseConnection(connectionId, function(d){
			createUserConnectionBtnsFor(uId, null, encodeURI(name), function(res){
				//do nothing
			});
		});
	}	
	else
	{
		createUserConnectionBtnsFor(uId, null, encodeURI(name), function(res){
			//do nothing
		});
	}
}

/**
 * Load the connection tables depending on what item is showing
 */
function loadConnectionTables(itemId)
{
	if(itemId == "currentConnections")
	{
		//reload current connections
		setCurrentConnectionTable(reqUser);
	}
	else if(itemId == "addConnections")
	{
		//reload add connections table
		setAddConnectionTable(reqUser);
	}
}

/** 
 * Accept the connection by the connectionId 
 */
function acceptConnection(connectionId, name)
{
	console.log("connection id");
	console.log(connectionId);
	var c = confirm("Are you sure you want to accept this connection with "+decodeURI(name)+"?");
	if(c)
	{
		var data = new FormData();
		data.append("function", "acceptConnection");
		data.append("timestamp", new Date().getTime()/1000);
		data.append("connectionId", connectionId);			
		$.ajax({
			url: path+"include/function.php",
			type: "POST", 
			data: data,
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				var itemId = $("#connectionCarousel .active").attr("id");
				loadConnectionTables(itemId);					
			}
		});
		
	}
}

/**
 * Reject a pending connection
 */
function rejectConnection(connectionId, name, userId)
{
	var c = confirm("Are you sure you want to reject this connection with "+decodeURI(name)+"?");
	if(c)
	{
		deactivateConnection(connectionId, function(d){
			console.log(d);
			if(d.success)
			{
				//reload the table
				var itemId = $("#connectionCarousel .active").attr("id");
				console.log(itemId);
				loadConnectionTables(itemId)
			}
			else
			{
				console.log(d.msg);
			}
		});
	}
}

/** 
 * Cancel a pending connection
 */
function cancelPendingConnection(connectionId, name)
{
	var c = confirm("Are you sure you want to cancel your pending connection request with "+decodeURI(name)+"?");
	if(c)
	{
		deactivateConnection(connectionId, function(d){
			//reload the table
			var itemId = $("#connectionCarousel .active").attr("id");
			loadConnectionTables(itemId);
		});
	}		
}

function cancelPendingConnectionfromUserView(connectionId, name, uId, elem)
{
	console.log("CANCEL");
	console.log(uId);
	var c = confirm("Are you sure you want to cancel your pending connection request with "+decodeURI(name)+"?");
	if(c)
	{
		decreaseConnection(connectionId, function(d){
			console.log(d);
			createUserConnectionBtnsFor(uId, null, encodeURI(name), function(res){
				//do nothing
			});
		});
	}	
	else
	{
		console.log("addClass active");
		console.log(elem);
		//elem.addClass("active");
		//reload buttons on cancel - cause the button won't stay active and I'm out of ideas
		//TODO find a better way then reloading buttons
		createUserConnectionBtnsFor(uId, null, encodeURI(name), function(res){
				//do nothing
		});
	}
}

/**
 * Deactivate a connection
 */
function deactivateConnection(connectionId, callback)
{
	var data = new FormData();
	data.append("function", "removeConnection");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("connectionId", connectionId);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data,
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		callback(d);
	});
}

function decreaseConnection(connectionId, callback)
{
	var data = new FormData();
	data.append("function", "rejectConnection");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("connectionId", connectionId);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data,
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		callback(d);
	});
}

/**
 * Request a connection with someone
 */
function requestConnection(leaderId, followerId, permission, requestBy, callback)
{
	var data = new FormData();
	data.append("function", "requestConnection");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("leaderId", leaderId);
	data.append("followerId", followerId);
	data.append("permission", permission);
	data.append("requestBy", requestBy);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data,
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		callback(d);
	});
}

function requestConnectionFromUserView(leaderId, followerId, requestBy, name, elem)
{
	console.log("requestConnection");
	var type = (leaderId == requestBy) ? "follow me" : "leads me";
	var requestTo = (requestBy != leaderId) ? leaderId : followerId;
	var c = confirm("Are you sure you want to request a "+type+" connection with "+decodeURI(name)+"?");
	if(c)
	{
		requestConnection(leaderId, followerId, 2, requestBy, function(d){			
			//change the buttons to pending
			createUserConnectionBtnsFor(requestTo, null, name, function(res){
				//do nothing
			});
		});
	}		
	else
	{
		console.log("removeClass active");
		elem.parent().removeClass("active");
		createUserConnectionBtnsFor(requestTo, null, encodeURI(name), function(res){
			//do nothing
		});
	}
}


/* 
 *Send the apple codes to the server to send emails
 *Return the success and failed emails
 */
function readCsvForAppleCodes(){

	//UPDATE TEXTAREA WITH EDITOR CONTENT
	for (instance in CKEDITOR.instances) {
		CKEDITOR.instances[instance].updateElement();
	}

	var code = $("#code").val();
	var e = $("#entity").val();
	var emailContent = $("#emailContent").val();

	if($("#file_input_hidden").val() == "" || code == "" || e == "" || emailContent == "")
	{
		$(".failed .alert").html("File, code, entity and e-mail content required.");
	}
	else
	{
		var file = $("#file_input_hidden")[0].files[0];
		if(file.name.match(/.+(\.csv)$/))
		{
			//if file is not csv
			//else
			var data = new FormData();
			data.append("function", "sendAppleUpdateEmailByCsvSite");
			data.append("csv_file", file);
			data.append("code", code);
			data.append("entity", e);
			data.append("emailContent", emailContent);

			$.ajax({
				url: path+"include/function.php",
				type: "POST",
				data: data,
				contentType: false, 
				processData: false,
				beforeSend: showLoader(5,5)
			}).done(function(d){
				//clear the progress bar timeout
				clearTimeout(obj);		
				//set progress to 100%;
				$(".progress-bar").css("width", "100%").attr("aria-valuenow", 100);		
				console.log(d);
				d = JSON.parse(d);	
				showEmailSuccess(d.success, d.fail, $(".successful .alert"), $(".failed .alert"));
			});		
		}
	}
}

/* 
 *Send the apple codes to the server to send emails
 *Return the success and failed emails
 */
function readCsvForMissing(){		
	//if file
	if($("#file_input_hidden_missing").val() != "")
	{
		var file = $("#file_input_hidden_missing")[0].files[0];
		if(file.name.match(/.+(\.csv)$/))
		{
			//if file is not csv
			//else
			var data = new FormData();
			data.append("function", "sendMissingEmailByCsv");
			data.append("csv_file", file);
			$.ajax({
				url: path+"include/function.php",
				type: "POST",
				data: data,
				contentType: false,
				processData: false,
				beforeSend: showLoader(5,5)
			}).done(function(d){
				//clear the progress bar timeout
				clearTimeout(obj);
				//set progress to 100%;
				$(".progress-bar").css("width", "100%").attr("aria-valuenow", 100);
				
				d = JSON.parse(d);
				showEmailSuccess(d.success, d.fail, $(".successful .alert"), $(".failed .alert"));
			});		
		}
	}
}

/**
 * Send an individual an apple code
 */
function sendInvite()
{
    //UPDATE TEXTAREA WITH EDITOR CONTENT
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }

    var name = $("#userName").val().trim();
    var email = $("#email").val().trim();
    var code = $("#code").val();
    var e = $("#entity").val();
    var emailContent = $("#emailContent").val();

    if(name == "" || email == "" || code == "" || e == "" || emailContent == "")
    {
        $(".failed .alert").html("Name, e-mail, code, entity and e-mail content required.");
    }
    else
    {
        var data = new FormData();
        data.append("function", "sendInvite");
        data.append("name", name);
        data.append("email", email);
        data.append("code", code);
        data.append("entity", e);
        data.append("emailContent", emailContent);

        $.ajax({
            url : path+"include/function.php",
            data: data,
            type: "POST",
            contentType: false,
            processData: false
        }).done(function(d){
			console.log(d);
			d = JSON.parse(d);
			showEmailSuccess(d.success, d.fail, $(".successful .alert"), $(".failed .alert"));
			var name = $("#userName").val('');
			var email = $("#email").val('');
        });
    }
}

/**
 * Send an individual an apple code
 */
function sendIndividualCode()
{
	var name = $("#userName").val().trim();
	var email = $("#email").val().trim();
	var code = $("#code").val().trim();
	var newUser = $("#newUser").prop("checked");
	var e = $(".entity select").val();
	
	if(email == "" || code == "")
	{
		$(".failed .alert").html("Email and Code required");
	}
	else
	{
		var data = new FormData();
		data.append("function", "sendAppleCodeByPerson");
		data.append("name", name);
		data.append("email", email);
		data.append("code", code);
		data.append("newUser", newUser);
		data.append("entity", e);
		
		$.ajax({
			url : path+"include/function.php",
			data: data, 
			type: "POST", 
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			showEmailSuccess(d.success, d.fail, $(".successful .alert"), $(".failed .alert"));			
		});
	}	
}

/**
 * After send email show the success and fails
 * succ = String[]
 * fail = String[]
 * succElem = success div
 * failElem = fail div
 */
function showEmailSuccess(succ, fail, succElem, failElem)
{
	succElem.addClass("hidden");
	failElem.addClass("hidden");
	
	//show mail sent and failed
	if(fail.length > 0)
	{
		var txt = "<strong>Email Failed For</strong><br/>";
		for(f in fail)
		{
			txt += fail[f]+"<br/>";
		}
		failElem.html(txt).removeClass("hidden");
		
	}
	if(succ.length > 0)
	{
		var txt = "<strong>Email Sent To</strong><br/>";
		for(s in succ)
		{
			console.log(succ[s]);
			txt += succ[s]+"<br/>";
		}
		succElem.html(txt).removeClass("hidden");
		
	}					
}

/**
	Start the progress bar
	Each time it runs add 5%
**/
function showLoader(start, add)
{
	obj = setTimeout(function(){
		var progress = start/10;
		$(".progress-bar").css("width", progress+"%").attr("aria-valuenow", progress);
		//if progress hits 95% stop the loader bar until its finished
		if(progress < 90)
		{
			showLoader(start+add, add);
		}
	}, 50);
}

/**
 * Depending on the entity selected
 * set the leave types
 */
function setLeaveTypesByEntity(entityId)
{
	var data = new FormData();
	data.append("function", "getLeaveTypesByEntity");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("entityId", entityId);
	
	$.ajax({
		url: path+"include/function.php",
		data: data, 
		type: "POST", 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			var typesTxt = "";
			for(var i = 0; i < d.leaveTypes.length; i++)
			{
				var type = d.leaveTypes[i];
				typesTxt += "<option value='"+type.leaveTypeId+"'>"+type.leaveTypeName+"</option>";
			}
			$("#leaveType").html(typesTxt);
		}
		else
		{
			showAlertRow($(".alert-row .alert"), false, d.msg);
		}
	});
}


/**
 * Set the personal leave page
 * Creates headers and panels based on what sort by is chosen
 */
function setPersonalLeave(userId, inPanel)
{	
	var sortBy = $("#sort").val();
	
	var data = new FormData();
	data.append("function", "findLeaveByUser");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("sortBy", sortBy);
	data.append("userId", userId);
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			//remove current panels
			$("#leaveAccordion").html("");
			var open = false;
			for(h in d.headers)
			{
				//make a new collapsible panel
				var headerName = d.headers[h];	
				var panel = "<div class='panel panel-default' data-headerid='"+h+"'>"
								+"<div class='panel-heading' role='tab' id='heading_"+headerName+"'>"
									  +"<h4 class='panel-title'>"
										+"<a data-toggle='collapse' data-parent='#leaveAccordion' href='#panel_"+h+"' aria-expanded='false' aria-controls='panel_"+h+"'>"
										 +headerName
										+"</a>"
									  +"</h4>"
									+"</div>"
									+"<div id='panel_"+h+"' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading_"+headerName+"'>"
										+"<div class='panel-body'>"
										+"</div>"
									+"</div>"
								+"</div>";						
				$("#leaveAccordion").append(panel);					
			}
						
			for(leave in d.leave)
			{
				var l = d.leave[leave];
				var momentFrom = moment.unix(l.timeFrom).tz(l.timezone);
				var momentTo = moment.unix(l.timeTo).tz(l.timezone);
				var today = moment().startOf("day").format("x");
				
				var headerId = 0;
				
				//Decide what name and id to put on the panel
				if(sortBy == "time")
				{
					//headers are TIME 
					if(momentFrom.startOf("day").format("x") == today)
					{
						headerId = 0;
					}						
					else if(momentFrom.startOf("day").format("x") > today)
					{
						headerId = 1;
					}
					else if(momentFrom.startOf("day").format("x") < today)
					{
						headerId = 2;
					}							
				}
				else if(sortBy == "entity")
				{
					//headers are ENTITY
					headerId = l.entityId;
					
				}
				else if(sortBy == "leaveType")
				{
					//headers are LEAVE TYPE
					headerId = l.leaveTypeId;
				}
				
				var removeBtn =  (l.userId == null) ? "" : "<button type='button' class='btn btn-danger pull-right' data-leavebtn='"+l.leaveId+"'>Remove</button>";
				
				var from = momentFrom.format("DD/MM/YY");
				var to = momentTo.format("DD/MM/YY");
				//write each row of leave
				var row = "<div class='row' data-leaveid='"+l.leaveId+"'>"
							+"<div class='col-xs-12'>"
								+"<div class='row bg-primary'>"
									+"<h4 class='col-xs-8'>"+l.entityName+"</h4>"
									+"<h4 class='col-xs-4'>"+removeBtn+"</h4>"
								+"</div>"
								+"<br/>"
								+"<div class='row'>"
									+"<div class='col-xs-4'><strong>"+l.leaveTypeName+"</strong></div>"
									+"<div class='col-xs-8 desc'><em>"+l.description+"</em></div>"
								+"</div>"
								+"<div class='row'>"
									+"<div class='col-xs-1'><strong>Start:</strong></div><div class='col-xs-3'>"+from+"</div>"									
									+"<div class='col-xs-1'><strong>End:</strong></div><div class='col-xs-3'>"+to+"</div>"
								+"</div>"									
							+"</div>"
						+"</div><br/>";
						
				$("[data-headerid='"+headerId+"'] .panel-body").append(row);
			}
			
			//if a panel is set to be in open it
			if(inPanel)
			{
				//console.log(inPanel);
				$("#"+inPanel).addClass("in");
			}
			else
			{
				//open the first panel
				$("#leaveAccordion .panel:first .panel-collapse").addClass("in");
			}				
			
			//after completion, set buttons
			$("[data-leaveBtn]").click(function(){
				var leaveId = $(this).data("leavebtn");
				var desc = $("[data-leaveid='"+leaveId+"'] desc em").text();
				removeLeave(leaveId, desc, function(){
					//get uncollapsed
					var inPanel = $(".collapse.in").attr("id");
					setPersonalLeave(userId, inPanel);
				});
			});
			
			
		}
		else
		{
			showAlertRow($(".alert-row .alert"), false, d.msg);
		}
	});
}

/**
 * Confirm that the leave is to be removed 
 * Remove the leave with a callback function
 */
function removeLeave(leaveId, desc, callback)
{
	console.log(decodeURI(desc));
	var c = confirm("Are you sure you want to remove leave "+desc+"?");
	if(c)
	{
		var data = new FormData();
		data.append("function", "removeLeaveById");
		data.append("timestamp", new Date().getTime()/1000);
		data.append("leaveId", leaveId);
		//remove the leave
		$.ajax({
			url: path+"include/function.php",
			data: data, 
			type: "POST", 
			contentType: false, 
			processData: false
		}).done(function(d){
			d = JSON.parse(d);
			if(d.success)
			{
				showAlertRow($(".alert-row .alert"), true, d.msg);
				//redraw
				callback();
			}
			else
			{
				showAlertRow($(".alert-row .alert"), false, d.msg);
			}
		});
	}
}



/**
 * reset the leave modal after an add
 */
function resetLeaveModal()
{
	var modal = $(".modal");
	modal.find(".desc").val("");
	todayStr = moment().format("ddd DD/MM/YYYY");
	modal.find("#dateFrom, #dateTo").val(todayStr)
							.datepicker("setDate", todayStr);
	//leave entity and leave type as they are
}

/**
 *Set a dropdown with entities available 
 *Can be used in future
 */
function setEntitiesDropdown(userId, entityId)
{
	var data = new FormData();
	data.append("function", "getEntities");
	data.append("timestamp", new Date().getTime()/1000);
	if(entityId != null) //if entitId is set get only this entity
	{
		data.append("userId", userId);
		data.append("entityId", entityId);
	}
	//else get all entities for this user
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data,
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			var entTxt = "";
			if(d.entities.length > 0)
			{
				if(entityId == null) //entity not selected
				{
					//show all entities
					for(var i = 0; i < d.entities.length; i++)
					{
						var ent = d.entities[i];
						entTxt += "<option value='"+ent.entityId+"'>"+ent.entityName+"</option>";
					}				
					$(".entityDropdown").html(entTxt).removeClass("hidden");
					$(".entityName").addClass("hidden");
					
					//if entity dropdown changes, update the leavetypes
					$(".entityDropdown").change(function(){
						setLeaveTypesByEntity($(this).val());
					});
					
					//set the leave types
					setLeaveTypesByEntity(d.entities[0].entityId);
				}
				else //only one entity selected
				{
					//hide the dropdown its not available 
					$(".entityDropdown").addClass("hidden");
					//loop through all the users available entities
					for(e in d.entities)
					{
						//if their entity is found
						if(d.entities[e].entityId == entityId)
						{
							$(".entityName").val(d.entities[e].entityName);
							$("#entityId").val(d.entities[e].entityId);
							
							//set the leave types
							setLeaveTypesByEntity(d.entities[e].entityId);
							break;
						}
					}				
				}				
			}
		}
		else
		{
			showAlertRow($(".alert-row .alert"), false, d.msg);
		}			
	});
}


/**
 * Simple aff leave function taking all the values and sending them through ajax
 */
function addLeave(userId, leaveTypeId, timeFrom, timeTo, timezone, desc, callback)
{
	$(".alert-row .alert").addClass("hidden");
	var data = new FormData();
	data.append("function", "addLeave");
	(userId != false) ? data.append("userId", userId) : false; 
	data.append("leaveTypeId", leaveTypeId);
	data.append("timeFrom", timeFrom);
	data.append("timeTo", timeTo);
	data.append("timezone", timezone);
	data.append("desc", desc);
	
	data.append("timestamp", new Date().getTime());
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			//reload page
			callback();
		}
		else
		{
			$(".alert-row .alert").text(d.msg).removeClass("hidden");
		}
	});
}



/**
 * Get the leave for entity based on filters
 */
function getLeaveForEntity(filters, successCallback, failCallback)
{
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findLeaveByEntity");
	filters = (filters) ? filters : [];
	data.append("filters", JSON.stringify(filters));
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{	
			successCallback(d);
		}
		else
		{
			failCallback(d);
		}
	});
}


/**
 *Get the leave for the user
 */
function getLeaveByUser(userId, entityId, filters, successCallback, failCallback)
{
	var data = new FormData();
	data.append("function", "findLeaveByUser");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("userId", userId);
	filters = (filters) ? filters : [];
	data.append("filters", JSON.stringify(filters));	
	//if entity id is set get this entity
	(entityId) ? data.append("entityId", entityId) : false;
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: data, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			console.log("success");
			successCallback(d);
		}
		else
		{
			//failCallback(d);
			$(".alert-row .alert").text(d.msg)
								  .removeClass("hidden")
								  .addClass("alert-danger")
								  .removeClass("alert-success");
		}
	});
}	
