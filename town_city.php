<?php
include("db.php"); 
class TownCity {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $recordsPerPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $offset = ($page - 1) * $recordsPerPage;

        try {
            $sql = "SELECT * FROM town_city tc";

            if (!empty($search)) {
                $searchParam = "%$search%";
                $sql .= "
                            WHERE tc.name LIKE :searchParam
                        ";
            }

            $sql .= " ORDER BY tc.name LIMIT :limit OFFSET :offset";

            $stmt = $this->db->getConnection()->prepare($sql);

            if (!empty($search)) {
                $stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
            }

            $stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            
            throw $e; 
        }
    }
	
	public function displayAll() {
		try {
            $sql = "SELECT * FROM town_city";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            
            throw $e; 
        }
	}

    public function create($data) {
        try {
            
            $sql = "INSERT INTO town_city(name) VALUES(:name);";
            $stmt = $this->db->getConnection()->prepare($sql);

            
            $stmt->bindParam(':name', $data['name']);

            
            $stmt->execute();

            // Check if the insert was successful

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

            $sql = "SELECT * FROM town_city WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            
            $town_city = $stmt->fetch(PDO::FETCH_ASSOC);

            return $town_city;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE town_city SET
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
            $sql = "DELETE FROM town_city WHERE id = :id";
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

    public function getTownCount() {
        $conn = $this->db->getConnection();

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        try {
            $sql = "SELECT COUNT(*) as total FROM town_city tc";

            if (!empty($search)) {
                $searchParam = "%$search%";
                $sql .= "
                            WHERE tc.name LIKE :searchParam
                        ";
            }

            $stmt = $conn->prepare($sql);

            if (!empty($search)) {
                $stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result[0]['total'];
        } catch (PDOException $e) {
            
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
}

?>
