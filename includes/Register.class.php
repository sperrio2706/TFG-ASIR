<?php

	require_once('functions.php');
	
	# Clase que registra a un usuario en la base de datos en caso de que no exista

	class Register{
		
		private $nombre;
		private $apellidos;
		private $email;
		private $password;
		private $jefe;	
		private $connectionDB;
		private $result_register;
	
		public function __construct($nombre, $apellidos, $email, $password, $jefe){
			$this->nombre = secure_data($nombre);
			$this->apellidos = secure_data($apellidos);
			$this->email = secure_data($email);
			$this->password = secure_data($password);
			$this->password = hash_password($this->password);
			$this->jefe = $jefe;
			$this->connectionDB = connectionDB();

			try{
				# Comprueba si existe el email
				if($this->check_email_exists()){
						$this->result_register = false;
				} else {
				# Si no existe el email crea el usuario
						$this->create_user();
						$this->add_working_hours();
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
	
			if(isset($result['Email'])){
	            return true;
	        } else {
	            return false;
	        }
		}
	
		private function create_user(){
			$consulta = "
			INSERT INTO Empleados( ID_Empleado, Nombre, Apellidos, Email, Passwordd, Jefe)
			VALUES (
				UUID(),
				:nombre, 
				:apellidos, 
				:email, 
				:password,
				:jefe
				);
			";
			$stmt = $this->connectionDB->prepare($consulta);
			$stmt->bindParam(':nombre',$this->nombre);
			$stmt->bindParam(':apellidos',$this->apellidos);
			$stmt->bindParam(':email',$this->email);
			$stmt->bindParam(':password',$this->password);
			$stmt->bindParam(':jefe',$this->jefe);
			$stmt->execute();
		}

		private function add_working_hours(){
			# Consultamos el UUID generado para el usuario
			$stmt = $this->connectionDB->prepare('SELECT * FROM Empleados WHERE Email=:email');
            $stmt->bindParam(':email',$this->email);
            $stmt->execute();

            $result = $stmt->fetch();

            $id =  $result['Id_Empleado'];

			$consulta = "
			INSERT INTO Horarios( ID_Empleado, Hora_Entrada, Hora_Salida)
			VALUES (
				:id_empleado,
				:hora_entrada,
				:hora_salida
				);
			";
			$stmt = $this->connectionDB->prepare($consulta);
			$stmt->bindParam(':id_empleado',$id);
			$stmt->bindParam(':hora_entrada','08:00:00');
			$stmt->bindParam(':hora_salida','15:00:00');
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