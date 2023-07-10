<?php
require_once "Class/Cliente.php";


$cliente = new Cliente();
$rubros = $cliente->traerRubros();
$categorias = $cliente->traerCategorias();
$bancos = $cliente->traerBancos();


$desde = (isset($_GET['desde'])) ? $_GET['desde'] : null;
$hasta = (isset($_GET['hasta'])) ? $_GET['hasta'] : null;

$selectBanco = (isset($_GET['selectBanco'])) ? $_GET['selectBanco'] : [];
$selectRubro = (isset($_GET['selectRubro'])) ? $_GET['selectRubro'] : [];

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestion de Conceptos</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="css/segmentacionDeClientes.css">
        

        </link>
    </head>
    
    <body>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h6>Segmentacion de Clientes</h6></div>
                    <div class="card card-1">
                        <div class="row" style = "height:845px;width:100%;margin-left:10px;margin-top:5px;margin-bottom:5px">

                            <div class="col-3" style="border:solid 1px;">
                                <form action="">
                                    <div style="margin-top:30px">
                                        <button type="submit"  name="submit"class="btn btn-primary btn-block submit"style="height:40px">Filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                                    </div>
                                    <div class="contenedor" style="margin-top:10px">
                                        <div class="row">
                                            <div class="col">
                                                <label>Desde:</label>
                                                <input type="date"  name="desde" value="<?= $desde ?>" style="width:120px">
                                            </div>

                                            <div class="col">
                                                <label>Hasta:</label>
                                                <input type="date" name="hasta" value="<?= $hasta ?>" style="width:120px">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <label style="margin-left:20px"><i class="bi bi-bank2"></i> Banco</label>

                                        <select id="selectBanco" name="selectBanco[]" style="width:100%;margin-left:20px" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="selectBanco" tabindex="-1" aria-hidden="true">
                                           
                                            <?php 
                                                foreach ($bancos as  $banco) {

                                                    $text = '<option value="'.$banco['DESC_CTA_BANCO'].'"';
                
                                                    if(count($selectBanco) > 0){
                                        
                                                        if(in_array($banco['DESC_CTA_BANCO'],$selectBanco)){

                                                            $text = $text.'selected="selected"';

                                                        }
                                                    }
                                                    
                                                    $text = $text.'>'.$banco['DESC_CTA_BANCO'].'</option>';
                                                    echo $text;
                                                }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="row" style="margin-top:5px">

                                        <label style="margin-left:20px"><i class="bi bi-bag"></i> Rubro</label>

                                        <select id="selectRubro" name="selectRubro[]" style="width:100%;margin-left:20px" class="js-states form-control select2-hidden-accessible" multiple="" onchange="filtrarCategoria()" data-select2-id="selectRubro" tabindex="-1" aria-hidden="true">
                                            
                                            <?php 
                                                foreach ($rubros as  $rubro) {
                                                    $text = '<option value="'.$rubro['DESCRIP'].'"';

                                                    if(count($selectRubro) > 0){
                                        
                                                        if(in_array($rubro['DESCRIP'],$selectRubro)){

                                                            $text = $text.'selected="selected"';

                                                        }
                                                    }

                                                    $text = $text.'>'.$rubro['DESCRIP'].'</option>';
                                                    echo $text;
                                                }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="row" style="margin-top:5px">

                                        <label style="margin-left:20px"><i class="bi bi-briefcase"></i> Categoria</label>

                                        <select id="selectCategoria" style="width:100%;margin-left:20px" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="selectCategoria" tabindex="-1" aria-hidden="true">
                                           
                                            <?php 
                                                foreach ($categorias as  $categoria) {
                                                    echo '<option value="'.$categoria['RUBRO'].'">'.$categoria['CATEGORIA'].'</option>';
                                                }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="row" style="margin-top:5px">

                                        <label style="margin-left:20px"><i class="bi bi-person"></i> Rango Etario</label>

                                        <select id="selectRangoEtario" style="width:100%;margin-left:20px" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="selectRangoEtario" tabindex="-1" aria-hidden="true">
                                           
                                            <option value="10-14" >10-14</option>
                                            <option value="15-19" >15-19</option>
                                            <option value="20-24" >20-24</option>
                                            <option value="25-29" >25-29</option>
                                            <option value="30-34" >30-34</option>
                                            <option value="35-39" >35-39</option>
                                            <option value="40-44" >40-44</option>
                                            <option value="45-49" >45-49</option>
                                            <option value="50-54" >50-54</option>
                                            <option value="55-59" >55-59</option>
                                            <option value="60-64" >60-64</option>
                                            <option value="65-69" >65-69</option>
                                            <option value="70-74" >70-74</option>
                                            <option value="75-79" >75-79</option>
                                            <option value="80-84" >80-84</option>
                                            <option value="85-89" >85-89</option>
                                            <option value="90-94" >90-94</option>

                                        </select>
                                    </div>
                                </form>

                            </div>

                            <div class="col"  style="border:solid 1px;margin-left:20px;margin-right:20px">
                        
                                    <div class="row" style="margin-left:50px">
                                        <h3><i class="bi bi-archive-fill" style="margin-right: 20px; font-size: 50px"></i>Datos de Clientes</h3>
                                    </div>
                            
                                    <div class="row" style="margin-left:5px;margin-right:30px;;margin-top:30px">

                                        <div class="table-responsive" id="tableIndex">
                                            <table class="table table-hover table-condensed table-striped text-center" style="width: 100%;" cellspacing="0" data-page-length="100">
                                                <thead class="thead-dark" style="font-size: small;">
                                                    <th scope="col" style="width: 6%">SUCURSAL</th>
                                                    <th scope="col" style="width: 15%">NOMBRE</th>
                                                    <th scope="col" style="width: 8%">PORCENTAJE</th>
                                                    <th scope="col" style="width: 8%"></th>

                                                </thead>

                                                <tbody id="tableVb" style="font-size: small;">

                                                </tbody>

                                            </table>
                                        </div>

                                    </div>
                        
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="js/segmentacionDeClientes.js"></script>
    </body>

    </html>
    <script>    


   



    // document.querySelector("#selectBanco").selectedOptions[0].value
    </script>
