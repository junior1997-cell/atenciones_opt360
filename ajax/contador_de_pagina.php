<?php

require_once "../modelos/Contador_de_pagina.php";

$contador = new Contador_de_pagina();

$pagina = isset($_POST["pagina_nombre"]) ? limpiarCadena($_POST["pagina_nombre"]) : "";

$rspta =  $contador->contador_de_pagina($pagina);

echo json_encode($rspta, true);