<?php
//If need database require it before we start

require_once('initialise.php');
//require_once(LIB_PATH.DS.'member_platform.php');


class Member extends DatabaseObject{
	
	protected static $table_name="members1";
	//can we fetch datbase names and store them here?
	//protected static $db_fields = array('username','email', 'password', 'firstname', 'lastname', 'location', 'member_type', 'personal_info');
	protected static $db_fields = array('username','email', 'password', 'firstname', 'lastname', 'country', 'town', 'street_name', 'postcode', 'member_type', 'marital_status', 'number_of_children', 'occupation', 'skills', 'additional_skills', 'payment', 'additional_info');
	protected static $required = array(1,1,1,1,1,1,1,0,0,1,0,1,0,1,0,1,0);
	protected static $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	
	protected $members_id;
	protected $email;
	protected $username;
	protected $password;
	protected $firstname;
	protected $lastname;
	protected $country;
	protected $town;
	protected $street_name;
	protected $postcode;
	protected $member_type;
	protected $marital_status;
	protected $number_of_children;
	protected $occupation;
	protected $skills;
	protected $additional_skills;
	protected $payment;
	protected $additional_info;
	
	//problem is we would need to create 7 times variables to store all days so make an array?
	/* protected $Monday;
	protected $Tuesday;
	protected $Wednesday;
	protected $Thursday;
	protected $Friday;
	protected $Saturday;
	protected $Sunday; */
	
	public function get_attribute($field){
		return $this->{$field};
	}
	public function set_attribute($field, $value){
		$this->{$field} = $value;
	}
	
	
	public static function authenticate_admin($username="",$password=""){
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);
		
		//need to encrypt password here.
		
		$sql = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND member_type = 'Admin' "; //make more abstract i.e the 2
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		
		
		if(!empty($result_array)){
			return array_shift($result_array);
		}
		
		$sql = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE email = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND member_type = 'Admin' ";
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		
		if(!empty($result_array)){
			return array_shift($result_array);
		}
		else{
			return false;
		}
		
	}
	
	public static function authenticate_member($username="",$password=""){
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);
		
		//need to encrypt password here.
		
		$sql = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		
		
		if(!empty($result_array)){
			return array_shift($result_array);
		}
		
		$sql = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE email = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		
		if(!empty($result_array)){
			return array_shift($result_array);
		}
		else{
			return false;
		}
		
	}
	
	public static function addColumn($newCol){
		//check both functions work if col = 0 and col = end and col = random
		//this allows us to add column.
		//self::$db_fields = array_slice($db_fields, 0, $col, true) + array($newCol)
		//		+ array_slice($db_fields, $col, count($db_fields) -1, true);
		$temp = self::$db_fields;
		array_push($temp, $newCol);
		self::$db_fields = $temp;
	}
	
	public static function removeColumn($col){
		
		//self::$db_fields = array_slice($db_fields, 0, $col, true) + 
		//			array_slice($db_fields, $col + 1, count($db_fields) -1, true);
				
	}
	
	public function addAttribute($attributeName, $attributeValue){
		
        $this->{$attributeName} = $attributeValue;
    }
	
	public function removeAttribute($attributeName){
		
        unset($this->{$attributeName});
    }
		
	
	public function full_name() {
		if(isset($this->firstname) && isset($this->lastname)){
			return $this->firstname . " " . $this->lastname;
		}
		else{
			return "";
		}
	}	
	
	private function has_attribute($attribute){
		//get_object_vars returns an asssociative array with all attributes
		//(inlcuding private ones) as keys and current values as value.
		
		$object_vars = $this->attributes();
		//want to check key exists, dont care about value here
		//returns true or false	
		
		return array_key_exists($attribute, $object_vars);
	}
	
	protected function attributes() {
		//return associative array of attribute keys and their values.
		$attributes = array();
		foreach(self::$db_fields as $field) {
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	
			
	protected function sanitised_attributes(){
		
		global $database;
		$clean_attributes = array();
		//sanitise values before submitting.
		//make them SQL safe
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
					
	}
		
	public function get_safe_attributes(){
		$attributes = $this->sanitised_attributes();
		return $attributes;
	}
	
	
	public static function set_table_name($name){
		self::$table_name = $name;		
	}
	
	public static function get_table_name(){
		return self::$table_name;	
	}
	
	
	public static function get_database_columns(){
		return self::$db_fields;
	}
	
	public static function get_required_columns(){
		return self::$required;
	}
	public static function set_required_columns($field, $value){
		$temp = self::$db_fields;
		if($field == 'username' || $field == 'password' || $field == 'email' || $field == 'firstname' || $field == 'lastname'){
			echo "{$field} must be a required field";
		}
		else{
			
			for($j = 0; $j < count($temp); $j++){
				if($field === $temp[$j]){
					self::$required[$j] = $value; //assuming value is 1 or 0!!
				}
			}
			
		}
				
	}
	
	public static function get_schedule_columns(){
		return self::$schedule_fields;
	}
	public static function get_days(){
		return self::$days;
	}
	
	public function save() {
		//a new record wont have id
		
		return isset($this->members_id) ? $this->update() : $this->create();
		
	}
	public function create() {
		global $database;
		//Dont forget SQL syntax and good habits.
		//single quotes around values.
		//escape all values prevent SQL injection
		$attributes = $this->sanitised_attributes();
				
		
		$sql = "INSERT INTO ".self::$table_name." ("; 
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";	
		
		if($database->query($sql)){
			
			$this->members_id = $database->insert_id();
			return true;
		}
		else{
			return false;
		}
		
	}
	
	public function update() {
		global $database;
		$attributes = $this->sanitised_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value){
			$attributes_pairs[]="{$key}='{$value}'";
		}
		
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attributes_pairs);
		$sql .= " WHERE members_id= " . $database->escape_value($this->members_id);
		/* $sql = "UPDATE ".self::$table_name." SET ";
		$sql .= "username='". $database->escape_value($this->username) ."', ";
		$sql .= "email='". $database->escape_value($this->email) ."', ";
		$sql .= "password='". $database->escape_value($this->password) ."', ";
		$sql .= "firstname='". $database->escape_value($this->firstname) ."', ";
		$sql .= "lastname='". $database->escape_value($this->lastname) ."', ";
		$sql .= "location='". $database->escape_value($this->location) ."', ";
		$sql .= "member_type='". $database->escape_value($this->member_type) ."', ";
		$sql .= "personal_info='". $database->escape_value($this->personal_info) ."' ";
		$sql .= " WHERE members_id=". $database->escape_value($this->members_id); */
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		
	}
	
	public function delete() {
		global $database;
		
		$sql = "DELETE FROM ".self::$table_name." ";
		$sql .= "WHERE members_id=". $database->escape_value($this->members_id);
		$sql .= " LIMIT 1";
		
		
		
		$database->query($sql);
		return ($database->affected_rows()==1) ? true : false;

		/*if($database->affected_rows()==1){
			$sql = "DELETE FROM schedules ";
			$sql .= "WHERE members_id=". $database->escape_value($this->members_id);
			$sql .= " LIMIT 7";
			if($database->affected_rows()==7){
				return true;
			}
			else{ return false;} DONT NEED AS DELETE ON CASCADE.
		}
		else{ return false;} */
	
	}
	
}

?>