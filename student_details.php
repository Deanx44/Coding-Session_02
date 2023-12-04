<?php
include_once("db.php"); 

class StudentDetails {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function displayAll(){
        try {
            $sql = "SELECT * FROM student_details LIMIT 20"; 
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    
    public function create($data) {
        try {
            
            $sql = "INSERT INTO student_details(student_id, contact_number, street, zip_code, town_city, province) VALUES(:student_id, :contact_number, :street, :zip_code, :town_city,:province);";
            $stmt = $this->db->getConnection()->prepare($sql);

            
            $stmt->bindParam(':student_id', $data['student_number']);
            $stmt->bindParam(':contact_number', $data['contact_num']);
            $stmt->bindParam(':street', $data['street']);
            $stmt->bindParam(':zip_code', $data['zip']);
            $stmt->bindParam(':town_city', $data['town_city']);
            $stmt->bindParam(':province', $data['province']);

            
            $stmt->execute();

            
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM student_details WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            
            if ($stmt->rowCount() > 0) {
                
            } else {
                return false; 
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    
}

?>