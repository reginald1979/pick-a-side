<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate vote object
include_once '../objects/vote.php';

$database = new Database();
$db = $database->getConnection();
 
$vote = new Vote($db);

$data = json_decode(file_get_contents("php://input"));

if(
	!empty($data->poll_id) &&
	!empty($data->ip)
) 
{
	$vote->poll_id = $data->poll_id;
	$vote->ip = $data->ip;
	
	if($vote->canVote() == 0) {
		// set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("can_vote" => "true"));
	}
	else {
		// set response code - 400 error
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("can_vote" => "false"));
	}
}
else {
	// set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to cast vote. Data is incomplete."));
}
?>