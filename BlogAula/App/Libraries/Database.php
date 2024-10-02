<?php
class Database{
    private $host = "localhost";
    private $usuario = "root";
    private $senha = "";
    private $banco = "blogaula";
    private $porta = "3306"; //verificar a porta do seu banco
    private $dbh;
    private $stmt;

    public function __construct(){
        //fonte de daos ou DNS que contém as informações para conectar ao banco de dados.
        $dns = 'mysql:host='.$this->host.';port='.$this->porta.'dbname='.$this->banco;

        //armazenar em cache a conexão para ser reutilizada, evitando sobrecarga de uma nova conexão.
        $opcoes = [
            PDO::ATTR_PERSISTENT => true,
        //lança um PDOException se ocorrer um erro
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        try{
            //cria  a instancia do POO
            $this->dbh = new PDO($dns, $this->usuario, $this->senha, $opcoes);
        }catch(PDOException $error){
            print "Error!";$error -> getMessage()."<br/>";
            die();
        }//fim do catch
    }//fim do metodo construtor

    // prepara o statement com query
    public function query($sql){
        //prepara a consulta sql
        $this -> stmt= $this -> dbh -> prepare($sql);
    }//fim da funcao query
    //vincula um valor a um parametro
    public function bind($parametro, $valor, $tipo= null){
        if(is_null($tipo)):
            switch(true):
                case is_int($valor):
                    $tipo = PDO::PARAM_INT;
                    break;
                case is_bool($valor):
                    $tipo = PDO::PARAM_BOOL;
                    break;
                case is_null($valor):
                    $tipo = PDO::PARAM_NULL;
                    break;
            endswitch;
        endif;
    }// fim da funcao bind
    //executa prepared statement
    public function executa(){
        return $this -> stmt ->execute();
    }//fim da função executa

    //obtem um unico registo
    public function resultado(){
        $this -> executa();
        return $this -> stmt -> fetch(PDO::FETCH_OBJ);
    }//fimd a funcao resultados

    //obtem um conjunto de registros
    public function resultados(){
        $this -> executa();
        return $this -> stmt -> fecthAll(PDO::FETCH_OBJ);
    }///fim da funcao resultados

    //retorna o numero de linhas afetadas pela ultima instruçã SQL
    public function totalResultados(){
        return $this -> stmt -> rowCount();
    }//fim da função totalResultados

    //retorna o ultimo ID inserido no banco de dados
    public function ultimoIdInserido(){
        return $this -> dbh ->lastInsertId();
    }
}//fim da classe Database
