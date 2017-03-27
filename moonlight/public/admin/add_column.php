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
if((time() - $_SESSION['timeout']) > 600){
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
//IMPORTANT WHEN CREATING NEW COLUMN. USER MUST ENTER LOWER CASE FOR NAMES ONLY
//SEPERATE WORDS MUST BE SEPERATED BY _ SYMBOL.
//ALSO ABILITY TO MAKE REQUIRED COLUMN
//After a specific Column
//Default Value to prevent database query.
//column added to database column names in member.
//unique?
//length?
//ALTER TABLE `members` ADD `schedule` VARCHAR(30) NOT NULL AFTER `personal_info`; 

//call add column statically on member:
//then for each member that already exists. add attribute and assign a value.
//usually a default such as null!!!
//remind database owner this operation is costly and thus to be avoided if necessary.
//add column before adding members.

//update $db_fields i.e add  

//length can be determined using sql.

//gives our forms a submit tag a name = "submit" attribute
if(isset($_POST['submit'])){
	
	//all fields are required here and must be set.
	//if(required = true add required
	//if(input type == whatever... do required action
	
}
else{
	//form not submitted
	
}

?>

<?php
	include_layout_template('admin_header.php');
?>

		<h2>Add Column</h2>
		<?php echo output_message($message); ?>
		
		<form action="add_column.php" method="post">
			<table>
				<tr>
					<td>Column Name</td>
					<td>
						<input type="text" name="column_name" maxlength="30" value="<?php
						echo htmlentities($ColumnName); ?>" />
					</td>
				</tr>
				<tr>
					<td>Required</td> <!-- make radio field here-->
					<td>
						<input type="radio" name="required" value ="Yes">Yes <br>
						<input type="radio" name="not_required" value ="No">No <br>
					</td>
				</tr>
				<tr>
					<td>Choose input type</td>
						<input type="radio" name="text" value ="somevalue">Text/Paragraph <br>
						<input type="radio" name="var_char" value ="somevalue1">Single Line Text <br>
						<input type="radio" name="int" value ="somevalue2">Integer <br>
						<input type="radio" name="enum" value="somevalue3">Enum<br> <!-- have a link describing what it is -->
						<input type="radio" name="set" value="somevalue4">Set<br> <!-- have a link describing what it is -->
					</td>
				</tr>
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