<?php
require_once("../../includes/initialise.php");
?>

<?php
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
if(!$session->is_logged_in()){
	redirect_to("http://localhost/moonlight/public/admin/login.php");
}
?>
<?php

//delete's it from table and redirects to view_members.
//prints message succesffuly deleted. Else error deleting.
if(isset($_GET['id'])){
	$id = $_GET['id'];
	if(!Member::find_by_id($id)){
		redirect_to("http://localhost/moonlight/public/admin/index.php");
	}
	$member = Member::find_by_id($id); 
	if($member->delete()){
		$message = "Member deleted successfully";
	//redirect_to("view_members.php");
	
	}
	else{
		$message = "Error deleting member";
	}
}
else{
	redirect_to("view_members.php");
}

?>

<?php
	include_layout_template('admin_header.php');
?>

<html>
	<a href="view_members.php">&laquo; Back</a><br />
	<?php echo " {$message} ";?> <br />
	
</html>

<?php
	include_layout_template('admin_footer.php');
?>