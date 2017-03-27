<?php
require_once('../../includes/initialise.php');
if((time() - $_SESSION['timeout'] > 600)){
	$session->logout();
}
else{
	$_SESSION['timeout'] = time();
}
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('header.php'); ?>

	<h2>Menu</h2>
	<ul>
		<?php echo" <li><a href=\"view_member.php\">View/Edit Details</a></li>";?>
		<?php echo" <li><a href=\"view_schedule.php\">View/Edit Schedule</a></li>";?>
		<li><a href="logout.php">Logout</a></li>
	</ul>

<?php include_layout_template('footer.php'); ?>