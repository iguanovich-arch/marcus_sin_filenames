<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('presupuestos');

render_header('Presupuestos');
?>
<div class="card"><h2>Presupuestos</h2><p>Creación de presupuestos por cliente incluyendo materiales, mano de obra y conversión a pedido/albarán/factura.</p></div>
<?php render_footer(); ?>
