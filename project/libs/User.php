<?php
require_once "ValidationException.php";

class User implements JsonSerializable {
    private string $username;
    private string $email;
    private string $password;
    private string $confirmPassword;

    public function __construct(array $data) {
        $this->username = isset($data['username']) ? $data['username'] : "";
        $this->email = isset($data['email']) ? $data['email'] : "";
        $this->password = isset($data['password']) ? $data['password'] : "";
        $this->confirmPassword = isset($data['confirm']) ? $data['confirm'] : "";
    }
    public function validate(): void {
        $errors = [];

        if(!$this->username) {
            $errors['username'] = "Username is empty!";
        }
        if(!$this->email) {
            $errors['email'] = "Email is empty!";
        }
        if(!$this->password) {
            $errors['password'] = "Password is empty!";
        }

        if (mb_strlen($this->username, "utf-8") > 100) {
            $errors['username'] = "Username too long!";
        }
        if (mb_strlen($this->email, "utf-8") > 100) {
            $errors['email'] = "Email too long!";
        }
        if (mb_strlen($this->password, "utf-8") > 100) {
            $errors['password'] = "Password too long!";
        }

        if (preg_match("/^[\w\-]{1,100}$/", $this->username) == false) {
            $errors['username'] = "Invalid username! " . $this->username;
        }
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) == false) {
            $errors['email'] = "Invalid email! " . $this->email;
        }
        // if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $this->password) == false) {
        //     $errors['password'] = "Invalid password!";
        // }
        if (preg_match("/^[\w\-]{1,100}$/", $this->password) == false) {
            $errors['username'] = "Invalid password! " . $this->password;
        }
        if ($this->password != $this->confirmPassword) {
            $errors['confirm-password'] = "Passwords must match!";
        }

        $db = (new Db());
        if ($db->userExists(['username' => $this->username])) {
            $errors['username'] = "Username already in use! " . $this->username;
        }

        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    public function jsonSerialize(): array {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
        ];
    }

} 