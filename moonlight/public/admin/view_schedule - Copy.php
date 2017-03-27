<?php
//gets all necessary files.
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
	//if not logged in redirect to login page
	redirect_to("http://localhost/moonlight/public/admin/login.php");
}
?>
<?php 
	include_layout_template('admin_header.php');
?>

<?php
	//fetch id from website.
	$id = $_GET['id'];
	//find member using this id. Need to check if admin and do the same if they are. Do i even need admin class?
	if(!Member::find_by_id($id)){
		redirect_to("http://localhost/moonlight/public/admin/index.php");
	}
	
	//get attributes (row values) specific for member
	
	$db_names = Schedule::get_database_columns();
	$db_names = array_splice($db_names,1);
	$schedule_names = Schedule::get_schedule_columns(); //returns nice column titles.
	$schedule_print = array_splice($schedule_names,1);
	$days = Schedule::get_days();
	//Generates nice column names.
	$i = 0;
	$column_names = array();
	$col = '';
	
?>
<?php
//after save is done...
//then check if it is checked or not by using $_POST on [{$col}{$day}]

if(isset($_POST['submit'])){
	
	
	
	$message = "submitted";
		//new array that stores updated attributes
	foreach($days as $day){
		$attributes1 = array(); 
		$schedule = Schedule::find_schedule($id, $day);
		$print = $schedule->get_attribute('schedule_id');
		
		$attributes = $schedule->get_safe_attributes();
		$attributes = array_splice($attributes, 2); //no member_id
		$i = 1;
		foreach($attributes as $value){
			
			$field = $db_names[$i];
			//$field1 = $schedule_print[$i];
			$col = "{$db_names[$i]}{$day}";
			
			if(!empty(trim($_POST[$col]))){
				
				$value = trim($_POST[$col]);
				//echo " {$value} ";
				/* if($value == 'Available'){
					$value = 0;
				}
				else if($value == 'Unavailable'){
					$value = 1;
				} */
				//echo " {$schedule->get_attribute($field)} ";
				$schedule->set_attribute($field, $value); //updates attributes of member in class
				//echo " {$schedule->get_attribute($field)} ";
				
			}
			else{
				
				$dim = strcmp($_POST[$col], "0");
				if($dim === 0){
					$value = 0;
					//echo " {$value} ";
					$schedule->set_attribute($field, $value);
				}
				else {
					$success = 0;
					$message = "not set";	
				}
			}
			//$attributes1[$day][$column] = $dynamic; //has updated attributes.
			array_push($attributes1, $value);
			$i++;
		}
		$attributes = $attributes1;
		$schedule->update();
		$i = 0;
	}
	//$attributes = $attributes1; //sets attributes on page to updated attributes immediately.
	
	//$member->update(); //updates database.
	//$message = "Member updated successfully";
	//$i = 0;
	
}
else{
	//not submitted	
	$message = "";
}

	

	

?>
<html>
	
	<a href="view_members.php">&laquo; Back</a><br />
	<h2>View and Edit Schedule</h2>
		<?php echo output_message($message); ?>
	
	<body>
	
	<form action="view_schedule.php?id=<?php echo "{$id}"?>" method="post">
	
		<table style="width:100%" align="left">
		
				<tr>		
				<?php $i = 0; //gets attributes and displays them as well as Column names.
				
				foreach($schedule_print as $col){
				?>
				<th align="left"><?php echo "{$col}"?></th>
				<?php }?></tr>
				<?php foreach($days as $day){$schedule = Schedule::find_schedule($id, $day);?>
				<tr><?php
					foreach($db_names as $col){ 
						//$field = $db_names[$i]
						$attribute = $schedule->get_attribute($col);
						/* if($attribute == '0'){
							$attribute = 'Available';
						} 
						else if($attribute == '1'){
							$attribute = 'Unavailable'; //ON CLICK CHANGES FROM UNAVALABLE TO AVAILABLE.
							//<td> <td><button class="btn--raised btn--blue g--10 m--4" onclick="location.href='view_schedule.php?id=<?php echo "{$id}"?>'"><h2 class="color--white ">View All Members</h2></button> </td>
						} *///if($attribute == 1){ $attribute = 0;} else{ $attribute =1;}
						if($col == 'day'){ echo "<td>{$attribute}</td>";}  else{ ?>
						<!-- Need to work out here best way to create toggle button, then once clicked it submits values to form -->
						<!-- float:left;   width:4.0em; -->
						<td><div id="ck-button">
							<label>
								<input type="checkbox" name="<?php echo "{$col}{$day}";?>" value="<?php echo "{$attribute}";?>" hidden><span>___</span></td>
							</label>
						</div></td>
						<!--<input type="hidden" name="<?php //echo "{$col}{$day}";?>" maxlength="30" value="<?php //echo "{$attribute}"; ?>" />
						<td><button name="b<?php //echo "{$col}{$day}";?>" type="button" value="<?php //echo "{$attribute}";?>"><h2><?php //echo "{$attribute}";?></h2>  </button></td>-->
						<?php //?>
						
				<?php } ?>
				<?php } ?> </tr>
				<?php } ?>	
		
				<td>
					
				</td>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Save" />
					</td>	
				</tr>
		</table>
			<br />
			
	</form>

<?php

	include_layout_template('admin_footer.php');
?>