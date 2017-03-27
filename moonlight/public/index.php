<?php

require_once("../includes/initialise.php");
?>
<?php
	include_layout_template('header.php');
?>

	<h1>Menu Page</h1>
<?php
//if(isset($database)) { echo "true"; } else { echo "false"; }
//echo "<br />";

//echo $database->escape_value("It's working?<br />");

//$sql = "INSERT INTO members (email, password, firstname, lastname)";
//$sql .= "VALUES ('dan.tomo', 'secretpwd', 'Dan', 'Thompson')";
//$result = $database -> query($sql);

//$sql = "SELECT * FROM members";
//$result = $database->query($sql);
//$found_member = $database->fetch_array($result);
//echo $found_member['firstname'];

//echo "<hr />";
$member = Member::find_by_id(1);
//$member = new Member();


echo $member -> full_name();

echo "<hr />";

$members = Member::find_all();

foreach($members as $member){
	echo "Member: ". $member->username . "<br />";
	echo "Name: ". $member->full_name() ."<br /><br />";
}

/* $member_set = Member::find_all();
while($member = $database->fetch_array($member_set)){
	echo "member: ". $member['firstname'] . "<br />";
	echo "Name: ". $member['lastname'] . " " . $member['lastname'] . "<br /><br />";
} */

?>


	
		
<?php
	include_layout_template('footer.php');
?>