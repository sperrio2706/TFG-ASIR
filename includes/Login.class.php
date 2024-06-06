<?php
    require_once('functions.php');

    # Clase que inicia sesion
    class Login{
        private $email;
        private $password;
        private $connectionDB;

        public function __construct($email, $password)
        {
            $this->email = secure_data($email);
            $this->password = secure_data($password);
            $this->connectionDB = connectionDB();

            if($this->check_email_exists()){
                 $passInDB = $this->get_pass_in_db();

                 # Compara el hash de la contraseña en la  base de datos 
                 $auth = password_verify($this->password,$passInDB);

                 # Si el hash y la contraseña introducida coinciden
                 if($auth){
                    ob_start();
                    session_start();
                    $_SESSION['email'] = $this->email;
                    $_SESSION['valid'] = true;

                    if (es_jefe($this->email)){
                        header('Location: index-jefes.html');
                    }else{
                        # Redirige a fichero privado
                        header('Location: index.html');
                    }

                    
                 } else {
                    # Acceso denegado (contraseña no coincide)
                    header('Location: https://bettercallsergio.es/index.html');
                 }
            } else {
                # Acceso denegado (email no registrado)
                header('Location: https://bettercallsergio.es/index.html');
            }


        }

        private function check_email_exists(){
			$stmt = $this->connectionDB->prepare('SELECT * FROM Empleados WHERE Email=:email');
			$stmt->bindParam(':email',$this->email);
			$stmt->execute();
			
			$result = $stmt->fetch();
	
			if(isset($result['Email'])){
	            return true;
	        } else {
	            return false;
	        }
		}

        private function get_pass_in_db(){
            $stmt = $this->connectionDB->prepare('SELECT * FROM Empleados WHERE Email=:email');
            $stmt->bindParam(':email',$this->email);
            $stmt->execute();

            $result = $stmt->fetch();

            return $result['Passwordd'];
        }
    }

?>