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

?>