<?php
include("php/com/header.php");
include("php/sql/dbCredentials.php");
 ?>

 <?php
 if(isset($_SESSION["user"])){
echo "<div id='queryForm' class='formi'>
  <form class='' >

    <select id='cmbFecha' class='selec' name='typeDate' onchange='muestra()'>
      <option value='oneday'>Dia</option>
      <option value='range'>Rango</option>
      <option value='rangesum'>Rango Acumulado</option>
    </select>

    <input id='optone' type='text' name='txtfecha' value='' placeholder='Fecha AAAA-MM-DD' autocomplete='off'>

    <input id='optrange' type='text' name='txtfechafin' value='' placeholder='Fecha Fin AAAA-MM-DD' autocomplete='off'>

    <input type='button' name='btnSubmit' value='Consultar' id='btnSubmit'>

  </form>
</div><!--logInForm-->
<div id='rightside'>
  <a href='php/files/reporteDia.php' id='linkReporte'><i class='fa fa-file-excel-o fa-2x' aria-hidden='true'>
  <div>
  </i> Exportar a Excel
  </div>
  </a>
</div>
";
 }else {
echo "
<div class='contentcenter'>
  <div class='formi' >
    <div class='warning'>
      <h1><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Debe estar registrado </h1>
    </div>
  </div>
</div>";
 }
  ?>

<div id="loadingcontainer" >
    <div >
      <i class="fa fa-cog fa-spin fa-3x fa-fw"></i> <h3>Loading...</h3>
    </div>
</div>
  <div class="formi" id="resultset">

  </div>
<script type="text/javascript" src="js/search.js"></script>
 <?php
include("php/com/footer.php");
  ?>
