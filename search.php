<?php
include("php/header.php");
include("php/dbCredentials.php");
 ?>

 <?php
 if(isset($_SESSION["user"])){
echo "<div id='queryForm' class='formi'>
  <form class='' action='query.php' method='post'>

    <select id='cmbFecha' class='selec' name='typeDate' onchange='muestra()''>
      <option value='oneday'>Dia</option>
      <option value='range'>Rango</option>
    </select>

    <input id='optone' type='text' name='txtfecha' value='' placeholder='Fecha AAAA-MM-DD' autocomplete='off'>

    <input id='optrange' type='text' name='txtfechafin' value='' placeholder='Fecha Fin AAAA-MM-DD' autocomplete='off'>

    <input type='button' name='btnSubmit' value='Consultar' id='btnSubmit'>

  </form>
</div><!--logInForm-->
<div id='rightside'>
  <a href='php/reporteDia.php' id='linkReporte'><i class='fa fa-file-excel-o fa-2x' aria-hidden='true'>
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
<script type="text/javascript">
$("#linkhome").removeClass( "active" );
$("#linkconsulta").addClass( "active" );
$("#loadingcontainer").hide();
$("#rightside").hide();
$("#optrange").hide();
$( function() {
  $( "#optone" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#optrange" ).datepicker({ dateFormat: "yy-mm-dd" });
} );

function muestra(){
  var cmbSelect = document.getElementById("cmbFecha");
  if (cmbSelect.value=="range") {
    $("#optrange").toggle(500);
    $("#linkReporte").attr("href", "php/reporteRango.php");
  }else {
    $("#optrange").toggle(500);
      $("#linkReporte").attr("href", "php/reporteDia.php");
  }
}
$(document).ajaxStart(function(){
    $("#loadingcontainer").css("display", "block");
    $("#rightside").css("display", "none");
    $("#resultset").css("display", "none");
});
$(document).ajaxComplete(function(){
    $("#loadingcontainer").css("display", "none");
    $("#resultset").css("display", "block");
    $("#rightside").css("display", "block");
});
$(document).ready(function(){
$("#btnSubmit").click(function(){
  var tipoBusqueda = document.getElementById("cmbFecha").value;
  var fechainicio = document.getElementById("optone").value;
  var fechafin = document.getElementById("optrange").value;
  var parametros = {
    'fechaA':fechainicio,
    'fechaFin':fechafin
  };
  if (tipoBusqueda=='oneday') {
    $.ajax({
    		data: parametros,
    		url: "http://localhost:8080/reporting.es/php/oneday.php",
    		type:'POST',
    		success: function(result){
        $("#resultset").html(result);
    }
			});
  }else if (tipoBusqueda=='range') {
    $.ajax({
    		data: parametros,
    		url: "http://localhost:8080/reporting.es/php/range.php",
    		type:'POST',
    		success: function(result){
        $("#resultset").html(result);
    }
			});
  }

});
});
</script>
 <?php
include("php/footer.php");
  ?>
