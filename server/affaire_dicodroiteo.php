<?php
require_once 'gdt/cldbgoeland.php';
header("Access-Control-Allow-Origin: *");
$sSql = "cn_dicoaffairedroitempou_liste";
$dbgo = new DBGoeland();
$dbgo->queryRetJson2($sSql);
echo $dbgo->resString;
unset($dbgo);
