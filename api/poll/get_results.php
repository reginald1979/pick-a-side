<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// get database connection
include_once '../config/database.php';

// instantiate poll object
include_once '../objects/poll.php';

$database = new Database();
$db = $database->getConnection();
 
$poll = new Poll($db);

// set ID poll of record to read
$poll->id = isset($_GET['id']) ? $_GET['id'] : die();

$poll->getResults();

if($poll->id != null) 
{
    // create array
    $poll_arr = array(
        "id" =>  $poll->id,
        "description" => $poll->description,
        "total_a" => $poll->total_a,
		"total_b" => $poll->total_b,
		"total_votes" => $poll->total_votes
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($poll_arr);
}
else {
	// set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to get results for poll. Data is incomplete.", "total_votes" => "0"));
}
?>