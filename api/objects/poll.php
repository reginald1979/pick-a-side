<?php
class Poll{
 
    // database connection and table name
    private $conn;
    private $table_name = "polls";
 
    // object properties
    public $id;
    public $description;
    public $start_date;
    public $stop_date;
	public $total_a;
	public $total_b;
	public $total_votes;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	// read products
	function read(){
 
		// select all query
		$query = "SELECT
					p.pollid as id, p.description as description, p.startdate as start_date, p.stopdate as stop_date
				FROM
					" . $this->table_name . " p
				ORDER BY
					p.startdate DESC";

					// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	
	function readOne(){
					
		$query = "SELECT 
			p.pollid as id, p.description as description, p.startdate as start_date, p.stopdate as stop_date, ifnull(sum(v.debatera),0) as total_a, ifnull(sum(v.debaterb),0) as total_b, ifnull(sum(v.debatera),0) + ifnull(sum(v.debaterb),0) as total_votes
		FROM 
		" . $this->table_name ." p 
		LEFT JOIN
			votes v
		ON 
			p.pollid = v.pollid
		WHERE 
			p.pollid = ?
		LIMIT 
			0,1";
					
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		$stmt->bindParam(1, $this->id);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		$this->id = $row['id'];
		$this->description = $row['description'];
		$this->start_date = $row['start_date'];
		$this->stop_date = $row['stop_date'];
		$this->total_a = $row['total_a'];
		$this->total_b = $row['total_b'];
		$this->total_votes = $row['total_votes'];


	}
	
	function create() {
		
		// query to insert record
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					description=:description, startdate=:start_date";
		
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->start_date=htmlspecialchars(strip_tags($this->start_date));
	 
		// bind values
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":start_date", $this->start_date);
	 
		// execute query
		if($stmt->execute()){
			$this->id = $this->conn->lastInsertId();
			return true;
		}
	 
		return false;
	}
	
	function stopPoll() {
		$query = "UPDATE 
					" . $this->table_name . "
				 SET 
					stopdate=:stop_date
				 WHERE 
					pollid=:id ";
		
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":stop_date", $this->stop_date);

	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
				
	}
	
	// get open poll, if no open polls, get last poll and display results
	function getOpenPoll() {
		// select all query
		$query = "SELECT
					p.pollid as id, p.description as description, p.startdate as start_date, p.stopdate as stop_date, 0 as total_a, 0 as total_b, 0 as total_votes
				FROM
					" . $this->table_name . " p
				WHERE 
					p.stopdate is null
				LIMIT
					0,1";
					
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
		$count = $stmt->rowCount();
		
		// get most recent poll and show those results
		if($count == 0) {
			$query = "SELECT 
						p.pollid as id, p.description as description, p.startdate as start_date, p.stopdate as stop_date, ifnull(sum(v.debatera),0) as total_a, ifnull(sum(v.debaterb),0) as total_b, ifnull(sum(v.debatera),0) + ifnull(sum(v.debaterb),0) as total_votes
					FROM 
					" . $this->table_name ." p 
					INNER JOIN
						votes v
					ON 
						p.pollid = v.pollid
					WHERE p.pollid = (SELECT MAX(PollId) FROM polls)";
						
			$stmt = $this->conn->prepare($query);
			$stmt->execute();			
		}
		
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		$this->id = $row['id'];
		$this->description = $row['description'];
		$this->start_date = $row['start_date'];
		$this->stop_date = $row['stop_date'];
		$this->total_a = $row['total_a'];
		$this->total_b = $row['total_b'];
		$this->total_votes = $row['total_votes'];
	}
	
	function getResults() {
		// select all query
		$query = "SELECT
					p.pollid as poll_id, p.description as description, sum(v.debatera) as total_a, sum(v.debaterb) as total_b
				FROM
					" . $this->table_name . " p
				INNER JOIN votes v
					ON p.pollid = v.pollid
				WHERE 
					v.pollid = ?";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		$stmt->bindParam(1, $this->id);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		$this->id = $row['poll_id'];
		$this->description = $row['description'];
		$this->total_a = $row['total_a'];
		$this->total_b = $row['total_b'];
		$this->total_votes = 0;
		$this->total_votes = $this->total_a + $this->total_b;
	}
}
?>