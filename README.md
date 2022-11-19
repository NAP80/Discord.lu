# Discord.lu
Refonte du projet scolaire https://github.com/EnseignantLapro/Covid.

# Bugs :
En cas de problème rencontré, n'hésitez pas à le signaler ici, de préférence avec une capture d'écrans : https://github.com/Piebleu/Discord.lu/issues

# Avancement :

## En cours :
- Uniformisation Code,
- Refonte Entitée/Monster/Personnage,
- Système d'Actions Personnages,

## Todo List :
- Dev :
- - Vérification JS Inscription FRONT,
- - Intégration envoie de mail et confirmation inscription,
- - Ajout Boite de Dialog pour RepMSG,
- - Intégration Panel Admin,
- - Sécurisation Injection SQL,
- - Intégrer les différents display/affichage.php dans l'objet PHP Map,
- - Retirer Bing,
- - - Retirer les Avatars/Fonds Aléatoires,
- - - Implémenter des Avatars/Fonds par défaults,
- - Séparer Attaque Base/Magique et idem avec Défense/Résistance,
- Style :
- - Patch CSS Décalage Dialog,
- - Patch le footer,
- - Refaire l'intégralité du CSS,
- - Refaire le Menu,
- JS :
- - Afficher Coord des Monsters Captured dans Combat,
- - Faire la partie JS du changement d'équipement,
- Site :
- - Page de présentation du site (Inscription),
- - Mettre des CGU ou un truc légal,
- - Page Profil,
- - Mettre en place Top/Classement
- Jeu :
- - Système d'Actions Personnages,
- - Amélioration du système de Faction,
- - Réalisation du système de Classe,
- - Intégration Hiérarchie Utilisateur,
- - Intégration Système de Ban,
- - Ajouter une "fouille" avant de faire apparaitre des items,
- - Refonte des objets / équipements,
- - - Retirer les LVL Objets -> Passage en Type,
- - - Refonte Inventaire,
- - Refonte des LVL,
- - Refonte des combats,
- - Refonte des déplacements MAP (Retrait du système de TP, système de direction pour les déplacements),
- - Mécanique de Gains d'Argent lors d'une capture de monstre,
- - Mécanique de monstre Innofensif pour X heure une fois capturé,
- - Ajouter Fonction JS SoinsMonster,
- - Ajouts de Logs persistant sur les maps,
- - Refonte de la Map,
- - Intégration des Pouvoirs,
- - Système de Code/Bonus,
- - Ajouter des effets Personnage,
- - Système d'Amis,
- - Intégration Bâtiments,
- - - Forge,
- - - Marché,
- - - Cachette,

# Todo Patch :
- PHP : Déplacement Map : Non affichage de l'origine de déplacement quand refresh si Monster.
- PHP : Bug déplacement (téléporation) lors de changement personnage (Au final c'est pas un bug mais une "feature") -> À encadrer.

## Fait :
- Récupération du projet,
- Ajouts Commentaires,
- Mise à jours des crédits,
- Hash des MDP en BDD,
- Refonte Form Choix Faction,
- Refonte Inscription/Connexion FRONT,
- Refonte Inscription/Connexion BACK,
- Refonte Form Création Personnage, [À Terminer CSS]
- Refonte Form Choix Personnage, [À Terminer CSS]
- Sécurisation MDP Session,
- Refonte de Session.php,
- Ajouter Sécurité sur l'idPersonnage User,
- Mise à jours Boolen User Admin -> TypeUser,
- Mise à jours TypePersonnage,
- Mise à jours TypeMonsters,
- Uniformisation Variable et BDD,
- Refonte Class Objet -> Efficacite,
- Patch PHP/JS Multiples,
- Zone Non-PVP et Vérification Attaque,
- Refonte DisplayHTML Monster/Personnage - Patch CSS,
- Patch PHP Divers.