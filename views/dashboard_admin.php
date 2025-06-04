<?php
// dashboard_admin.php

require_once __DIR__ . '/../includes/functions.php';

if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
    $con = Database::connect();
}



?>

<div class="container mt-4">
    <div class="admin-dashboard">
        <h3 class="text-center mb-4">Panel de Administración</h3>

        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="clases-tab" data-toggle="tab" href="#clases" role="tab">Clases</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="notas-tab" data-toggle="tab" href="#notas" role="tab">Notas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="horarios-tab" data-toggle="tab" href="#horarios" role="tab">Horarios</a>
            </li>
        </ul>

<!--  PESTAÑA USUARIOS Y FORMULARIO CREACIÓN USUARIO -------------------------------->
        <div class="tab-content mt-3" id="adminTabContent">
            <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                <div class="admin-section mb-4">
                    <h4>Crear nuevo usuario</h4>
                    <form id="user-create-form">
                        <input type="text" name="username" placeholder="Nombre de usuario" class="form-control mb-2" required />
                        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required />
                        <input type="password" name="pword" placeholder="Contraseña" class="form-control mb-2" required />
                        <select name="ulevel" class="form-control mb-2" required>
                            <option value="" disabled selected>Rango de usuario</option>
                            <option value="1">Admin</option>
                            <option value="2">Profesor</option>
                            <option value="3">Alumno</option>
                        </select>

                        <select name="class" class="form-control mb-2">
                            <option value="" disabled selected>Seleccione una clase</option>
                            <option value ="">Sin clase</option>
                            <?= $classOptions ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Crear usuario</button>
                    </form>
                    <div id="create-user-feedback" class="mt-2 text-success"></div>
                </div>
                <div class="admin-section">
                    <h4>Lista de usuarios</h4>
                    <div id="user-table-container"></div>
                </div>
            </div>

            <!--  PESTAÑA CLASES Y FORMULARIO CREACIÓN CLASE -------------------------------->
            <div class="tab-pane fade" id="clases" role="tabpanel">
                <div class="admin-section mb-4">
                    <h4>Crear nueva clase</h4>
                    <form id="class-create-form">
                        <input type="text" name="classname" placeholder="Nombre del curso" class="form-control mb-2" required />
                        <select name="profesor" class="form-control mb-2" required>
                            <option value="" disabled selected>Seleccione un profesor</option>
                            <?= $teacherOptions ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Crear clase</button>
                    </form>
                    <div id="create-class-feedback" class="mt-2 text-success"></div>
                </div>
                <div class="admin-section">
                    <h4>Lista de clases</h4>
                    <div id="class-table-container"></div>
                </div>
            </div>

            <!--  PESTAÑA NOTAS -------------------------------->
            <div class="tab-pane fade" id="notas" role="tabpanel">
                <div class="admin-section">
                    <h4>Lista de notas por alumno</h4>
                    <div id="notas-table-container"></div>
                </div>
            </div>

            <!--  PESTAÑA HORARIOS -------------------------------->
            <div class="tab-pane fade" id="horarios" role="tabpanel">
                <div class="admin-section">
                    <h4>Horarios</h4>
                    <div id="horarios-table-container"></div>
                </div>
            </div>


        </div><!-- Tab Content End -->
    </div>
</div>

<script src="/Business-First-English-Center/public/assets/js/usuarios.js"></script>
<script src="/Business-First-English-Center/public/assets/js/clases.js"></script>
<script src="/Business-First-English-Center/public/assets/js/notas.js"></script>
<script src="/Business-First-English-Center/public/assets/js/horarios.js"></script>

