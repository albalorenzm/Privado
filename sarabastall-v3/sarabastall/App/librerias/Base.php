<?php
    //Clase de conexion a base de datos y ejecutar consultas PDO
    class Base{
        private $host = DB_HOST;
        private $usuario = DB_USUARIO;
        private $password = DB_PASSWORD;
        private $nombre_base = DB_NOMBRE;
        private $charset = 'utf8mb4';

        private $dbh;    //database handler
        private $stmt;  //statement
        private $error;

        public function __construct(){
            //Configurar conexión
            $dsn = "mysql:host=$this->host;dbname=$this->nombre_base;charset=$this->charset";

            $opciones = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            //INSTANCIA DE PDO
            try{
                $this->dbh = new PDO($dsn, $this->usuario, $this->password, $opciones);
                //$this->dbh->exec('set names utf-8');  //Inclulle tildes y la ñ
            }
            catch (PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }

        public function bind($parametro, $valor, $tipo=null){
            if (is_null($tipo)){
                switch (true){
                    case is_int($valor):
                        $tipo = PDO::PARAM_INT;
                        break;
                    case is_bool($valor):
                        $tipo = PDO::PARAM_BOOL;
                        break;
                    case is_null($valor):
                        $tipo = PDO::PARAM_NULL;
                        break;
                    default:
                        $tipo = PDO::PARAM_STR;
                        break;
                }
            }
            $this->stmt->bindValue($parametro, $valor, $tipo);
        }


        // Ejecutar consulta
        public function execute(){
            return $this->stmt->execute();
        }

        // Obtener todos los registros
        public function registros(){
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        // Obtener un solo registro
        public function registro(){
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        // Obtener numero de registros
        public function rowCount(){
            $this->execute();
            return $this->stmt->rowCount();
        }


    }
?>