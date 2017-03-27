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
	redirect_to("login.php");
}
?>


<?php
	include_layout_template('admin_view_members_header.php');
	//get attributes to set as headings to make generic.
	//also getting attributes make more generic in general.
	//use members_id always. Required column. Can't delete.
	//use php for a for loop once got attributes.
	$db_column_names = Member::get_database_columns();
	$i = 0;
	$column_names = array();
	foreach($db_column_names as $column){
		$column = str_replace('_',' ', $column);
		$column = ucwords(strtolower($column));
		$column_names[$i] = $column;
		$i++;
	}
	$i = 0;
	
?>
<html>
	<a href="index.php">&laquo; Back</a><br />
	<br />
	<h1>All Members</h1>
	
	<body>
		
	<table style="width:100%">
		<tr class ="bg--emerald ">
				
					<th>Members Id</th>
				<?php foreach($column_names as $dynamic){ ?>					
					<?php if($dynamic == 'Password'){} else{ ?>
					<th><?php echo htmlentities("{$dynamic}"); }?></th>
				<?php } ?>
				</tr>
<?php
?>


					

<?php
	global $database;
	
	
	$sql = "SELECT * FROM members1";
	//$result = Member::find_by_sql($sql);
	$result = $database->query($sql);
	//foreach($result as $attribute=>$value){
	$i = 0;
	while($row = $database->fetch_array($result)){
		
		//try to use object instantiation?
		
?>
			<tr>
					<td><?php echo " {$row['members_id']}" ?> </td>
					<?php foreach($db_column_names as $dynamic){ ?>
					<?php if($dynamic == 'password'){ } else{?>
					<td><?php echo "{$row[$dynamic]}"; } }?> </td>
					<?php
					echo "<td><a href=\"view_schedule.php?id=$row[members_id]\">View Schedule</a>  </td><td> |</td><td><a href=\"edit_member.php?id=$row[members_id]\">Edit</a>  </td><td> |</td> <td> <a href=\"delete_member.php?id=$row[members_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
					$i++;
	}
					?>
				</tr>

	</table>

<?php
	include_layout_template('admin_view_members_footer.php');
?>