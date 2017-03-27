<?php
//If need database require it before we start

require_once('initialise.php');
//require_once(LIB_PATH.DS.'member_platform.php');


class Admin extends DatabaseObject{
	
	protected static $table_name="members";
	protected static $db_fields = array('username','email', 'password', 'firstname', 'lastname', 'location', 'member_type', 'personal_info');
	public $members_id;
	public $email;
	public $username;
	public $password;
	public $firstname;
	public $lastname;
	public $location;
	public $member_type;
	public $personal_info;
	
	public static function authenticate($username="",$password=""){
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);
		
		//need to encrypt password here.
		
		$sql = "SELECT * FROM members ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND member_type = '2' "; //make more abstract i.e the 2
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		
		
		if(!empty($result_array)){
			return array_shift($result_array);
		}
		
		$sql = "SELECT * FROM members ";
		$sql .= "WHERE email = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND member_type = '2' ";
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		
		if(!empty($result_array)){
			return array_shift($result_array);
		}
		else{
			return false;
		}
		
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
		return $this->sanitised_attributes();
	}
	
	public static function get_database_columns(){
		return self::$db_fields;
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
		
		
		
		/* $sql .= " username, email, password, firstname, lastname, location, member_type, personal_info";
		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($this->username) ."', '";
		$sql .= $database->escape_value($this->email) ."', '";
		$sql .= $database->escape_value($this->password) ."', '";
		$sql .= $database->escape_value($this->firstname) ."', '";
		$sql .= $database->escape_value($this->lastname) ."', '";
		$sql .= $database->escape_value($this->location) ."', '";
		$sql .= $database->escape_value($this->member_type) ."', '";
		$sql .= $database->escape_value($this->personal_info) ."')"; */

		
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
	
	}
	
	
	
}