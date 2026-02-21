<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('clientes');

render_header('Clientes');
?>
<div class="card"><h2>Clientes</h2><p>CRM de clientes, potenciales, contactos, cuentas bancarias y activos en cliente.</p></div>
<?php render_footer(); ?>
