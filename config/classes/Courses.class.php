<?php
class Courses {
    private $conn;
    private $code;
    private $name;
    private $progression;
    private $syllabus;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // hämta alla kurser
    public function getCourses() {
        $sql = $this->conn->prepare("SELECT * FROM courses ORDER BY progression");
        $sql->execute();

        // hämtar alla rader från db
        return $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // hämta specifik kurs
    public function getCoursesId(int $id) {
        $sql = $this->conn->prepare("SELECT * FROM courses WHERE id=$id");
        $sql->execute();

        // hämtar rad med motsvarande id från db
        return $result = $sql->fetch(PDO::FETCH_ASSOC);
    }

    // lägga till ny kurs
    public function addCourse(string $code, string $name, string $progression, string $syllabus) {
        $sql = "INSERT INTO courses (code, name, progression, syllabus) VALUES ('$code', '$name', '$progression', '$syllabus')";
       
        // exec för att inget returneras
        $this->conn->exec($sql);
    }

    // uppdatera existerande kurs
    function updateCourse(int $id, string $code, string $name, string $progression, string $syllabus) {
        $sql = "UPDATE courses SET code = '$code', name = '$name', progression = '$progression', syllabus = '$syllabus' WHERE id=$id;";
        
        // exec för att inget returneras
        $this->conn->exec($sql);
    }

    // ta bort kurs
    public function deleteCourse(int $id) {
        $sql = "DELETE FROM courses WHERE id=$id;";
        
        // exec för att inget returneras
        $this->conn->exec($sql);
    }
}