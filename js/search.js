
/*NavBar Color*/
$("#linkhome").removeClass( "active" );
$("#linkconsulta").addClass( "active" );

/*hiden items*/
$("#loadingcontainer").hide();
$("#rightside").hide();
$("#optrange").hide();

/*Date pickers*/
$( function() {
  $( "#optone" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#optrange" ).datepicker({ dateFormat: "yy-mm-dd" });
} );

/*Function to show/hide second datepicker*/
function muestra(){
  var cmbSelect = document.getElementById("cmbFecha");
  if (cmbSelect.value=="range") {
    $("#optrange").show(500);
    $("#linkReporte").attr("href", "php/files/reporteRango.php");
  }else if (cmbSelect.value=="oneday") {
    $("#optrange").hide(500);
    $("#linkReporte").attr("href", "php/files/reporteDia.php");
  }else if (cmbSelect.value=="rangesum") {
      $("#optrange").show(500);
      $("#linkReporte").attr("href", "php/files/reporteRangoSuma.php");
  }
}

/*Ajax Events to show/hide items*/
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

/*Function to request querys[AJAX]*/
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
    		url: "http://localhost:8080/reporting.com/php/ajax/oneday.php",
    		type:'POST',
    		success: function(result){
        $("#resultset").html(result);
    }
			});
  }else if (tipoBusqueda=='range') {
    $.ajax({
    		data: parametros,
    		url: "http://localhost:8080/reporting.com/php/ajax/range.php",
    		type:'POST',
    		success: function(result){
        $("#resultset").html(result);
    }
			});
  }else if (tipoBusqueda=='rangesum') {
    $.ajax({
    		data: parametros,
    		url: "http://localhost:8080/reporting.com/php/ajax/rangeSum.php",
    		type:'POST',
    		success: function(result){
        $("#resultset").html(result);
    }
			});
  }

});
});
