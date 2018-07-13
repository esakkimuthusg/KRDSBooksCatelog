<?php
class DbConfig{

	//DB variables
	private $host = "localhost";
	private $username = "root";
	private $password = "";
	private $database = "krds_bookscatalog";
	
	protected $connection;

	//Database constructor
	public function __construct()
	{
		if(!isset($this->connection)){
			try{
			$this->connection = new mysqli($this->host,$this->username,$this->password,$this->database);
			}
			catch(Exception $e){
				echo "Cannot connect to the database".$e->message;
				exit;
			}	
		}

		return 	$this->connection;
	}	
}	




//$db = new DbConfig();
?>