<?php
require_once "../conexion/config.php"; // Asegúrate de que esta ruta sea correcta
require_once "../modelo/modPersonas.php"; // Asegúrate de que esta ruta sea correcta

// Controlador de Persona
class ctrlPersonas {
    private $db;
    private $persona;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->persona = new modPersonas($this->db); // Asegúrate de que la clase Persona esté definida correctamente
    }

    // Método para registrar una nueva persona
    public function registrarPersona($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento) {
        // Validar los datos antes de intentar registrar
        if (empty($nombres) || empty($a_paterno) || empty($correo) || empty($fecha_nacimiento)) {
            echo "<div class='alert alert-danger'>Error: Todos los campos son obligatorios.</div>";
            return false;
        }

        // Asignar valores a la clase modPersona
        $this->persona->nombres = $nombres;
        $this->persona->a_paterno = $a_paterno;
        $this->persona->a_materno = $a_materno;
        $this->persona->correo = $correo;
        $this->persona->fecha_nacimiento = $fecha_nacimiento;

        // Intentar registrar la persona
        try {
            if ($this->persona->registrar()) { // Llamar al método registrar() de la clase modPersona
                echo "<div class='alert alert-success'>Persona registrada con éxito.</div>";
                return true; // Retorna true si se registró correctamente
            } else {
                echo "<div class='alert alert-danger'>Error: No se pudo registrar la persona. Verifica los datos.</div>";
                return false;
            }
        } catch (PDOException $e) {
            // Mostrar mensaje de error detallado en caso de excepción
            echo "<div class='alert alert-danger'>Error al registrar la persona: " . $e->getMessage() . "</div>";
            return false;
        }
    }
}
?>
