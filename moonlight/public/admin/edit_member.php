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
	
	if(!Member::find_by_id($id)){
		redirect_to("http://localhost/moonlight/public/admin/index.php");
	}
	//find member using this id. Need to check if admin and do the same if they are. Do i even need admin class?
	$member = Member::find_by_id($id);
	//get attributes (row values) specific for member
	$attributes = $member->get_safe_attributes();
	
	$db_column_names = Member::get_database_columns();
	$required_cols = Member::get_required_columns();
	//Generates nice column names.
	$i = 0;
	$column_names = array();
	$error = array();
	$display = array();
	foreach($db_column_names as $column){
		$column = str_replace('_',' ', $column);
		$column = ucwords(strtolower($column));
		$column_names[$i] = $column;
		$error[$i] = '';
		$display[$i] = '';
		$i++;
	}
	//create an array in member with required status?
	//1 === required else 0 means not required.

if(isset($_POST['submit'])){
	$attributes = $member->get_safe_attributes();
	//if form sent need to change attributes of member to new values.
	//Then we can update the database.
	//Then need to update attributes to present page immediately!!
	
	//counter variable for columns.
	$success = 1;
	$i = 0;			
	$attributes1 = array(); 	//new array that stores updated attributes
	foreach($attributes as $dynamic){
	
			$field1 = $db_column_names[$i];
			if(!empty(trim($_POST[$field1]))){
				if($field1 == 'username'){
					$user = trim($_POST[$field1]);
					$table_name = Member::get_table_name();
					$search = "SELECT * FROM ".$table_name." WHERE (`username` = '{$user}' OR `email` = '{$user}') AND `members_id` != '{$id}'";
					if($database->query($search)){
						if($database->affected_rows() != 0){
							$success = 0;
							$var = trim($_POST[$field1]);
							$error[$i] = "'{$var}' username was already taken";
						}
						else{
							$dynamic = trim($_POST[$field1]);
							$member->set_attribute($field1, $dynamic);
						}
					}
				}
				else if($field1 == 'email'){
					$mail = trim($_POST[$field1]);
					$table_name = Member::get_table_name();
					$search = "SELECT * FROM ".$table_name." WHERE (`username` = '{$mail}' OR `email` = '{$mail}') AND `members_id` != '{$id}'"; //AND 'members_id' != {$id}??
					if($database->query($search)){
						if($database->affected_rows() != 0){
							$success = 0;
							$var = trim($_POST[$field1]);
							$error[$i] = "'{$var}' That email was already taken";
						}
						else{
							$dynamic = trim($_POST[$field1]);
							$member->set_attribute($field1, $dynamic);
						}
					}
				}
				else{//this makes it return initial values if something goes wrong.
					$dynamic = trim($_POST[$field1]);
					$member->set_attribute($field1, $dynamic); //updates attributes of member in class
				}
			}
			else{
				//$message = "not set";
				$dim = strcmp($_POST[$field1], '0');
							
				if($dim === 0){
					$dynamic = $_POST[$field1];
					$member->set_attribute($field1, $dynamic);
				}
				else if($required_cols[$i] === 0){
					$dynamic = "";
					$member->set_attribute($field1, $dynamic);
				}
				else{
					$success = 0;
					$display[$i] = '';
					$error[$i] = "Field was empty";
				}
			}
			//$attributes1[$dynamic] = $dynamic; //has updated attributes.
			array_push($attributes1, $dynamic);
			$i++;
			
			
	} 
	$attributes = $attributes1; //sets attributes on page to updated attributes immediately.
	$i = 0;
	if($success == 1){
		
		$member->update(); //updates database.
		$message = "Member updated successfully";
		$i = 0;
	}
	else{
		$message = "Could not add member";
	}
}
else{
	//not submitted	
	$message = "";
}


	//CHILDREN -> YES OR NO 

?>
<html>
	<a href="view_members.php">&laquo; Back</a><br />
	<h2>Edit Member</h2>
		<?php echo output_message($message); ?>
	
	<body>
	
	<form action="edit_member.php?id=<?php echo "{$id}"?>" method="post">
	
		<table style="width:100%" align="left">
		
				<tr><th align="left">Members Id:</th><td><?php echo "{$id}" ?></td><td><?php echo " Change details below"?></td></tr>			
				<?php $i = 0; //gets attributes and displays them as well as Column names.
				foreach($attributes as $dynamic){
					$field = $column_names[$i];
					$field1 = $db_column_names[$i];
				?>
				<tr><th align="left"><?php echo "{$field}"; if($required_cols[$i] == 1){ echo "*";}?></th><td><?php echo "{$dynamic}" ?></td>
				
				<td><input type="text" name="<?php echo "{$field1}";?>" maxlength="30" value="<?php
					echo htmlentities($dynamic); ?>" /><?php echo " {$error[$i]} "; ?></td>
				</tr>
				<?php
					$i++;
				}
				?>		
		
		
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