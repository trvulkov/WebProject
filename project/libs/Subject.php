<?php
require_once "ValidationException.php";

class Subject implements JsonSerializable {
    private string $name;
    private string $lecturer;

    public function __construct(array $data) {
        $this->name = isset($data['name']) ? $data['name'] : "";
        $this->lecturer = isset($data['lecturer']) ? $data['lecturer'] : "";
    }
    public function validate(): void {
        $errors = [];

        if(!$this->name) {
            $errors['name'] = "Name is empty!";
        }
        if(!$this->lecturer) {
            $errors['lecturer'] = "Lecturer is empty!";
        }

        if (mb_strlen($this->name, "utf-8") > 100) {
            $errors['name'] = "Name too long!";
        }
        if (mb_strlen($this->lecturer, "utf-8") > 100) {
            $errors['lecturer'] = "Lecturer too long!";
        }    
        
        $db = (new Db());
        if ($db->subjectExists(['name' => $this->name])) {
            $errors['name'] = "Name already in use! " . $this->name;
        }

        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    public function jsonSerialize(): array {
        return [
            'name' => $this->name, 
            'lecturer' => $this->lecturer
        ];
    }
}

?>