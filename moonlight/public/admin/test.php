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

<?php include_layout_template('admin_header.php'); ?>

<?php
	
	/* $member = new Admin();
	$member->username = "me";
	$member->email = "howdy";	
	$member->password = "ofc";
	$member->firstname = "QED";
	$member->lastname = "QEF";
	$member->location = 9;
	$member->member_type = 1;
	$member->personal_info = 5;
	$member->save(); */
	//$member = Member::find_by_id(2);
	//echo " {$member->firstname} {$member->lastname} was found" ;
	/* $schedule_names = Member::get_schedule_columns();
	$schedule_pieces = array_splice($schedule_names,2);
	foreach($schedule_pieces as $key => $value){
		echo " {$key} => {$value} ";
	} */

	/* $member = Member::find_by_id(78);
	$attributes = $member->get_safe_attributes();
	foreach($attributes as $value){
		echo " {$value} ";
	}
	$member->set_schedule('Monday', 'members_id', '78');
	$schedule = $member->get_schedule('Monday', 'members_id');
	echo " {$schedule} ";
	$something = $member->get_stuff();
	
	//$scheduler = new Schedule();
	$scheduler = new Schedule();
	$scheduler = Schedule::find_by_id(78);
	$scheduler->set_schedule('Monday', 'members_id', '78');
	$scheduling = $scheduler->get_safe_attributes('Monday');
	foreach($scheduling as $timetable){
		
			echo " {$timetable} ";
		
	} */
	
			//return associative array of attribute keys and their values.		
	
	
	
	/* $admin = Admin::find_by_id(1);
	$admin->username = "nicko";
	$admin->save();  */
	
	/* $admin = Admin::find_by_id(20);
	$admin->delete(); */
	//note that once deleted from sql, instance is still around in php.
	//so echo->firstname ." was deleted"; is allowed and if id 3 was dabeer, would print dabeer.
	
?>

<?php include_layout_template('admin_footer.php'); ?>