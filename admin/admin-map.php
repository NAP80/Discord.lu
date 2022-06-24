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
        <!-- Informations Générales -->
            <title>Panel Admin - Map</title>
            <meta name='description' content='Panel Admin - Map'>
            <meta name='author' content='La Providence - Amiens'>
            <link rel='shortcut icon' href='favicon.ico'>
        <!-- Intégration Facebook -->
            <meta property='og:title' content='Panel Admin - Map'>
            <meta property='og:description' content='Panel Admin - Map'>
            <meta property='og:image' content='favicon.ico'>
        <!-- Intégration Twitter -->
            <meta name='twitter:title' content='Panel Admin - Map'>
            <meta name='twitter:description' content='Panel Admin - Map'>
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
                            <h1 class='TITRE'>Panel Administrateur - Map</h2>
                        </div>
                        <div class='Div1 BG_Jaune'>
                            <h3 class='TC'>Modification Map</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom FR</th>
                                        <th>Nom EN</th>
                                        <th colspan="2">Actions</th>
                                    <tr>
                                </thead>
                                <tbody>
                                    <form action="" method="post">
                                        <tr>
                                            <td><?= 'N°ID' ?></td>
                                            <td class="TC"><?= 'Nom Français' ?></td>
                                            <td class="TC"><?= 'Nom Anglais' ?></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text"      name="TYPEMAP_EDIT_ID"      value="<?= 'N°ID' ?>"           minlength="1"   maxlength="4"  size="4"></td>
                                            <td><input type="text"      name="TYPEMAP_EDIT_NAME_FR" value="<?= 'Nom Français' ?>"   minlength="3"   maxlength="16"  size="12"></td>
                                            <td><input type="text"      name="TYPEMAP_EDIT_NAME_EN" value="<?= 'Nom Anglais' ?>"    minlength="3"   maxlength="16"  size="12"></td>
                                            <td><input type="submit"    name="TYPEMAP_RESET"        value="Annuler"></td>
                                            <td><input type="submit"    name="TYPEMAP_EDIT_SAVE"    value="Modifier"></td>
                                            <td><input type="hidden"    name="TYPEMAP_ID"           value="<?= 'N°ID' ?>"></td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    <?php
                }
                else{
                    include "non_acces.php";
                }
            }
            else{
                echo $errorMessage;
            }
        ?>
    </body>
</html>