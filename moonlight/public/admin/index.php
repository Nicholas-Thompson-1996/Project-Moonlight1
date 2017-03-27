<?php
require_once('../../includes/initialise.php');
global $database;
$id = $_SESSION['members_id'];
$temp = Member::find_by_id($id);
$table_name = Member::get_table_name();
$sql = "SELECT * FROM ". $table_name ." WHERE `members_id` = '{$id}' AND `member_type` = 'Admin'";
if($database->query($sql)){
	if($database->affected_rows() == 0){
		$session->logout();
		redirect_to("http://localhost/moonlight/public/admin/login.php");
	}
}
if((time() - $_SESSION['timeout'] > 600)){
	$session->logout();
}
else{
	$_SESSION['timeout'] = time();
}
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_index_header.php'); ?>
	
<table>
	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='view_members.php'"><h2 class="color--white ">View All Members</h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='index.php'"><h2 class="color--white ">New Member Requests</h2></button> </td>
	</tr>

	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='create_member.php'"><h2 class="color--white ">Add Member</h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='logfile.php'"><h2 class="color--white ">View Logfile</h2></button> </td>
	</tr>

	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='index.php'"><h2 class="color--white ">Edit Tags </h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='index.php'"><h2 class="color--white ">Edit Description</h2></button> </td>
	</tr>

	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='index.php'"><h2 class="color--white ">Edit Pay Options</h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='index.php'"><h2 class="color--white ">Refresh Database</h2></button> </td>
	</tr>
	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='index.php'"><h2 class="color--white ">Send Email </h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='logout.php'"><h2 class="color--white ">Logout</h2></button> </td>
	</tr>
	<!-- This is the side bar of the webpage -->
	<aside class="nav--super-vertical g--2 g-m--3 g-s--6 g-t--12 no-margin-vertical">

			<div class="g--12 logo-area no-margin-vertical">
				<h4 class="color--midnight-blue no-margin-vertical">Moonlight</h4>
			</div>

			<div class="g--12 no-margin-vertical">
				<!-- the information in [] are to be fed into the website via php -->
				<a >Organisation ID: [ ]  </a>
				<a >  Total Members: [ ] </a>
				<a > Type: [ ]</a>
				<a> Tags: [ ] </a>
				<a>Description: [ ] </a>
			</div>

	</aside>
	<!--<h2>Menu</h2> -->
	<!--<ul> -->
	<!--	<li><a href="logfile.php">View Log file</a></li>
	<!--	<li><a href="view_members.php">View Members</a></li>
	<!--	<li><a href="create_member.php">Create New Member</a></li>
	<!--	<li><a href="logout.php">Logout</a></li>
	<!--</ul> 

<?php include_layout_template('admin_index_footer.php'); ?>