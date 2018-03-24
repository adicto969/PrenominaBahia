<?php
$objBDSQL = new ConexionSRV();
$objBDSQL->conectarBD();

if(isset($_POST['pruebaMul'])){
  $result = new stdClass();
  $result->status = "OK";
  $result->mensaje = "Registros Insertados";

  $verifiFecha = 0;
  $periodoInicial = 1;
  $tnInicial = $_POST['tnomina'];
  $valor = $_POST['valor'];
  $idEmpresa = $_POST['IDEmpresa'];
  $fechaPeticionI = explode("/", $_POST["fechaIni"])[2]."/".explode("/", $_POST["fechaIni"])[1]."/".explode("/", $_POST["fechaIni"])[0];//"2018/12/28";
  $fechaPeticionF = explode("/", $_POST["fechaFin"])[2]."/".explode("/", $_POST["fechaFin"])[1]."/".explode("/", $_POST["fechaFin"])[0];
  $contador = 0;
  $codigo = $_POST["codigo"];  
  $denuevo = 0;
  if($DepOsub == 1)
  {
    $ComSql = "LEFT (L.centro, ".$MascaraEm.") = LEFT ('".$centro."', ".$MascaraEm.")";
  }else {
    $ComSql = "L.centro = '".$centro."'";
  }

  $verificarCodigo = "SELECT E.codigo FROM empleados AS E 
                      INNER JOIN Llaves AS L ON L.codigo = E.codigo AND L.empresa = E.empresa
                      WHERE E.empresa = $idEmpresa 
                      AND E.codigo = $codigo 
                      AND E.activo = 'S' 
                      AND L.tiponom = $tnInicial
                      AND $ComSql";

  $resultQuery = $objBDSQL->consultaBD($verificarCodigo);
  if($resultQuery['error'] == 1)
  {
    $file = fopen("log/log".date("d-m-Y").".txt", "a");
    fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
    fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultQuery['SQLSTATE'].PHP_EOL);
    fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultQuery['CODIGO'].PHP_EOL);
    fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultQuery['MENSAJE'].PHP_EOL);
    fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$verificarCodigo.PHP_EOL);
    fclose($file);
    $result->status = "error";
    $result->mensaje = "Error al verificar usuario";
    exit();
  }

  $datos = $objBDSQL->obtenResult();
  $objBDSQL->liberarC();
  if(empty($datos['codigo']))
  {
    $result->status = "error";
    $result->mensaje = "El empleado $codigo no exite con los datos ingresados";
    exit();
  }

  do{
    $_fechas = periodo($periodoInicial, $tnInicial);
    list($fecha1, $fecha2, $fecha3, $fecha4) = explode(',', $_fechas);    
    $fecha3 = explode("/", $fecha3)[2]."/".explode("/", $fecha3)[1]."/".explode("/", $fecha3)[0];
    $fecha2 = explode("/", $fecha2)[2]."/".explode("/", $fecha2)[1]."/".explode("/", $fecha2)[0];
    $fecha1 = explode("/", $fecha1)[2]."/".explode("/", $fecha1)[1]."/".explode("/", $fecha1)[0];
      
    if($denuevo == 1){
      if($periodoInicial == 1){
        $ayo = (int)explode("/", $fecha1)[0]+1;
        $ayo1 = (int)explode("/", $fecha1)[0]+2;
        $fecha1 = $ayo."/".explode("/", $fecha1)[1]."/".explode("/", $fecha1)[2];
        $fecha2 = $ayo1."/".explode("/", $fecha2)[1]."/".explode("/", $fecha2)[2];
        $fecha3 = $ayo1."/".explode("/", $fecha3)[1]."/".explode("/", $fecha3)[2];
      }else {
        $ayo = (int)explode("/", $fecha1)[0]+1;     
        $fecha1 = $ayo."/".explode("/", $fecha1)[1]."/".explode("/", $fecha1)[2];
        $fecha2 = $ayo."/".explode("/", $fecha2)[1]."/".explode("/", $fecha2)[2];
        $fecha3 = $ayo."/".explode("/", $fecha3)[1]."/".explode("/", $fecha3)[2];
      }
      
    }    
    $fechaC = $fecha1;
    $fechaC3 = $fecha3;
    for($i = 0; $fecha1 < $fecha2; $i++){                  
      $fecha1 = date("Y/m/d", strtotime($fechaC." +".$i." day"));
      $fecha3 = date("Y/m/d", strtotime($fechaC3." +".$i." day"));
      if($fecha1 <= $fechaPeticionF && $fecha1 >= $fechaPeticionI){        
        $f1Insert = explode("/", $fecha1)[2]."-".explode("/", $fecha1)[1]."-".explode("/", $fecha1)[0];
        $f2Insert = explode("/", $fecha3)[2]."/".explode("/", $fecha3)[1]."/".explode("/", $fecha3)[0];
        $queryVr = "SELECT codigo FROM datosanti WHERE codigo = $codigo AND nombre = '$f1Insert' AND fechaO = '$f2Insert' AND periodoP = $periodoInicial AND tipoN = $tnInicial AND IDEmpresa = $idEmpresa AND Centro = '$centro'";
        $resultVQuery = $objBDSQL->consultaBD($queryVr);
        if($resultVQuery['error'] == 1)
        {
          $file = fopen("log/log".date("d-m-Y").".txt", "a");
          fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
          fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultVQuery['SQLSTATE'].PHP_EOL);
          fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultVQuery['CODIGO'].PHP_EOL);
          fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultVQuery['MENSAJE'].PHP_EOL);
          fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$queryVr.PHP_EOL);
          fclose($file);          
        }else {
          $datosVr = $objBDSQL->obtenResult();
          $objBDSQL->liberarC();
          if(empty($datosVr['codigo']))
          {
            $query = "INSERT INTO datosanti VALUES ($codigo, '".$f1Insert."', '".$f2Insert."', '$valor', $periodoInicial, $tnInicial, $idEmpresa, '$centro', 0, 0, 0)";        
            $_resultados = $objBDSQL->consultaBD($query);
            if($_resultados['error'] == 1)
            {
              $file = fopen("log/log".date("d-m-Y").".txt", "a");
              fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['SQLSTATE'].PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['CODIGO'].PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['MENSAJE'].PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$query.PHP_EOL);
              fclose($file);
            }
          }else {
            $query = "UPDATE datosanti SET valor = '$valor' WHERE codigo = $codigo AND nombre = '$f1Insert' AND fechaO = '$f2Insert' AND periodoP = $periodoInicial AND tipoN = $tnInicial AND IDEmpresa = $idEmpresa AND Centro = '$centro'";
            $_resultados = $objBDSQL->consultaBD($query);
            if($_resultados['error'] == 1)
            {
              $file = fopen("log/log".date("d-m-Y").".txt", "a");
              fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['SQLSTATE'].PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['CODIGO'].PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['MENSAJE'].PHP_EOL);
              fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$query.PHP_EOL);
              fclose($file);
            }
          }          
        }        
      }
      
    }
    
    if($periodoInicial == 24){
      $periodoInicial = 1;      
      $denuevo = 1;
    }else {
      $periodoInicial++;
    }
    $contador++;
  }while( $fecha1 <= $fechaPeticionF);
  echo json_encode($result, JSON_FORCE_OBJECT);
  exit();
}

$Periodo = $_POST["periodo"];
$Tn = $_POST["TN"];
$codigo = $_POST["codigo"];
$numr = "";
$centroE = $centro;
$resultV = array();
$resultV['error'] = 0;
$resultV['codigoError'] = "";

if($Periodo <= 24){
$_fechas = periodo($Periodo, $Tn);
list($fecha1, $fecha2, $fecha3, $fecha4) = explode(',', $_fechas);
}

if($Periodo > 24 || $Tn == 1){
  $_queryFechas = "SELECT CONVERT (VARCHAR (10), inicio, 103) AS 'FECHA1',
													CONVERT (VARCHAR (10), cierre, 103) AS 'FECHA2'
									 FROM Periodos
									 WHERE tiponom = 1
									 AND periodo = $PC-1
									 AND ayo_operacion = $ayoA
									 AND empresa = $IDEmpresa ;";

  $_resultados = $objBDSQL->consultaBD($_queryFechas);
  if($_resultados['error'] == 1)
  {
		$file = fopen("log/log".date("d-m-Y").".txt", "a");
		fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['SQLSTATE'].PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['CODIGO'].PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['MENSAJE'].PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$_queryFechas.PHP_EOL);
		fclose($file);
    $resultV['error'] = 1;
		/////////////////////////////
    $resultV['codigoError'] = '<h1>Error al consultar las Fechas</h1>';
		$objBDSQL->cerrarBD();

  }
  $_datos = $objBDSQL->obtenResult();

  $fecha1 = $_datos['FECHA1'];
  $fecha2 = $_datos['FECHA2'];
	$objBDSQL->liberarC();

	$_queryFechas = "SELECT CONVERT (VARCHAR (10), inicio, 103) AS 'FECHA3',
													CONVERT (VARCHAR (10), cierre, 103) AS 'FECHA4'
									 FROM Periodos
									 WHERE tiponom = 1
									 AND periodo = $PC
									 AND ayo_operacion = $ayoA
									 AND empresa = $IDEmpresa ;";

  $_resultados = $objBDSQL->consultaBD($_queryFechas);
  if($_resultados['error'] == 1)
  {
		$file = fopen("log/log".date("d-m-Y").".txt", "a");
		fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['SQLSTATE'].PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['CODIGO'].PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['MENSAJE'].PHP_EOL);
		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$_queryFechas.PHP_EOL);
		fclose($file);
    $resultV['error'] = 1;
    $resultV['codigoError'] = '<h1>Error al consultar las Fechas</h1>';
		/////////////////////////////
		$objBDSQL->cerrarBD();
  }
  $_datos = $objBDSQL->obtenResult();

  $fecha3 = $_datos['FECHA3'];
  $fecha4 = $_datos['FECHA4'];

  $objBDSQL->liberarC();
}

if($DepOsub == 1)
{
	$ComSql = "LEFT (Centro, ".$MascaraEm.") = LEFT ('".$centro."', ".$MascaraEm.")";
}else {
	$ComSql = "Centro = '".$centro."'";
}

$fechaSuma = "";
$ff = str_replace('/', '-', $fecha1);
list($fOd, $fOm, $fOY) = explode('/', $fecha3);
$ffO = $fOY."/".$fOm."/".$fOd;
$ffO = str_replace('/', '-', $fecha3);

$ConsultaConfirm = "SELECT LTRIM(RTRIM(centro)) AS centro, codigo
                    FROM Llaves
                    WHERE codigo = '".$codigo."'
                    AND tiponom = ".$Tn."
                    AND empresa = ".$IDEmpresa."";

$consulta = $objBDSQL->consultaBD($ConsultaConfirm);
if($consulta['error'] == 1){
  $file = fopen("log/log".date("d-m-Y").".txt", "a");
  fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['SQLSTATE'].PHP_EOL);
  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['CODIGO'].PHP_EOL);
  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['MENSAJE'].PHP_EOL);
  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$ConsultaConfirm.PHP_EOL);
  fclose($file);
  $resultV['error'] = 1;
  echo json_encode($resultV);
  /////////////////////////////
  $objBDSQL->cerrarBD();
  exit();
}
$codigoConfirm = $objBDSQL->obtenResult();
if(!empty($codigoConfirm)){
  $centroE = $codigoConfirm['centro'];
}

$objBDSQL->liberarC();
if(empty($codigoConfirm['codigo'])){
  $resultV['codigoError'] = "El empleado $codigoConfirm no exite con los datos ingresados";
  $resultV['error'] = 1;
  echo json_encode($resultV);
  $objBDSQL->cerrarBD();
  exit();
}else {
  for ($i=0; $i <= 40; $i++) {
  	if($fechaSuma != $fecha2){
  		$fechaSuma = date("d/m/Y", strtotime($ff." +".$i." day"));
      $ff2 = str_replace('/', '-', $fechaSuma);

      $fechaSO = date("d/m/Y", strtotime($ffO." +".$i." day"));

  		$nombre = "fecha".$ff2;
      if(isset($_POST[$nombre])){
        if(!empty($_POST[$nombre])){

          $queryM = "SELECT valor
                     FROM datosanti
                     WHERE codigo = '".$codigo."'
                     AND nombre = '".$ff2."'
                     AND periodoP = '".$Periodo."'
                     AND tipoN = '".$Tn."'
                     AND IDEmpresa = '".$IDEmpresa."'
                     AND ".$ComSql.";";

          $numr = $objBDSQL->obtenfilas($queryM);
          if($numr['error'] == 1){
            $file = fopen("log/log".date("d-m-Y").".txt", "a");
        		fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$numr['SQLSTATE'].PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$numr['CODIGO'].PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$numr['MENSAJE'].PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$queryM.PHP_EOL);
        		fclose($file);
            $resultV['error'] = 1;
            echo json_encode($resultV);
        		/////////////////////////////
        		$objBDSQL->cerrarBD();
            exit();
          }

          #######################################
          ######  VERIFICAR RELCH_REGISTRO  #####
          #######################################
          list($diaRe, $mesRe, $ayoRe) = explode('/', $fechaSuma);
          $fechaRel = $ayoRe.$mesRe.$diaRe;
          $queryRelch = "SELECT codigo FROM relch_registro
                				 WHERE codigo = '$codigo'
                				 AND empresa = '$IDEmpresa'
                				 AND periodo = '$Periodo'
                				 AND fecha = '$fechaRel'
                				 AND tiponom = '$Tn'
                				 AND checada <> '00:00:00'
                				 AND (EoS = 'E' OR EoS = '1')
                				 AND ".$ComSql;
          #######################################
          $resultR = $objBDSQL->obtenfilas($queryRelch);
          if($resultR['error'] == 1){
            $file = fopen("log/log".date("d-m-Y").".txt", "a");
        		fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultR['SQLSTATE'].PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultR['CODIGO'].PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$resultR['MENSAJE'].PHP_EOL);
        		fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$queryRelch.PHP_EOL);
        		fclose($file);
            $resultV['error'] = 1;
            echo json_encode($resultV);
        		/////////////////////////////
        		$objBDSQL->cerrarBD();
            exit();
          }

          if($resultR['cantidad'] <= 0){
            if($numr['cantidad'] <= 0){
                if(strtoupper($_POST[$nombre]) == "PG"){
                  $Minsert = "INSERT INTO datosanti VALUES ('".$codigo."', '".$ff2."', '".$fechaSO."', '".strtoupper($_POST[$nombre])."', '".$Periodo."', '".$Tn."', '".$IDEmpresa."', '".$centroE."', 0, 0, 0);";
                }else {
                  $Minsert = "INSERT INTO datosanti VALUES ('".$codigo."', '".$ff2."', '".$fechaSO."', '".strtoupper($_POST[$nombre])."', '".$Periodo."', '".$Tn."', '".$IDEmpresa."', '".$centroE."', 1, 1, 1);";
                }

                $consulta = $objBDSQL->consultaBD($Minsert);
                if($consulta['error'] == 1){
                  $file = fopen("log/log".date("d-m-Y").".txt", "a");
                  fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['SQLSTATE'].PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['CODIGO'].PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['MENSAJE'].PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$Minsert.PHP_EOL);
                  fclose($file);
                  $resultV['error'] = 1;
                  echo json_encode($resultV);
                  /////////////////////////////
                  $objBDSQL->cerrarBD();
                  exit();
                }

            }else{

                $Minsert = "UPDATE datosanti SET valor = '".strtoupper($_POST[$nombre])."' WHERE codigo = '".$codigo."' and nombre = '".$ff2."' and periodoP = '".$Periodo."' and tipoN = '".$Tn."' and IDEmpresa = '".$IDEmpresa."' and Centro = '".$centroE."';";
                $consulta = $objBDSQL->consultaBD($Minsert);
                if($consulta['error'] == 1){
                  $file = fopen("log/log".date("d-m-Y").".txt", "a");
                  fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['SQLSTATE'].PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['CODIGO'].PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$consulta['MENSAJE'].PHP_EOL);
                  fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$Minsert.PHP_EOL);
                  fclose($file);
                  $resultV['error'] = 1;
                  echo json_encode($resultV);
                  /////////////////////////////
                  $objBDSQL->cerrarBD();
                  exit();
                }
            }
          }

        }
      }
  	}
  }
}
echo json_encode($resultV);
$objBDSQL->cerrarBD();


?>
