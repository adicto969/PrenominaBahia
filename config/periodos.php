<?php

///Periodos administrativos

$A = array ("26/12/".($ayoA-1), "10/01/".$ayoA, "01/01/".$ayoA, "16/01/".$ayoA);//1

$B = array ("11/01/".$ayoA, "25/01/".$ayoA, "16/01/".$ayoA, "31/01/".$ayoA);//2

$C = array ("26/01/".$ayoA, "10/02/".$ayoA, "01/02/".$ayoA, "15/02/".$ayoA);//3

$D = array ("11/02/".$ayoA, "25/02/".$ayoA, "16/02/".$ayoA, "29/02/".$ayoA);//4

$E = array ("26/02/".$ayoA, "10/03/".$ayoA, "01/03/".$ayoA, "15/03/".$ayoA);//5

$F = array ("11/03/".$ayoA, "25/03/".$ayoA, "16/03/".$ayoA, "31/03/".$ayoA);//6

$G = array ("26/03/".$ayoA, "10/04/".$ayoA, "01/04/".$ayoA, "15/04/".$ayoA);//7

$H = array ("11/04/".$ayoA, "25/04/".$ayoA, "16/04/".$ayoA, "30/04/".$ayoA);//8

$I = array ("26/04/".$ayoA, "10/05/".$ayoA, "01/05/".$ayoA, "15/05/".$ayoA);//9

$J = array ("11/05/".$ayoA, "25/05/".$ayoA, "16/05/".$ayoA, "31/05/".$ayoA);//10

$K = array ("26/05/".$ayoA, "10/06/".$ayoA, "01/06/".$ayoA, "15/06/".$ayoA);//11

$L = array ("11/06/".$ayoA, "25/06/".$ayoA, "16/06/".$ayoA, "30/06/".$ayoA);//12

$M = array ("26/06/".$ayoA, "10/07/".$ayoA, "01/07/".$ayoA, "15/07/".$ayoA);//13

$N = array ("11/07/".$ayoA, "25/07/".$ayoA, "16/07/".$ayoA, "31/07/".$ayoA);//14

$O = array ("26/07/".$ayoA, "10/08/".$ayoA, "01/08/".$ayoA, "15/08/".$ayoA);//15

$P = array ("11/08/".$ayoA, "25/08/".$ayoA, "16/08/".$ayoA, "31/08/".$ayoA);//16

$Q = array ("26/08/".$ayoA, "10/09/".$ayoA, "01/09/".$ayoA, "15/09/".$ayoA);//17

$R = array ("11/09/".$ayoA, "25/09/".$ayoA, "16/09/".$ayoA, "30/09/".$ayoA);//18

$S = array ("26/09/".$ayoA, "10/10/".$ayoA, "01/10/".$ayoA, "15/10/".$ayoA);//19

$T = array ("11/10/".$ayoA, "25/10/".$ayoA, "16/10/".$ayoA, "31/10/".$ayoA);//20

$U = array ("26/10/".$ayoA, "10/11/".$ayoA, "01/11/".$ayoA, "15/11/".$ayoA);//21

$V = array ("11/11/".$ayoA, "25/11/".$ayoA, "16/11/".$ayoA, "30/11/".$ayoA);//22

$W = array ("26/11/".$ayoA, "10/12/".$ayoA, "01/12/".$ayoA, "15/12/".$ayoA);//23

$X = array ("11/12/".$ayoA, "25/12/".$ayoA, "16/12/".$ayoA, "31/12/".$ayoA);//24



///Periodos de pago
function periodo($p, $tn){
  if($p > 24 || $tn == 1)
  {
	   $objBDSQL = new ConexionSRV();
	   $objBDSQL->conectarBD();

	   $_queryFechas = "SELECT CONVERT (VARCHAR (10), inicio, 103) AS 'FECHA1',
                             CONVERT (VARCHAR (10), cierre, 103) AS 'FECHA2'
                      FROM Periodos
                      WHERE tiponom = 1
                      AND periodo = $p-1
                      AND ayo_operacion = ".$_SESSION['AYO_ACTUAL']."
                      AND empresa = ".$_SESSION['ID_EMPRESA'].";";

  	 $_resultados = $objBDSQL->consultaBD($_queryFechas);
     if($_resultados['error'] == 1)
     {
       $file = fopen("log/log".date("d-m-Y").".txt", "a");
       fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['SQLSTATE'].PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['CODIGO'].PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['MENSAJE'].PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$_queryFechas);
       fclose($file);
       return "00/00/0000,00/00/0000,00/00/0000,00/00/0000";
       /////////////////////////////
       $objBDSQL->cerrarBD();
       exit();
     }
     $_datos = $objBDSQL->obtenResult();

     $fecha1 = $_datos['FECHA1'];
     $fecha2 = $_datos['FECHA2'];
     $objBDSQL->liberarC();

     $_queryFechas = "SELECT CONVERT (VARCHAR (10), inicio, 103) AS 'FECHA3',
                             CONVERT (VARCHAR (10), cierre, 103) AS 'FECHA4'
                      FROM Periodos
                      WHERE tiponom = 1
                      AND periodo = $p
                      AND ayo_operacion = ".$_SESSION['AYO_ACTUAL']."
                      AND empresa = ".$_SESSION['ID_EMPRESA'].";";

  	 $_resultados = $objBDSQL->consultaBD($_queryFechas);
     if($_resultados['error'] == 1)
     {
       $file = fopen("log/log".date("d-m-Y").".txt", "a");
       fwrite($file, ":::::::::::::::::::::::ERROR SQL:::::::::::::::::::::::".PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['SQLSTATE'].PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['CODIGO'].PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - '.$_resultados['MENSAJE'].PHP_EOL);
       fwrite($file, '['.date('d/m/Y h:i:s A').']'.' - CONSULTA: '.$_queryFechas);
       fclose($file);
       return "00/00/0000,00/00/0000,00/00/0000,00/00/0000";
       /////////////////////////////
       $objBDSQL->cerrarBD();
       exit();
     }
     $_datos = $objBDSQL->obtenResult();

     $fecha3 = $_datos['FECHA3'];
     $fecha4 = $_datos['FECHA4'];
     $objBDSQL->liberarC();

	   $objBDSQL->cerrarBD();
	   return $fecha1.",".$fecha2.",".$fecha3.",".$fecha4;
     exit();
  }
  switch ($p) {
      case 1:
          $fecha1 = $GLOBALS['A'][0];
          $fecha2 = $GLOBALS['A'][1];
          $fecha3 = $GLOBALS['A'][2];
          $fecha4 = $GLOBALS['A'][3];
          break;
      case 2:
          $fecha1 = $GLOBALS['B'][0];
          $fecha2 = $GLOBALS['B'][1];
          $fecha3 = $GLOBALS['B'][2];
          $fecha4 = $GLOBALS['B'][3];
          break;
      case 3:
          $fecha1 = $GLOBALS['C'][0];
          $fecha2 = $GLOBALS['C'][1];
          $fecha3 = $GLOBALS['C'][2];
          $fecha4 = $GLOBALS['C'][3];
          break;
      case 4:
          $fecha1 = $GLOBALS['D'][0];
          $fecha2 = $GLOBALS['D'][1];
          $fecha3 = $GLOBALS['D'][2];
          $fecha4 = $GLOBALS['D'][3];
          break;
      case 5:
          $fecha1 = $GLOBALS['E'][0];
          $fecha2 = $GLOBALS['E'][1];
          $fecha3 = $GLOBALS['E'][2];
          $fecha4 = $GLOBALS['E'][3];
          break;
      case 6:
          $fecha1 = $GLOBALS['F'][0];
          $fecha2 = $GLOBALS['F'][1];
          $fecha3 = $GLOBALS['F'][2];
          $fecha4 = $GLOBALS['F'][3];
          break;
      case 7:
          $fecha1 = $GLOBALS['G'][0];
          $fecha2 = $GLOBALS['G'][1];
          $fecha3 = $GLOBALS['G'][2];
          $fecha4 = $GLOBALS['G'][3];
          break;
      case 8:
          $fecha1 = $GLOBALS['H'][0];
          $fecha2 = $GLOBALS['H'][1];
          $fecha3 = $GLOBALS['H'][2];
          $fecha4 = $GLOBALS['H'][3];
          break;
      case 9:
          $fecha1 = $GLOBALS['I'][0];
          $fecha2 = $GLOBALS['I'][1];
          $fecha3 = $GLOBALS['I'][2];
          $fecha4 = $GLOBALS['I'][3];
          break;
      case 10:
          $fecha1 = $GLOBALS['J'][0];
          $fecha2 = $GLOBALS['J'][1];
          $fecha3 = $GLOBALS['J'][2];
          $fecha4 = $GLOBALS['J'][3];
          break;
      case 11:
          $fecha1 = $GLOBALS['K'][0];
          $fecha2 = $GLOBALS['K'][1];
          $fecha3 = $GLOBALS['K'][2];
          $fecha4 = $GLOBALS['K'][3];
          break;
      case 12:
          $fecha1 = $GLOBALS['L'][0];
          $fecha2 = $GLOBALS['L'][1];
          $fecha3 = $GLOBALS['L'][2];
          $fecha4 = $GLOBALS['L'][3];
          break;
      case 13:
          $fecha1 = $GLOBALS['M'][0];
          $fecha2 = $GLOBALS['M'][1];
          $fecha3 = $GLOBALS['M'][2];
          $fecha4 = $GLOBALS['M'][3];
          break;
      case 14:
          $fecha1 = $GLOBALS['N'][0];
          $fecha2 = $GLOBALS['N'][1];
          $fecha3 = $GLOBALS['N'][2];
          $fecha4 = $GLOBALS['N'][3];
          break;
      case 15:
          $fecha1 = $GLOBALS['O'][0];
          $fecha2 = $GLOBALS['O'][1];
          $fecha3 = $GLOBALS['O'][2];
          $fecha4 = $GLOBALS['O'][3];
          break;
      case 16:
          $fecha1 = $GLOBALS['P'][0];
          $fecha2 = $GLOBALS['P'][1];
          $fecha3 = $GLOBALS['P'][2];
          $fecha4 = $GLOBALS['P'][3];
          break;
      case 17:
          $fecha1 = $GLOBALS['Q'][0];
          $fecha2 = $GLOBALS['Q'][1];
          $fecha3 = $GLOBALS['Q'][2];
          $fecha4 = $GLOBALS['Q'][3];
          break;
      case 18:
          $fecha1 = $GLOBALS['R'][0];
          $fecha2 = $GLOBALS['R'][1];
          $fecha3 = $GLOBALS['R'][2];
          $fecha4 = $GLOBALS['R'][3];
          break;
      case 19:
          $fecha1 = $GLOBALS['S'][0];
          $fecha2 = $GLOBALS['S'][1];
          $fecha3 = $GLOBALS['S'][2];
          $fecha4 = $GLOBALS['S'][3];
          break;
      case 20:
          $fecha1 = $GLOBALS['T'][0];
          $fecha2 = $GLOBALS['T'][1];
          $fecha3 = $GLOBALS['T'][2];
          $fecha4 = $GLOBALS['T'][3];
          break;
      case 21:
          $fecha1 = $GLOBALS['U'][0];
          $fecha2 = $GLOBALS['U'][1];
          $fecha3 = $GLOBALS['U'][2];
          $fecha4 = $GLOBALS['U'][3];
          break;
      case 22:
          $fecha1 = $GLOBALS['V'][0];
          $fecha2 = $GLOBALS['V'][1];
          $fecha3 = $GLOBALS['V'][2];
          $fecha4 = $GLOBALS['V'][3];
          break;
      case 23:
          $fecha1 = $GLOBALS['W'][0];
          $fecha2 = $GLOBALS['W'][1];
          $fecha3 = $GLOBALS['W'][2];
          $fecha4 = $GLOBALS['W'][3];
          break;
      case 24:
          $fecha1 = $GLOBALS['X'][0];
          $fecha2 = $GLOBALS['X'][1];
          $fecha3 = $GLOBALS['X'][2];
          $fecha4 = $GLOBALS['X'][3];
          break;
  }

  return $fecha1.",".$fecha2.",".$fecha3.",".$fecha4;
}

?>