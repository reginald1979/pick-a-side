<?php
class Vote {
	private $conn;
	private $table_name = "votes";
	
	public $vote_id;
	public $poll_id;
	public $debater_a;
	public $debater_b;
	public $ip;
	
	// constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	// read products
	function read(){
 
		// select all query
		$query = "SELECT
					v.voteid as vote_id, v.pollid as poll_id, v.debatera as debater_a, v.debaterb as debater_b
				FROM
					" . $this->table_name . " v";

					// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	
	function castVote() {
		// query to insert record
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					pollid=:poll_id, debatera=:debater_a, debaterb=:debater_b, ip=:ip";
					
		$stmt = $this->conn->prepare($query);
		
		// bind values
		$stmt->bindParam(":poll_id", $this->poll_id);
		$stmt->bindParam(":debater_a", $this->debater_a);
		$stmt->bindParam(":debater_b", $this->debater_b);
		$stmt->bindParam(":ip", $this->ip);
		
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function canVote() {
		// query to insert record
		$query = "SELECT COUNT(VoteId)
					FROM
					" . $this->table_name . "
				WHERE 
					pollid=:poll_id and ip=:ip";
					
		$stmt = $this->conn->prepare($query);
		
		// bind values
		$stmt->bindParam(":poll_id", $this->poll_id);
		$stmt->bindParam(":ip", $this->ip);
		
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$total = $stmt->fetchColumn();
		// set values to object properties
		return $total;
	}
}
?>