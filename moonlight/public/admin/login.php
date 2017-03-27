<?php
require_once("../../includes/initialise.php");

if($session->is_logged_in()){
	redirect_to("http://localhost/moonlight/public/admin/index.php");
}

//gives our forms a submit tag a name = "submit" attribute
if(isset($_POST['submit'])){
	
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	
	//cheeck database to see if username/password exists.
	
	$found_member = Member::authenticate_admin($username,$password);
	
	if($found_member){
		$session->login($found_member);
		log_action('Login', "{$found_member->get_attribute('username')} logged in.");
		redirect_to("index.php");
	}
	else{
		//username/password doesnt exist.
		$message = "Username or password doesn't exist.";
	}
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

		<h2>Admin Login</h2>
		<?php echo output_message($message); ?>
		
		<form action="login.php" method="post">
			<table>
				<tr>
					<td>Username Or Email:</td>
					<td>
						<input type="text" name="username" maxlength="30" value ="<?php
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