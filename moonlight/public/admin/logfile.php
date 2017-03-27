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
	redirect_to("login.php");
}
?>

<?php
	
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	
	//(isset($_GET['query_age']) ? $_GET['query_age'] : null);
	if(isset($_GET['clear']) ? $_GET['clear'] : null) {
		file_put_contents($logfile, '');
		//add first log entry
		log_action('Logs cleared', "by Member ID {$session->members_id}");
		//redirect to this same page so that URL won't have
		//"clear=true" anymore
		redirect_to('logfile.php');
	}
	
?>
<?php
	include_layout_template('admin_header.php');
?>

<a href="index.php">&laquo; Back</a><br />
<br />

<h2>Log File </h2>

<p><a href="logfile.php?clear=true">Clear log file</a><p>

<?php

	if(file_exists($logfile) && is_readable($logfile) &&
		$handle = fopen($logfile, 'r')){//read
		echo "<ul class=\"log-entries\">";
		while(!feof($handle)){
			$entry = fgets($handle);
			if(trim($entry) != "") {
				echo "<li>{$entry}</li>";
			}
		}
		echo "</ul>";
		fclose($handle);
		}
	else{
		echo "Could not read from {$logfile}.";
	}
?>				
		
<?php
	include_layout_template('admin_footer.php');
?>