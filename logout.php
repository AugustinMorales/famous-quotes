<?php
require_once 'includes/auth.php';
logout(); // Destroys session completely
header('Location: login.php');
exit;
