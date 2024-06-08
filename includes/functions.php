<?php

        # Funcion que filtra cadenas
		function secure_data($data){
            # quita los espacios al principio y a final
			$data = trim($data);

            # quita las comillas simples de la cadena
	        $data = stripslashes($data);

            # cambia caracteres especiales a su notación html
	        $data = htmlspecialchars($data);
	
	        return $data;
		}


        # Funcion que transforma una contraseña en texto plano en su hash
       function hash_password($password){
			return password_hash($password, PASSWORD_DEFAULT);
		}

        # Funcion que se conecta a la base de datos y devuelve el objeto pdo
        function connectionDB(){
            $host = '192.168.1.202:3306';
            $dbName = 'employees_db';
            $user = 'empleados';
            $pass = 'Departamento1!';
            $hostDB = 'mysql:host='.$host.';dbname='.$dbName.';';
    
            try{
                $connection = new PDO($hostDB,$user,$pass);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                return $connection;
            } catch(PDOException $e){
                die('ERROR: '.$e->getMessage());
            }
        }

        # Función que comprueba si el usuario es jefe

        function es_jefe($email){
            $conexion = connectionDB();
            $stmt = $conexion->prepare('SELECT * FROM Empleados WHERE Email=:email');
			$stmt->bindParam(':email',$email);
			$stmt->execute();
			
			$result = $stmt->fetch();
	
			if($result['Jefe'] == 'S'){
	            return true;
	        } else {
	            return false;
	        }
        }

        function removeAccents($string) {
            $tildes = [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                'ñ' => 'n', 'Ñ' => 'N'
            ];
            // strtr cambia carateres con tilde almacenados en el array
            return strtr($string, $tildes);
        }
        
        function generateImagePath($nombre, $apellidos) {
            $nombre = removeAccents(strtolower(str_replace(' ', '', $nombre)));
            $apellidos = removeAccents(strtolower(str_replace(' ', '', $apellidos)));
            return "/var/www/html/images/user-images/{$nombre}{$apellidos}/perfil.jpg";
        }

?>