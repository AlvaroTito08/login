<?php
class modPersonas {
    private $conn;

    public $nombres;
    public $a_paterno;
    public $a_materno;
    public $correo;
    public $fecha_nacimiento;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar una nueva persona
    public function registrar() {
        // Consulta SQL para insertar una nueva persona
        $query = "INSERT INTO persona (nombres, a_paterno, a_materno, correo, fecha_nacimiento) 
                  VALUES (:nombres, :a_paterno, :a_materno, :correo, :fecha_nacimiento)";
        $stmt = $this->conn->prepare($query);

        // Enlazar parámetros
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':a_paterno', $this->a_paterno);
        $stmt->bindParam(':a_materno', $this->a_materno);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);

        try {
            // Ejecutar consulta
            if ($stmt->execute()) {
                // Retornar el último ID insertado
                return $this->conn->lastInsertId();
            } else {
                // Mostrar el error en caso de fallo
                echo "<div class='alert alert-danger'>Error: No se pudo registrar la persona. Verifica los datos.</div>";
                return false;
            }
        } catch (PDOException $e) {
            // Capturar y mostrar el mensaje de error
            echo "<div class='alert alert-danger'>Error al registrar la persona: " . $e->getMessage() . "</div>";
            return false;
        }
    }
}
?>
