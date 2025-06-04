<?php
if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
    $con = Database::connect();
}

function check_login(){
    return (isset($_SESSION['login'])) ? true : false;
}//end check_login


//                                              DASHBOARD ADMIN HANDLERS


//                                      INYECCIÓN DE DATOS

// USUARIOS  ////////////Handler para que el dropdown de clases disponibles solo muestre clases sin profesor asignado
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['availableClasses'])) {
    $query = "SELECT classid, classname FROM clases 
              WHERE classid NOT IN (SELECT class FROM users WHERE ulevel = 2 AND class IS NOT NULL)";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['classid']}'>{$row['classname']}</option>";
    }
    exit;
}


// Obtener clases para el dropdown
$classOptions = "";
$classResult = $con->query("SELECT classid, classname FROM clases ORDER BY classid ASC");
if ($classResult && $classResult->num_rows > 0) {
    while ($row = $classResult->fetch_assoc()) {
        $classOptions .= "<option value='{$row['classid']}'>{$row['classname']}</option>";
    }
}

// Obtener profesores para el dropdown
$teacherOptions = "";
$profResult = $con->query("SELECT user_id, username FROM users WHERE ulevel = 2 ORDER BY username ASC");
if ($profResult && $profResult->num_rows > 0) {
    while ($row = $profResult->fetch_assoc()) {
        $teacherOptions .= "<option value='{$row['user_id']}'>{$row['username']}</option>";
    }
}

$classOptionsJS = json_encode($classOptions);
$teacherOptionsJS = json_encode($teacherOptions);




//
//                              HANDLERS DE USUARIOS
//

// Handler para que el dropdown de clases disponibles solo muestre clases sin profesor asignado
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['availableClasses'])) {
    $query = "SELECT classid, classname FROM clases 
              WHERE classid NOT IN (SELECT class FROM users WHERE ulevel = 2 AND class IS NOT NULL)";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['classid']}'>{$row['classname']}</option>";
    }
    exit;
}




// AJAX handler para lista de usuarios
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadUsers'])) {
    $res = $con->query("SELECT users.*, clases.classname FROM users LEFT JOIN clases ON users.class = clases.classid ORDER BY users.user_id ASC");
    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr><th>ID</th><th>Usuario</th><th>Email</th><th>Clase</th><th>Nivel</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['user_id']}'>
                    <td>{$row['user_id']}</td>
                    <td class='username'>{$row['username']}</td>
                    <td class='email'>{$row['email']}</td>
                    <td class='class' data-classid='{$row['class']}'>{$row['classname']}</td>
                    <td class='ulevel'>{$row['ulevel']}</td>
                    <td>
                        <button class='btn btn-sm btn-warning edit-btn'>Editar</button>
                        <button class='btn btn-sm btn-danger delete-btn'>Borrar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay usuarios registrados.</p>";
    }
    exit;
}

// AJAX handler para actualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $ulevel = $_POST['ulevel'];

    $stmt = $con->prepare("UPDATE users SET username=?, email=?, class=?, ulevel=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $username, $email, $class, $ulevel, $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}

// AJAX handler para eliminar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $id = $_POST['user_id'];
    $stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}


//
//                  HANDLERS DE CLASES
//

// AJAX handler para clases
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadClasses'])) {
    $res = $con->query("SELECT * FROM clases LEFT JOIN users ON clases.classid=users.class AND users.ulevel='2'");
    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr><th>ID</th><th>Nombre Curso</th><th>Profesor</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['classid']}'>
                    <td>{$row['classid']}</td>
                    <td class='classname'>{$row['classname']}</td>
                    <td class='profesor' data-profid='{$row['user_id']}'>{$row['username']}</td>
                    <td>
                        <button class='btn btn-sm btn-warning edit-class-btn'>Editar</button>
                        <button class='btn btn-sm btn-danger delete-class-btn'>Borrar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay clases registradas.</p>";
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createClass'])) {
    $classname = $_POST['classname'];
    $profesor_id = $_POST['profesor'];

    $stmt = $con->prepare("INSERT INTO clases (classname) VALUES (?)");
    $stmt->bind_param("s", $classname);
    $success1 = $stmt->execute();

    if (!$success1) {
        echo "error";
        exit;
    }

    $classid = $con->insert_id; // Capturamos el ID autogenerado

// Si no se asigna profesor, terminamos aquí
    if ($profesor_id == '') {
        echo "success";
        exit;
    }

// Asignamos el profesor a la clase
    $stmt2 = $con->prepare("UPDATE users SET class=? WHERE user_id=?");
    $stmt2->bind_param("si", $classid, $profesor_id);
    $success2 = $stmt2->execute();

    echo ($success2) ? "success" : "error";
    exit;


    $stmt2 = $con->prepare("UPDATE users SET class=? WHERE user_id=?");
    $stmt2->bind_param("si", $classid, $profesor_id);
    $success2 = $stmt2->execute();

    if ($success1 && $success2) {
        echo "success";
    } else {
        echo "error";
    }

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateClass'])) {
    $classid = $_POST['classid'];
    $classname = $_POST['classname'];
    $profesor_id = $_POST['profesor'];

    // 1. Actualizar nombre de la clase
    $stmt1 = $con->prepare("UPDATE clases SET classname=? WHERE classid=?");
    $stmt1->bind_param("si", $classname, $classid);
    $success1 = $stmt1->execute();

    // 2. Desasignar a ese profesor de cualquier clase previa
    $stmt2 = $con->prepare("UPDATE users SET class='' WHERE ulevel=2 AND class=?");
    $stmt2->bind_param("s", $classid);
    $success2 = $stmt2->execute();

    // 3. Asignar al nuevo profesor la clase
    $stmt3 = $con->prepare("UPDATE users SET class=? WHERE user_id=?");
    $stmt3->bind_param("si", $classid, $profesor_id);
    $success3 = $stmt3->execute();

    if ($success1 && $success2 && $success3) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteClass'])) {
    $classid = $_POST['classid'];
    $stmt = $con->prepare("DELETE FROM clases WHERE classid = ?");
    $stmt->bind_param("i", $classid);

    echo $stmt->execute() ? "success" : "error";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['availableTeachers'])) {
    $query = "SELECT user_id, username FROM users WHERE ulevel = 2 AND (class IS NULL OR class = '')";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['user_id']}'>{$row['username']}</option>";
    }
    exit;
}

//
//                                 HANDLERS DE NOTAS
//

// Handler para cargar notas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadNotas'])) {
    $res = $con->query("SELECT u.user_id, u.username, c.classname, n.Nota1, n.Nota2, n.Nota3
                         FROM users u
                         LEFT JOIN clases c ON u.class = c.classid
                         LEFT JOIN notas n ON u.user_id = n.idAlumno
                         WHERE u.ulevel = 3
                         ORDER BY c.classname, u.username");
    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr>
                <th>Alumno</th><th>Curso</th><th>Nota 1</th><th>Nota 2</th><th>Nota 3</th><th>Acciones</th>
              </tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['user_id']}'>
                    <td class='alumno'>{$row['username']}</td>
                    <td class='curso'>{$row['classname']}</td>
                    <td class='nota1'>" . (isset($row['Nota1']) ? $row['Nota1'] : '') . "</td>
                    <td class='nota2'>" . (isset($row['Nota2']) ? $row['Nota2'] : '') . "</td>
                    <td class='nota3'>" . (isset($row['Nota3']) ? $row['Nota3'] : '') . "</td>
                    <td>
                        <button class='btn btn-sm btn-warning edit-nota-btn'>Editar</button>
                        <button class='btn btn-sm btn-success save-nota-btn d-none'>Guardar</button>
                        <button class='btn btn-sm btn-secondary cancel-nota-btn d-none'>Cancelar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay notas registradas.</p>";
    }
    exit;
}

// Guardar notas actualizadas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNota'])) {
    $id = $_POST['idAlumno'];
    $nota1 = floatval($_POST['nota1']);
    $nota2 = floatval($_POST['nota2']);
    $nota3 = floatval($_POST['nota3']);

//      Validar que se han introducido valores válidos en las notas
    function validarNota($n) {
        return is_numeric($n) && $n >= 0 && $n <= 10;
    }

    if (!validarNota($nota1) || !validarNota($nota2) || !validarNota($nota3)) {
        echo "error: valores de nota fuera de rango";
        exit;
    }

    $res = $con->query("SELECT * FROM notas WHERE idAlumno = $id");
    if ($res && $res->num_rows > 0) {
        $stmt = $con->prepare("UPDATE notas SET Nota1=?, Nota2=?, Nota3=? WHERE idAlumno=?");
        $stmt->bind_param("dddi", $nota1, $nota2, $nota3, $id);
    } else {
        $stmt = $con->prepare("INSERT INTO notas (Nota1, Nota2, Nota3, idAlumno) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("dddi", $nota1, $nota2, $nota3, $id);
    }

    echo $stmt->execute() ? "success" : "error";
    exit;
}

//
//                                 HANDLERS DE HORARIOS
//

// Cargar horarios
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadHorarios'])) {
    $res = $con->query("
    SELECT s.day_id, s.week_day,
           c1.classname AS firstclass_name,
           c2.classname AS secondclass_name,
           c3.classname AS thirdclass_name
    FROM schedule s
    LEFT JOIN clases c1 ON s.firstclass = c1.classid
    LEFT JOIN clases c2 ON s.secondclass = c2.classid
    LEFT JOIN clases c3 ON s.thirdclass = c3.classid
    ORDER BY s.day_id ASC
    ");

    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr>
                <th>Día</th><th>Primera Clase</th><th>Segunda Clase</th><th>Tercera Clase</th><th>Acciones</th>
              </tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['day_id']}'>
                <td class='weekday'>{$row['week_day']}</td>
                <td class='firstclass'>" . ($row['firstclass_name'] ?? '') . "</td>
                <td class='secondclass'>" . ($row['secondclass_name'] ?? '') . "</td>
                <td class='thirdclass'>" . ($row['thirdclass_name'] ?? '') . "</td>
                <td>
                    <button class='btn btn-sm btn-warning edit-horario-btn'>Editar</button>
                    <button class='btn btn-sm btn-success save-horario-btn d-none'>Guardar</button>
                    <button class='btn btn-sm btn-secondary cancel-horario-btn d-none'>Cancelar</button>
                </td>
              </tr>";

        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay horarios registrados.</p>";
    }
    exit;
}

// Guardar horario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateHorario'])) {
    $id = $_POST['day_id'];
    $first = $_POST['firstclass'];
    $second = $_POST['secondclass'];
    $third = $_POST['thirdclass'];

    $stmt = $con->prepare("UPDATE schedule SET firstclass=?, secondclass=?, thirdclass=? WHERE day_id=?");
    $stmt->bind_param("sssi", $first, $second, $third, $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}




echo "<script>window.classOptions = {$classOptionsJS}; window.teacherOptions = {$teacherOptionsJS};</script>";
echo "<script>window.classOptions = " . json_encode($classOptions) . ";</script>";






