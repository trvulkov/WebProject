<?php

class Prerequisite implements JsonSerializable {
    private string $subject;
    private string $prerequisite;

    public function __construct(array $data) {
        $this->subject = isset($data['subject']) ? $data['subject'] : "";
        $this->prerequisite = isset($data['prerequisite']) ? $data['prerequisite'] : "";
    }

    public function validate() {
        $errors = [];

        if(!$this->subject) {
            $errors['subject'] = "Subject is empty!";
        }
        if(!$this->prerequisite) {
            $errors['prerequisite'] = "Prerequisite is empty!";
        }

        if (mb_strlen($this->subject, "utf-8") > 100) {
            $errors['subject'] = "Subject too long!";
        }
        if (mb_strlen($this->prerequisite, "utf-8") > 100) {
            $errors['lecturer'] = "Prerequisite too long!";
        }    
        
        $db = (new Db());
        if ($this->subject == $this->prerequisite) {
            $errors['subject'] = "A subject cannot require itself! " . $this->subject;
        }
        if ($db->subjectExists(['name' => $this->subject]) == false) {
            $errors['subject'] = "No such subject! " . $this->subject;
        }
        if ($db->subjectExists(['name' => $this->prerequisite]) == false) {
            $errors['prerequisite'] = "No such subject! " . $this->prerequisite;
        }
        if ($db->prerequisiteExists($this->jsonSerialize())) {
            $errors['subject'] = "Prerequisite already exists! " . $this->prerequisite . " -> " . $this->subject;
        }
        
        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    public function jsonSerialize(): array {
        return [
            'subject' => $this->subject, 
            'prerequisite' => $this->prerequisite
        ];
    }
}

?>