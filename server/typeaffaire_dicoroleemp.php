<?php
require_once('gdt/utf8go.php');
header("Access-Control-Allow-Origin: *");
$idTypeAffaire = isset($_GET['idtypeaffaire'])?$_GET['idtypeaffaire']:'0';
if (isset($_GET['idtypeaffaire'])) {
    $idTypeAffaire = $_GET['idtypeaffaire'];
    $pathConfigXml = '/data/dataweb/GoelandWeb/goeland/affaire2/xml/';
    $fdicoRoleEmp = $pathConfigXml . 'dicoRoleEmpT' . $idTypeAffaire . '.xml';
    if (!file_exists($fdicoRoleEmp)) {
        $fdicoRoleEmp = $pathConfigXml . 'dicoRoleEmpT0.xml';
    }
    $sjsonRoleEmp = '';
    $oDicoRoleUO = simplexml_load_file($fdicoRoleEmp);
    $roles = $oDicoRoleUO->Role;
    foreach ($roles as $role) {
        $id = $role->Id;
        $libelle = utf8go_decode($role->Libelle);
        if ($sjsonRoleEmp != '') {
            $sjsonRoleEmp .= ',';
        }
        $sjsonRoleEmp .= '{"id":' . $id . ',"libelle":"' . $libelle . '"}';
    }
    echo "[$sjsonRoleEmp]";
} else {
    echo'[]';
}