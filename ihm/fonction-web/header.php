<?php
    // Définition des variables du site.
        $NameWebsite    = "Discord.lu";
        $NameIcon       = "favicon.ico";
        $NameAutor      = "Discord.lu";
?>
    <!-- Compatible / UTF / Viewport-->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Informations Générales -->
        <title><?= $NameWebsite ?> - <?= $NameLocal ?></title>
        <meta name='description' content='<?= $NameWebsite ?> - <?= $NameLocal ?>'>
        <link rel='shortcut icon' href='<?= $NameIcon ?>'>
        <meta name='author' content='<?= $NameWebsite ?>'>
    <!-- Intégration Facebook -->
        <meta property='og:title' content='<?= $NameWebsite ?> - <?= $NameLocal ?>'>
        <meta property='og:description' content='<?= $NameWebsite ?> - <?= $NameLocal ?>'>
        <meta property='og:image' content='<?= $NameIcon ?>'>
    <!-- Intégration Twitter -->
        <meta name='twitter:title' content='<?= $NameWebsite ?> - <?= $NameLocal ?>'>
        <meta name='twitter:description' content='<?= $NameWebsite ?> - <?= $NameLocal ?>'>
        <meta name='twitter:image' content='<?= $NameIcon ?>'>
    <!-- Style Script -->
        <script src="main.js"></script>
    <!-- Style CSS Style Base -->
        <link rel="stylesheet" href="css/style.css">