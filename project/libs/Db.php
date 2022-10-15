<?php

require_once "Config.php";

class Db {
    private PDO $connection;

    public function __construct() {
        $dbHost = Config::DB_HOST;
        $dbName = Config::DB_NAME;
        $userName = Config::DB_USERNAME;
        $userPassword = Config::DB_PASSWORD;

        $this->connection = new PDO("mysql:host=$dbHost;dbname=$dbName", $userName, $userPassword, 
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
    public function selectUser(array $data): array {
        try {
            $selectStatement = $this->connection->prepare("
                SELECT * 
                FROM `users`
                WHERE username=:username
            ");

            $result = $selectStatement->execute(['username' => $data['username']]);
            
            $user = $selectStatement->fetch();
            if ($user) {
                return ['success' => true, 'user' => $user];
            } else {
                return ['success' => false];
  
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    public function selectSubject(array $data): array {
        try {
            $selectStatement = $this->connection->prepare("
                SELECT * 
                FROM `subjects`
                WHERE name=:name
            ");

            $result = $selectStatement->execute(['name' => $data['name']]);
            
            $subject = $selectStatement->fetch();
            if ($subject) {
                return ['success' => true, 'subject' => $subject];
            } else {
                return ['success' => false];
  
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }    
    }
    public function selectPrerequisite(array $data): array {
        try {
            $selectStatement = $this->connection->prepare("
                SELECT * 
                FROM `prerequisites`
                WHERE subject=:subject AND prerequisite=:prerequisite
            ");

            $result = $selectStatement->execute($data);
            
            $prerequisite = $selectStatement->fetch();
            if ($prerequisite) {
                return ['success' => true, 'prerequisite' => $prerequisite];
            } else {
                return ['success' => false];
  
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }    
    }

    public function fetchSubjects(): array {
        $subjects = [];
        
        $query = $this->connection->query("SELECT * FROM `subjects`") or die("failed!");
        while ($subject = $query->fetch()) {
            $subjects[] = new Subject($subject);
        }

        return $subjects;
    }
    public function fetchPrerequisites(): array {
        $prerequisites = [];
        
        $query = $this->connection->query("SELECT * FROM `prerequisites`") or die("failed!");
        while ($prerequisite = $query->fetch()) {
            $prerequisites[] = new Prerequisite($prerequisite);
        }

        return $prerequisites;
    }

    public function fetchPlanProgrammes(): array {
        $programmes;
        $query = $this->connection->query("SELECT DISTINCT programme FROM `plan`") or die("failed!");
        while ($row = $query->fetch()) {
            $programmes[] = $row;
        }

        return $programmes;
    }
    public function fetchPlanSemesters(): array {
        $semesters;
        $query = $this->connection->query("SELECT DISTINCT semester FROM `plan`") or die("failed!");
        while ($row = $query->fetch()) {
            $semesters[] = $row;
        }

        return $semesters;
    }
    public function fetchPlan(string $programme, string $semester): array {
        $selectStatement = $this->connection->prepare("
            SELECT DISTINCT subject 
            FROM `plan`
            WHERE programme=:programme AND semester=:semester
        ");

        $result = $selectStatement->execute(['programme' => $programme, 'semester' => $semester]);
        
        $plan = $selectStatement->fetchAll();
        return $plan;
    }

    public function checkLogin(array $data): array {
        $query = $this->selectUser($data);

        if ($query['success'] == false) {
            return ['success' => false, 'error' => "No such user: " . $data['username']];
        }

        $user = $query['user'];
        if (password_verify($data['password'], $user['password']) == false) {
            return ['success' => false, 'error' => "Invalid password! " . $data['password']];
        }

        return ['success' => true, 'user' => $user];
    }
    public function userExists(array $data): bool {
        $query = $this->selectUser($data);

        if ($query["success"] && $query['user']) {
            return true;
        }

        return false;
    }
    public function subjectExists(array $data): bool {
        $query = $this->selectSubject($data);

        if ($query["success"] && $query['subject']) {
            return true;
        }

        return false;
    }
    public function prerequisiteExists(array $data): bool {
        $query = $this->selectPrerequisite($data);

        if ($query["success"] && $query['prerequisite']) {
            return true;
        }

        return false;
    }

    public function addUser(User $user): array {
        try {
            $user->validate();
        } catch (ValidationException $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()];
        }

        try {
            $insertStatement = $this->connection->prepare("
                INSERT INTO `users` (username, email, password)
                VALUES (:username, :email, :password)
            ");
    
            $serialized = $user->jsonSerialize();

            $insertStatement->execute($serialized);
            return ['success' => true, 'message' => $serialized['username'] . " added successfully!"];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    public function addSubject(Subject $subject): array {
        try {
            $subject->validate();
        } catch (ValidationException $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()];
        }

        try {
            $insertStatement = $this->connection->prepare("
                INSERT INTO `subjects` (name, lecturer)
                VALUES (:name, :lecturer)
            ");
    
            $serialized = $subject->jsonSerialize();

            $insertStatement->execute($serialized);
            return ['success' => true, 'message' => $serialized['name'] . " added successfully!"];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    public function addPrerequisite(Prerequisite $prerequisite) {
        try {
            $prerequisite->validate();
        } catch (ValidationException $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()];
        }

        try {
            $insertStatement = $this->connection->prepare("
                INSERT INTO `prerequisites` (subject, prerequisite)
                VALUES (:subject, :prerequisite)
            ");
            
            $serialized = $prerequisite->jsonSerialize();

            $insertStatement->execute($serialized);
            return ['success' => true, 'message' => $serialized['prerequisite'] . " -> " . $serialized['subject'] . " added successfully!"];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function removeSubject(string $subjectName): array {
        try {
            $deleteStatement = $this->connection->prepare("
                DELETE FROM `subjects`
                WHERE name=:name
            ");
    
            $deleteStatement->execute(['name' => $subjectName]);
            return ['success' => true, 'message' => $subjectName . " removed successfully!"];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    public function removePrerequisite(string $subjectName, string $prerequisiteName): array {
        try {
            $deleteStatement = $this->connection->prepare("
                DELETE FROM `prerequisites`
                WHERE subject=:subject AND prerequisite=:prerequisite
            ");
    
            $deleteStatement->execute(['subject' => $subjectName, 'prerequisite' => $prerequisiteName]);
            return ['success' => true, 'message' => $prerequisiteName . " -> " . $subjectName . " removed successfully!"];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

?>
