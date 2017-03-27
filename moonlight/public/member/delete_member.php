<?php
require_once("../../includes/initialise.php");
?>

<?php
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
$id = $_GET['id'];
if(isset($_GET['id'])){
	
	$admin = Admin::find_by_id($id);
	if($admin->delete()){
		$message = "Member deleted successfully";
	//redirect_to("view_members.php");
	
	}
	else{
		$message = "Error deleting member";
	}
}
else{
	redirect_to("view_member.php?id=$id");
}

?>

<?php
	include_layout_template('header.php');
?>

<html>
	<?php echo "<a href=\"view_member.php\">&laquo; Back</a><br />";?>
	<?php echo " {$message} ";?> <br />
	
</html>

<?php
	include_layout_template('footer.php');
?>