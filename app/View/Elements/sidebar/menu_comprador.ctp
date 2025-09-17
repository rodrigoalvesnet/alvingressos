<?php
$isAdmin = false;
$isAjudante = false;
$userId = AuthComponent::user('id');
App::import('Model', 'Event');
$Event = new Event();
//Verifica se foi selecionado como AJUDANTE de um evento
$sql = "SELECT COUNT(id) AS total FROM events_users WHERE user_id = $userId";
$events = $Event->query($sql);
if ($events[0][0]['total'] > 0) {
    $isAjudante = true;
}
//Verifica se foi selecionado como ADMIN de um evento
$sql = "SELECT COUNT(id) AS total FROM events_admins WHERE user_id = $userId";
$events = $Event->query($sql);
if ($events[0][0]['total'] > 0) {
    $isAdmin = true;
}

//Se tem permissão de ADMIN ou AJUDANTE de algum evento
if ($isAjudante || $isAdmin) {
?>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/events" aria-expanded="false"><i class="mdi mdi-calendar"></i><span class="hide-menu">Eventos </span></a>
    </li>
<?php }

if ($isAdmin) {
?>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/orders" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Pedidos </span></a>
    </li>
<?php } ?>