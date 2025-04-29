<?php
require 'gdt/gautentificationf5.php';
require_once '/data/dataweb/GoelandWeb/webservice/employe/clCNWSEmployeSecurite.php';
require_once 'gdt/cldbgoeland.php';
require_once 'gdt/utf8go.php';
require_once '/data/dataweb/GoelandWeb/webservice/employe/clMPWSEmploye.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:  *");
header("Access-Control-Allow-Methods:  POST");
$idCaller = 0;
if (array_key_exists('empid', $_SESSION)) {
    $idCaller = $_SESSION['empid'];
}
if ($idCaller > 0) {
    $sPrmsInitXml = '';
    $pseudoWSEmployeSecurite = new CNWSEmployeSecurite();
    if ($pseudoWSEmployeSecurite->isInGroupe($idCaller, 'GoelandManager')) {
        $strMessage = '';
        $pathConfigXmlPrmsInit = '/data/goelanddocs/Godoc/typeaffaireinit/prmsinit/';
        $pathConfigXmlDataInit = '/data/goelanddocs/Godoc/typeaffaireinit/datainit/';
        $jsonData = file_get_contents('php://input');
        if ($jsonData == '') {die;}
        $oData = json_decode($jsonData);
        $idTypeAffaire = $oData->prmsinit->idtypeaffaire;
        $filePrmsInit = $oData->prmsinit->file_prmsinit;
        $dataInitPour = $oData->prmsinit->datainit_pour;
        $dbgo = new DBGoeland();
        $dbgo->queryRetJson2("cn_TypeAffaire_data $idTypeAffaire");
        $oTypeAffaire = json_decode($dbgo->resString);
        $typeAffaire = $oTypeAffaire[0]->typeaffaire;
        unset($dbgo);

        //gestion du fichier prmsInitTxxx.xml
        $bChangeFilePrmsInit = false;
        $fileNamePrmsInit = '';
        if ($filePrmsInit == 'defaut') {
            switch ($dataInitPour) {
                case 'tous':
                    $fileNamePrmsInit = "prmsInitT$idTypeAffaire.xml";
                    //configuration par défaut demandée, si un fichier dédié existe, on le supprime
                    if (file_exists("$pathConfigXmlPrmsInit$fileNamePrmsInit")) {
                        $bChangeFilePrmsInit = true;
                        if (unlink("$pathConfigXmlPrmsInit$fileNamePrmsInit")) {
                            $strMessage .= "#Suppression du fichier $pathConfigXmlPrmsInit$fileNamePrmsInit";
                        } else {
                            $strMessage .= "#ERREUR lors de la supression du fichier $pathConfigXmlPrmsInit$fileNamePrmsInit";
                        }
                        //Si un fichier dataInitTxxxU0.xml existe, on le supprime c'est dataInitTxxx.xml qui sera utilisé
                        $fileU0Name = "$pathConfigXmlDataInit" . "dataInitT$idTypeAffaire" . "U0.xml";
                        if (file_exists($fileU0Name)) {
                            if (unlink($fileU0Name)) {
                                $strMessage .= "#Suppression du fichier $fileU0Name";
                            } else {
                                $strMessage .= "#ERREUR lors de la supression du fichier $fileU0Name";
                            }
                        }
                    }
                    $sXml = '';
                    break;
                case 'unite':
                    //création du fichier prmsInit$filePrmsInit.xml avec <DataInit>TypeAffUOEmpU</DataInit>
                    $sXml = strFilePrmsInit('TypeAffUOEmpU');
                    break;
                case 'service':
                    //création du fichier prmsInit$filePrmsInit.xml avec <DataInit>TypeAffSerEmpU</DataInit>
                    $sXml = strFilePrmsInit('TypeAffSerEmpU');
                    break;
                case 'direction':
                    //création du fichier prmsInit$filePrmsInit.xml avec <DataInit>TypeAffDirEmpU</DataInit>
                    $sXml = strFilePrmsInit('TypeAffDirEmpU');
                    break;
            }
            if ($sXml != '') {
                $fileNamePrmsInit = "prmsInitT$idTypeAffaire.xml";
                $sPrmsInitXml = $sXml;
                $bChangeFilePrmsInit = true;
                $sPrmsInitXml = str_replace("?>", "?>\n<!-- Type affaire : $idTypeAffaire - $typeAffaire -->", $sPrmsInitXml);
                //Ecriture du fichier $fileNamePrmsInit (création ou remplace si existe déjà)
                $nbrBytes = file_put_contents("$pathConfigXmlPrmsInit$fileNamePrmsInit", $sPrmsInitXml);
                if ($nbrBytes !== false) {
                    $strMessage .= "#Ecriture du fichier $pathConfigXmlPrmsInit$fileNamePrmsInit. $nbrBytes bytes";
                } else {
                    $strMessage .= "#ERREUR lors de l'écriture du fichier $pathConfigXmlPrmsInit$fileNamePrmsInit";
                }
            }
        } else {
            $fileNamePrmsInit = "prmsInit$filePrmsInit.xml";
            $sPrmsInitXml = file_get_contents("$pathConfigXmlPrmsInit$fileNamePrmsInit");
            $debutBalise = "<DataInit>";
            $finBalise = "</DataInit>";
            $positionDebut = strpos($sPrmsInitXml, $debutBalise) + strlen($debutBalise);
            $positionFin = strpos($sPrmsInitXml, $finBalise);
            $longueurContenu = $positionFin - $positionDebut;
            switch ($dataInitPour) {
                case 'tous':
                    $sDataInit = '<DataInit>TypeAff</DataInit>';
                    break;
                case 'unite':
                    $sDataInit = '<DataInit>TypeAffUOEmpU</DataInit>';
                    break;
                case 'service':
                    $sDataInit = '<DataInit>TypeAffSerEmpU</DataInit>';
                    break;
                case 'direction':
                    $sDataInit = '<DataInit>TypeAffDirEmpU</DataInit>';
                    break;
            }
            if (strpos($sPrmsInitXml, $sDataInit) === false) {
                $sPrmsInitXml = substr_replace($sPrmsInitXml, $sDataInit, $positionDebut, $longueurContenu);
                $bChangeFilePrmsInit = true;
                $sPrmsInitXml = str_replace("?>", "?>\n<!-- Type affaire : $idTypeAffaire - $typeAffaire -->", $sPrmsInitXml);
                //Ecriture du fichier $fileNamePrmsInit (création ou remplace si existe déjà)
                $nbrBytes = file_put_contents("$pathConfigXmlPrmsInit$fileNamePrmsInit", $sPrmsInitXml);
                if ($nbrBytes !== false) {
                    $strMessage .= "#Ecriture du fichier $pathConfigXmlPrmsInit$fileNamePrmsInit. $nbrBytes bytes";
                } else {
                    $strMessage .= "#ERREUR lors de l'écriture du fichier $pathConfigXmlPrmsInit$fileNamePrmsInit";
                }
            }
        }
        if (!$bChangeFilePrmsInit) {
            $strMessage .= "PrmsInitXml = NO CHANGE. ";
        }

        //Fichiers pour les unités
        //------------------------
        //Chargement des dictionnaires Rôle (employés et unités) et Droit pour les commentaires
        $dbgo = new DBGoeland();
        $dbgo->queryRetJson2("cn_dicoemployerole_liste");
        $dicoRoleEmployeById = array_column(json_decode($dbgo->resString, true), 'role', 'idrole');
        $dbgo->queryRetJson2("cn_dicoorgunitrole_liste");
        $dicoRoleUniteById = array_column(json_decode($dbgo->resString, true), 'role', 'idrole');
        $dbgo->queryRetJson2("cn_dicoaffairedroitemporou_liste");
        $dicoDroitById = array_column(json_decode($dbgo->resString, true), 'droit', 'iddroit');
        unset($dbgo);

        //Info employé
        $pseudoWSEmploye = new MPWSEmploye();
        $fileNameDataInit = '';
        $aUnites = $oData->prmsinit->unites;
        if ($dataInitPour == 'tous') {
            if (count($aUnites) == 1 && $aUnites[0]->unite->id == 0) {
                $fileNameDataInit = "dataInitT$idTypeAffaire.xml";
            } else {
                $strMessage .= "#ERREUR incohérence Données initiales pour et Unités passées";
                $aUnites = array();
            }
        }

        foreach ($aUnites as $unite) {
            $idUO = $unite->unite->id;
            $nomUO = $unite->unite->libelle;
            if ($fileNameDataInit == '') {
                $fileNameDataInit = 'dataInitT' . $idTypeAffaire . 'U' . $idUO . '.xml';
            }
            $commentaire = "<!-- Type affaire : $idTypeAffaire - $typeAffaire  Pour : $dataInitPour - $nomUO -->";
            $sXmldataInit = '<?xml version="1.0" encoding="UTF-8"?>' . "\n$commentaire\n<DataIni>";
            $nomAffaire = $unite->unite->nom;
            if ($nomAffaire !== '') {
                $sXmldataInit .= "\n<Nom><![CDATA[$nomAffaire]]></Nom>";
            }

            //Roles employés
            $aRolesEmployes = $unite->unite->roleemp;
            foreach ($aRolesEmployes as $roleemp) {
                $idemploye = $roleemp->idemp;
                $idroleemploye = $roleemp->idroleemp;
                $roleName = $dicoRoleEmployeById[$idroleemploye];
                if ($idemploye == 0) {
                    $sXmldataInit .= "\n<Role><IdEmp>#idEmploye#</IdEmp><IdRole>$idroleemploye</IdRole></Role> <!-- Créateur / $roleName -->";
                } else {
                    $aRet = json_decode($pseudoWSEmploye->dataLigth($idemploye));
                    $nomEmploye = utf8go_encode(rawurldecode($aRet[0]->Nom)) . ' ' . utf8go_encode(rawurldecode($aRet[0]->Prenom)) . ' (' . utf8go_encode(rawurldecode($aRet[0]->UODesc)) . ')';
                    $sXmldataInit .= "\n<Role><IdEmp>$idemploye</IdEmp><IdRole>$idroleemploye</IdRole></Role> <!-- $nomEmploye / $roleName -->";
                }
            }

            //Roles unités
            $dbgo = new DBGoeland();
            $aRolesUnites = $unite->unite->roleuo;
            foreach ($aRolesUnites as $roleuo) {
                $idunite = $roleuo->iduo;
                $idroleunite = $roleuo->idroleuo;
                $roleName = $dicoRoleUniteById[$idroleunite];
                if ($idunite == 0) {
                    $sXmldataInit .= "\n<Role><IdUO>#idEmploye#</IdUO><IdRole>$idroleunite</IdRole></Role> <!-- Unité créateur / $roleName -->";
                } else {
                    $dbgo->queryRetJson2("CN_OrgUnitDataLight $idunite");
                    $oUnite = json_decode($dbgo->resString);
                    $libelleUnite = $oUnite[0]->Description;
                    $sXmldataInit .= "\n<Role><IdUO>$idunite</IdUO><IdRole>$idroleunite</IdRole></Role> <!-- $libelleUnite / $roleName -->";
                }
            }
            unset($dbgo);

            //Droits employés
            $aDroitsEmployes = $unite->unite->droitemp;
            foreach ($aDroitsEmployes as $droitemp) {
                $idemploye = $droitemp->idemp;
                $iddroitemploye = $droitemp->iddroitemp;
                $droitName = $dicoDroitById[$iddroitemploye];
                if ($idemploye == 0) {
                    $sXmldataInit .= "\n<Droit><IdEmp>#idEmploye#</IdEmp><IdDroit>$iddroitemploye</IdDroit></Droit> <!-- Créateur / $droitName -->";
                } else {
                    $aRet = json_decode($pseudoWSEmploye->dataLigth($idemploye));
                    $nomEmploye = utf8go_encode(rawurldecode($aRet[0]->Nom)) . ' ' . utf8go_encode(rawurldecode($aRet[0]->Prenom)) . ' (' . utf8go_encode(rawurldecode($aRet[0]->UODesc)) . ')';
                    $sXmldataInit .= "\n<Droit><IdEmp>$idemploye</IdEmp><IdDroit>$iddroitemploye</IdDroit></Droit> <!-- $nomEmploye / $droitName -->";
                }
            }

            //Droits unités
            $dbgo = new DBGoeland();
            $aDroitsUnites = $unite->unite->droituo;
            foreach ($aDroitsUnites as $droituo) {
                $idunite = $droituo->iduo;
                $iddroitunite = $droituo->iddroituo;
                $droitName = $dicoDroitById[$iddroitunite];
                if ($idunite == 0) {
                    $sXmldataInit .= "\n<Droit><IdUO>#idEmploye#</IdUO><IdDroit>$iddroitunite</IdDroit></Droit> <!-- Unité créateur / $droitName -->";
                } else {
                    $dbgo->queryRetJson2("CN_OrgUnitDataLight $idunite");
                    $oUnite = json_decode($dbgo->resString);
                    $libelleUnite = $oUnite[0]->Description;
                    $sXmldataInit .= "\n<Droit><IdUO>$idunite</IdUO><IdDroit>$iddroitunite</IdDroit></Droit> <!-- $libelleUnite / $droitName -->";
                }
            }
            unset($dbgo);

            //Droits groupes sécurité
            $aDroitsGroupesSec = $unite->unite->droitgrpsec;
            foreach ($aDroitsGroupesSec as $droitgrpsec) {
                $idgroupesec = $droitgrpsec->idgrpsec;
                $iddroitgroupesec = $droitgrpsec->iddroitgrpsec;
                $droitName = $dicoDroitById[$iddroitgroupesec];
                $groupesecName = $droitgrpsec->nomgrpsec;
                $sXmldataInit .= "\n<Droit><IdGS>$idgroupesec</IdGS><IdDroit>$iddroitgroupesec</IdDroit></Droit> <!-- $groupesecName / $droitName -->";
            }

            $sXmldataInit .= "\n</DataIni>";

            $nbrBytes = file_put_contents("$pathConfigXmlDataInit$fileNameDataInit", $sXmldataInit);
            if ($nbrBytes !== false) {
                $strMessage .= "#Ecriture du fichier $pathConfigXmlDataInit$fileNameDataInit. $nbrBytes bytes";
            } else {
                $strMessage .= "#ERREUR lors de l'écriture du fichier $pathConfigXmlDataInit$fileNameDataInit";
            }
            $fileNameDataInit = '';
        }
        echo '{"idtypeaffaire":"' . $idTypeAffaire . '","message":"' . utf8go_decode($strMessage) . '"}';
    } else {
        echo '{"message":"ERREUR GoelandManager requis"}';
    }
} else {
    echo '{"message":"ERREUR athentification F5"}';
}

function strFilePrmsInit($dataInit) {
    $sXml = '<?xml version="1.0" encoding="UTF-8"?>';
    $sXml .= "\n<PrmsIni>";
    $sXml .= "\n\t<DataInit>$dataInit</DataInit>";
    $sXml .= "\n\t<IndexationDocumentInit>TypeAff</IndexationDocumentInit>";
    $sXml .= "\n\t<MenuAffaireEdition>TypeAff</MenuAffaireEdition>";
    $sXml .= "\n\t<MenuAffaireSuiviEdition>TypeAff</MenuAffaireSuiviEdition>";
    $sXml .= "\n</PrmsIni>";
    return $sXml;
}
