<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate poll object
include_once '../objects/poll.php';
 
$database = new Database();
$db = $database->getConnection();
 
$poll = new Poll($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id) 
){
	
	$poll->id = $data->id;
	$poll->stop_date = date('Y-m-d H:i:s');
	
	// create the poll
	if($poll->stopPoll()){

		// set response code - 201 created
		http_response_code(201);

		// tell the user
		echo json_encode(array("message" => "Poll was stopped."));
	}

	// if unable to create the poll, tell the user
	else{

		// set response code - 503 service unavailable
		http_response_code(503);

		// tell the user
		echo json_encode(array("message" => "Unable to stop poll."));
	}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to stop poll. Data is incomplete."));
}
 

?>