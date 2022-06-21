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
            <title>Panel Admin - Utilisateurs</title>
            <meta name='description' content='Panel Admin - Utilisateurs'>
            <meta name='author' content='La Providence - Amiens'>
            <link rel='shortcut icon' href='favicon.ico'>
        <!-- Intégration Facebook -->
            <meta property='og:title' content='Panel Admin - Utilisateurs'>
            <meta property='og:description' content='Panel Admin - Utilisateurs'>
            <meta property='og:image' content='favicon.ico'>
        <!-- Intégration Twitter -->
            <meta name='twitter:title' content='Panel Admin - Utilisateurs'>
            <meta name='twitter:description' content='Panel Admin - Utilisateurs'>
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
                        <div class='Div1 BG_Cyan'>
                            <h3 class='TC'>Modification Utilisateurs</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pseudo</th>
                                        <th>Prénom</th>
                                        <th>Mail</th>
                                        <th>Type</th>
                                        <th colspan="3">Actions</th>
                                    <tr>
                                </thead>
                                <tbody>
                                    <form action="" method="post">
                                        <tr>
                                            <td><?= 'N°ID' ?></td>
                                            <td class="TC"><?= 'Pseudo' ?></td>
                                            <td class="TC"><?= 'Prénom' ?></td>
                                            <td class="TC"><?= 'Mail' ?></td>
                                            <td class="TC"><?= 'MDP' ?></td>
                                            <td class="TC">
                                                <?php
                                                    if($Joueur1->isAdmin() == true){
                                                        echo 'Admin';
                                                    }
                                                    else{
                                                        echo 'Joueur';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input  type="text" name="USER_EDIT_ID"         value="<?= 'N°ID' ?>"   size="4"></td>
                                            <td><input  type="text" name="USER_EDIT_PSEUDO"     value="<?= 'Pseudo' ?>" size="12"></td>
                                            <td><input  type="text" name="USER_EDIT_PRENAME"    value="<?= 'Prénom' ?>" size="12"></td>
                                            <td><input  type="text" name="USER_EDIT_MAIL"       value="<?= 'Mail' ?>"   size="14"></td>
                                            <td><input  type="text" name="USER_EDIT_MDP"        value="<?= 'MDP' ?>"    size="10"></td>
                                            <td>
                                                <select type="text" name="USER_EDIT_TYPE">
                                                    <option value="">Type d'Utilisateur</option>
                                                    <option value="User">Joueur</option>
                                                    <option value="Admin">Admin</option>
                                                </select>
                                            </td>
                                            <td><input  type="submit"   name="USER_RESET"       value="Annuler"></td>
                                            <td><input  type="submit"   name="USER_EDIT_SAVE"   value="Modifier"></td>
                                            <td><input  type="hidden"   name="USER_ID"          value="<?= 'N°ID' ?>"></td>
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