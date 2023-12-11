$('#selectBanco').select2({});
 
$('#selectBanco').on('select2:opening select2:closing', function( event ) {
var $searchfield = $(this).parent().find('.select2-search__field');
// $searchfield.prop('disabled', true);
});



$('#selectRubro').select2();

$('#selectRubro').on('select2:opening select2:closing', function( event ) {
var $searchfield = $(this).parent().find('.select2-search__field');
// $searchfield.prop('disabled', true);
});

const mostrarSpiner = () =>{

    let spinner = document.querySelector("#boxLoading");

    spinner.classList.add("loading");


}

$('#selectCategoria').select2();

$('#selectCategoria').on('select2:opening select2:closing', function( event ) {
var $searchfield = $(this).parent().find('.select2-search__field');
// $searchfield.prop('disabled', true);
});


$('#selectRangoEtario').select2();

$('#selectRangoEtario').on('select2:opening select2:closing', function( event ) {
var $searchfield = $(this).parent().find('.select2-search__field');
// $searchfield.prop('disabled', true);
});

const filtrarCategoria = () =>{

    let allOptions = Array.from(document.querySelector("#selectRubro").selectedOptions);

    let arraySelected = [];
    allOptions.forEach((element,x )=> {
        arraySelected[x] = element.value;
    });

    let rubros = "";

    if(arraySelected.length != 0){

        rubros = JSON.stringify(arraySelected).replace("[", "(").replace("]", ")").replace(/"/g, "'");
    
    }

        $.ajax({

            url: 'Controller/ClienteController.php?accion=traerCategorias',
            type: 'POST',
            dataType: 'json',
            data: {rubros: rubros},
            success: function (data) {
                let html = '';
                data.forEach(element => {
                    html += `<option value="${element.RUBRO}-${element.CATEGORIA}">${element.CATEGORIA}</option>`;
                });
                $('#selectCategoria').html("");
                $('#selectCategoria').html(html);
                $('#selectCategoria').select2();
            }

        })

}

const exportTable = () =>{

    $(`#tablaClientes`).table2excel({
    // exclude CSS class
    exclude: ".noE  xl",
    name: "excel Document ",
    filename: "Excel", //do not include extension
    fileext: ".xlsx" // file extension
    });

    }