<?php

session_start();
echo json_encode(['logged' => isset($_SESSION['username'])]);

?>