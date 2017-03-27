<?php

function strip_zeros_from_date($marked_string="") {
	
	$no_zeros = str_replace('*0', '', $marked_string);
	//then remove any remaining marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
}
function redirect_to($location = NULL){
	if($location != NULL){
		header("Location: {$location}");
		exit;
	}
}

function output_message($message=""){
	if(!empty($message)){
		return "<p class=\"message\">{$message}</p>";
	}
	else{
		return "";
	}
}

function __autoload($class_name){
	
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path)){		
		require_once($path);
	}
	else{
		die("the file {class_name}.php could not be found.");
	}
}

function include_layout_template($template=""){
	
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action, $message=""){
	
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a+')){ //append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		if($new) { chmod($logfile, 0755);}
	}
	else{
		echo "Could not open log file for writing";
	}
}
//paypal functions
function check_txnid($tnxid){
	global $link;
	return true;
	$valid_txnid = true;
	//get result set
	$sql = mysql_query("SELECT * FROM `payments` WHERE txnid = '$tnxid'", $link);
	if ($row = mysql_fetch_array($sql)) {
		$valid_txnid = false;
	}
	return $valid_txnid;
}

function check_price($price, $id){
	$valid_price = false;
	//you could use the below to check whether the correct price has been paid for the product
	
	/*
	$sql = mysql_query("SELECT amount FROM `products` WHERE id = '$id'");
	if (mysql_num_rows($sql) != 0) {
		while ($row = mysql_fetch_array($sql)) {
			$num = (float)$row['amount'];
			if($num == $price){
				$valid_price = true;
			}
		}
	}
	return $valid_price;
	*/
	return true;
}

function updatePayments($data){
	global $link;
	
	if (is_array($data)) {
		$sql = mysql_query("INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, createdtime) VALUES (
				'".$data['txn_id']."' ,
				'".$data['payment_amount']."' ,
				'".$data['payment_status']."' ,
				'".$data['item_number']."' ,
				'".date("Y-m-d H:i:s")."'
				)", $link);
		return mysql_insert_id($link);
	}
}


?>