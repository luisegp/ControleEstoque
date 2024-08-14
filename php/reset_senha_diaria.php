<?php
session_start();
$_SESSION['senha_diaria'] = 1;
$_SESSION['data_ultima_senha'] = date('Y-m-d');
?>
