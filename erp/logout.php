<?php
require __DIR__ . '/core/bootstrap.php';
session_destroy();
header('Location: /erp/login.php');
