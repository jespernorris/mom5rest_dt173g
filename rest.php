<?php
// includes
include_once("config/config.php");
include_once ("config/Database.php");

// Gör att webbtjänsten går att komma åt från alla domäner (asterisk * betyder alla)
header('Access-Control-Allow-Origin: *');

// Talar om att webbtjänsten skickar data i JSON-format
header('Content-Type: application/json; charset=UTF-8');

// Vilka metoder som webbtjänsten accepterar, som standard tillåts bara GET.
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

// Vilka headers som är tillåtna vid anrop från klient-sidan, kan bli problem med CORS (Cross-Origin Resource Sharing) utan denna.
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$database = new Database();
$db = $database->connect();

$courses = new Courses($db);

// Läser in vilken metod som skickats och lagrar i en variabel
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// Om en parameter av id finns i urlen lagras det i en variabel
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

// variabler
if(isset($data)) {
    $code = $data["code"];
    $name = $data["name"];
    $progression = $data["progression"];
    $syllabus = $data["syllabus"];
}

switch($method) {
    case 'GET': // hämta kurser
        if(isset($id)) {
            $result = $courses->getCoursesId($id);
        } else {
            $result = $courses->getCourses();
        }

        if(sizeof($result) > 0) {
            http_response_code(200);
        } else {
            http_response_code(404);
            $result = array("message" => "No courses found");
        }
        break;
    case 'POST': // lägg till ny kurs
        // alla fält måste vara ifyllda
        if($code == "" || $name == "" || $progression == "" || $syllabus == "") {
            http_response_code(400);
            $result = array("message" => "Fill all fields!");
        } else {
            // kurs tillagd
            $courses->addCourse($code, $name, $progression, $syllabus);
            http_response_code(201);
            $result = array("message" => "Course added!");
        }
        break;
    case 'PUT': // uppdatera motsvarande id
        // om id ej skickas
        if(!isset($id)) {
            http_response_code(400);
            $result = array("message" => "No id is sent");
        // om id skickas   
        } else {
            // uppdatering lyckad
            $courses->updateCourse($id, $code, $name, $progression, $syllabus);
            http_response_code(200);
            $result = array("message" => "Course was updated!");
        }
        break;
    case 'DELETE': // raderar motsvarande id
        if(!isset($id)) {
            http_response_code(400);
            $result = array("message" => "No id is sent");  
        } else {
            // radera kurs
            $courses->deleteCourse($id);
            http_response_code(200); // borttagning lyckad
            $result = array("message" => "Course was deleted!");  
        }
        break;   
}

echo json_encode($result);

$db = $database->close();