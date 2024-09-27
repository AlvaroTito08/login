<?php
// Clase para manejar los usuarios
class modUsuario {
    private $conn;

    public $nombre;
    public $password;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar un nuevo usuario
    public function registrar($persona_idPersona) {
        // Consulta SQL para insertar un nuevo usuario
        $query = "INSERT INTO usuario (nombre, password, persona_idPersona) VALUES (:nombre, :password, :persona_idPersona)";
        $stmt = $this->conn->prepare($query);

        // Enlazar parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        
        // Encriptar la contraseña antes de enviarla a la base de datos
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
        
        // Asegúrate de usar la variable correcta
        $stmt->bindParam(':persona_idPersona', $persona_idPersona);

        // Ejecutar consulta y devolver el resultado
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Mostrar error para depuración (puedes cambiarlo para registrar logs en producción)
            echo "Error al registrar el usuario: " . $e->getMessage();
        }
        
        return false;
    }

    // Método para iniciar sesión
    public function iniciarSesion() {
        // Consulta SQL para verificar la existencia del usuario
        $query = "SELECT password FROM usuario WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->execute();

        // Verificar si existe al menos un resultado
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar la contraseña con el hash almacenado en la base de datos
            if (password_verify($this->password, $row['password'])) {
                return true;
            }
        }
        return false;
    }
}
?>
