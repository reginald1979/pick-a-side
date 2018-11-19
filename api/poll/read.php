<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/poll.php'; 
 
// database connection will be here
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$poll = new Poll($db);
 
// read polls will be here
// query products
$stmt = $poll->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $polls_arr=array();
    $polls_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
		
        $poll_item=array(
            "id" => $id,
            "description" => $description,
            "start_date" => $start_date,
            "stop_date" => $stop_date
        );
 
        array_push($polls_arr["records"], $poll_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show polls data in json format
    echo json_encode($polls_arr);
}
 
// no polls found will be here
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>