<?php
require_once "../conexion/config.php";
require_once "../modelo/modUsuario.php";
require_once "../modelo/modPersonas.php";
require_once "ctrlPersonas.php"; // Asegúrate de que esta ruta sea correcta

// Controlador de Usuario
class ctrlUsuarios {
    private $db;
    private $usuarios;
    private $persona;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuarios = new modUsuario($this->db);
        $this->persona = new ctrlPersonas(); // No necesitas pasar la conexión aquí
    }

    // Método para manejar el registro de usuarios
    public function registrarUsuario($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento, $nombre_usuario, $password) {
        try {
            // Intentar registrar la persona usando el controlador de Persona
            $persona_id = $this->persona->registrarPersona($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento);

            // Verificar si se obtuvo un ID válido
            if ($persona_id) { // Ya no necesitas validar si es vacío y numérico
                echo "<div class='alert alert-success'>ID obtenido: $persona_id</div>";

                // Registrar el usuario con el ID de persona
                $this->usuarios->nombre = $nombre_usuario;
                $this->usuarios->password = $password;

                if ($this->usuarios->registrar($persona_id)) {
                    echo '<div class="alert alert-success">Usuario registrado correctamente.</div>';
                    return true;
                } else {
                    echo '<div class="alert alert-danger">Error al registrar el usuario.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">No se obtuvo un ID válido de la persona registrada. Posible error en la inserción de la persona.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error al registrar el usuario: ' . $e->getMessage() . '</div>';
        }
        return false;
    }

    // Método para manejar el inicio de sesión
    public function iniciarSesion($nombre_usuario, $password) {
        $this->usuarios->nombre = $nombre_usuario;
        $this->usuarios->password = $password;
        if ($this->usuarios->iniciarSesion()) {
            session_start();
            $_SESSION['usuarios'] = $this->usuarios->nombre;
            header("Location: ../vista/bienvenido.php");
        } else {
            header("Location: ../vista/login&registro.php?error=1");
        }
    }

    // Método para cerrar sesión
    public function cerrarSesion() {
        session_start();
        session_destroy();
        header("Location: ../vista/login&registro.php");
    }
}
?>
