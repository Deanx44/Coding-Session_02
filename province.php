<?php
include_once("db.php"); 

class Province {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        try {
            $sql = "SELECT * FROM province";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            
            throw $e; 
        }
    }
    
    public function create($data) {
        try {
            
            $sql = "INSERT INTO province(name) VALUES(:name);";
            $stmt = $this->db->getConnection()->prepare($sql);

            // Bind values to placeholders
            $stmt->bindParam(':name', $data['name']);

            
            $stmt->execute();

            
             
            if($stmt->rowCount() > 0)
            {
                return $this->db->getConnection()->lastInsertId();
            }

        } catch (PDOException $e) {
            
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
    public function read($id) {
        try {
            $connection = $this->db->getConnection();

            $sql = "SELECT * FROM province WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            
            $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

            return $studentData;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE province SET
                    name = :name 
                    WHERE id = :id";

            $stmt = $this->db->getConnection()->prepare($sql);
            
            $stmt->bindValue(':id', $data['id']);
            $stmt->bindValue(':name', $data['name']);
        

            
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
    public function delete($id) {
        try {
            $sql = "DELETE FROM province WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            
            if ($stmt->rowCount() > 0) {
                return true; 
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
