<?php
require_once 'gdt/cldbgoeland.php';
header("Access-Control-Allow-Origin: *");
$idTypeAffaire = isset($_GET['idtypeaffaire'])?$_GET['idtypeaffaire']:'0';
if (isset($_GET['idtypeaffaire'])) {
    $idTypeAffaire = $_GET['idtypeaffaire'];
    $pathConfigXml = '/data/dataweb/GoelandWeb/goeland/affaire2/xml/';
    $fprmsInit = $pathConfigXml . 'prmsInitT' . $idTypeAffaire . '.xml';
    if (!file_exists($fprmsInit)) {
        $fprmsInit = $pathConfigXml . 'prmsInitT0.xml';
        $file_prmsinit = 'defaut';
    } else {
        $file_prmsinit = 'T' . strval($idTypeAffaire);
    }
    $sprmsInit = file_get_contents($fprmsInit);
    if (strstr($sprmsInit, '<DataInit>TypeAff</DataInit>')) {
        $datainit_pour = 'tous';
    } elseif (strstr($sprmsInit, 'TypeAffUOEmpU')) {
        $datainit_pour = 'unite';
    } elseif (strstr($sprmsInit, 'TypeAffSerEmpU')) {
        $datainit_pour = 'service';
    } elseif (strstr($sprmsInit, 'TypeAffDirEmpU')) {
        $datainit_pour = 'direction';
    } else {
        $datainit_pour = 'code_inconnu';
    }

    if ($datainit_pour == 'tous') {
        $fdataInit = $pathConfigXml . 'dataInitT' . $idTypeAffaire . '.xml';
        if (!file_exists($fdataInit)) {
            $fdataInit = $pathConfigXml . 'dataInitT0.xml';
            $file_datainit = 'defaut';
        } else {
            $file_datainit = 't' . strval($idTypeAffaire);
        }
        $sjsonroledroit = getFileXmldata('0', $fdataInit);
    } else {
        $configFiles = glob($pathConfigXml . 'dataInitT' .strval($idTypeAffaire) . 'U*.xml');
        $sjsonroledroit = '';
        foreach ($configFiles as $configFile) {
            $sidUnite = str_replace('.xml', '', str_replace($pathConfigXml . 'dataInitT' .strval($idTypeAffaire) . 'U', '', $configFile));
            if ($sjsonroledroit != '') {
                $sjsonroledroit .= ',';
            }
            $sjsonroledroit .= getFileXmldata($sidUnite, $configFile);
        }
    }

    $sjsonret = '{"prmsinit":{';
    $sjsonret .= '"idtypeaffaire":' . strval($idTypeAffaire);
    $sjsonret .= ',"file_prmsinit":"' . $file_prmsinit . '"';
    $sjsonret .= ',"datainit_pour":"' . $datainit_pour . '"';
    $sjsonret .= ',"unites":[' . $sjsonroledroit . ']';
    $sjsonret .= '}}';
    echo ($sjsonret);
}
else {
    echo '{}';
}

function getFileXmldata($idUnite, $fdataInit) {
    $oDataIni = simplexml_load_file($fdataInit);
    $nom = $oDataIni->Nom;

    $dbgo = new DBGoeland();
    //Information unité concernée
    if ($idUnite == '0') {
        $libelleUnite = 'toutes';
    } else {
        $dbgo->queryRetJson2("CN_OrgUnitDataLight $idUnite");
        $oUnite = json_decode($dbgo->resString);
        $libelleUnite = $oUnite[0]->Description;
        if ($oUnite[0]->bactif == 0) {
            $libelleUnite .= ' (désactivée)';
        }
    }

    //Liste des rôles
    $sjsonRoleUO = '';
    $sjsonRoleEmp = '';
    $roles = $oDataIni->Role;
    foreach ($roles as $role) {
        $idUO = $role->IdUO;
        $idEmp = $role->IdEmp;
        if ($idUO != '') {
            $idRole = $role->IdRole;
            if ($idUO == '#idUO#') {
                $idUO = '0';
            }
            $dbgo->queryRetJson2("cn_typeaffaire_initroleuo_info $idUO, $idRole");
            $sJson = $dbgo->resString;
            if (substr($sJson, 0, 1) == '[') {
                $sJson = substr($sJson, 1, strlen($sJson) - 2);
            }
            if ($sjsonRoleUO == '') {
                $sjsonRoleUO = $sJson;
            } else {
                $sjsonRoleUO .= ',' . $sJson;
            }
        }
        if ($idEmp != '') {
            $idRole = $role->IdRole;
            if ($idEmp == '#idEmploye#') {
                $idEmp = '0';
            }
            $dbgo->queryRetJson2("cn_typeaffaire_initroleemp_info $idEmp, $idRole");
            $sJson = $dbgo->resString;
            if (substr($sJson, 0, 1) == '[') {
                $sJson = substr($sJson, 1, strlen($sJson) - 2);
            }
            if ($sjsonRoleEmp == '') {
                $sjsonRoleEmp = $sJson;
            } else {
                $sjsonRoleEmp .= ',' . $sJson;
            }
        }
    }
    $sjsonRoleUO = "[$sjsonRoleUO]";
    $sjsonRoleEmp = "[$sjsonRoleEmp]";

    //Liste des droits
    $sjsonDroitUO = '';
    $sjsonDroitEmp = '';
    $sjsonDroitGrpSec = '';
    $droits = $oDataIni->Droit;
    foreach ($droits as $droit) {
        $idUO = $droit->IdUO;
        $idEmp = $droit->IdEmp;
        $idGrpSec = $droit->IdGS;
        if ($idUO != '') {
            $idDroit = $droit->IdDroit;
            if ($idUO == '#idUO#') {
                $idUO = '0';
            }
            $dbgo->queryRetJson2("cn_typeaffaire_initdroituo_info $idUO, $idDroit");
            $sJson = $dbgo->resString;
            if (substr($sJson, 0, 1) == '[') {
                $sJson = substr($sJson, 1, strlen($sJson) - 2);
            }
            if ($sjsonDroitUO == '') {
                $sjsonDroitUO = $sJson;
            } else {
                $sjsonDroitUO .= ',' . $sJson;
            }
        }
        if ($idEmp != '') {
            $idDroit = $droit->IdDroit;
            if ($idEmp == '#idEmploye#') {
                $idEmp = '0';
            }
            $dbgo->queryRetJson2("cn_typeaffaire_initdroitemp_info $idEmp, $idDroit");
            $sJson = $dbgo->resString;
            if (substr($sJson, 0, 1) == '[') {
                $sJson = substr($sJson, 1, strlen($sJson) - 2);
            }
            if ($sjsonDroitEmp == '') {
                $sjsonDroitEmp = $sJson;
            } else {
                $sjsonDroitEmp .= ',' . $sJson;
            }
        }
        if ($idGrpSec != '') {
            $idDroit = $droit->IdDroit;
            $dbgo->queryRetJson2("cn_typeaffaire_initdroigrpsec_info $idGrpSec, $idDroit");
            $sJson = $dbgo->resString;
            if (substr($sJson, 0, 1) == '[') {
                $sJson = substr($sJson, 1, strlen($sJson) - 2);
            }
            if ($sjsonDroitGrpSec == '') {
                $sjsonDroitGrpSec = $sJson;
            } else {
                $sjsonDroitGrpSec .= ',' . $sJson;
            }
        }
    }
    $sjsonDroitUO = "[$sjsonDroitUO]";
    $sjsonDroitEmp = "[$sjsonDroitEmp]";
    $sjsonDroitGrpSec = "[$sjsonDroitGrpSec]";
    unset($dbgo);
    $sjsonroledroit = '{"unite":{"id":' . $idUnite . ',"libelle":"' . rawurlencode($libelleUnite) . '"';
    $sjsonroledroit .= ',"nom":"' . rawurlencode($nom) . '"';
    $sjsonroledroit .= ',"roleemp":' . $sjsonRoleEmp;
    $sjsonroledroit .= ',"roleuo":' . $sjsonRoleUO ;
    $sjsonroledroit .= ',"droitemp":' . $sjsonDroitEmp;
    $sjsonroledroit .= ',"droituo":' . $sjsonDroitUO ;
    $sjsonroledroit .= ',"droitgrpsec":' . $sjsonDroitGrpSec;
    $sjsonroledroit .= '}}';
    return $sjsonroledroit;
}