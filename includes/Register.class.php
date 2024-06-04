<?php

	require_once('functions.php');
	
	# Clase que registra a un usuario en la base de datos en caso de que no exista

	class Register{
		
		private $email;
		private $password;	
		private $connectionDB;
		private $result_register;
	
		public function __construct($email, $password){
			$this->email = secure_data($email);
			$this->password = secure_data($password);
			$this->password = hash_password($this->password);
			$this->connectionDB = connectionDB();

			try{
				# Comprueba si existe el email
				if($this->check_email_exists()){
						$this->result_register = false;
				} else {
				# Si no existe el email crea el usuario
						$this->create_user();
						$this->result_register = true;
				}
			} catch(Exception $e){
				die('ERROR: '. $e->getMessage());
			}
		}
	
		private function check_email_exists(){
			$stmt = $this->connectionDB->prepare('SELECT * FROM Empleados WHERE Email=:email');
			$stmt->bindParam(':email',$this->email);
			$stmt->execute();
			
			$result = $stmt->fetch();
	
			if(isset($result['email'])){
	            return true;
	        } else {
	            return false;
	        }
		}
	
		private function create_user(){
			$stmt = $this->connectionDB->prepare('INSERT INTO Empleados (Email, Passwordd) VALUES (:email,:password)');
			$stmt->bindParam(':email',$this->email);
			$stmt->bindParam(':password',$this->password);
			$stmt->execute();
		}

		public function get_confirmation(){
			if($this->result_register){
				return 'Usuario creado con éxito';
			} else {
				return 'El email ya existe en el sistema';
			}
		}
		
	}

?>