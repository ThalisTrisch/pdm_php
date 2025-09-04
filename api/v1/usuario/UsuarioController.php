<?php
      
    require_once('./UsuarioService.php');
    require_once('../../database/Banco.php');
    
    $s_nm_usuario = isset($_REQUEST['nm_usuario'])?$_REQUEST['nm_usuario']:"";
    $i_id_usuario = isset($_REQUEST['id_usuario'])?$_REQUEST['id_usuario']:"";

    $Oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] :"";
    
    try {  
        $banco = new Banco(null,null,null,null,null,null);
        
        $usuarioService = new UsuarioService($banco);

        $usuarioService->setOper($Oper);
        $usuarioService->SetNmUsuario($s_nm_usuario);
        $usuarioService->SetIdUsuario($i_id_usuario);
        
        switch ($Oper) {
            case 'Inserir':
                $usuarioService->Inserir();
                break;  
            case 'Alterar':
                $usuarioService->AlterarDadosUsuario();
                break;   
            case 'Excluir':
                $usuarioService->Excluir();
                break; 
            case 'Consultar':
                $usuarioService->Consultar();
                break;   
            case 'Listar':
                $usuarioService->Listar();
                break; 
            case 'Login':
                $usuarioService->Login();
                break;   
            default:
                $banco->setMensagem(1,'Operacao informada nao tratada');
                break;
        }

        echo $banco->getRetorno();
        unset($banco);
    }
    catch(Exception $e) {   
        if (isset($banco)) {   
            $banco->setMensagem(1,$e->getMessage());
            echo $banco->getRetorno();
            unset($banco);
        }   
        else {
            echo $e.getMessage();
        }
    }
            
?>