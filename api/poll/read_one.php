<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/poll.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare poll object
$poll = new Poll($db);
 
// set ID poll of record to read
$poll->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of poll to be edited
$poll->readOne();
 
if($poll->description != null){
	
    // create array
    $poll_arr = array(
        "id" =>  $poll->id,
        "description" => $poll->description,
        "start_date" => $poll->start_date,
        "stop_date" => $poll->stop_date,
		"total_a" => $poll->total_a,
		"total_b" => $poll->total_b,
		"total_votes" => $poll->total_votes
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($poll_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Poll does not exist."));
}
?>