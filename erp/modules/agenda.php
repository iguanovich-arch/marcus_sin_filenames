<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('agenda');

render_header('Agenda');
?>
<div class="card"><h2>Agenda</h2><p>Calendario de eventos, tareas y planificación de incidencias SAT por técnico y equipo.</p></div>
<?php render_footer(); ?>
