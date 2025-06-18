<?php

$pageTitle = "Exámenes";
include_once __DIR__ . '/../views/header.php';
?>

<div class="all">   
        <h1 class="TítuloPágina">INGLÉS CORPORATIVO PARA EMPRESAS</h1>
      
<div class="wrapper">
        <div class="pageContent flex-column info-column">    
                
                <br>

                <h2 class="TítuloMenor">Nuestros cursos de inglés están dirigidos a:</h2>

                <ul>
                    <li>Empresas que quieren ser más competitivas en el mercado internacional.</li>
                    <li>Personas que quieren avanzar dentro de su empresa o encontrar un trabajo de calidad.</li>
                    <li>Trabajadores del sector turístico que necesitan perfeccionar su nivel de inglés</li>
                </ul>
<br>                
                <h2 class="TítuloMenor">Modalidades de clases:</h2>
                <ul>
                    <li>Cursos adaptados para empresas según el objetivo</li>
                    <li>Cursos presenciales en nuestro local o en la propia empresa</li>
                    <li>Grupos reducidos y agrupados según el nivel</li>
                    <li>Cursos intensivos enfocados al ámbito empresarial</li>
                </ul>
<br>
                <h2 class="TítuloMenor">Niveles de Inglés que ofrecemos:</h2>
                <ul>
                    <li>B1 Level (Medium)</li>
                    <li>B2 Level (Intermediate)</li>
                    <li>C1 Level (Advanced)</li>
                    <li>C2 Level (Proficiency)</li>
                </ul>
                <br>
                <ul>
                    <li id="Bulletchange">Solicita información rellenando el formulario con tus datos, nos pondremos en contacto contigo
                        para informarte<br> sobre nuesros precios, niveles, horarios, modalidades, etc.
                    Responderemos a todas tus preguntas y podrás visitar<br> nuestras instalaciones. </li>
                </ul>
                
                
                <table class="table table-bordered infotable">
                    <thead>
                        <tr>
                            <th colspan="2"><h2>Formulario</h2></th>
                        </tr>
                                        
                    </thead>
                    <tbody>
                        <form id="entryform">
                        <tr>
                            <th>Nombre:</th>
                            <td>
                                <input type="text" id="form_nombre" maxlength="20" class="entry-form-input" required>
                                <span id="nombremsg" class="text-danger" class="entryformlog"></span>
                            </td>                            
                        </tr>
                        <tr>
                            <th>Apellidos:</th>
                            <td>
                                <input type="text" id="form_apellidos" maxlength="20" class="entry-form-input" required>
                                <span id="apellidosmsg" class="text-danger" class="entryformlog"></span>
                            </td>                            
                        </tr>  
                        <tr>
                            <th>Teléfono:</th>
                            <td>
                                <!--  https://stackoverflow.com/questions/69329427/why-does-inputtype-tel-allow-non-numerical-characters-to-be-entered#:~:text=If%20you%20do%20not%20want,%2C%20%27%27)%3B"%20.-->
                                <input type="tel" id="form_tel" maxlength="9" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"  class="entry-form-input" onkeyup="this.value = this.value.replace(/[^0-9-]/g, '');" required><br>
                                <span id="telmsg" class="text-danger" class="entryformlog"></span>
                            </td>                            
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>
                                <input type="email" id="form_email" class="entry-form-input" required><br>
                                <span id="emailmsg" class="text-danger" class="entryformlog"></span>
                            </td>                            
                        </tr>
                        <tr>
                            <th>Mensaje:</th>
                            <td><input type="text" id="form_msg" maxlength="50" class="entry-form-input"></td>                            
                        </tr>                                                                                            
                    </tbody>       
                    <tfoot>
                        <tr>
                            <th colspan="2">
                                <div class="container">
                                <button type="button" name="ic_submit_button" class="btn btn-success" id="entryform_submit_button" onclick="forminsert();">Enviar</button>
                                <input type="reset" class="btn btn-danger resetBtn"/><br>
                                <span id="form_log" class="text-danger"></span>      
                                </div>               
                            </th>
                        </tr>
                        </form>
                    </tfoot>          
                </table>
        </div> <!--Div pagecontent End-->   


        <div class="pageSide">
                <img src="assets/pics/oficinistaconprisa.png" alt="ejecutivo corriendo con maletín"/>
                <p>Estudia con nosotros<br>
                Serás el primero en llegar a la meta</p>
                <br>
                <img src="assets/pics/siluetas.png" alt="siluetas de ejecutivos"/>
                <p>Los ejecutivos de tu empresa destacarán en cualquier reunión internacional</p>
        </div><!-- DIV pageside end-->

</div> <!-- Div Wrapper End-->       
</div><!--end all Div-->

<!-- Footer -->
<?php include_once __DIR__ . '/../views/footer.php'; ?>
</body>
</html>