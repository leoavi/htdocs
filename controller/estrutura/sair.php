<?php
include_once('../tecnologia/Sistema.php');
    unset($_SESSION['CPF']);
	unset($_SESSION['NOME']);
    unset($_SESSION['SENHAWEB']);
	unset($_SESSION);
	unset($_COOKIE);
    header('Location: ../../view/estrutura/acesso.php');
?>