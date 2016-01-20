/**
* communityControl.php
* Create a table that shows the associated communities of the user
* Display the accessDate and administrator details and a button to unassociate them
*
*/
var path = "";

function createAssociatedTable(userId, entityId)
{
	var data = new FormData();
	data.append("function", "findEntityDetails");
	
	data.append("userId", userId);
	data.append("entityId", entityId);
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		var tenants = JSON.parse(d);		
		var table = "";
		if(tenants.length > 0)
		{
			table += "<thead>"
					+	"<tr>"
					+		"<th>Name</th>"
					+		"<th>End of access</th>"
					+		"<th>Entity Access</th>"
					+		"<th>Administrator</th>"
					+		"<th>Entity Code</th>"
                    +		"<th>Details</th>"
                    +		"<th>Invite Team</th>"
					+		"<th>Unassociate</th>"
					+	"</tr>"
					+"</thead>"
					+"<tbody>";
			for(var i = 0; i < tenants.length; i++)
			{
				var t = tenants[i];
				var id = t.id;
				var name = t.name;
				var db = t.db;
				var accessEnd = t.accessEnd;
				var sessionAccess = t.accessLevel;
				var adminId = t.adminId;
				var admin = t.admin;
				var email = t.adminEmail;
				var mobile = t.adminMobile;
				
				//get now in seconds to match accessEnd data
				var now = new Date().getTime()/1000;
				
				var detailBtn = "";
                var inviteBtn = "";
				var disable = "";
				var btnClass = "success";
				var accessList = entityAccessList();
				var unassocBtn = "<button onclick='unassocEntity("+id+", \""+name+"\", "+userId+")' type='button' class='btn btn-danger btn-block'><span class='glyphicon glyphicon-minus'></span></button>";
				//key is accessval -1
				//open modal with session access , user access, entity Id
				var accessBtn = "<button type='button' class='btn btn-block btn-default' data-toggle='modal' onclick='clickAccessModal("+sessionAccess+","+sessionAccess+","+id+")'>"+accessList[sessionAccess-1]+"</button>";
				var codeBtn = "";
				if(sessionAccess < 3)
				{
					codeBtn = "<button type='button' class='btn btn-block btn-default' data-toggle='modal' onclick='clickEntityCodeModal("+id+")'><span class='glyphicon glyphicon-qrcode'></span></button>";
					//change session to this entity and direct to entitySettings.php
					detailBtn = "<button type='button' class='btn btn-block btn-default' onclick='toEntitySettings("+id+")'><span class='glyphicon glyphicon-pencil'></span></a>";
                    inviteBtn = "<button type='button' class='btn btn-block btn-default' onclick=\"window.open('/send-invites/"+id+"', '_self');\"><span class='glyphicon glyphicon-send'></span></a>";
				}
				
				
				//disable remove button if they are admin
				if(adminId == userId)
				{
					//disable = "disabled";
					unassocBtn = "";
				}
				
				if(accessEnd == null)
				{
					accessEndStr = "<span class='alert alert-block text-center'>N/A</span>";
				}
				else
				{
					//create str to show date
					accessEndStr = timestampToDate(accessEnd, "d-m-Y");
					//if date is less that now
					if(accessEnd <= now)
					{
						btnClass = "danger";
						unassocBtn = "";
						accessBtn = "N/A";
					}
				}
				table += "<tr data-entity='"+id+"'>"
						+	"<td>"+name+"</td>"
						+	"<td class='text-center bg-"+btnClass+"'>"+accessEndStr+"</td>" //class='bg-"+btnClass+"'
						+	"<td class='text-center accessBtn'>"+accessBtn+"</td>"
						+	"<td>"+admin+"<br/><em>"+email+"</em><em class='pull-right'>"+mobile+"</em></td>"
						+	"<td>"+codeBtn+"</td>"
						+	"<td>"+detailBtn+"</td>"
                        +	"<td>"+inviteBtn+"</td>"
						+	"<td>"+unassocBtn+"</td>"
						+"</tr>";
						//"<tr>"+
						//	"<td><em>"+email+"</em></td>"
						//"</tr>";					
			}
			table += "</tbody>";
		}
		else
		{
			//no tenants
			table += "<thead>"+
						"<tr>"+
							"<th class='text-center'>"+
								"<div class='alert alert-info text-center'>"+
								"<h4>No Communities. "+
								"<br/>Add one now.</h4>"+
								//"<br/><button type='button' class='btn btn-primary col-md-4 col-md-offset-4' data-toggle='modal' data-target='#create'><span class='glyphicon glyphicon-plus'></span></button>"+
								"</div>"+
							"</th>"+
						"</tr>"+
					"</thead>";
		}
		$("#communityTable").html(table);
	});
}



/**
*	Creates a table for all the sites
*   If an entity Id is passed this entity's sites are shown.
*   Check for access in the php
*/
function createSiteTable(entityId)
{
	var search = $("#search").val();
	var data = new FormData();
	data.append("function", "findSites");
	data.append("search", search);
	if(entityId)
	{
		data.append("entityId", entityId);
	}
	data.append("timestamp", new Date().getTime()/1000);

	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	ajaxObj = $.ajax({
		url: path+"include/function.php",
		data: data,
		type: "POST",
		contentType: false,
		processData: false
	}).done(function(d){
        console.log("Result: "+d);
		d = JSON.parse(d);
		$("#siteTable").empty();
		if(d.success)
		{
			var tableTxt = "";
			//display the entries
			var sites = d.sites;
			//do the map
			createSiteControlMap(sites);
			if(sites.length > 0)
			{

				tableTxt += "<tr>"
							+ "<th class='col-md-3'>Site Name</th>"
							+ "<th class='col-md-4'>Address</th>"
							+ "<th class='col-md-1'>State</th>"
							+ "<th colspan='2' class='col-md-2'>Details</th>"
							+ "<th class='col-md-1'>OHS</th>"
							+ "<th class='col-md-1'>&nbsp;</th>"
							+"</tr>";
				//$("#siteTable").append(tableTxt);
				for(var i = 0; i < sites.length; i++)
				{
					var site = sites[i];
					//var address = site.stNumber+" "+site.stName+", "+site.suburb;
					var address = "";
					address += site.stNumber;
					address += (address != "") ? " "+site.stName : site.stName;
					address += (address != "") ?  ", "+site.suburb : site.suburb;



					tableTxt += "<tr data-site="+site.siteId+">"
								+ "<td class='siteName'>"+site.name+"</td>"
								+ "<td>"+address+"</td>"
								+ "<td>"+site.state+"</td>"
								+ "<td><a type='button' class='btn btn-block btn-default' href='/site/view/"+site.siteId+"'>View</a></td>"
								+ "<td><a type='button' class='btn btn-block btn-default' href='/site/edit/"+site.siteId+"'>Edit</a></td>"
								+ "<td><a type='button' class='btn btn-block btn-default' href='/site/questions/"+site.siteId+"'>OHS</a></td>"
								+ "<td><button type='button' class='btn btn-danger btn-block' onclick='deleteSite("+site.siteId+")'><span class='glyphicon glyphicon-trash'></span></button></td>"
								+"</tr>";
					//$("#siteTable").append(tableTxt);
					//do I want to put page breaks?
				}
			}
			else
			{
				//no entries
				tableTxt = "<tr><th><div class='alert alert-warning alert-block text-center'>There are no sites in this entity.<br/><a href='/site/create'>Create one now</a></div></th></tr>";
			}

			$("#siteTable").html(tableTxt);
		}
		else
		{
			//there was an error
			//this catches no results too
			$("#alert-row").html("<div class='alert alert-danger'>"+d.msg+"</div>");
		}
	});

}

/**
*	Creates a table for all the users
*	If an entity Id is passed this entity's users are shown
*	Check for access in the php
*/
function createUserTable(entityId)
{
	var search = $("#search").val();
	$("#alert-row").html("");
	

	var data = new FormData();
	data.append("function", "findUsers");
	data.append("search", search);
	if(entityId)
	{
		data.append("entityId", entityId);
	}
	data.append("accessOnly", true);
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
		console.log(d);
		$("#userTable").empty();
		if(d.success)
		{
			var tableTxt = "";
			//display the entries
			var users = d.users;
			if(users.length > 0)
			{
				tableTxt += "<tr>"
							//+ "<th></th>"
							+ "<th colspan='2'>Name</th>"
							+ "<th class='hidden-xs'>Email</th>"
							+ "<th colspan='2'>Details</th>"
							+ "<th></th>"
							+"</tr>";
				//$("#siteTable").append(tableTxt);
				for(var i = 0; i < users.length; i++)
				{
					var user = users[i];
					tableTxt += "<tr>"
								//+ "<td><img class='img-circle memberTableImg' src='"+path+"include/userImg.php?userId="+user.userId+"' alt='"+user.firstName+"'></td>"
								+ "<td>"+user.firstName+"</td>"
								+ "<td>"+user.lastName+"</td>"
								+ "<td class='hidden-xs'>"+user.email+"</td>"
								+ "<td><button type='button' class='btn btn-block' onclick='location.href=\"/user/view/"+user.userId+"\"'>View</button></td>"
								+ "<td><button type='button' class='btn btn-block' onclick='location.href=\"/user/edit/"+user.userId+"\"'>Edit</button></td>"	
								+ "<td><button type='button' class='btn btn-danger btn-block' onclick='unassociateUser("+user.userId+", \""+user.firstName+"\")'><span class='glyphicon glyphicon-remove'></span></button></td>"
								+"</tr>";
								//(entityId, userId, userName, entityName)
				
					//do I want to put page breaks?
				}
			}
			else
			{
				//no entries
			}
			$("#userTable").html(tableTxt);
		}
		else
		{
			//there was an error
			//this catches no results too
			$("#alert-row").html("<div class='alert alert-danger text-center alert-block'>"+d.msg+"</div>");
		}
	});
}

/**
* Set the dashbaord for this entity
* displays their name, location, action and status
* indicates if they're from our entity
**/
function getDashboard()
{
	//anything with class refresh clear it
	$("div.refresh").html("");
	$("#total-row").html("<h5 class='col-xs-12'></h5>");
	
	var search = $("#search").val();
	
	//timestamp for access
	var data = new FormData();
	data.append("function", "findCheckins");
	data.append("timestamp", new Date().getTime()/1000);
	data.append("search",search);
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	ajaxObj = $.ajax({
		url: path + "include/function.php",
		data: data,
		type: "POST",
		processData: false,
		contentType: false,
		beforeSend: function () {
			$('.loading-bg').fadeIn();
			$('.loading-bar').fadeIn();
		}
	}).done(function(d){
		$('.loading-bg').fadeOut();
		$('.loading-bar').fadeOut();

		d = JSON.parse(d);
		if(d.success)
		{			
			//this has been moved here. If something buggy happens change it back
			//getDashboardEntityDropdown(d);
			var checkins = d.checkinData;
			var tableTxt = "";
			var pulses = [];
			if(checkins.length > 0)
			{
				var totalActive = 0;
				var actStr = "Total Activity";
				if(search != "")
				{
					actStr += " for search '"+search+"'";
				}
				actStr += ": "+checkins.length;
				
				//show totla
				$("#total-row").html("<h4 class='col-xs-12'>"+actStr+"</h4>");
				
				tableTxt += "<tr>"
							+ "<th class='col-xs-5 col-md-3'>Name</th>"
							+ "<th class='col-xs-5 col-md-4'>Location</th>"
							+ "<th class='col-xs-2'>Last Action</th>"
							+ "<th class='col-md-1 hidden-xs hidden-sm'>Date</th>"
							+ "<th class='col-md-2 hidden-xs hidden-sm' colspan='2'>Time</th>"
							+"</tr>";					
				
				
				for(var i = 0; i < checkins.length; i++)
				{
				
					var checkin = checkins[i];
					var isPulse = checkin.isPulse;
					
					var loc = "<i class='fa fa-spinner fa-spin'></i>";
					var name = "";
					var activity = "";
					var time = "";
					var date = "";
					var timezone = "";
					var checkinClass = "";
					var rowClass = "";
					
					//add to active count
					if(checkin.status != 0)
					{
						totalActive++;
					}
					
					name = "<a class='btn btn-default btn-block dashUserNameBtn' data-userid='"+checkin.userId+"' href='/user/view/"+checkin.userId+"'>"+checkin.firstName+" "+checkin.lastName+"</a>";
					activity = checkin.checkinName;
					//console.log(activity);
					
					timezone = checkin.timezone;

					var dateObj = moment.unix(checkin.time).tz(timezone);
					date = dateObj.format("MMM DD");
					time = dateObj.format("HH:mm");				
					
					var slash = timezone.lastIndexOf("/")+1;
					timezone = timezone.substr(slash);
					timezone = timezone.replace(/_/g, " ");
					
					var colour = "_green";					
					var action = "_checkin";
					var us = "";
					
					//check if entity has been used
					//if not change the colour
					//if it has use that entities colour					
					
					var alt = false;
					if(checkin.isPulse == false)
					{
						//get the location
						loc = "<a class='btn btn-default btn-block' href='/site/view/"+checkin.siteId+"'>"+checkin.siteName+"</a>";
						
						if(checkin.userEntityId == checkin.siteEntityId)
						{
							alt = false;
						}
						else if(checkin.userEntityId != d.entityId)
						{
							var rowClass = "bg-warning";
							alt = true;
						}
					}
					else
					{
						checkinClass = "glyphicon glyphicon-flash";
							
							
						//user is part of this entity
						if(checkin.userEntityId == d.entityId)
						{
							
						}
						else
						{
							var rowClass = "bg-warning";
						}
						
						pulses.push(checkin);
					}
					
					
					var markerImg = createUserMarkerImg(checkin.checkinTypeId, checkin.status, checkin.isPulse, alt, true);
					var icon = 	"<img class='' src='"+markerImg+"' alt='"+checkin.firstName+" "+checkin.lastName+"'><span class='visible-md-inline visible-lg-inline'>&nbsp;"+activity+"</span>";
					
					//var localTime = moment().tz(checkin.timezone).format("H:m");
					//THIS IS FOR THE POPOVER
					var hr = addZero(moment().tz(checkin.timezone).format("H"));
					var min = addZero(moment().tz(checkin.timezone).format("m"));
					//I WANT THE ZEROS AT THE FRONT
					var localTime = hr+":"+min;
					var tz = parseInt(moment().tz(checkin.timezone).format("Z"));
					var myTz = new Date().getTimezoneOffset()/-60;
					var diffTz = myTz - tz;
					
					var tooltip = "Local "+timezone+" time: "+localTime+"<br/>";
					if(diffTz != 0)
					{
						tooltip += diffTz;
						tooltip += (diffTz == 1 || diffTz == -1) ? " hour" : " hours";
						//if mytz - user tz > 0 they are behind me!
						tooltip += (diffTz > 0) ? " behind" : " ahead";
					}
					
					//fix columns sizes for small screens col-sm-visibe etc
					
					tableTxt += "<tr class='"+rowClass+"' id='row_"+checkin.checkinId+"'>"
						+ "<td><span>"+name+"</span></td>"
						+ "<td class='location text-center'>"+loc+"</td>"
						//+ "<td><span class='"+checkinClass+"'></span><span class='visible-md-inline visible-lg-inline'>&nbsp;"+activity+"</span></td>"
						+ "<td>"+icon+"</td>"
						+ "<td class='hidden-xs hidden-sm'>"+date+"</td>"
						+ "<td class='hidden-xs hidden-sm'>"+time+"</td><td class='hidden-xs hidden-sm'><span data-toggle='tooltip' title='"+tooltip+"' data-position='top' data-html='true' data-timezone='"+checkin.timezone+"'>"+timezone+"</span></td>"
						+"</tr>";						
				}
			}
			else
			{
				var msg = "No activity found for this entity";
				//console.log(d.search);
				if(!d.search.match("\w"))
				{
					msg = "No activity found for search "+d.search.trim();
				}
				//no checkins
				tableTxt = "<tr>"
							+ "<th><div class='alert alert-block alert-warning text-center'>"+msg+"</div></th>"
							+"</tr>";
			}
						
			$("#dashboardTable").html(tableTxt);
	
			$("[data-toggle='tooltip']").tooltip();
			
		
			
			//once table is populated populate pulse location adn popover
			for(var i = 0; i < checkins.length; i++)
			{
				var checkin = checkins[i];
				var userId = checkin.userId;
				var entityName = checkin.userEntityName;
				var firstName = checkin.firstName;
				var isPulse = checkin.isPulse;				
				
				if(isPulse == true)
				{
					locationForDash(checkin, function(l, id){
						$("#row_"+id+" .location").html(l);
					});
				}
					
				var content = "<div class='dashUserInfo row'>"
						+"<div class='row'>"						
							+"<div class='col-xs-12'>"
								+"<div class='col-xs-10 col-xs-offset-1'>"
									+"<img src='"+path+"include/userImg.php?userId="+userId+"' class='img-circle img-responsive col-xs-12' alt='"+firstName+"'>"
								+"</div>"
							+"</div>"
						+"</div>"
						+"<div class='row'>"
							+"<div class='col-xs-12 text-center'>"
								+"<h5 class='col-xs-12'><em>"+entityName+"</em></h5>"
							+"</div>"
						+"</div>"
					+"</div>";
								
				
				$(".dashUserNameBtn[data-userid='"+userId+"']").popover({
					content: content,
					html: true,
					trigger: "hover",
					placement: "top"
				});		

				$(".dashUserNameBtn[data-userid='"+userId+"']").on("show.bs.popover", function(){
					//console.log("show popober");
				});
			}	
			
		}
		else
		{
			//error
			$(".alert-row").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
			//alertTimeout($(".alert-row"), 3000);
		}
		
		timeout = setTimeout(function(){
						//need this so that it doesnt create a new timeout each time dropdown is changed
						(timeout != null) ? clearInterval(timeout) : false;
						//getDashboardEntityDropdown(); //make sure to add the dropdown if the data changes
						getDashboard();
					}, 60000);

		}).fail(function(){
			$('.loading-bg').fadeOut();
			$('.loading-bar').fadeOut();
			$("#total-row").html("<div class=\"alert alert-danger\" role=\"alert\"><button class=\"close\" data-dimiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>Error loading the page!</div>");
		});
}
/*
 * Create the table that shows the permissions groups
 */
function getPermissionsGroups()
{
	
	var data = new FormData();
	data.append("function", "findGroups");
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
		var tableTxt = "";
		if(d.success)
		{
			if(d.groups.length > 0)
			{
				tableTxt += "<thead><tr>"						
						+		"<th class='col-md-4'>Group Name</th>"
						+	 	"<th class='col-md-2'>Permissions</th>"
						+		"<th class='col-md-2'>Members</th>"						
						+ 		"<th class='col-md-2'>&nbsp;</th>"
						+	"</tr></thead>";
				for(var i = 0; i < d.groups.length; i++)
				{
					tableTxt += "<tr data-groupId='"+d.groups[i].groupId+"'>"
							+		"<td class='groupName'>"+d.groups[i].groupName+"</td>"
							+		"<td><a type='button' class='btn btn-default btn-block' href='/group/permissions/"+d.groups[i].groupId+"'><span class='glyphicon glyphicon-list-alt'></span></a></td>"
							+		"<td><a type='button' class='btn btn-default btn-block' href='/group/members/"+d.groups[i].groupId+"'><span class='glyphicon glyphicon-user'></span></a></td>"
							+		"<td><button type='button' class='btn btn-danger btn-block' onclick='removePermissionGroup("+d.groups[i].groupId+")'><span class='glyphicon glyphicon-trash')'></span></button></td>"
							+	"</tr>";
				}
			}
			else
			{
				tableTxt += "<tr><th><div class='alert alert-block text-center'>No Groups Available</div></th></tr>"
			}
		}
		else
		{
			tableTxt += "<tr><th><div class='alert alert-block text-center'>"+d.msg+"</div></th></tr>";
		}
		
		$("#groupTable").html(tableTxt);
	});
}

/*
* Sets the member table in groupMembers.php
* ajax Elem : input - the input that has the search value in it
* int groupId : groupId to get the members for 
*/
function searchGroupMembers(input, groupId)
{
	//inGroup = false;
	var data = new FormData();
	
	data.append("search", input.val());
	data.append("groupId", groupId);
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findGroupMembers");
	
	//kill the ajaxRequest before starting a new one
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	
	ajaxObj = $.ajax({
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
			var tableTxt = "";
			var defaultId = d.defaultId;
			console.log("Default"+defaultId);
			$(".header.groupName").text(d.groupName);
			$(".memberCount").attr("data-count",d.memberCount);
			var members = " members";
			if(d.memberCount == 1)
			{
				members = " member";
			}
			//write the count
			$(".memberCount").text(d.memberCount+members);
			
			if(d.users.length > 0)
			{
				//create header row 
				tableTxt += "<tr>"
							+"<th class='col-md-5'>Name</th>"
							+"<th class='col-md-5'>Current Group</th>"
							+"<th class='col-md-2'></th>"
							+"</tr>";
			
				//for each user
				for(var i = 0; i < d.users.length; i++)
				{
					var user = d.users[i];
					var icon = "minus";
					var btn = "danger";
					var disabled = "";
					//remove group member
					console.log("default "+defaultId);
					//set to default/basic group
					var func = "updateGroupMember("+user.userId+","+user.groupId+", "+defaultId+", "+groupId+")";	
					
					//if they are not part of this group let them add
					if(user.groupId != groupId)
					{
						icon = "plus";
						btn = "success";
						func = "updateGroupMember("+user.userId+", "+user.groupId+", "+groupId+", "+groupId+")";
					}	
					else if(defaultId == user.groupId) //else if they are part of the default group don't let them delete
					{
						disabled = "disabled";
					}					
					
					//add to the table
					tableTxt += "<tr data-user='"+user.userId+"'>"
							+"<td>"+user.firstName+" "+user.lastName+"</td>"
							+"<td class='currentGroup'>"+user.groupName+"</td>"
							+"<td><button type='button' class='btn btn-"+btn+" btn-block updateBtn' onclick='"+func+"' "+disabled+"><span class='glyphicon glyphicon-"+icon+"' "+disabled+"></span></button></td>"
							+"</tr>";
				}
			}
			else
			{
				var msg ="";
				if(input.val() == "")
				{
					//this should never happen but her ejust in case
					msg = "No users in entity";
				}
				else
				{
					msg = "No users match your search";
				}
				//show no users
				tableTxt = "<tr><th><div class='alert alert-warning alert-block text-center'>"+msg+"</div></th></tr>";
			}
			
			$("#memberTable").html(tableTxt);
		}
		else
		{
			//show error
			$("#alert-row").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
		}
	});
}

/**
* Create the group permissions table
* int groupId : groupId
* Sets the check boxes to t/f
*/
function getGroupPermissions(groupId)
{
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "getGroupPermissions");
	data.append("groupId", groupId);
	
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
			var tableTxt = "";
			//if the group name is not set (its a new group) make it blank
			(d.groupName == null) ? d.groupName = "" : false;
			//add the name of the group
			$(".header.groupName").text(d.groupName);
			$("[name='groupName']").val(d.groupName);
			if(d.permissions.length > 0)
			{
				//add the checkboxes
				
				tableTxt += "<thead><tr>"
							+"<th>Page Name</th>"
							+"<th>Page URL</th>"
							+"<th class='text-center'>Can View</br><input type='checkbox' name='canView' class='chooseAll'></th>"
							+"<th class='text-center'>Can Edit</br><input type='checkbox' name='canEdit' class='chooseAll'></th>"
							+"<th class='text-center'>Can Delete</br><input type='checkbox' name='canDelete' class='chooseAll'></th>"
							+"<th class='text-center'>Can View Restricted</br><input type='checkbox' name='canViewRes' class='chooseAll'></th>"
						+"</tr></thead>";
				$("#permissionsTable").html(tableTxt);
				//set the chooseAll btns
				$(".chooseAll").change(function(){
					var type = $(this).attr("name");
					$("[name='"+type+"']").prop("checked", this.checked);
				});
				
				for(var i = 0; i < d.permissions.length; i++)
				{
					var txt = "";
				
					var p = d.permissions[i];
					var permissionId = p.permissionsId;
					var pageName = p.name;
					var pageId = p.pageId;						
					var path = p.path;
					var canView = (p.canView == 1) ? true : false;
					var canEdit = (p.canEdit == 1) ? true : false;;
					var canDelete = (p.canDelete == 1) ? true : false;;
					var canViewRestricted = (p.canViewRestricted == 1) ? true : false;
					
					
					txt = "<tr data-page='"+pageId+"'>"
									+"<td>"+pageName+"</td>"
									+"<td>"+path+"</td>"
									+"<td class='text-center'><input name='canView' type='checkbox'></td>"
									+"<td class='text-center'><input name='canEdit' type='checkbox'></td>"
									+"<td class='text-center'><input name='canDelete' type='checkbox'></td>"
									+"<td class='text-center'><input name='canViewRes' type='checkbox'></td>"
								"</tr>";
					$("#permissionsTable").append(txt);
					$("[data-page='"+pageId+"'] [name='canView']").prop("checked", canView);
					$("[data-page='"+pageId+"'] [name='canEdit']").prop("checked", canEdit);
					$("[data-page='"+pageId+"'] [name='canDelete']").prop("checked", canDelete);
					$("[data-page='"+pageId+"'] [name='canViewRes']").prop("checked", canViewRestricted);
				}
				
				//after they're all loaded check if any of the cols are fully checked and check the 'all' button
				//maybe?
			}
			else
			{
				$(".controlBtns .edit").addClass("hide");
				$(".controlBtns .view").removeClass("hide");
				//no permissions
				tableTxt += "<tr>"+
								"<th><div class='alert alert-warning alert-block text-center'>No pages in database for group "+d.groupName+"</div></th>"
							"</tr>";
				$("#permissionsTable").html(tableTxt);
			}
			
		}
		else
		{
			//errrror
			$("#alert-row").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
			alertTimeout($("#alert-row"), 3000);
		}
	});

}
	
	
/**
 * Sets the question report page
 * Adds the checkin details to the top of the page
 * Creates a table showing the answers and a pass or fail next to each
**/
function getQuestionAnswers(checkinId)
{
	var data = new FormData();
	data.append("function", "getQuestionAnswers");
	data.append("checkinId", checkinId);
	data.append("timestamp", new Date().getTime()/1000);
	
	$.ajax({
		url: path+"include/function.php", 
		data: data, 
		type: "POST", 
		processData: false, 
		contentType: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		var tableTxt = "";
		var questionDetails = "";
		
		if(d.success)
		{
			var timeStr = d.dateObj.month+" "+d.dateObj.mday+" "+d.dateObj.year;
			
			//add question details
			questionDetails += "<div class='row text-center' ><div class='col-md-12'><h2>"+d.siteName+"</h2></div></div>"
							+	"<div class='row text-center'><div class='col-md-12'><h4>"+d.userName+"</h4></div></div>"
							+"<div class='row text-center'><div class='col-md-12'><h5>"+timeStr+"</h5></div></div>"
		
			if(d.questionAnswers.length > 0)
			{
				tableTxt += "<thead><tr>"
							+	"<th class='col-md-9'>Question</th>"
							+	"<th class='col-md-1 text-center'>Answer</th>"
							+	"<th class='col-md-1 text-center'>Safe Answer</th>"
							+	"<th class='col-md-1 text-center'>Pass</th>"
							+"</tr></thead>";
				for(var i = 0; i < d.questionAnswers.length; i++)
				{
					var qa = d.questionAnswers[i];
					console.log(qa);
					var answer = "";
					var safeAnswer = "Both";
					//question safe
					var safeIcon = "minus";
					
					
					switch(parseInt(qa.answer))
					{
						case 0:
							answer = "No";
							break;
						case 1:
							answer = "Yes";
							break;
						default:
							answer = "Both";
							break;
					}
					
					switch(parseInt(qa.safeAnswer))
					{
						case 0:
							safeAnswer = "No";
							break;
						case 1:
							safeAnswer = "Yes";
							break;
						default:	
							safeAnswer = "Both";
							break;
					}
					
					if(qa.safeAnswer != 2 && qa.answer != qa.safeAnswer)
					{
						//question not safe
						safeIcon = "remove";
					}
					else
					{
						safeIcon = "ok";
					}
					
					tableTxt += "<tr>"
								+	"<td>"+qa.question+"</td>"
								+	"<td class='text-center'>"+answer+"</td>"
								+	"<td class='text-center'>"+safeAnswer+"</td>"
								+	"<td class='text-center'><span class='glyphicon glyphicon-"+safeIcon+"'></span></td>"
								+"</tr>";
				}
			}
			else
			{
				//no questions answered
				tableTxt += "<tr>"
							+"<th><div class='alert alert-warning text-center'>No Questions Answered For This Entry</div></th>"
							+"</tr>";
			}
		}
		else
		{
			//error
			tableTxt += "<tr>"
						+	"<th><div class='alert alert-warning text-center'>"+d.msg+"</div></th>";
						+"</tr>";
		}
		
		$(".questionSetDetails").html(questionDetails);
		$(".questionSetAnswers table").html(tableTxt);
	});
}

/**
 * Creates a list of checkins based on the site id for the user to choose the question list from 
 * This will be filtered out
**/
function getSiteQuestionTable(siteId)
{
	var data = new FormData();
	data.append("function", "getSiteQuestionSet");
	data.append("siteId", siteId);
	data.append("timestamp", new Date().getTime()/1000);	
	
	$.ajax({
		url: path+"include/function.php",
		type: "POST",
		data: data, 
		processData: false, 
		contentType: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		
		var tableTxt = "";
		
		if(d.success)
		{	
			$(".pageHeader .siteName").text(": "+d.siteName);
			if(d.questionSets.length > 0)
			{
				tableTxt += "<tr>"
							+	"<th>Member</th>"
							+	"<th>Date</th>"
							+	"<th>Time</th>"
							+ 	"<th class='col-md-2'>&nbsp;</th>"
							+"</tr>";
				
				for(var i = 0; i < d.questionSets.length; i++)
				{
					var qs = d.questionSets[i];
					var dateObj = qs.dateObj;
					var date = dateObj.mday+"/"+dateObj.mon;
					
					var currYear = new Date().getFullYear();
					if(currYear != dateObj.year)
					{
						date+= "/"+dateObj.yday;
					}
					
					var time = dateObj.hours+":"+dateObj.minutes;
					
					
					tableTxt += "<tr>"
								+"<td>"+qs.firstName+" "+qs.lastName+"</td>"
								+"<td>"+date+"</td>"
								+"<td>"+time+"</td>"
								+"<td><a type='button' class='btn btn-default btn-block' href='/question-report/"+qs.checkinId+"'>View</a></td>"
							+"</tr>";
				}
			}
			else
			{
				//no checkins
				tableTxt += "<tr>"
								+"<th><div class='alert alert-warning alert-block text-center'>There are no question sets for this site</div></th>"
							+"</tr>";
			}			
		}
		else
		{
			//siteId doesn't exist or there's an error
			tableTxt += "<tr>"
						+ "<th><div class='alert alert-warning alert-block text-center'>"+d.msg+"</div></th>"
						+"</tr>";
			
		}
		
		console.log(tableTxt);
		$("#siteQuestionTable").html(tableTxt);
	});
}

/**
 * Sets the team member table in editMyTeam.php
 * ajax Elem : input - the input that has the search value in it
 **/
function searchTeamMembers(input, userId)
{
	//remove any alerts
	$("#alert-row").empty();
	
	var data = new FormData();
	data.append("search", input.val());
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findTeamMembers");
	data.append("userId", userId);
	
	//kill the ajaxRequest before starting a new one
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	
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
			//in team 
			var inTeamTbl = "";
			if(d.inTeam.length > 0)
			{
				inTeamTbl += "<tr>"
							+"<th>Name</th>"
							+"<th class='col-md-3'></th>"
							+"</tr>";
				for(var i = 0; i < d.inTeam.length; i++)
				{
					var oc = "removeFollower("+userId+", "+d.inTeam[i].userId+", "+d.entityId+")";
				
					inTeamTbl +="<tr>"
								+"<td>"+d.inTeam[i].firstName+" "+d.inTeam[i].lastName+"</td>"
								+"<td><button type='button' class='btn btn-danger btn-block' onclick='"+oc+"'><span class='glyphicon glyphicon-minus'></span></button></td>"
								+"</tr>";
				}
			}
			else
			{
				//no people in team
				inTeamTbl += "<tr>"
							+	"<th><div class='alert alert-block alert-warning'>No Users in Your Team</div></th>"
							+"</tr>";
			}
			
			
			
			//not in team
			var notInTeamTbl = "";
			if(d.notInTeam.length > 0)
			{
				notInTeamTbl += "<tr>"
								+"<th>Name</th>"
								+"<th class='col-md-3'></th>"
								+"</tr>";
				for(var i = 0; i < d.notInTeam.length; i++)
				{
					var oc = "addFollower("+userId+", "+d.notInTeam[i].userId+", "+d.entityId+")";
					notInTeamTbl += "<tr>"
									+"<td>"+d.notInTeam[i].firstName+" "+d.notInTeam[i].lastName+"</td>"
									+"<td><button type='button' class='btn btn-success btn-block' onclick='"+oc+"'><span class='glyphicon glyphicon-plus'></span></button></td>"
								+"</tr>";
				}
			}
			else
			{
				notInTeamTbl += "<tr>"
								+"<th><div class='alert alert-block alert-warning'>No Users to Add to Your Team</div></th>"									
								+"</tr>";
			}
			
			$("#inTeamTable").html(inTeamTbl); //create the team table
			$("#notInTeamTable").html(notInTeamTbl); //create the not team table
		}
		else
		{
			//error
			$("#alert-row").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
		}
	});
}

/** 
 * Creates the text to go in the site code table
 * Only run if there's an entry
 * siteCode = [
 *	{
 *		accessEnd : int
 * 		accessObj : {dateObj}
 *		code : int
 * 		users : [user]
 *  }	
 *]
 */ 
function siteCodeTableTxt(siteCodes)
{
	//create the head row
	tblTxt = "<tr>"
				+"<th class='col-md-2'>Code</th>"
				+"<th class='col-md-2'>Valid until</th>"
				+"<th>Members using code</th>"
			+"</tr>";

	for(var i = 0; i < siteCodes.length; i++)
	{
		//wipe current user rows
		var userTblTxt = "";
		var accessStr = "";
		var dateObj = siteCodes[i].accessObj;
		
		if(siteCodes[i].accessEnd == null)
		{
			accessStr = dateObj;
		}
		else
		{
			accessStr = dateObj.mday+"/"+dateObj.mon+"/"+dateObj.yday;
		}
		//show code and access end
		tblTxt += "<tr>"
					+"<td>"+siteCodes[i].code+"</td>"
					+"<td>"+accessStr+"</td>"
							

		//if there is a user
		if(siteCodes[i].users.length > 0)
		{
		
			//add the name of the first one to the current row
			tblTxt += "<td>"+siteCodes[i].users[0].firstName+"</td>";
			for(var x = 1; x < siteCodes[i].users.length; x++)
			{
				//make a list of the rest of them
				userTblTxt += "<tr>"
								+"<td colspan='2'></td>"
								+"<td>"+siteCodes[i].users[x].firstName+"</td>"
								+"</tr>";
			}
		}
		else //there are no users
		{
			//show this message
			tblTxt += "<td>No Current Users</td>";
		}			
		//finish the top row
		tblTxt += "</tr>"
					  +userTblTxt; //add the rest of the users to the next row
			
	}
	return tblTxt;
}

/** 
 * Creates the table of site codes
 * seperated into current/expired
 * shows the users and the entity they were part of when they joined the site
 */
function setSiteCodeTable(site, currentTable, expiredTable)
{
	var siteCodes = site.siteCodes;
	
	var current = siteCodes.current;
	var expired = siteCodes.expired;
	
	var currentTblTxt = "";
	var expiredTblTxt = "";
	
	if(current.length > 0)
	{
		currentTblTxt = siteCodeTableTxt(current);
	}
	else
	{
		currentTblTxt = "<tr>"
							+"<th>"
								+"<div class='alert alert-block alert-warning text-center'>No current codes for this site</div>"
							+"</th>"
						+"</tr>";
	}
	
	
	if(expired.length > 0)
	{
		$(".expiredCodes hidden").removeClass("hidden");
		expiredTblTxt = siteCodeTableTxt(expired);
	}
	else
	{
		//do nothing
	}
		
	currentTable.html(currentTblTxt);
	expiredTable.html(expiredTblTxt);
}

/**
 * Gets the users team for current entity
 * Makes a map of their team location
 * Makes an extendable table for the team
 * pos = html5 geolocation response
 * 		- getBrowserLocation(success,error);
 */
function getMyTeam()
{
	//console.log(mapObj.map);
	//set the map and move the boundaries to the markers
	//if(mapObj.map == null)
	//{
	//	mapObj.map = showMyTeamMap("myTeamMap", []);
	//}

	//start map if not started 
	//mapObj.map = showMyTeamMap("myTeamMap");
	
	var data = new FormData();
	data.append("timestamp", new Date().getTime()/1000);
	data.append("function", "findMyTeam");

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
			var markers = [];
		
			//pos = users location
			var tableTxt ="";
			var recent = "";
			var notRecent = "";
			//if there are team members
			if(d.team.length > 0)
			{
				//find bounds of team
				for(var i = 0; i < d.team.length; i++)
				{
					var member = d.team[i];
					
					//if they have recent activity
					if(member.checkinId != null)
					{
						if(recent == "") //if no recent activity before add the header
						{
							recent += "<tr><th colspan='4'>Recent Activity</th></tr>"
										 +"<tr>"
											+"<th></th>"
											+"<th>Name</th>"
											+"<th>Last Location</th>"
											+"<th>Time</th>"
										 +"</tr>";
						}
						
						//find location
						var siteName = "";
						var dateObj = moment.unix(member.time).tz(member.timezone);
						var date = dateObj.format("DD-MM");
						var time = dateObj.format("HH:mm");
						
						if(member.isPulse == false)
						{
							siteName = member.siteName;
						}
						
						
						//add the row
						recent += "<tr data-checkin='"+member.checkinId+"'>"
								+"<th class='col-md-2'><a type='button' href='/user/view/"+member.userId+"' class='btn btn-default btn-block'><span class='glyphicon glyphicon-user'></span></a></th>"
								+"<th class='col-md-2'><a type='button' href='/user/view/"+member.userId+"' class='btn btn-default btn-block'><span class='glyphicon glyphicon-user'></span></a></th>"
								+ "<td>"+member.firstName+" "+member.lastName+"</td>"
								+"<td class='location'>"+siteName+"</td>"
								+"<td>"+date+" &nbsp;&nbsp; "+time+"</td>"								
								+"</tr>";						
						
						//make a marker
						markers.push(member);
					}
					else
					{
						if(notRecent == "")
						{
							notRecent += "<tr><th colspan='4'>Other Team Members</th></tr>"
										 +"<tr>"
											+"<th></th>"
											+"<th colspan='3'>Name</th>"
											//+"<th>Last Location</th>"
											//+"<th>Time</th>"
										 +"</tr>";
						}
						//not recent
						notRecent += "<tr>"
								+"<th class='col-md-2'><a type='button' href='/user/view/"+member.userId+"' class='btn btn-default btn-block'><span class='glyphicon glyphicon-user'></span></a></th>"
								+"<th class='col-md-2'><a type='button' href='/user/view/"+member.userId+"' class='btn btn-default btn-block'><span class='glyphicon glyphicon-user'></span></a></th>"
								+ "<td colspan='3'>"+member.firstName+" "+member.lastName+"</td>"
								//+"<td></td>"
								//+"<td></td>"
								"</tr>";
					}					
				}
				
				tableTxt = recent;
				//if there is no recent activity
				if(recent == "")
				{
					tableTxt += "<tr><th colspan='4'><div class='alert alert-block alert-warning'>No recent activity</div></th></tr>";
				}
				else
				{					
					//add the space between tables
					tableTxt += "<tr><th colspan='4'>&nbsp;</th></tr>";
				}
				tableTxt += notRecent;
			}
			else
			{
				//no team
				tableTxt += "<tr><th colspan='3'><div class='alert alert-block alert-warning'>No Team Found</div></th></tr>";
			}
			
			$("#myTeamTable").html(tableTxt);
			
			//fill in the suburb
			for(var i = 0; i < d.team.length; i++)
			{
				if(d.team[i].isPulse == true)
				{
					//find the suburb of the pulse from the lat/lng
					locationForDash(d.team[i], function(l, id){					
						$("[data-checkin='"+id+"'] .location").html(l);
					});
				}
			}
			
			if(mapObj.map == null)
			{
				
				//set the map and move the boundaries to the markers
				mapObj.map = showMyTeamMap("myTeamMap", markers);
			}
			else if(markers.length > 0)
			{
				//console.log("markers are more then 0");
				var bounds = getBoundsFromMarkers(markers)
				//this makes the map surround the new markers. 
				//will reverse user's zoom/pan
				//mapObj.map.fitBounds(bounds);
											
				//unset the clusters
				mapObj.userClusters.clearMarkers();
				//unset the markers
				mapObj.userMarkers = unsetMarkerSet(mapObj.userMarkers);	
			}
			//set the users to the map
			mapObj = setUserMarkers(mapObj, markers);
		}
		else
		{
			$(".alert-row").html("<div class='alert alert-danger alert-block text-center'>"+d.msg+"</div>");
		}
		
		setTimeout(function(){
			getMyTeam();
		}, 15000);
	});
}

/** 
 * Get the pronto data table for prontoData.php
 * Fliter for the search and show the jobs relevant
 * Show the gauges
 */
function getProntoData()
{
	var search = $("#search").val();
	var data = new FormData();
	data.append("search", search);
	data.append("function", "getProntoData");
	data.append("timestamp", new Date().getTime()/1000);
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	ajaxObj = $.ajax({
		url: path+"include/function.php", 
		data: data, 
		type: "POST",
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			var tableTxt = "";
			//clear the table
			$("#prontoTable").empty();
			if(d.pronto.length > 0)
			{				
				$("#prontoTable").append("<thead>"
										+"<tr>"
											+"<th>SB Name</th>"
											+"<th>PNUM</th>"
											+"<th>Project Name</th>"
											+"<th>Person Responsible</th>"
											+"<th>Work Remaining</th>"
											+"<th>Fuel Remaining</th>"
											+"<th>Lost on Job</th>"
											+"<th>Variation</th>"
										+"</tr>"
										+"</thead><tbody>");
				
				for(var i = 0; i < d.pronto.length; i++)
				{
					var p = d.pronto[i];				
					
					//site Name
					var name = (p.name == null) ? "" : "<a href='/site/view/"+p.siteId+"'>"+p.name+"</a>";
					
					//variation
					var variation = (p.variation < 0) ? "-$"+p.variation.replace("-", "") : "$"+p.variation;
					//left on job
					var leftOnJob = (p.leftOnJob < 0) ? "-$"+p.leftOnJob.replace("-", "") : "$0";
													
					tableTxt = "<tr>"
									+"<td class='siteName'>"+name+"</td>"
									+"<td class='pnum'>"+p.projectId+"</td>"
									+"<td class='projectName'>"+p.projectName+"</td>"
									+"<td class='manager'>"+p.manager+"</td>"
									+"<td class='completion'><div id='completion_"+p.projectStatusId+"'></div></td>"
									+"<td class='budget'><div id='budget_"+p.projectStatusId+"'></div></td>"
									+"<td class='leftOnJob'>"+leftOnJob+"</td>"
									+"<td class='variation'>"+variation+"</td>"
								+"</tr>";
				
					$("#prontoTable").append(tableTxt);								
				}
							
				$("#prontoTable").append("</tbody>");
				
				var statusId = [];
				//go through each of the ids and add the gauge
				for(var i = 0; i < d.pronto.length; i++)
				{	
					var p = d.pronto[i];
					//if projectStatus hasn't been updated, update it
					if(statusId.indexOf(p.projectStatusId) == -1)
					{	
						var budget = (p.budget < 0) ? 0 : p.budget;
						var pid = p.projectStatusId;
						var pointsListId = p.pointsListId; 
						var pointsListValid = p.pointsListValid;
						
						//do the gauge
						//if the points list is not linked don't show the gauge. 
						//Should check for valid points lists but one job has one that isn't valid
						if(pointsListId != null && pointsListId != "")
						{
							//if points list isn't valid highlight the box and show tooltip on hover
							if(pointsListValid == false)
							{
								$("#completion_"+pid).addClass("bg-danger");
								$("#completion_"+pid).parent().parent().tooltip({
									title : "There is a problem with the points list for this site.<br/>Please Review",
									html: true
								});
							}
							var c = new JustGage({
								id: "completion_"+pid,
								value: p.completion,
								min: 0,
								max: 100,
								title: " ",
								label: "%",
								levelColors: ["#5cb85c", "#f0ad4e", "#d9534f"]
							});
							
							
						}
						
						 var b = new JustGage({
							id: "budget_"+pid,
							value: budget,
							min: 0,
							max: 100,
							title: " ",
							label: "%",
							levelColors: ["#d9534f","#f0ad4e","#5cb85c"]
						});
						statusId.push(p.projectStatusId);
					}				
				}				
			}
			else
			{
				//no data
				tableTxt = "<div class='alert alert-block text-center alert-error'>No Pronto Data</div>";
			}		
		}	
	});
}

/** 
 * Sets the userLog page
 * Uses the userId to find all the places they've been in a set time 
 * Sets the select box depending current day
 * Changes label on page depending on what is selected
 */
function createUserLog(userId)
{			
	var d = new Date();
	if($("#selectTime option").length == 0)
	{				
		var year = d.getFullYear();
		var month = d.getMonth();				
		var day = d.getDate();		
	
		//create the select box
		var selectBox = "<option value='0'>All</option>"
						+"<option value='"+new Date(year, month, day, 0,0,0,0).getTime()/1000+"'>Daily</option>"
						+"<option value='"+new Date(year, month, day-7, 0,0,0,0).getTime()/1000+"'>Weekly</option>"
						+"<option value='"+new Date(year, month-1, day, 0,0,0,0).getTime()/1000+"'>Montly</option>"
						+"<option value='"+new Date(year-1, month, day, 0,0,0,0).getTime()/1000+"'>Yearly</option>";
		$("#selectTime").html(selectBox);
		//select daily to begin
		$("#selectTime").val(new Date(year, month, day, 0,0,0,0).getTime()/1000);
	}
	
	var timeVal = $("#selectTime").val();
	var searchVal = $("#search").val();
	var timeStr = $("#selectTime option:selected").text();
	
	//change the label depending on time selected
	var fromTime = timestampToDate(timeVal, "d-m-Y");
	var toTime = timestampToDate(d.getTime()/1000, "d-m-Y");
	var betweenStr = "";
	
	if(timeVal == 0) //all results has no label
	{
		betweenStr = "";
	}
	else if(fromTime.match(toTime)) //if daily only show it once
	{
		betweenStr = "User Activty for "+toTime;
	}
	else //between two set dates. Show both.
	{		
		betweenStr =  "User Activity Between "+fromTime+" and "+toTime;
	}
	
	$("#betweenDateLabel").html("<h3>"+betweenStr+"</h3>");
	
	var data = new FormData();
	data.append("function", "findUserLog");
	data.append("timestamp", d.getTime()/1000);
	data.append("timeVal", timeVal);
	data.append("search", searchVal);
	data.append("userId", userId);
	
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	ajaxObj = $.ajax({
		data: data,
		url: path+"include/function.php", 
		type: "POST", 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);			
		if(d.success)
		{
			//add siteName to top of the screen
			$(".pageHeader span").text(": "+d.userName);
			$(".userViewBtn").html("<br/><a href='/user/view/"+userId+"' class='btn btn-default btn-block'>View Profile</a>");
			if(d.userLog.length > 0)
			{
				createUserLogPanels(d, $("#userLogPanel"));
			}
			else //no results
			{
				var err = "";
				
				(timeVal <= 0) ? timeStr = "" : false;
				err += "There are no "+timeStr.toLowerCase()+" results";
				err += (searchVal == "") ? "" : " for the search '"+searchVal+"'";
				
				$("#userLogPanel").html("<div class='alert alert-block alert-warning text-center'>"+err+"</div>");			
			}
		}
		else //there was an issue in the php
		{
			$("#alert-row").html("<div class='alert alert-error alert-block text-center'>"+d.msg+"</div>");
		}
	});			
}

/** 
 * Creates the user log panels
 * They collapse and show each grouped check in data
 **/
function createUserLogPanels(d, panelElem, timeVal, timeStr)
{
	var panel = "";
	var pulses = [];
	var startedPanel = false;
	var totalTime = [];
	for(var i = d.userLog.length-1; i >= 0; i--)
	{
		
		for(var x = 0; x < d.userLog[i].length; x++)
		{
			var s = d.userLog[i][x];
			var date = s.dateObj.month.substr(0,3)+" "+s.dateObj.mday;
			//if not this year show the year
			if(!s.dateObj.year.match(new Date().getFullYear()))
			{
				date = s.dateObj.mday+"/"+s.dateObj.mon+"/"+s.dateObj.yday;
			}
			var time = addZero(s.dateObj.hours)+" : "+addZero(s.dateObj.minutes);
			var timezone = s.timezone;
			var slash = timezone.lastIndexOf("/")+1;
			timezone = timezone.substr(slash);
			timezone = timezone.replace(/_/g, " ");
			
			//use the checkinType to group them
			if(s.isPulse == false) //checkin start a list
			{
				switch(parseInt(s.checkinTypeId))
				{
					case 1:
						startedPanel = true;
						//start the checkin
						panel += "<div class='panel panel-default logpanel'>"
									+"  <div class='panel-heading' role='tab' id='listGroupHeading_"+s.checkinId+"'>"
									+		"<h4> &nbsp; <!-- nbsp to add margin -->"
									+			"<div class='col-xs-4'>"
									+				"<a class='collapsed col-xs-8' data-toggle='collapse' href='#listGroup_"+s.checkinId+"' aria-expanded='false' aria-controls='listGroup_"+s.checkinId+"'>"
									+					s.name
									+				"</a>"
									+				"<a class='btn btn-link col-xs-3' type='button' href='/site/view/"+s.siteId+"'>View site</a>"
									+			"</div>"
									+			"<div class='col-xs-2'>"+s.checkinType+"</div>"
									+			"<div class='col-xs-2 total-hours'></div>"
									+			"<div class='col-xs-1' data-startTime='"+s.time+"'>"+date+"</div>"
									+			"<div class='col-xs-1 time'>"+time+"</div>"	
									+			"<div class='col-xs-1'>"+timezone+"</div>"
									+		"</h4>"
									+	"</div>"
									+ "<div id='listGroup_"+s.checkinId+"' class='panel-collapse collapse' role='tabpanel' aria-labelledby='listGroupHeading_"+s.checkinId+"'>"
									+ "<ul class='list-group'>";				
						
						break;
					case 10:
					case 2:
						panel += 	" <div class='list-group-item'>&nbsp;"
										+	"<div class='col-xs-3'>"+s.name+"</div>"
										+	"<div class='col-xs-3'>"+s.checkinType+"</div>"
										+	"<div class='col-xs-1 col-xs-offset-2'>"+date+"</div>"
										+	"<div class='col-xs-1 time'>"+time+"</div>"	
										+	"<div class='col-xs-1'>"+timezone+"</div>"
									+"</div>";
						
						break;
					case 3:
						startedPanel = false;
						//end the checkin
						 panel += 			" <div class='list-group-item end'>&nbsp;"
												+	"<div class='col-xs-3'>"+s.name+"</div>"
												+	"<div class='col-xs-3'>"+s.checkinType+"</div>"
												+	"<div class='col-xs-1 col-xs-offset-2' data-endTime='"+s.time+"'>"+date+"</div>"
												+	"<div class='col-xs-1'>"+time+"</div>"	
												+	"<div class='col-xs-1'>"+timezone+"</div>"
											+"</div>"
									 +	"</ul> <!-- end .list-group-item -->"
									 +"</div> <!-- end .list-group -->"
									 +"</div> <!-- end .panel--> ";
						break;
					default:
						break;
				}
				
			}
			else
			{
				console.log("pulse");
				//make its own entry
				panel += "<div class='panel panel-default panel-pulse'>"
						+"  <div class='panel-heading' role='tab' id='listGroupHeading_"+s.checkinId+"'>"
						+		"<h4> &nbsp; <!-- nbsp to add margin -->"	
						+ 			"<div class='col-xs-4 suburb'><i class='fa fa-spinner fa-spin'></i></div>"
						+			"<div class='col-xs-2'>"+s.checkinType+"</div>"									
						+			"<div class='col-xs-1 col-xs-offset-2'>"+date+"</div>"
						+			"<div class='col-xs-1'>"+time+"</div>"
						+			"<div class='col-xs-1'>"+timezone+"</div>"
						+		"</h4>"
						+	"</div>"
						+ "</div>";			

				//add to pulses array to search for location
				pulses.push(s);							
			}		
		}
		//if a panel has been started but there is no check out to close it, don't forget to end it.
		if(startedPanel == true)
		{
			//if action is started but no check out 
			panel += "</ul> <!-- end .list-group-item -->"
					+"</div> <!-- end .list-group -->"
			 +"</div> <!-- end .panel--> ";					
		}
	}

	panelElem.html(panel);
	
	var allTotal = 0;
	
	//for each panel
	//find the heading and the time in the heading
	//find the last .list-group-item in the panel and find the time
	//find the difference and display in the panel header
	$(".panel:not(.panel-pulse)").each(function(){
		var startTime = $(this).find(".panel-heading [data-startTime]").attr("data-startTime");
		var endTime = new Date().getTime()/1000; //make default end time now
		if($(this).find(".list-group-item [data-endTime]").length > 0) //if end time is specified change it
		{
			var endTime = $(this).find(".list-group-item [data-endTime]").attr("data-endTime");
		}
		
		var str = "";
		var total = endTime - startTime;
		var d = Math.floor(total/(24*60*60)); // days = time / 24*60*60
		var h = Math.floor((total % (24*60*60) / (60*60))); //hours = remainder of days / 60*60
		var m = Math.ceil((total % (60*60)) / 60); //minutes is the remainder of the hour / 60
		if(d > 0)
		{
			str += d+" day";
			(d != 1) ? str += "s<br/>" : str += "<br/>";
		}
		else
		{
			if(h > 0)
			{
				str += h+" hour";
				(h != 1) ? str += "s<br/>" : str+= "<br/>";
			}
			if(m > 0)
			{
				str += m+" minute";
				(m != 1) ? str += "s" : false;
			}
		}
		if(total < 0 )
		{
			str = h+" hours<br/> "+m+" minutes";
		}
		
		$(this).find(".total-hours").html(str);
		
		//add to all total to find average
		allTotal += total;
	});
	
	var average = allTotal/$(".panel:not(.panel-pulse)").length;
	var ad = Math.floor(average/(24*60*60)); // days = time / 24*60*60
	var ah = Math.floor((average % (24*60*60) / (60*60))); //hours = remainder of days / 60*60
	var am = Math.ceil((average % (60*60)) / 60); //minutes is the remainder of the hour / 60
	
	//average time here?

	
	//after panels complete add suburbs to pulses
	for(var p = 0; p < pulses.length; p++)
	{
		console.log(pulses);
		var checkin = pulses[p];				
		locationForDash(checkin, function(l, id){
			$("#listGroupHeading_"+id+" .suburb").html(l);
		});				
	}
}




/** 
 * Sets the siteLog page
 * Uses the siteId to find all the users that have been here in a set time 
 * Sets the select box depending current day
 * Changes label on page depending on what is selected
 */
function createSiteLog(siteId)
{			
	console.log("Create sitelog");
	var d = new Date();
	if($("#selectTime option").length == 0)
	{	
		
		var year = d.getFullYear();
		var month = d.getMonth();				
		var day = d.getDate();		
	
		//create the select box
		var selectBox = "<option value='0'>All</option>"
						+"<option value='"+new Date(year, month, day, 0,0,0,0).getTime()/1000+"'>Daily</option>"
						+"<option value='"+new Date(year, month, day-7, 0,0,0,0).getTime()/1000+"'>Weekly</option>"
						+"<option value='"+new Date(year, month-1, day, 0,0,0,0).getTime()/1000+"'>Montly</option>"
						+"<option value='"+new Date(year-1, month, day, 0,0,0,0).getTime()/1000+"'>Yearly</option>";
		$("#selectTime").html(selectBox);
		//select daily to begin
		$("#selectTime").val(new Date(year, month, day, 0,0,0,0).getTime()/1000);
	}
	
	var timeVal = $("#selectTime").val();
	var searchVal = $("#search").val();
	var timeStr = $("#selectTime option:selected").text();
	
	//change this depending on time seleted
	var fromTime = timestampToDate(timeVal, "d-m-Y");
	var toTime = timestampToDate(d.getTime()/1000, "d-m-Y");
	var betweenStr = "";
	
	if(timeVal == 0) //all results has no label
	{
		betweenStr = "";
	}
	else if(fromTime.match(toTime)) //if daily only show it once
	{
		betweenStr = "Site Activty for "+toTime;
	}
	else //between two set dates. Show both.
	{		
		betweenStr =  "Site Activity Between "+fromTime+" and "+toTime;
	}
	
	$("#betweenDateLabel").html("<h3>"+betweenStr+"</h3>");
	
	var data = new FormData();
	data.append("function", "findSiteLog");
	data.append("timestamp", d.getTime()/1000);
	data.append("timeVal", timeVal);
	data.append("search", searchVal);
	data.append("siteId", siteId);
	
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	ajaxObj = $.ajax({
		data: data,
		url: path+"include/function.php", 
		type: "POST", 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		console.log(d);
		if(d.success)
		{
			//add siteName to top of the screen
			$(".pageHeader span").text(": "+d.siteName);
			$(".siteViewBtn").html("<br/><a href='/site/view/"+siteId+"' class='btn btn-default btn-block'>View Site</a>");
			var tbl = "";
			if(d.siteLog.length > 0)
			{
				tbl += "<tr>"
							+"<th>Name</th>"
							+"<th class='col-xs-1'>Date</th>"
							+"<th class='col-xs-2' colspan='2'>Time</th>"
						+"</tr>";					
				for(var i = 0; i < d.siteLog.length; i++)
				{
					var s = d.siteLog[i];
					var date = s.dateObj.month.substr(0,3)+" "+s.dateObj.mday;
					//if not this year show the year
					if(s.dateObj.year != new Date().getFullYear)
					{
						date = s.dateObj.mday+"/"+s.dateObj.mon+"/"+s.dateObj.yday;
					}
					var time = addZero(s.dateObj.hours)+" : "+addZero(s.dateObj.minutes);
					var timezone = s.timezone;
					var slash = timezone.lastIndexOf("/")+1;
					timezone = timezone.substr(slash);
					tbl += "<tr>"
								+"<td><a href='/user/view/"+s.userId+"' class='btn btn-link'>"+s.firstName+" "+s.lastName+"</a></td>"
								+"<td>"+date+"</td>"
								+"<td>"+time+"</td>"
								+"<!--Dont need timezone for site I think"
								+"<td>"+timezone+"</td>-->"
							+"</tr>";
				}
			}
			else //no results
			{
				var err = "";
				
				(timeVal <= 0) ? timeStr = "" : false;
				err += "There are no "+timeStr.toLowerCase()+" results";
				err += (searchVal == "") ? "" : " for the search '"+searchVal+"'";
				
				tbl += "<tr>"
							+"<th><div class='alert alert-block alert-warning text-center'>"+err+"</div></th>"
						+"</tr>";
			}
			
			$("#siteLogTable").html(tbl);
		}
		else //there was an issue in the php
		{
			$("#alert-row").html("<div class='alert alert-error alert-block text-center'>"+msg+"</div>");
		}		
	});
}

/** 
 * Sets the profileLog page
 * Uses the userId to find all the places they've been in a set time 
 * Sets the select box depending current day
 * Changes label on page depending on what is selected
 */
function createProfileLog()
{			
	var d = new Date();
	if($("#selectTime option").length == 0)
	{				
		var year = d.getFullYear();
		var month = d.getMonth();				
		var day = d.getDate();		
	
		//create the select box
		var selectBox = "<option value='0'>All</option>"
						+"<option value='"+new Date(year, month, day, 0,0,0,0).getTime()/1000+"'>Daily</option>"
						+"<option value='"+new Date(year, month, day-7, 0,0,0,0).getTime()/1000+"'>Weekly</option>"
						+"<option value='"+new Date(year, month-1, day, 0,0,0,0).getTime()/1000+"'>Montly</option>"
						+"<option value='"+new Date(year-1, month, day, 0,0,0,0).getTime()/1000+"'>Yearly</option>";
		$("#selectTime").html(selectBox);
		//select daily to begin
		$("#selectTime").val(new Date(year, month, day, 0,0,0,0).getTime()/1000);
	}
	
	var timeVal = $("#selectTime").val();
	var searchVal = $("#search").val();
	var timeStr = $("#selectTime option:selected").text();
	
	//change the label depending on time selected
	var fromTime = timestampToDate(timeVal, "d-m-Y");
	var toTime = timestampToDate(d.getTime()/1000, "d-m-Y");
	var betweenStr = "";
	
	if(timeVal == 0) //all results has no label
	{
		betweenStr = "";
	}
	else if(fromTime.match(toTime)) //if daily only show it once
	{
		betweenStr = "User Activty for "+toTime;
	}
	else //between two set dates. Show both.
	{		
		betweenStr =  "User Activity Between "+fromTime+" and "+toTime;
	}
	
	$("#betweenDateLabel").html("<h3>"+betweenStr+"</h3>");
	
	var data = new FormData();
	data.append("function", "findUserLog");
	data.append("timestamp", d.getTime()/1000);
	data.append("entityId", -1);
	data.append("timeVal", timeVal);
	data.append("search", searchVal);
	
	if(ajaxObj != null)
	{
		ajaxObj.abort();
	}
	ajaxObj = $.ajax({
		data: data,
		url: path+"include/function.php", 
		type: "POST", 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);			
		if(d.success)
		{
			//add siteName to top of the screen
			$(".pageHeader span").text(": "+d.userName);
			$(".userViewBtn").html("<br/><a href='/profile/' class='btn btn-default btn-block'>View Profile</a>");
			
			if(d.userLog.length > 0)
			{
				createUserLogPanels(d, $("#profileLogPanel"));			
			}
			else //no results
			{
				var err = "";
				
				(timeVal <= 0) ? timeStr = "" : false;
				err += "There are no "+timeStr.toLowerCase()+" results";
				err += (searchVal == "") ? "" : " for the search '"+searchVal+"'";
				
				$("#profileLogPanel").html("<div class='alert alert-block alert-warning text-center'>"+err+"</div>");			
			}
		}
		else //there was an issue in the php
		{
			$("#alert-row").html("<div class='alert alert-error alert-block text-center'>"+d.msg+"</div>");
		}		
	});
}

/**
 * Finds the current emergency info for the site and creates a table with the option to remove them
 */
function setSiteEmergencyTable(siteId, elem, edit)
{	
	var emergencyData = new FormData();
	emergencyData.append("function", "findEmergencyBySite");
	emergencyData.append("timestamp", new Date().getTime()/1000);
	emergencyData.append("siteId", siteId);
	$.ajax({
		url: path+"include/function.php",
		type: "POST", 
		data: emergencyData, 
		contentType: false, 
		processData: false
	}).done(function(d){
		d = JSON.parse(d);
		if(d.success)
		{
			var popovers = [];
			
			if(d.emergency.length > 0)
			{
				var emergencyTable = "<table class='table table-bordered'>";
				//set the emergency numbers
				for(var i = 0 ; i < d.emergency.length; i++)
				{
					var e = d.emergency[i];
					emergencyTable += "<tr data-emergencyId='"+e.emergencyId+"'>";
					if(edit == true)
					{
						emergencyTable += "<td class='col-xs-2'>"
										+"<button type='button' class='btn btn-danger btn-block' onclick=\"removeEmergencyContact("+e.emergencyId+", '"+encodeURI(e.emergencyName)+"'); setSiteEmergencyTable("+siteId+", $('"+elem.selector+"'), "+edit+");\">"
											+"<i class='glyphicon glyphicon-minus'></i>"
										+"</button>"
									+"</td>";
					}
					emergencyTable += "<td class='col-xs-3'>"+e.emergencyType+"</td>"
										+"<td>"+e.emergencyName+"</td>"
									+"</tr>";
									
						var c = "<div class='row'>"
									+"<div class='col-xs-12'>"
										+"<h4 class='visible-*-inline'>Address:</h4>"
										+"<p class='visible-*-inline'>"+e.address+"</p>"
									+"</div>"
								+"</div>"
								+"<div class='row'>"
									+"<div class='col-xs-12'>"
										+"<h4 class='visible-*-inline'>Phone:</h4>"
										+"<p class='visible-*-inline'>"+e.phone+"</p>"
									+"</div>"
								+"</div>";
					popovers.push({
							"id" : e.emergencyId,
							"title" : e.emergencyName,
							"content" : c
						});
				}
				emergencyTable += "</table>";
				elem.html(emergencyTable);
				
				for(var i = 0; i < popovers.length; i++)
				{
					$("[data-emergencyId='"+popovers[i].id+"']").popover({
						container: "body",
						title: popovers[i].title,
						content: popovers[i].content,
						trigger: "hover",
						placement: "right",
						html: true
					});
				}
			}
			else
			{
				elem.html("<div class='alert alert-warning alert-block text-center'>No current emergency contacts</div>");
			}			
		}
	});
}

/**
 * Sets the emergency table for users
 */
function setUserEmergencyTable(userId, elem, edit)
{
	var data = new FormData();
	data.append("function", "findEmergencyByUser");
	data.append("timestamp", new Date().getTime()/1000);
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
			var popovers = [];
			var emergencySelect = "<select class='emergencySelect form-control'>"
										+"<option value='2'>Medical</option>" //hard coded for now. Add refType later
									+"</select>";
			
			var emergencyTable = "<table class='table table-bordered'>";
			if(edit ==  true)
			{		
				emergencyTable += "<tr id='customEmergencyContact'>"
					+"<td class='col-xs-2'><button type='button' class='btn btn-success btn-block' onclick=\"launchEmergencyContactModal()\"><i class='glyphicon glyphicon-plus'></i></button></td>"
					+"<td class='col-xs-3'>"+emergencySelect+"</td>"
					+"<td><input type='text' class='form-control' id='customEmergencyName' placeholder='Emergency Name'></td>"
				+ "</tr>";
			}
			if(d.emergency.length > 0)
			{				
				//set the emergency Numbers
				for(var i = 0; i < d.emergency.length; i++)
				{
					var e = d.emergency[i];
					emergencyTable += "<tr data-emergencyId='"+e.emergencyId+"'>";
					if(edit == true){
						emergencyTable += "<td class='col-xs-2'>"
												+"<button type='button' class='btn btn-danger btn-block' onclick=\"removeEmergencyContact("+e.emergencyId+", '"+encodeURI(e.emergencyName)+"', 1); setUserEmergencyTable("+userId+", $('"+elem.selector+"'), "+edit+")\">"
													+"<i class='glyphicon glyphicon-minus'></i>"
												+"</button>"
											+"</td>";
					}
					emergencyTable += "<td colspan='2'>"+e.emergencyName+"</td>"
									+"</tr>";
									
					var c = "<div class='row'>"
								+"<div class='col-xs-12'>"
									+"<h4 class='visible-*-inline'>Address:</h4>"
									+"<p class='visible-*-inline'>"+e.address+"</p>"
								+"</div>"
							+"</div>"
							+"<div class='row'>"
								+"<div class='col-xs-12'>"
									+"<h4 class='visible-*-inline'>Phone:</h4>"
									+"<p class='visible-*-inline'>"+e.phone+"</p>"
								+"</div>"
							+"</div>";
							
					popovers.push({
						"id" : e.emergencyId,
						"title" : e.emergencyName,
						"content" : c
					});					
				}
			}	
			else
			{
				$(".emergency").addClass("hidden");
				emergencyTable += "<tr><div class='alert alert-warning alert-block'>No current Emergency Contacts</div></tr>";
								
			}	
			emergencyTable += "</table>";
			elem.html(emergencyTable);
			
			for(var i = 0; i < popovers.length; i++)
			{
				$("[data-emergencyId='"+popovers[i].id+"']").popover({
					container: "body",
					title: popovers[i].title,
					content: popovers[i].content,
					trigger: "hover",
					placement: "right",
					html: true
				});
			}
		}
	});
}

/**
 * Set the current connections table
 * show the current connections of this user and pending connections
 * show the correct buttons for actions to take
 */
function setCurrentConnectionTable(userId)
{
	var data = new FormData();
	data.append("userId", userId);
	data.append("function", "findConnections");
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
			var usersOwnTable = d.requestByUser;
			var nameForTable = d.requestByName;
			
			$(".page-header h1").text(nameForTable.first+"'s Connections ");
	
			var pendingConnectionTbl = "";
			var currentMembersTbl = "";
			var currentLeadersTbl = "";
			var currentFollowersTbl = "";
			if(d.connections.length > 0)
			{
				var members = [];
				for(var i = 0; i < d.connections.length; i++)
				{
					var connection = d.connections[i];
					var connectionId = connection.reportToId
					var leaderId = connection.leaderId;
					var followerId = connection.followerId;
					var requestBy = connection.requestBy;
					var permissionType = connection.permission;
					var active = connection.active;
					
					var userIsLeader = false;
					var connectionUserId = -1;
					if(userId == leaderId)
					{
						userIsLeader = true;
						connName = connection.followerFirstName+" "+connection.followerLastName;
						connectionUserId = followerId;
					}
					else
					{
						userIsLeader = false;
						connName = connection.leaderFirstName+" "+connection.leaderLastName;
						connectionUserId = leaderId;
					}
					
					var requestSentByUser = (userId == requestBy) ? true : false;
					
					var blankRow = "<tr><th colspan='3'>&nbsp;</th></tr>";
					var removeBtn = "<button type='button' class='btn btn-danger btn-block' onclick='removeConnectionFromCurrentConnections("+connectionId+", \""+encodeURI(connName)+"\")'>Remove</button>";
					var rejectBtn = "<button type='button' class='btn btn-danger col-xs-6' onclick='rejectConnection("+connectionId+", \""+encodeURI(connName)+"\")'>Reject</button>";
					var acceptBtn = "<button type='button' class='btn btn-success col-xs-6' onclick='acceptConnection("+connectionId+", \""+encodeURI(connName)+"\")'>Accept</button>";
					
					var permissionStr = "Member Request";
					if(permissionType == 2)
					{
						permissionStr = (userIsLeader) ? connection.followerFirstName+" has requested you <strong>lead him</strong>" : connection.leaderFirstName+" has requested you <strong>follow him</strong>";
					}
					
					var tblRow = "<tr>";
					tblRow += (active == 1) ? "<td colspan='2'>"+connName+"</td>" : "<td>"+connName+"</td><td>"+permissionStr+"</td>";
					tblRow +=  		"<td class='col-xs-2'>";
					tblRow += (active == 2) ? acceptBtn+rejectBtn : removeBtn;						
					tblRow += 		"</td>"
								+"</tr>";
					
					if(active == 2) //pending
					{
						//person looking at the table owns this table and the connection request was not sent by this person
						if(requestSentByUser == false && usersOwnTable == true)
						{
							if(pendingConnectionTbl == "") //add the header
							{
								pendingConnectionTbl += "<tr class='warning pendingRow'><th colspan='3'>Your Pending Requests</th></tr>";
							}							
							pendingConnectionTbl += tblRow;
						}
					}
					else if(active == 1)
					{	
						//do we display "Your" or the name of the person
						var n = (usersOwnTable == true) ? "Your" : nameForTable.first+"'s";
						//permissiontype == member
						if(permissionType == 1)
						{
							if(currentMembersTbl == "")
							{
								
								currentMembersTbl += "<tr class='warning memberRow'><th colspan='3'>"+n+" Current Members</th></tr>";
							}
							//if member hasn't been added yet show them, otherwise ignore
							currentMembersTbl += (members.indexOf(connectionUserId) == -1) ?  tblRow : "";
							
							//push the memberId to array
							members.push(connectionUserId);
						}
						else
						{
							//else
						
							if(userIsLeader == true)//these are followers of current user
							{
								if(currentFollowersTbl == "")
								{							
									currentFollowersTbl += "<tr class='warning followerRow'><th colspan='3'>"+n+" Current Followers</th></tr>";
								}								
								currentFollowersTbl += tblRow;
							}
							else if(userIsLeader == false) //these are leaders of current user
							{
								if(currentLeadersTbl == "")
								{									
									currentLeadersTbl += "<tr class='warning leaderRow'><th colspan='3'>"+n+" Current Leaders</th></tr>";
								}
								currentLeadersTbl += tblRow;
							}				
						}
									
					}
					else //active = 0
					{
						
					}
				}	
				
				var tbl = pendingConnectionTbl;
				//if rows above are filled add a blank row to separate
				tbl += (tbl != "" && currentFollowersTbl != "") ? blankRow + currentFollowersTbl : currentFollowersTbl;
				tbl += (tbl != "" && currentLeadersTbl != "") ? blankRow + currentLeadersTbl : currentLeadersTbl;
				tbl += (tbl != "" && currentMembersTbl != "") ? blankRow + currentMembersTbl : currentMembersTbl;
				
				$("#currentConnections table").html(tbl);
		
			}
			else
			{
				console.log("no results");
				var tbl = "<tr><th><div class='alert alert-block text-center alert-warning'>No current connections</div></th></tr>";
				$("#currentConnections table").html(tbl);
			}
		}
		else
		{
			console.log(d.msg);
		}
	});
}

/** 
 * Set the add connection table
 * show all the users in the entity and the correct buttons
 */
function setAddConnectionTable(userId, requestUserId)
{
	//get all users and find their connection with userId
	var data = new FormData();
	data.append("function", "getAllUsersAndConnections");
	(requestUserId) ? data.append("userId", requestUserId) : false;
	data.append("timestamp", new Date().getTime()/1000);
	
	requestFromId = (requestUserId) ? requestUserId : userId; //if requestUserId is defined its from not logged in user, so an add is from this user.
	
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
			var addConnectionTbl = "";
			if(d.users.length > 0)
			{
				for(var i = 0; i < d.users.length; i++)
				{
					var user = d.users[i];
					var name = user.firstName+" "+user.lastName;
									
					//define add btn out of connection loop in case connection doesnt exist
					var addBtn = "<button type='button' class='btn btn-success btn-block' onclick='loadConnectionModal("+userId+", "+user.userId+", \""+encodeURI(name)+"\")'>Add</button>";
					var detail = "";
					if(user.connections.length > 0)
					{
						
						for(var x = 0; x < user.connections.length; x++)
						{
							var c = user.connections[x];
							var connectionId = c.reportToId;
							
							
							var removeBtn = "<button type='button' class='btn btn-danger btn-block' onclick='removeConnection("+connectionId+", \""+encodeURI(name)+"\", "+userId+")'>Remove</button>";
							var changeBtn = "<button type='button' class='btn btn-info btn-block' onclick='loadConnectionModal("+userId+", "+user.userId+", \""+encodeURI(name)+"\")'>Change</button>";
							var pendingBtn = "<button type='button' class='btn btn-default btn-block' onclick='cancelPendingConnection("+connectionId+", \""+encodeURI(name)+"\", "+userId+")'>Pending</button>";
							var acceptBtn = "<button type='button' class='btn btn-success col-xs-6' onclick='acceptConnection("+connectionId+", \""+encodeURI(name)+"\")', "+userId+">Accept</button>";
							var rejectBtn = "<button type='button' class='btn btn-danger col-xs-6' onclick='rejectConnection("+connectionId+", \""+encodeURI(name)+"\", "+userId+")'>Reject</button>";
								
							var showBtn = addBtn;
							
							if(c.active == 2)
							{
								if(c.requestBy == userId) //if this user requested shwo a pending button
								{
									showBtn = pendingBtn; 
								}
								else //if other user requested show accept/reject
								{
									showBtn = acceptBtn+rejectBtn;
									if(c.permission == 1)
									{
										detail += "Request to be a <strong>Member</strong>";
									}
									else if(c.permission == 2)
									{
										if(c.leaderId == userId)
										{
											detail += "Request to be "+user.firstName+"'s <strong>Leader</strong>";
										}
										else
										{
											detail += "Request to be "+user.firstName+"'s <strong>Follower</strong>";
										}
									}
									
								}
								break;
							}
							else if(c.active == 1)
							{
								showBtn = removeBtn;
							}
						}
					}
					else
					{
						showBtn = addBtn;
					}
					
					addConnectionTbl += "<tr>";
					if(detail == "")
					{
						addConnectionTbl += "<td colspan='2'>"+name+"</td>";
					}
					else
					{
						addConnectionTbl += "<td>"+name+"</td>"
											+"<td>"+detail+"</td>";
					}
					addConnectionTbl += "<td class='col-xs-2'>"+showBtn+"</td>"
										+"</tr>";
					//onsole.log(showBtn+" "+name);
				}
			}
			
			$("#addConnections table").html(addConnectionTbl);
		}
		else
		{
			console.log(d.msg);
		}
	});
}