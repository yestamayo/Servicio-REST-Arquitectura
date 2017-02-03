<?php
require_once "PeopleAPI.php"; 
if(isset($_POST['cedula'])){
    echo 'Consula de usuario identificado con cédula: '.$_POST['cedula'];
    //$peopleAPI = new PeopleAPI();
    $pagoDB = new PagoDB();
    $datos = $pagoDB->getPago($_POST['cedula']);
    //echo json_encode($datos,JSON_PRETTY_PRINT);
    
    if($_POST['password'] == 'qwerty'){

        if (count($datos)) {
            // Open the table
            echo "<br><table>";
            // Cycle through the array
            echo "<br><tr><th>id</th><th>Cédula</th><th>Monto del pago</th><th>Método de pago</th><th>Fecha</th></tr>";
            foreach ($datos as $stand) {
                // Output a row
                echo "<tr>";
                echo "<td>$stand[id]</td>";
                echo "<td>$stand[cedula]</td>";
                echo "<td>$stand[montopago]</td>";
                echo "<td>$stand[metodopago]</td>";
                echo "<td>$stand[fecha]</td>";
                echo "</tr>";
            }
            // Close the table
            echo "</table>";
        }else{
            echo '<br>No hay datos para la cédula consultada.';
        }
    
    }else{
        echo '<br>Error de autenticación, por favor verifique sus credenciales.';
    }
    
    //$url = "http://localhost/PasarelaPago/pagos/".$_POST['cedula'];
    //$url = 'http://10.161.63.132/sicc/index.php/apostadores/obtener_datos/jcano';
    //$json=file_get_contents($url);
    //$data =  json_decode($json);
    //echo 'data: '.$data;
    //print_r($data);
    //
    //echo 'URL: '.$url;
    //$json = file_get_contents($url);
    //$data =  json_decode($json);
    //print_r($data);
    
    
}
//else{
?>
<html>
    <head>
        <title>Consulta Histórico de Recargas</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
        function showHint() {
            //alert('funcion');
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                        alert('prueba');
                    }
                };
                console.log('http://localhost/PasarelaPago/pagos/'+document.getElementById("cedula").value);
                //xmlhttp.open("GET", "http://localhost/PasarelaPago/pagos/" + document.getElementById("cedula").value, true);
                //xmlhttp.open("GET", "http://10.161.63.132/sicc/index.php/apostadores/obtener_datos/jcano", true);
                xmlhttp.send();
            
        }
        </script>
    </head>
    <body>
        <div><br></div>
        <form action="#" method="post">
            Cédula: <input type="text" name="cedula" id="cedula"><br>
            Contraseña: <input type="password" name="password" id="password">
            <input type="submit" value="consultar" onclick="showHint()">
        </form>
        <p>Mensaje: <span id="txtHint"></span></p>
    </body>
</html>

<?php
//}
    //require_once "PeopleAPI.php";    
    //$peopleAPI = new PeopleAPI();
    //$peopleAPI->API();
    
?>