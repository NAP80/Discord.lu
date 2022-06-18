<?php
    session_start();
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
            <title>Panel Admin - Personnages</title>
            <meta name='description' content='Panel Admin - Personnages'>
            <meta name='author' content='La Providence - Amiens'>
            <link rel='shortcut icon' href='favicon.ico'>
        <!-- Intégration Facebook -->
            <meta property='og:title' content='Panel Admin - Personnages'>
            <meta property='og:description' content='Panel Admin - Personnages'>
            <meta property='og:image' content='favicon.ico'>
        <!-- Intégration Twitter -->
            <meta name='twitter:title' content='Panel Admin - Personnages'>
            <meta name='twitter:description' content='Panel Admin - Personnages'>
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
                            <h1 class='TITRE'>Panel Administrateur</h2>
                        </div>
                        <div class='Div1 BG_Rouge'>
                            <h3 class='TC'>Modification Personnages</h3>
                            <p class='TC'>Texte.</p>
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