<?php
//If need database require it before we start

require_once('initialise.php');
//require_once(LIB_PATH.DS.'member_platform.php');


class Schedule extends DatabaseObject{
	
	protected static $table_name="schedules";
	//can we fetch datbase names and store them here?
	//protected static $db_fields = array('username','email', 'password', 'firstname', 'lastname', 'location', 'member_type', 'personal_info');
	//protected static $db_fields = array('username','email', 'password', 'firstname', 'lastname', 'country', 'town', 'street_name', 'postcode', 'member_type', 'marital_status', 'number_of_children', 'occupation', 'skills', 'additional_skills', 'payment', 'additional_info');
	protected static $db_fields = array('members_id', 'day', 'six_am', 'seven_am', 'eight_am', 'nine_am', 'ten_am', 'eleven_am', 'twelve_pm', 'one_pm', 'two_pm', 'three_pm', 'four_pm', 'five_pm', 'six_pm', 'seven_pm', 'eight_pm', 'nine_pm');
	protected static $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	protected static $schedule_fields = array('members_id', 'Day', '06:00-07:00', '07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00', '19:00-20:00', '20:00-21:00', '21:00-22:00');
	
	protected $schedule_id;
	protected $members_id;
	protected $day;
	protected $six_am;
	protected $seven_am;
	protected $eight_am;
	protected $nine_am;
	protected $ten_am;
	protected $eleven_am;
	protected $twelve_pm;
	protected $one_pm;
	protected $two_pm;
	protected $three_pm;
	protected $four_pm;
	protected $five_pm;
	protected $six_pm;
	protected $seven_pm;
	protected $eight_pm;
	protected $nine_pm;
	
	//problem is we would need to create 7 times variables to store all days so make an array?
		
	
	public function get_attribute($field){
		return $this->{$field};
	}
	public function set_attribute($field, $value){
		$this->{$field} = $value;
	}
		
	public static function addColumn(int $col, String $newCol){
		//check both functions work if col = 0 and col = end and col = random
		self::$db_fields = array_slice($db_fields, 0, $col, true) + array($newCol)
				+ array_slice($db_fields, $col, count($db_fields) -1, true);
		
	}
	
	public static function removeColumn(int $col){
		
		self::$db_fields = array_slice($db_fields, 0, $col, true) + 
					array_slice($db_fields, $col + 1, count($db_fields) -1, true);
		
	}
	
	public static function find_schedule($id=0, $day){
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE `members_id` = '{$id}' AND `day` = '{$day}'" );
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public function addAttribute($attributeName, $attributeValue){
		
        $this->{$attributeName} = $attributeValue;
    }
	
	public function removeAttribute($attributeName){
        unset($this->{$attributeName});
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
				$attributes[$field] = $this->{$field};
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
	
	
	public function set_table_name($name){
		self::$table_name = $name;		
	}
	
	public static function get_database_columns(){
		return self::$db_fields;
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
				
			$sql = "INSERT INTO ".self::$table_name." (`"; 
			$sql .= join("`, `", array_keys($attributes));
			$sql .= "`) VALUES ('";
			$sql .= join("', '", array_values($attributes));
			$sql .= "')";
		
		
		
				
		if($database->query($sql)){
			$this->schedule_id = $database->insert_id();
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
		$sql .= " WHERE members_id= '" . $database->escape_value($this->get_attribute('members_id')). "' AND `day` = '" .$database->escape_value($this->get_attribute('day')). "'";
		
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
	
		
}

?>