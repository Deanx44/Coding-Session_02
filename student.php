<?php
include_once("db.php"); 

class Student {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function GenderCount($gender) {
        try {
            $sql = "SELECT COUNT(*) as count FROM students WHERE gender = :gender";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':gender', $gender);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
    
    public function BirthMonth() {
        try {
            $sql = "SELECT MONTH(birthday) as month, COUNT(*) as count FROM students GROUP BY MONTH(birthday)";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $monthData = array_column($result, 'count', 'month');

            for ($month = 1; $month <= 12; $month++) {
                if (!isset($monthData[$month])) {
                    $monthData[$month] = 0;
                }
            }

            ksort($monthData);

            return $monthData;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
    
    public function Millennials() {
        try {
            $sql = "SELECT 
                        CASE WHEN YEAR(birthday) >= 2000 THEN '2000 and above' 
                             ELSE 'Below 2000' 
                        END as birth_year_group,
                        COUNT(*) as count
                    FROM students
                    GROUP BY birth_year_group";
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
            $sql = "INSERT INTO students(student_number, first_name, middle_name, last_name, gender, birthday) VALUES(:student_number, :first_name, :middle_name, :last_name, :gender, :birthday);";
            $stmt = $this->db->getConnection()->prepare($sql);
            
            
            $stmt->bindParam(':student_number', $data['student_number']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':middle_name', $data['middle_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':birthday', $data['birthday']);
            $stmt->execute();
            // Check    
            if($stmt->rowCount() > 0)
            {
                return $this->db->getConnection()->lastInsertId();
            }

        } catch (PDOException $e) 
            
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    public function read($id) {
        try {
            $connection = $this->db->getConnection();

            $sql = "SELECT students.*, student_details.contact_number, student_details.street, student_details.town_city, student_details.province, student_details.zip_code
            FROM students
            LEFT JOIN student_details ON students.id = student_details.student_id
            WHERE students.id = :id";  
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
            $this->db->getConnection()->beginTransaction();
        
        $sqlDetails = "UPDATE student_details SET
            contact_number = :contact_number,
            street = :street,
            town_city = :town_city,
            province = :province,
            zip_code = :zip_code
            WHERE student_id = :id";

        $stmtDetails = $this->db->getConnection()->prepare($sqlDetails);
        $stmtDetails->bindValue(':id', $id);
        $stmtDetails->bindValue(':contact_number', $data['contact_number']);
        $stmtDetails->bindValue(':street', $data['street']);
        $stmtDetails->bindValue(':town_city', $data['town_city']);
        $stmtDetails->bindValue(':province', $data['province']);
        $stmtDetails->bindValue(':zip_code', $data['zip_code']);
        $stmtDetails->execute();

        
        $sqlStudents = "UPDATE students SET
            student_number = :student_number,
            first_name = :first_name,
            middle_name = :middle_name,
            last_name = :last_name,
            gender = :gender,
            birthday = :birthday
            WHERE id = :id";

        $stmtStudents = $this->db->getConnection()->prepare($sqlStudents);
        $stmtStudents->bindValue(':id', $id);
        $stmtStudents->bindValue(':student_number', $data['student_number']);
        $stmtStudents->bindValue(':first_name', $data['first_name']);
        $stmtStudents->bindValue(':middle_name', $data['middle_name']);
        $stmtStudents->bindValue(':last_name', $data['last_name']);
        $stmtStudents->bindValue(':gender', $data['gender']);
        $stmtStudents->bindValue(':birthday', $data['birthday']);
        $stmtStudents->execute();

        $this->db->getConnection()->commit();

        
        return $stmtStudents->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
    public function delete($id) {
        try {
            $this->db->getConnection()->beginTransaction();
        
        $sqlDetails = "DELETE FROM student_details WHERE student_id = :id";
        $stmtDetails = $this->db->getConnection()->prepare($sqlDetails);
        $stmtDetails->bindValue(':id', $id);
        $stmtDetails->execute();

        
        $sqlStudents = "DELETE FROM students WHERE id = :id";
        $stmtStudents = $this->db->getConnection()->prepare($sqlStudents);
        $stmtStudents->bindValue(':id', $id);
        $stmtStudents->execute();

        $this->db->getConnection()->commit();

        
        return $stmtStudents->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }

    public function displayAll(){
        try {
            $sql = "SELECT students.*, student_details.contact_number, student_details.street, student_details.town_city, student_details.province, student_details.zip_code
            FROM students
            LEFT JOIN student_details ON students.id = student_details.student_id;"; 
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; 
        }
    }
    public function testCreateStudent() {
        $data = [
            'student_number' => 'S12345',
            'first_name' => 'John',
            'middle_name' => 'Doe',
            'last_name' => 'Smith',
            'gender' => '1',
            'birthday' => '1990-01-15',
        ];

        $student_id = $this->create($data);

        if ($student_id !== null) {
            echo "Test passed. Student created with ID: " . $student_id . PHP_EOL;
            return $student_id;
        } else {
            echo "Test failed. Student creation unsuccessful." . PHP_EOL;
        }
    }

    public function testReadStudent($id) {
        $studentData = $this->read($id);

        if ($studentData !== false) {
            echo "Test passed. Student data read successfully: " . PHP_EOL;
            print_r($studentData);
        } else {
            echo "Test failed. Unable to read student data." . PHP_EOL;
        }
    }

    public function testUpdateStudent($id, $data) {
        $success = $this->update($id, $data);

        if ($success) {
            echo "Test passed. Student data updated successfully." . PHP_EOL;
        } else {
            echo "Test failed. Unable to update student data." . PHP_EOL;
        }
    }

    public function testDeleteStudent($id) {
        $deleted = $this->delete($id);

        if ($deleted) {
            echo "Test passed. Student data deleted successfully." . PHP_EOL;
        } else {
            echo "Test failed. Unable to delete student data." . PHP_EOL;
        }
    }
}


$student = new Student(new Database());

$student_id = $student->testCreateStudent();


$student->testReadStudent($student_id);


$update_data = [
    'id' => $student_id,
    'student_number' => 'S67890',
    'first_name' => 'Alice',
    'middle_name' => 'Jane',
    'last_name' => 'Doe',
    'gender' => '0',
    'birthday' => '1995-05-20',

];
$student->testUpdateStudent($student_id, $update_data);


$student->testDeleteStudent($student_id);

?>

?>
