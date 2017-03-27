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
//gives our forms a submit tag a name = "submit" attribute
if(isset($_POST['submit'])){
	
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	//do for all columns.
	
}
else{
	//form not submitted
	$username = "";
	$password = "";
	$message = "";
}

?>

<?php
	include_layout_template('admin_header.php');
?>

		<h2>Create new Member</h2>
		<?php echo output_message($message); ?>
		
		<form action="create_member.php" method="post">
			<table>
				<tr>
					<td>Username:</td>
					<td>
						<input type="text" name="username" maxlength="30" value="<?php
						echo htmlentities($username); ?>" />
					</td>
				</tr>
				<tr>
					<td>Password:</td>
					<td>
						<input type="password" name="password" maxlength="30"
						value ="<?php echo htmlentities($password); ?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Login" />
					</td>	
				</tr>
			</table>
			<br />
			
		</form>
<?php
	include_layout_template('admin_footer.php');
?>