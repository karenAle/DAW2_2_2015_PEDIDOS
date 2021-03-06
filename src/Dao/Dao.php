<?php

class Dao{
    
    protected $connection;
    private $host = 'localhost';
    private $dbname = 'ifBurguer';
    private $usuario = 'root';
    private $senha = 'mysql';
       
    function __construct() {

        $this->connection = new PDO(
                        "mysql:host={$this->host};dbname={$this->dbname};",
                        $this->usuario,
                        $this->senha);         
                        
        $this->connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    }
                        
    protected function executeCommand($sqlCommand, $parameters){
        
        try{
                    
            $this->connection ->beginTransaction();
            $comand = $this->connection ->prepare($sqlCommand);

            if($parameters != null){
                foreach ($parameters as $key => $value){
                    $comand->bindValue($key, $value);
                    var_dump($comand);
                }
            }
            $comand ->execute();
            $this->connection->commit();
    //        return true;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            if($this->connection != null){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
            }
    //        return false;
        } finally {
            if($this->connection != null){
                $this->connection = null;
            }
        }
    }

    protected function executeQuery($sqlCommand, $parameters) {
        
        try {
            $this->connection ->beginTransaction();
            $command = $this->connection->prepare($sqlCommand);

            if ($parameters != null) {
                foreach ($parameters as $key => $value) {
                    $listaRetorno = array();
                    $command->bindValue($key, $value);
                }
            }
            $command->execute();
            $listaRetorno = array();
            for ($i = 0; $row = $command->fetch(PDO::FETCH_ASSOC); $i++) {
                array_push($listaRetorno, $row);
            };
            return $listaRetorno;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            if ($this->connection != null) {
                if ($this->connection->inTransaction()) {
                    $this->connection->rollBack();
                }
            }
            return array();
        } finally {
            //UTILIZADO NA VERSAO DO PHP 5.6 NAS PREVIAS NAO É COMPATÍVEL
            if ($this->connection != null) {
                //FECHA A CONEXAO COM O BANCO DE DADOS
                $this->connection = null;
            }
        }
    }
}
