<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('catalogo');

render_header('Catálogo');
?>
<div class="card"><h2>Catálogo</h2><p>Gestión de artículos, servicios, gastos e inversiones con control por rol (ver/crear/editar/eliminar).</p></div>
<?php render_footer(); ?>
