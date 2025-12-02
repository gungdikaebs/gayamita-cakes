<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\logout.php
session_start();
require_once '../method/function.php';

admin_logout();
header('Location: login.php');
exit;
