<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Classement";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/classement.css">
    </head>
    <body class="bodyAccueil">
        <?php
            include "session.php";

            // Vérifie que la Session est Valide avec le bon Mot de Passe.
            if($access === true){
                $access = $Joueur1->DeconnectToi();
            }
            // Vérifie qu'il ne s'est pas déconnecté.
            if($access === true){
                include "ihm/fonction-web/menu.php";
                ?>
                    <div class="divMainPage">
                        <h1>Classement des 100 Meilleurs Personnages</h1>
                        <table id="classementPerso">
                            <thead>
                            <tr>
                                <th onclick="sortTable0(0)">Nom</th>
                                <th onclick="sortTable0(1)">Type</th>
                                <th onclick="sortTable0(2)">Level</th>
                                <th onclick="sortTable0(3)">Expérience</th>
                                <th onclick="sortTable0(4)">Money</th>
                                <th onclick="sortTable0(5)">Date de Création</th>
                                <th onclick="sortTable0(6)">Joueur</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $ClassementPersonnage = new Personnage($mabase);
                                    $PersonnageInfo = new Personnage($mabase);
                                    $UserPersonnage = new User($mabase);
                                    $TypePersonnage = new TypePersonnage($mabase);
                                    // On fetch et récup tout
                                    foreach($ClassementPersonnage -> getListIdPersonnage() as $Personnage){
                                        $PersonnageInfo = new Personnage($mabase);
                                        $UserPersonnage = new User($mabase);
                                        $PersonnageInfo = $PersonnageInfo->GetInfoPersonnageById($Personnage['idEntite']);
                                        ?>
                                            <tr>
                                                <td><?= $Personnage['nameEntite']?></td>
                                                <td><?= $TypePersonnage->getNameTypePersoById($PersonnageInfo['idTypePersonnage'])?></td>
                                                <td><?= $PersonnageInfo['levelPersonnage']?></td>
                                                <td><?= $PersonnageInfo['expPersonnage'] ?></td>
                                                <td><?= $PersonnageInfo['moneyPersonnage'] ?></td>
                                                <td><?= date("d/m/Y", strtotime($Personnage['dateTimeEntite'])) ?></td>
                                                <td><?= $UserPersonnage->getPseudoById($Personnage['idUser']) ?></td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>

                        <h1>Classement des 100 Meilleurs Joueurs</h1>
                        <table id="classementJoueur">
                            <thead>
                            <tr>
                                <th onclick="sortTable1(0)">Pseudo</th>
                                <th onclick="sortTable1(1)">Faction</th>
                                <th onclick="sortTable1(2)">Créature(s)</th>
                                <th onclick="sortTable1(3)">Date de Création</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $UserPersonnage = new User($mabase);
                                    $TypePersonnage = new TypePersonnage($mabase);
                                    // On fetch et récup tout
                                    foreach($UserPersonnage -> getListIdPersonnage() as $UserInfo){
                                        ?>
                                            <tr>
                                                <td><?= $UserInfo['pseudo']?></td>
                                                <td><?= $PersonnageInfo['idTypePersonnage']?></td>
                                                <td><?= $PersonnageInfo['levelPersonnage']?></td>
                                                <td><?= $PersonnageInfo['expPersonnage'] ?></td>
                                                <td><?= $PersonnageInfo['moneyPersonnage'] ?></td>
                                                <td><?= date("d/m/Y", strtotime($Personnage['dateTimeEntite'])) ?></td>
                                                <td><?= $UserPersonnage->getPseudoById($Personnage['idUser']) ?></td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <script>
                            // Script Triage Tableau Personnage
                            let sortDirections0 = [1, 1, 1, 1, 1, 1, 1]; // Initial sorting direction for each column
                            function sortTable0(columnIndex){
                                let table, rows, switching, i, x, y, shouldSwitch, dir;
                                table = document.getElementById("classementPerso");
                                switching = true;
                                dir = sortDirections0[columnIndex];
                                while(switching){
                                    switching = false;
                                    rows = table.rows;
                                    for(i = 1; i < rows.length - 1; i++){
                                        shouldSwitch = false;
                                        x = rows[i].getElementsByTagName("td")[columnIndex];
                                        y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                                        let xText = x.innerHTML.toLowerCase();
                                        let yText = y.innerHTML.toLowerCase();
                                        if(dir === 1){
                                            if(xText > yText){
                                                shouldSwitch = true;
                                                break;
                                            }
                                        }
                                        else{
                                            if(xText < yText){
                                                shouldSwitch = true;
                                                break;
                                            }
                                        }
                                    }
                                    if(shouldSwitch){
                                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                                        switching = true;
                                    }
                                }
                                sortDirections0[columnIndex] *= -1; // Toggle the sorting direction
                            }

                            // Script Triage Tableau Joueur
                            let sortDirections1 = [1, 1, 1, 1]; // Initial sorting direction for each column
                            function sortTable1(columnIndex){
                                let table, rows, switching, i, x, y, shouldSwitch, dir;
                                table = document.getElementById("classementJoueur");
                                switching = true;
                                dir = sortDirections1[columnIndex];
                                while(switching){
                                    switching = false;
                                    rows = table.rows;
                                    for(i = 1; i < rows.length - 1; i++){
                                        shouldSwitch = false;
                                        x = rows[i].getElementsByTagName("td")[columnIndex];
                                        y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                                        let xText = x.innerHTML.toLowerCase();
                                        let yText = y.innerHTML.toLowerCase();
                                        if(dir === 1){
                                            if(xText > yText){
                                                shouldSwitch = true;
                                                break;
                                            }
                                        }
                                        else{
                                            if(xText < yText){
                                                shouldSwitch = true;
                                                break;
                                            }
                                        }
                                    }
                                    if(shouldSwitch){
                                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                                        switching = true;
                                    }
                                }
                                sortDirections1[columnIndex] *= -1; // Toggle the sorting direction
                            }
                        </script>
                    </div>
                <?php
            }
            else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>