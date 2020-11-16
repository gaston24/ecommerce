function pulsar(e) {
  tecla = (document.all) ? e.keyCode : e.which;
  return (tecla != 13);
  }

function mostrarDatos(){
 var textEnMensaje = document.getElementById("textBox").value;
 document.getElementById("prueba").innerHTML = textEnMensaje;
}

function busquedaRapida() {
	var input, filter, table, tr, td, td2, i, txtValue;
	input = document.getElementById('textBox');
	filter = input.value.toUpperCase();
	table = document.getElementById("table");
	tr = table.getElementsByTagName('tr');
	//tr = document.getElementById('tr');
	
	 for (i = 0; i < tr.length; i++) {
    visible = false;
    /* Obtenemos todas las celdas de la fila, no sólo la primera */
    td = tr[i].getElementsByTagName("td");
	
    for (j = 0; j < td.length; j++) {
      if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
        visible = true;
      }
    }
    if (visible === true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}

var	valores = [];
//usuario = document.getElementById("usuario").value;

function total() {

  //usuario = document.getElementById("usuario").value;
	var x = document.querySelectorAll("#id_tabla input[name='nro_pedido[]']"); //tomo todos los input con name='cantProd[]'
	valor = x.value;
	var i;
	for (i = 0; i < x.length; i++) {
		if (x[i].checked) {
			valores.push(x[i].value);
		}
  }
  // console.log(valores);
  agregar(valores);
  
}



function agregar(a) {
  // console.log(a);


  $.ajax({
    url: 'Controlador/procesarImprimir.php',
    method: 'POST',
    data: {
      
      pedidos: a
    },
    success: function(data) {
      // console.log(data);
    }
  });
  
 valores = [];
 ponerCero();
}


function ponerCero() {
  swal("Listo!", "Ya podes imprimir la etiqueta!", "success");
	var x = document.querySelectorAll("#id_tabla input[name='nro_pedido[]']"); 
	var i;
	for (i = 0; i < x.length; i++) {
		if (x[i].checked) {
			x[i].checked = false;
		}
  }
  
}


$( document ).ready(function() {
  $("#busqueda").show();
  $("#textBox").focus();
});