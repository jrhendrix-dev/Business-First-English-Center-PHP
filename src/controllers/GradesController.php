<?php

require_once __DIR__ . '/../models/Database.php';

/**
 * Save or update student grades.
 *
 * Handles POST requests to save or update a student's grades in the 'notas' table.
 * If the student does not have a row in 'notas', one is created.
 * Validates that grades are either null or between 0 and 10.
 *
 * Expects the following POST parameters:
 * - updateNota: (any value, used as a flag)
 * - idAlumno: int, the student user ID
 * - nota1: float|string|null, the first grade (0-10 or empty)
 * - nota2: float|string|null, the second grade (0-10 or empty)
 * - nota3: float|string|null, the third grade (0-10 or empty)
 *
 * @return void Outputs "success" on success, "error" or "error: valores de nota fuera de rango" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNota'])) {
    $con = Database::connect();

    $id = $_POST['idAlumno'];
    $nota1 = isset($_POST['nota1']) && $_POST['nota1'] !== "" ? floatval($_POST['nota1']) : null;
    $nota2 = isset($_POST['nota2']) && $_POST['nota2'] !== "" ? floatval($_POST['nota2']) : null;
    $nota3 = isset($_POST['nota3']) && $_POST['nota3'] !== "" ? floatval($_POST['nota3']) : null;

    /**
     * Validate a grade value.
     *
     * @param float|null $n The grade value.
     * @return bool True if valid, false otherwise.
     */
    function validarNota($n) {
        return is_null($n) || (is_numeric($n) && $n >= 0 && $n <= 10);
    }

    if (!validarNota($nota1) || !validarNota($nota2) || !validarNota($nota3)) {
        echo "error: valores de nota fuera de rango";
        exit;
    }

    $res = $con->query("SELECT * FROM notas WHERE idAlumno = $id");
    if ($res && $res->num_rows > 0) {
        // UPDATE
        $sql = "UPDATE notas SET 
            Nota1=" . (is_null($nota1) ? "NULL" : "?") . ", 
            Nota2=" . (is_null($nota2) ? "NULL" : "?") . ", 
            Nota3=" . (is_null($nota3) ? "NULL" : "?") . " 
            WHERE idAlumno=?";
        $stmt = $con->prepare($sql);

        $bindTypes = "";
        $bindValues = [];
        if (!is_null($nota1)) { $bindTypes .= "d"; $bindValues[] = $nota1; }
        if (!is_null($nota2)) { $bindTypes .= "d"; $bindValues[] = $nota2; }
        if (!is_null($nota3)) { $bindTypes .= "d"; $bindValues[] = $nota3; }
        $bindTypes .= "i";
        $bindValues[] = $id;

        $stmt->bind_param($bindTypes, ...$bindValues);
    } else {
        // INSERT
        $sql = "INSERT INTO notas (Nota1, Nota2, Nota3, idAlumno) VALUES (" .
            (is_null($nota1) ? "NULL" : "?") . ", " .
            (is_null($nota2) ? "NULL" : "?") . ", " .
            (is_null($nota3) ? "NULL" : "?") . ", ?)";
        $stmt = $con->prepare($sql);

        $bindTypes = "";
        $bindValues = [];
        if (!is_null($nota1)) { $bindTypes .= "d"; $bindValues[] = $nota1; }
        if (!is_null($nota2)) { $bindTypes .= "d"; $bindValues[] = $nota2; }
        if (!is_null($nota3)) { $bindTypes .= "d"; $bindValues[] = $nota3; }
        $bindTypes .= "i";
        $bindValues[] = $id;

        $stmt->bind_param($bindTypes, ...$bindValues);
    }

    echo $stmt->execute() ? "success" : "error: " . $stmt->error;
    exit;
}
