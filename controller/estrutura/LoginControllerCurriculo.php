<?php
include_once('../tecnologia/Sistema.php');
    $entrar = Sistema::getPost('entrar');

if (isset($entrar)) {
    $connect                = Sistema::getConexao();
    $usuario                = strtolower(Sistema::getPost('usuario'));
    $senhaNaoCriptografada  = Sistema::getPost('senha');
    $senha                  = base64_encode(sha1($senhaNaoCriptografada, true));
        
    // Verifica login digitado
    $queryLogin = $connect->prepare("SELECT A.CPF,
									   A.NOME,
									   A.SENHAWEB,
									   A.HANDLE
				  	                FROM 
                                        RC_CURRICULO A
				 				    WHERE A.CPF = '".$usuario."'
                                    AND 
                                     A.SENHAWEB = '".$senha."'
                                    ");
    $queryLogin->execute();
    
    $rowLogin = $queryLogin->fetch(PDO::FETCH_ASSOC);
    
    $login          = $rowLogin['CPF'];
    $nome           = $rowLogin['NOME'];
    $senha          = $rowLogin['SENHAWEB'];
    $handle         = $rowLogin['HANDLE'];
       
   //// Verifica senha digitada
    $querySenha = $connect->prepare("SELECT A.SENHAWEB
     			                FROM 
                                     RC_CURRICULO A
     		 	                WHERE 
                                     A.CPF = '".$usuario."'
                                     AND 
                                     A.SENHAWEB = '".$senha."'
     						");
    $querySenha->execute();
   
   
    $rowSenha = $querySenha->fetch(PDO::FETCH_ASSOC);
    
    $password   = $rowSenha['SENHAWEB'];
    
    //if login e senha existir
    if ($login == $usuario and $password == $senha) {
        $_SESSION['CPF']        = $login;
        $_SESSION['SENHAWEB']   = $senha;
        $_SESSION['NOME']       = $nome;
        $_SESSION['HANDLE']     = $handle;

        
        setcookie("ultimologinWeb", $login, time()+3600*24*30, '/');
        
        header('Location: ../../view/recrutamento/CurriculoListar.php');
    }
    elseif ($login == $usuario and $password == $senha || $login == $usuario and $password != $senha) {
        setcookie("ultimologinWeb", $login, time()+3600*24*30, '/');
            
        header('Location: ../../view/estrutura/acesso.php?success=F');
    } else {
        unset($_SESSION['CPF']);
        unset($_SESSION['NOME']);
        //unset($_SESSION['HANDLE']);
        unset($_SESSION['SENHAWEB']); 

        header('Location: ../../view/estrutura/acesso.php?success=F');
    }
}//isset entrar
else {
    echo "<script type='application/javascript'> window.location.href='../../view/estrutura/acesso.php?success=F';</script>";
}
