<?php

require_once('../Model/Base.php');

class Tb_Usuario extends Base
{
    private $id_usuario;
    private $nm_usuario;
  

    function __construct($p_banco)
    {
        parent::__construct($p_banco);
    }

    function SetNmUsuario($p_NmUsuario)
    {
        $this->nm_usuario = $p_NmUsuario;
    }

    function SetIdUsuario($p_IdUsuario)
    {
        $this->id_usuario = $p_IdUsuario;
    }
 

    public function verificaExistencia()
    {
        $consulta = $this->conexao->query(
            "SELECT 1 FROM Tb_Usuario where id_usuario = $this->id_usuario");

        $ret = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!$ret) 
        {
            throw new Exception("Usuario nao Localizado");
        }

        return $ret;
    }

    public function buscaUsuario()
    {

        $sql = "SELECT * FROM tb_usuario WHERE id_usuario = " . $this->id_usuario;

        $consulta = $this->conexao->query($sql);
        $ret = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!$ret) 
        {
            throw new Exception("Usuario nao Localizado");
        }
        return $ret;
    }

    public function Inserir(){
        try 
        {
            $this->verificaExistencia();
            $this->banco->setMensagem(0, "Usuario ja Cadastrado");
        } 
        catch (Exception $e) 
        {
      
            $stmt = $this->conexao->prepare("INSERT INTO TB_USUARIO(id_usuario, nm_usuario) VALUES " .
                                            "(nextval('sq_usuario'), :NmUsuario)");
           
            $stmt->bindValue(':NmUsuario' , $this->nm_usuario , PDO::PARAM_STR);
           
            $this->conexao->beginTransaction();
            $stmt->execute();
            $this->conexao->commit(); 
    
            $this->banco->setMensagem(1, "Usuario incluso com sucesso");
        }
    }

    public function AlterarDadosUsuario(){
        try 
        {
            $this->buscaUsuario();
            $stmt = $this->conexao->prepare("UPDATE TB_Usuario  set  nm_usuario = :NmUsuario WHERE id_usuario = :IdUsuario");
            $stmt->bindValue(':IdUsuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':NmUsuario', $this->nm_usuario, PDO::PARAM_STR);
            $this->conexao->beginTransaction();
            $stmt->execute();
            $this->conexao->commit();
            $this->banco->setMensagem(1, "Dados do usuario Alterados");
        } 
        catch (Exception $e) 
        {
            throw new Exception($e->getMessage());
        }
    }
 
    public function Excluir()
    {
        try 
        {
            $this->buscaUsuario();
            $stmt = $this->conexao->prepare(
                'Delete From TB_Usuario ' .
                    'WHERE id_usuario = :IdUsuario'
            );

            $stmt->bindValue(':IdUsuario', $this->id_usuario, PDO::PARAM_INT);
            $this->conexao->beginTransaction();
            $stmt->execute();
            $this->conexao->commit();
            $this->banco->setMensagem(1, "Usuario Excluido com Sucesso");
        } 
        catch (Exception $e) 
        {
            throw new Exception($e->getMessage());
        }
    }

    public function Consultar()
    {
        try 
        {
            $ret = $this->buscaUsuario();
            $this->banco->setMensagem(1, "Consulta efetuada com Sucesso");
            $this->banco->setDados(count($ret), $ret);
        } 
        catch (Exception $e) 
        {
            throw new Exception($e->getMessage());
        }
    }

    public function Listar()
    {
        $ret = $this->conexao->query("SELECT id_usuario, nm_usuario FROM Tb_Usuario;");
        $ret = $ret->fetchAll();
        $this->banco->setMensagem(1, "Sucesso na Pesquisa");
        $this->banco->setDados(count($ret), $ret);
    }

    public function Login()
    {
        try
        {
            $ret = $this->verificaExistencia();
            $this->banco->setMensagem(1, "Login Permitido");
            $this->banco->setDados(count($ret), $ret);
        } 
        catch (Exception $e) 
        {
            $this->banco->setMensagem(0, "Usuario Inexistente");
        }
    }

}
