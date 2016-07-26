<?php
include("php/header.php");
include("php/dbCredentials.php");
 ?>

 <?php
 if(isset($_SESSION["user"])){
echo "<div id='queryForm' class='formi'>
  <form class='' action='query.php' method='post'>

    <select id='cmbFecha' class='selec' name='typeDate' onchange='muestra()''>
      <option value='range'>Rango</option>
      <option value='oneday'>Dia</option>
    </select>

    <input id='optone' type='text' name='txtfecha' value='' placeholder='Fecha AAAA-MM-DD' autocomplete='off'>

    <input id='optrange' type='text' name='txtfechafin' value='' placeholder='Fecha Fin AAAA-MM-DD' autocomplete='off'>

    <input type='button' name='btnSubmit' value='Consultar' id='btnSubmit'>

  </form>
</div><!--logInForm-->
<div id='leftside'>
  <div>
  <a href='php/reporte.php'><input type='button' name='btnExportar' value='Exportar a Excel' id='btnExportar'></a>
  </div>
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
<script type="text/javascript">
$( "#linkhome" ).removeClass( "active" );
$( "#linkconsulta" ).addClass( "active" );

$( function() {
  $( "#optone" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#optrange" ).datepicker({ dateFormat: "yy-mm-dd" });
} );

function muestra(){
  var cmbSelect = document.getElementById("cmbFecha");
  if (cmbSelect.value=="range") {
    $("#optrange").show();
  }else {
    $("#optrange").hide();
  }
}
$("#loadingcontainer").hide();
$("#leftside").hide();
$(document).ajaxStart(function(){
    $("#loadingcontainer").css("display", "block");
    $("#leftside").css("display", "none");
});
$(document).ajaxComplete(function(){
    $("#loadingcontainer").css("display", "none");
    $("#leftside").css("display", "block");
});
$(document).ready(function(){
$("#btnSubmit").click(function(){
	var fechainicio = document.getElementById("optone").value;
  var fechafin = document.getElementById("optrange").value;
  var parametros = {
    'fechainicio':fechainicio,
    'fechafin':fechafin
  };
    $.ajax({
    		data: parametros,
    		url: "http://localhost/reporting.es/php/query.php",
    		type:'POST',
    		success: function(result){
        $("#resultset").html(result);
    }
			});
});
});
</script>
 <?php
include("php/footer.php");
  ?>
