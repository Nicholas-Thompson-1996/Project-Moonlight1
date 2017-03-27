<?php

require_once(LIB_PATH.DS.'member_platform.php');

class DatabaseObject {
	//should member be able to do these?
	public static function find_all(){
	
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
		
	}
	
	public static function find_by_id($id=0){
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE members_id ={$id}");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_by_sql($sql=""){
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set)){
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}
	
	private static function instantiate($record){
		//could check that $record ecists and is an array
		//simple, long form approach.
		//$class_name = get_called_class();
		//$object = new $class_name;
		$object = new static;
		/*$object->members_id 	= $record['members_id'];
		$object->email			= $record['email'];
		$object->username 		= $record['username'];
		$object->password 		= $record['password'];
		$object->firstname 		= $record['firstname'];
		$object->lastname 		= $record['lastname'];
		$object->location 		= $record['location'];
		$object->member_type 	= $record['member_type'];
		$object->personal_info 	= $record['personal_info']; */
		
		//more dynamic, short -form approach.
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	private function has_attribute($attribute){
		//get_object_vars returns an asssociative array with all attributes
		//(inlcuding private ones) as keys and current values as value.
		
		$object_vars = get_object_vars($this);
		//want to check key exists, dont care about value here
		//returns true or false	
		
		return array_key_exists($attribute, $object_vars);
	}
		
	
}

?>