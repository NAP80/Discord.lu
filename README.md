# Discord.lu
Refonte du projet scolaire https://github.com/EnseignantLapro/Covid.
En ligne sur http://game.discord.lu/

# Bugs :
En cas de problème rencontré, n'hésitez pas à le signaler ici, de préférence avec une capture d'écrans : https://github.com/NAP80/Discord.lu/issues

# Avancement :

## En cours :
- Normalisation SQL,
- Mise en ligne du Site,
- Uniformisation Code,
- Refonte Entitée/Creature/Personnage,
- Système d'Actions Personnages,

## Todo List :
- Dev :
- - Refaire le délire des "Valeurs",
- - Vérification JS Inscription FRONT,
- - Intégration envoie de mail et confirmation inscription,
- - Ajout Boite de Dialog pour RepMSG,
- - Intégration Panel Admin,
- - Intégrer les différents display/affichage.php dans l'objet PHP Map,
- - Séparer Attaque Physique/Distance et idem avec Défense/Résistance,
- - Regex pour les MDP/Mails/Pseudo,
- Style :
- - Patch CSS Décalage Dialog,
- - Patch le footer,
- - Refaire l'intégralité du CSS,
- - Refaire le Menu (Y compris Boutton Déco),
- JS :
- - Afficher Coord des Creatures Captured dans Combat,
- Site :
- - Page de présentation du site (Inscription),
- - Mettre des CGU ou un truc légal,
- - Page Profil,
- - Intégerer Vie/Attaque/Défense au Classement,
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
- - Refonte des combats,
- - Refonte des déplacements MAP (Retrait du système de TP, système de direction pour les déplacements),
- - Mécanique de Gains d'Argent lors d'une capture de créature,
- - Mécanique de créature Innofensif pour X heure une fois capturé,
- - Ajouter Fonction JS SoinsCreature,
- - Ajouts de Logs persistant sur les maps,
- - Refonte de la Map,
- - Intégration des Armes à Distances,
- - Intégration des Capacités Spécials,
- - Système de Code/Bonus,
- - Ajouter des effets Personnage,
- - Système d'Amis,
- - Intégration Bâtiments,
- - - Forge,
- - - Marché,
- - - Cachette,

# Todo Patch :
- PHP : Déplacement Map : Non affichage de l'origine de déplacement quand refresh si Creature.
- PHP : Bug déplacement (téléporation) lors de changement personnage (Au final c'est pas un bug mais une "feature") -> À encadrer.
- ??? : Quand Kill d'un personnage, les personnage présent au même endroit sont aussi Kill.
- ??? : Quand on refresh on voit les personnages du même user présent sur une map, mais quand on se déplace, non.
- PHP : Quand on capture un animal, on se déplace une fois la map clear.
- PHP : Refaire au propre la génération de typeCréature.
- PHP : Prévoire cas de figure où joueur est sur une idMap non Existante.

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
- Mise à jours TypeCreatures,
- Uniformisation Variable et BDD,
- Refonte Class Objet -> Efficacite,
- Patch PHP/JS Multiples,
- Zone Non-PVP et Vérification Attaque,
- Refonte DisplayHTML Creature/Personnage - Patch CSS,
- Patch PHP Divers,
- Mise en ligne sur game.Discord.lu,
- Refonte TypeMap,
- Refonte création Map,
- Patch Image Map,
- Retirer Bing et ses Avatars/Fonds Aléatoires,
- Implémenter des Avatars/Fonds par défaults,
- Refonte d'une partie des API et JS,
- Patch des erreurs JS originels du changement d'équipement,
- Sécurisation Injection SQL,
- Mettre en place correctement le bouton Déco,
- Mettre en place Classement.