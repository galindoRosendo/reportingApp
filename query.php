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

    <input id='optone' type='text' name='txtfecha' value='' placeholder='Fecha AAAA-MM-DD'>

    <input id='optrange' type='text' name='txtfechafin' value='' placeholder='Fecha Fin AAAA-MM-DD'>

    <input type='button' name='btnSubmit' value='Consultar' id='btnSubmit'>
  </form>

</div><!--logInForm-->";
 }else {
echo "<div class='formi' ><h1>Debe estar registrado</h1> </div>";
 }
  ?>



  <div class="formi" id="resultset">

  </div>
<script type="text/javascript">
function muestra(){
  var cmbSelect = document.getElementById("cmbFecha");
  if (cmbSelect.value=="range") {
    $("#optrange").show();
  }else {
    $("#optrange").hide();
  }
}

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
