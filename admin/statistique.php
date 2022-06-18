<?php
    session_start();

    // Attention, Function et code temporaire :
    // Optimisation avec des Boucles For dès que possible.
    Function ReturnTest(){
        return '12345' ;
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Compatible / UTF / Viewport-->
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style CSS / Script -->
            <link rel="stylesheet" href="../css/admin.css">
            <link rel="stylesheet" href="../css/style.css">
            <script src="main.js"></script>
        <!-- Informations Générales -->
            <title>Projet Full Stack - Statistique</title>
            <meta name='description' content='Projet Full Stack - Panel Admin'>
            <meta name='author' content='La Providence - Amiens'>
            <link rel='shortcut icon' href='favicon.ico'>
        <!-- Intégration Facebook -->
            <meta property='og:title' content='Projet Full Stack - Panel Admin'>
            <meta property='og:description' content='Projet Full Stack - Panel Admin'>
            <meta property='og:image' content='favicon.ico'>
        <!-- Intégration Twitter -->
            <meta name='twitter:title' content='Projet Full Stack - Panel Admin'>
            <meta name='twitter:description' content='Projet Full Stack - Panel Admin'>
            <meta name='twitter:image' content='favicon.ico'>
    </head>
    <body class="AdminPanel">
        <?php
            include "../session.php";

            // Vérifie que la Session est Valide avec le bon Mot de Passe.
            if($access === true){
                $access = $Joueur1->DeconnectToi();
            }
            // Vérifie qu'il ne s'est pas déconnecté.
            if($access === true){
                include "admin-menu.php";
                if($Joueur1->isAdmin() == true){
                    ?>
                        <div class='Div1 BG_Blanc'>
                            <h1 class='TITRE'>Statistique du Jeu</h2>
                        </div>
                        <div class='Div1 BG_Cyan'>
                            <h3 class='TC'>Statistiques Utilisateurs :</h3>
                          <!-- Totaux -->
                            <p class='TC'>Nombre d'utilisateurs inscrits <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                          <!-- Faction -->
                            <p class='TC'>Nombre d'utilisateurs inscrits de la faction <b>Manga</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'utilisateurs inscrits de la faction <b>Comics</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'utilisateurs inscrits de la faction <b>Science Fiction</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'utilisateurs inscrits de la faction <b>Gaming</b> : <?= ReturnTest() ?>.</p>
                        </div>
                        <div class='Div1 BG_Rouge'>
                            <h3 class='TC'>Statistiques Personnage :</h3>
                          <!-- Totaux -->
                            <p class='TC'>Nombre de personnage <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                          <!-- LV -->
                            <?php
                                for($i = 1 ; $i < 4 ; $i++){
                                    ?>
                                        <p class='TC'>Nombre de personnage de <b>LV <?= $i ?></b> : <?= ReturnTest() ?>.</p>
                                    <?php
                                }
                            ?>
                          <!-- Type Personnage -->
                            <p class='TC'>Nombre de personnage étant <b>Alien - Alien</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Alien - Humain</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Alien - Marines</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Batman Humain</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Batman Magicien</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Dbz - Humain</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>DbZ - Magicien</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Dbz - Sayen</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Flash agicien</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Hulk Magicen</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>humain - 1</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>humain - 2</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>humain - 3</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>humain - 4</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>league of legend - Assassin</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>league of legend - Tank</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Starwars - jedi</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de personnage étant <b>Starwars - sith</b> : <?= ReturnTest() ?>.</p>
                        </div>
                        <div class='Div1 BG_Bleu'>
                            <h3 class='TC'>Statistiques Monstre :</h3>
                          <!-- Totaux -->
                            <p class='TC'>Nombre de monstre <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                          <!-- LV -->
                            <?php
                                for($i = 1 ; $i < 4 ; $i++){
                                    ?>
                                        <p class='TC'>Nombre de monstre de <b>LV <?= $i ?></b> : <?= ReturnTest() ?>.</p>
                                    <?php
                                }
                            ?>
                          <!-- Type Monstre -->
                            <p class='TC'>Nombre de monstre étant <b>Super Sith Légendaire</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Sauron</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Silon</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Dragon</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Jedi</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Pirate</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Géant</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Viking</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Alien</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Vampire</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Zombie</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Loup</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre de monstre étant <b>Menir</b> : <?= ReturnTest() ?>.</p>
                        </div>
                        <div class='Div1 BG_Jaune'>
                            <h3 class='TC'>Statistiques Map :</h3>
                          <!-- Totaux -->
                            <p class='TC'>Nombre d'emplacement de map <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                          <!-- Présence Monstre -->
                            <p class='TC'>Nombre d'emplacement de map <b>ayant au moins un monstre</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map <b>sans aucun monstre</b> : <?= ReturnTest() ?>.</p>
                          <!-- LV Map -->
                            <?php
                                for($i = 1 ; $i < 4 ; $i++){
                                    ?>
                                        <p class='TC'>Nombre d'emplacement de map de <b>LV <?= $i ?></b> : <?= ReturnTest() ?>.</p>
                                    <?php
                                }
                            ?>
                          <!-- Type Map -->
                            <p class='TC'>Nombre d'emplacement de map de type <b>Plaine</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Fôret</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Montagne</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Chemin</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Donjon</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Château</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Dune</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Mer</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Océan</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Lac</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Bocage</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Marais</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Ville</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Campagne</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Enfer</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Glacier</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Sable</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Savane</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Colline</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Vallée</b> : <?= ReturnTest() ?>.</p>
                            <p class='TC'>Nombre d'emplacement de map de type <b>Désert</b> : <?= ReturnTest() ?>.</p>
                        </div>
                        <div class='Div1 BG_Vert'>
                            <h3 class='TC'>Statistiques d'Objet :</h3>
                          <!-- Totaux -->
                            <p class='TC'>Nombre d'Objet <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                            <div class='Div1 BG_Vert'>
                                <h4 class='TC'>Statistiques d'Item :</h4>
                              <!-- Totaux -->
                                <p class='TC'>Nombre d'item <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                              <!-- LV -->
                                <?php
                                    for($i = 1 ; $i < 4 ; $i++){
                                        ?>
                                            <p class='TC'>Nombre d'item de <b>LV <?= $i ?></b> : <?= ReturnTest() ?>.</p>
                                        <?php
                                    }
                                ?>
                              <!-- Efficacité -->
                                <p class='TC'>Nombre d'item <b>cassé</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>pourri</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>tout mou</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>moisie</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>usagé</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>moche</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>reconditionné</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>neuf</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>efficace</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>redoutable</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>puissant</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>magique</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>enchanté</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>en fusion</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>nucléaire</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item <b>infini</b> : <?= ReturnTest() ?>.</p>
                              <!-- Type Item -->
                                <p class='TC'>Nombre d'item de type <b>infini</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Pierre</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Fruit</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Fiole</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Mouchoir</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Morceau de Fer</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Pépite d'Or</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Œuf</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Bois</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Brique</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Canard en plastique</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Piece en toc</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Ficelle</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Gaudasse</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Pain</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'item de type <b>Haricot Magique</b> : <?= ReturnTest() ?>.</p>
                            </div>
                            <div class='Div1 BG_Vert'>
                                <h4 class='TC'>Statistiques d'Équipement :</h4>
                              <!-- Totaux -->
                                <p class='TC'>Nombre d'équipement <b>Totaux</b> : <?= ReturnTest() ?>.</p>
                              <!-- LV -->
                                <?php
                                    for($i = 1 ; $i < 4 ; $i++){
                                        ?>
                                            <p class='TC'>Nombre d'équipement de <b>LV <?= $i ?></b> : <?= ReturnTest() ?>.</p>
                                        <?php
                                    }
                                ?>
                              <!-- Efficacité -->
                                <p class='TC'>Nombre d'équipement <b>cassé</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>pourri</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>tout mou</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>moisie</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>usagé</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>moche</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>reconditionné</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>neuf</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>efficace</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>redoutable</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>puissant</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>magique</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>enchanté</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>en fusion</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>nucléaire</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement <b>infini</b> : <?= ReturnTest() ?>.</p>
                              <!-- Type Equipement -->
                                <p class='TC'>Nombre d'équipement de type <b>Brigandine</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Glaive</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Baton</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Pullover</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Planche en bois</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Épée</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Cote de maille</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Parapluie</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Fouet</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Dague</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Plastron</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Broigne</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Crosse</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Cuirasse</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Pistolet</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Kevlar</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Sabre Laser</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Cape invisible</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>Exosquelettes</b> : <?= ReturnTest() ?>.</p>
                                <p class='TC'>Nombre d'équipement de type <b>L'Amour</b> : <?= ReturnTest() ?>.</p>

                            </div>
                        </div>
                    <?php
                }else{
                    include "non_acces.php";
                }
            }else{
                echo $errorMessage;
            }
        ?>
    </body>
</html>