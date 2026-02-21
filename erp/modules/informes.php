<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('informes');

render_header('Informes');
?>
<div class="card"><h2>Informes</h2><p>Informes de ventas, compras, tesorería, stock, impuestos y productividad por cliente/empleado/catálogo.</p></div>
<?php render_footer(); ?>
