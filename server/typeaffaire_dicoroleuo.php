<?php
require_once('gdt/utf8go.php');
header("Access-Control-Allow-Origin: *");
$idTypeAffaire = isset($_GET['idtypeaffaire'])?$_GET['idtypeaffaire']:'0';
if (isset($_GET['idtypeaffaire'])) {
    $idTypeAffaire = $_GET['idtypeaffaire'];
    $pathConfigXml = '/data/dataweb/GoelandWeb/goeland/affaire2/xml/';
    $fdicoRoleUO = $pathConfigXml . 'dicoRoleUniteOrgT' . $idTypeAffaire . '.xml';
    if (!file_exists($fdicoRoleUO)) {
        $fdicoRoleUO = $pathConfigXml . 'dicoRoleUniteOrgT0.xml';
    }
    $sjsonRoleUO = '';
    $oDicoRoleUO = simplexml_load_file($fdicoRoleUO);
    $roles = $oDicoRoleUO->Role;
    foreach ($roles as $role) {
        $id = $role->Id;
        $libelle = utf8go_decode($role->Libelle);
        if ($sjsonRoleUO != '') {
            $sjsonRoleUO .= ',';
        }
        $sjsonRoleUO .= '{"id":' . $id . ',"libelle":"' . $libelle . '"}';
    }
    echo "[$sjsonRoleUO]";
} else {
    echo'[]';
}