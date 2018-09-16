<?php

// Tout n'est pas encore en fichier de langue: certains textes sont en bdd ou en dur dans le code. A terme, tout texte apparaissant coté client devra se trouver dans ce fichier.
// Ce fichier est actuellement utilisé pour certains textes d'evenements par exemple.

$lang = array();

// ------------------------------- GENERAL ------------------------------------- //

$lang["Aie!"] = "Aïe !";
$lang["Daccord"] = "D'accord !";
$lang["Miam!"] = "Miam !";
$lang["Desole..."] = "Désolé...";
$lang["Flute!"] = "Flûte !";
$lang["Super!"] = "Super !";
$lang["Oui"] = "Oui";
$lang["Non"] = "Non";

// ----------------------------- FIN GENERAL ------------------------------------- //

// ------------------------------- GAME.PHP ------------------------------------- //

$lang["DescriptionConfirmationChangementCycle"] = "Vous allez indiquer au maitre du jeu que vous êtes prêts à passer au prochain cycle. Etes-vous sûrs?";
$lang["DureeCycleConsommable"] = "Obtenu il y à %Number% cycles.";

$lang["Gains:"] = "Gains:";
$lang["Pertes:"] = "Pertes:";
$lang["Inventaire"] = "Inventaire";
$lang["InventaireCampement"] = "Inventaire du campement";

$lang["Tooltip_BarresStats_Pa"] = "Vos Points d'actions. Ils vous permettent d'éffectuer des actions et remontent de %Number% à chaque cycle.";
$lang["Tooltip_BarresStats_Pm"] = "Vos Points de mouvements. Ils vous permettent de vous déplacer sur l'ile et remontent de %Number% à chaque cycle.";
$lang["Tooltip_BarresStats_MP"] = "Votre santé mentale (Mental points). Elle représente votre santé psychique. Une valeur basse pourra entrainer de nombreuses complications.";
$lang["Tooltip_BarresStats_Pv"] = "Votre santé (Points de Vie). Elle représente votre état physique. Une santé basse entrainera de nombreuses complications.";

// ------------------------------- FIN GAME.PHP ------------------------------------- //

// ------------------------------- TCHATS ------------------------------------- //

$lang["Tchat_Partie"] = "Partie";
$lang["Tchat_Region"] = "Local";
$lang["Tchat_Radio"] = "Radio";
$lang["AucunMessage"] = "Aucun message";
$lang["SilenceRadio"] = "Silence radio.";
$lang["RadioPourUtiliserCanal"] = "Il vous faut une radio pour pouvoir utiliser ce canal.";
$lang["EcrireNouveauMessage"] = "Envoyer un nouveau message.";


// ----------------------------- FIN TCHATS ------------------------------------- //


// ------------------------------- EVENEMENTS ------------------------------------- //

$lang["EvenementLieuDecouvert"] = "Lieu découvert: %NomLieu%.";

$lang["Evenement_5_Message_Choix_1_A1"] = "Vous parvenez à éscalader le cocotier avec succès et à décrocher une précieuse noix de coco.";
$lang["Evenement_5_Message_Choix_1_AX"] = "Vous parvenez à éscalader le cocotier avec succès et à décrocher %Number% précieuses noix de coco.";
$lang["Evenement_5_Message_Choix_1_B"] = "En tentant d'escalader le cocotier, vous glissez et tombez lourdement sur le sable, aïe !";

$lang["Evenement_5_Message_Choix_2_A"] = "Vous donnez un coup si puissant qu'une noix de coco se détache et tombe à vos pieds !";
$lang["Evenement_5_Message_Choix_2_B"] = "Vous donnez un coup si puissant qu'une noix de coco se détache et... vous tombe sur la tête ! Le choc vous assome pendant quelques heures.";

$lang["Evenement_5_Message_Choix_3"] = "Jouant la sécurité, vous décidez de repartir bredouille.";

$lang["Evenement_9_Message_Choix_1_A"] = "Tel un ninja, vous pénétrez discretement dans le village. Vous parvenez à atteindre la cage dans lequel %NomJoueur% est enfermé, à l'ouvrir et à vous enfuir tous les deux. Vous venez de vous faire un ami pour la vie.";
$lang["Evenement_9_Message_Choix_1_B"] = "Tel un ninja, vous pénétrez discretement dans le village. Malheuresement, n'est pas ninja qui veut. Vous êtes attrapé par derrière et vous vous retrouvez dans une cage vous aussi. Votre compagnon d'infortune est lui transporté dans une sorte de caverne plus loin. Votre action héroïque vous à mis dans un sacré pétrin.";
$lang["Evenement_9_Message_Choix_2"] = "Vous préferez repartir, laissant votre camarade à son triste sort.";

// ----------------------------  FIN EVENEMENTS ------------------------------------//

// ------------------------------- LIEUX ------------------------------------- //

$lang["Lieu_1_Nom"] = "Banc de poisson";
$lang["Lieu_1_Description"] = "Vous voyez des poissons barbotter à la surface de l'eau. Avec un peu de technique, vous voici façe à une source de nourriture potentielle.";
$lang["Lieu_1_Contenu1"] = "Ce banc de poisson semble %Density%.";
$lang["Lieu_1_Density_0"] = "vide";
$lang["Lieu_1_Density_1"] = "peu rempli";
$lang["Lieu_1_Density_2"] = "moyennement rempli";
$lang["Lieu_1_Density_3"] = "abondant";
$lang["Lieu_1_Pecher"] = "Pêcher";


$lang["Lieu_2_Nom"] = "Emplacement de campement";
$lang["Lieu_2_Description"] = "Un emplacement idéal pour monter un camp de base. Il lui faudrait au moins deux ou trois choses pour être utilisable:Creuser le camp, un abris et de quoi installer une cuisine.";
$lang["Lieu_2_PelleManquante"] = "Vous n'avez pas de quoi creuser.";
$lang["Lieu_2_PelleObtenue"] = "Vous avez de quoi creuser.";
$lang["Lieu_2_ToileManquante"] = "Vous n'avez pas de quoi faire un abris.";
$lang["Lieu_2_ToileObtenue"] = "Vous avez de quoi faire un abris.";
$lang["Lieu_2_MarmitteManquante"] = "Vous n'avez pas de quoi cuisiner.";
$lang["Lieu_2_MarmitteObtenue"] = "Vous avez de quoi cuisiner.";
$lang["Lieu_2_Creuser"] = "Creuser";
$lang["Lieu_2_InstallerToile"] = "Installer l'abris";
$lang["Lieu_2_InstallerMarmitte"] = "Installer la cuisine";

$lang["Lieu_3_Nom"] = "Campement";
$lang["Lieu_3_Description"] = "Votre base stratégique principale sur l'ile. D'ici, vous pouvez vous reposer, alimenter le feu et faire la cuisine. Le feu est d'une importance cruciale. Il vous permet de préparer des plats chauts, vous protège contre les prédateurs et vous réchauffe la nuit. Veillez à ce qu'il soit toujours allumé la nuit et économisez vos combustibles en journée !";
$lang["Lieu_3_FeuEteint"] = "Le feu est éteint.";
$lang["Lieu_3_FeuAllume"] = "Le feu est allumé.";
$lang["Lieu_3_FeuAllumeBientotEteint"] = "Le feu est allumé, mais il va s'éteindre !";
$lang["Lieu_3_AucuneBuche"] = "Il n'y à plus de bûches dans le feu.";
$lang["Lieu_3_UneBuche"] = "Il n'y à plus qu'une bûche dans le feu.";
$lang["Lieu_3_PlusieursBuches"] = "Il y à encore %Number% bûches dans le feu.";
$lang["Lieu_3_AllumerFeuSilex"] = "Allumer le feu (Silex).";
$lang["Lieu_3_AllumerFeuBois"] = "Allumer le feu (Bois).";
$lang["Lieu_3_RienPourAllumerFeu"] = "Vous n'avez rien pour allumer le feu.";
$lang["Lieu_3_ErreurFeuDejaAllume"] = "Le feu est déjà allumé !";
$lang["Lieu_3_AjouterCombustible"] = "Ajouter du bois dans le feu.";
$lang["Lieu_3_PasDeBoisPourFeu"] = "Vous n'avez pas de bois pour le feu.";
$lang["Lieu_3_StockBucheDejaMax"] = "Le feu ne peut pas accueillir plus de bois.";
$lang["Lieu_3_InventaireCampement"] = "Inventaire du campement.";

$lang["Lieu_4_Nom"] = "Epave du Santa Marina";
$lang["Lieu_4_Description"] = "L'épave de votre bateau qui s'est echoué non loin de la plage. Une partie semble entièrement submergée, mais l'avant du bateau semble accessible. Aucune trace de survivants.";

$lang["Lieu_5_Nom"] = "Source d'eau";
$lang["Lieu_5_Description"] = "Une source d'eau pure et potable. A première vue tout du moins.";

$lang["Lieu_6_Nom"] = "Village voodoo";
$lang["Lieu_6_Description"] = "Le village d'une dangereuse tribu Voodoo locale. Vous ne devriez pas être ici.";

$lang["Lieu_7_Nom"] = "Idole voodoo";
$lang["Lieu_7_Description"] = "Une ancienne idole vénérée par les Voodoos.";

$lang["Lieu_8_Nom"] = "Antre de la Bête";
$lang["Lieu_8_Description"] = "Une tanière sombre dont provient une odeur pestilencielle.";

$lang["Lieu_9_Nom"] = "Epave d'avion";
$lang["Lieu_9_Description"] = "L'épave d'un avion qui s'est ecrasé ici il y à des lustres. Elle est là depuis bien plus longtemps que vous, en tout cas.";

$lang["Lieu_10_Nom"] = "Antenne radio";
$lang["Lieu_10_Description"] = "Une ancienne station radio désafectée.";

$lang["Lieu_11_Nom"] = "Autel voodoo";
$lang["Lieu_11_Description"] = "Un autel voodoo dédié à de sombres rituels sacrificiels.";

$lang["Lieu_12_Nom"] = "Coulée de lave";
$lang["Lieu_12_Description"] = "Une ardente coulée de lave s'échappe de la caldéra. Tout ce qui y penetrerai disparaitrait à jamais.";

// ------------------------------- FIN LIEUX ------------------------------------- //

// ------------------------------- ACTIONS ------------------------------------- //

//Pecher
$lang["ErreurAPPourAction"] = "Vous n'avez pas assez de points d'actions pour faire ça.";
$lang["ErreurPMPourAction"] = "Vous n'avez pas assez de PM pour voyager.";
$lang["ErreurObjetPourAction"] = "Vous n'avez pas l'objet nécéssaire pour effectuer cette action.";

$lang["Action_2_MessageTchat"] = "%Login% viens d'arriver dans la région.";

$lang["Action_6_PecheInfructueuse_Titre"] = "Pêche infructueuse";
$lang["Action_6_PecheInfructueuse_Description"] = "Malheuresement, vous n'avez rien attrapé ce coup-ci. Personne ne vous en voudra (sauf vos compagnons peut-être).";
$lang["Action_6_PechePoissonCru_Titre"] = "Belle prise !";
$lang["Action_6_PechePoissonCru_Description"] = "Après un glorieux combat, vous sortez de l'eau un beau poisson !";
$lang["Action_6_PecheTortue_Titre"] = "Une carapace hors de l'eau !";
$lang["Action_6_PecheTortue_Description"] = "Vous sortez de l'eau une tortue de mer ! C'est encore mieux qu'un poisson, non?";

$lang["Action_7_Creuser_Titre"] = "Travaux de terrassement";
$lang["Action_7_Creuser_Description"] = "Vous creusez le sable afin de préparer l'installation éventuelle d'un futur campement. Il vous faut maintenant de quoi installer un abri.";
$lang["Action_7_MessageTchat"] = "%Login% creuse le sable avec sa pelle afin de préparer un éventuel campement.";

$lang["Action_8_Creuser_Titre"] = "Un abri rudimentaire";
$lang["Action_8_Creuser_Description"] = "Vous bricolez un abri a partir d'une vieille toile déchirée. Ce n'est pas le grand luxe, mais c'est toujours mieux que rien.";
$lang["Action_8_MessageTchat"] = "%Login% installe un abris à l'aide d'une vieille toile. Vous voici avec un début de campement.";

$lang["Action_9_Creuser_Titre"] = "Premier objectif accompli !";
$lang["Action_9_Creuser_Description"] = "Vous avez maintenatn un campement opérationnel! Vous avez accompli le premier pas vers votre survie et vos chances de vous échapper de cette île un jour augmentent.. Enfin, au moins elles ne sont plus nulles.";
$lang["Action_9_MessageTchat"] = "%Login% installe une marmitte dans le campement, qui est désormais opérationnel.";

$lang["Action_10_ReussiteAllumerFeu_Titre"] = "3, 2, 1, allumage !";
$lang["Action_10_ReussiteAllumerFeu_Description"] = "Vous avez réussi à allumer le feu ! Vos camarades vous dédierons une danse de la joie plus tard.";
$lang["Action_10_EchecAllumerFeu_Titre"] = "Echec cuisant";
$lang["Action_10_EchecAllumerFeu_Description"] = "Malheuresement, vous n'avez pas réussi à allumer le feu cette fois-ci. Peut-être avez-vous manqué de chance? Ou de talent.";
$lang["Action_10_MessageTchatReussite"] = "%Login% allume le feu avec succès dans le campement.";
$lang["Action_10_MessageTchatEchec"] = "%Login% essaye d'allumer le feu dans le campement, en vain. Bouh !";

$lang["Action_11_FeuAlimente_Titre"] = "Entretien du feu";
$lang["Action_11_FeuAlimente_Description"] = "Vous déposez une bûche dans le feu. Combien de vie avez-vous sauvées aujourd'hui en réalisant ce geste héroïque?";
$lang["Action_11_MessageTchat"] = "%Login% dépose une bûche dans le feu.";


// ------------------------------- FIN ACTIONS ------------------------------------- //

// ------------------------------- ITEMS ------------------------------------- //

$lang["Item_1_Nom"] = "couteau";
$lang["Item_1_Description"] = "Une arme tranchante augmentant vos dégâts de 3 lors des affrontements contre les prédateurs ou d'autres joueurs. Le couteau est également utile pour la cuisine ou d'autres actions, comme dépecer des carcasses d'animaux.";
$lang["Item_2_Nom"] = "harpon";
$lang["Item_2_Description"] = "Une arme perçante augmentant vos dégâts de 3 lors des affrontements contre les prédateurs ou d'autres joueurs. Le harpon augmente également fortement les résultats de la pêche.";
$lang["Item_3_Nom"] = "pelle";
$lang["Item_3_Description"] = "La pelle vous permet de déterrer des objets sur les plages. Augmente également les dégâts de 1 lors des affrontements.";
$lang["Item_4_Nom"] = "poisson cru";
$lang["Item_4_Description"] = "Un poisson fraîchement pêché. Peut être cuisiné au campement. Attention, manger cru peut rendre malade !";
$lang["Item_5_Nom"] = "poisson cuit";
$lang["Item_5_Description"] = "Un poisson cuit a point, il semble délicieux.";
$lang["Item_6_Nom"] = "poisson brûlé";
$lang["Item_6_Description"] = "Un poisson totalement brûlé, oeuvre d'un piètre cuisinier. Ne présente pas de risque quand on le mange, mais rassasie beaucoup moins.";
$lang["Item_7_Nom"] = "poisson succulent";
$lang["Item_7_Description"] = "Un poisson extraordinairement bien cuit. Les compliments au chef !";
$lang["Item_8_Nom"] = "poisson pourri";
$lang["Item_8_Description"] = "Ce poisson dégage une odeur particulièrement pestilentielle. Le manger est fortement déconseillé. Peut empoisonner une source d'eau pure.";
$lang["Item_9_Nom"] = "tortue de mer";
$lang["Item_9_Description"] = "Peut-être cuisinée par un chef cuisiner équipé d'un couteau pour obtenir de bons steaks de tortue. Immangeable en l'état.";
$lang["Item_10_Nom"] = "steack de tortue";
$lang["Item_10_Description"] = "Un steak de tortue cuisiné par un chef cuisinier. Plat typiquement local !";
$lang["Item_11_Nom"] = "tortue (pourrie)";
$lang["Item_11_Description"] = "Cette tortue est totalement avariée. Son cadavre pourrait empoisonner une source d'eau pure s'il était jeté dedans.";
$lang["Item_12_Nom"] = "trousse de secours";
$lang["Item_12_Description"] = "Un kit médical de premier secours permettant de soigner les blessures classiques. A privilégier par les médecins.";
$lang["Item_13_Nom"] = "bouteille d'eau";
$lang["Item_13_Description"] = "Une bouteille remplie d'eau, idéal pour se désaltérer. Prenez tout de même garde à sa provenance. La consommer vous permettra de récupérer une bouteille vide.";
$lang["Item_14_Nom"] = "bouteille vide";
$lang["Item_14_Description"] = "Une bouteille vide. Il est possible de la remplir d'eau à une source.";
$lang["Item_15_Nom"] = "bouteille d'eau";
$lang["Item_15_Description"] = "Une bouteille remplie d'eau, idéal pour se désaltérer. Prenez tout de même garde à sa provenance. La consommer vous permettra de récupérer une bouteille vide.";
$lang["Item_16_Nom"] = "bouteille de vodka";
$lang["Item_16_Description"] = "Une bouteille de vodka en provenance de Russie. Dangereux pour les non-initiés.";
$lang["Item_17_Nom"] = "boite de conserve";
$lang["Item_17_Description"] = "Une boîte de conserve contenant une portion à cuisiner. 100% adaptée aux végétariens. Nécessite d'être cuisinée.";
$lang["Item_18_Nom"] = "portion cuisinée";
$lang["Item_18_Description"] = "Un plat issu d'une boite de conserve, préparée avec amour et adapté aux végétariens.";
$lang["Item_19_Nom"] = "portion cuisinée périmée";
$lang["Item_19_Description"] = "Cette portion dégage une odeur pestilentielle. Peut rendre malade en la mangeant, mais ne risque pas d'empoisonner une source d'eau.";
$lang["Item_20_Nom"] = "fruit";
$lang["Item_20_Description"] = "Un fruit. Miam !";
$lang["Item_21_Nom"] = "noix de coco";
$lang["Item_21_Description"] = "Une noix de coco locale. A manger et à boire en un seul repas !";
$lang["Item_22_Nom"] = "morceau de viande";
$lang["Item_22_Description"] = "Un morceau de viande cru. Peut-être cuisiné. Immangeable en l'état.";
$lang["Item_23_Nom"] = "steack cuisiné";
$lang["Item_23_Description"] = "Un bon steak grillé. Miam !";
$lang["Item_24_Nom"] = "steack cuisiné périmé";
$lang["Item_24_Description"] = "Ce steak dégage une forte odeur de pourri. Peut rendre malade et peut empoisonner une source d'eau pure.";
$lang["Item_25_Nom"] = "kit d'analyse hydraulique";
$lang["Item_25_Description"] = "Un kit d'analyse permettant de détecter la présente de poison ou de microbes dans une source d'eau. Réutilisable tant qu'il n'est pas contaminé.";
$lang["Item_26_Nom"] = "kit d'analyse d'eau contaminé";
$lang["Item_26_Description"] = "Ce kit d'analyse à été contaminé par une source impure. L'utiliser à nouveau risque de contaminer les sources analysées.";
$lang["Item_27_Nom"] = "statuette voodoo";
$lang["Item_27_Description"] = "Une étrange statuette en bois.";
$lang["Item_28_Nom"] = "bois";
$lang["Item_28_Description"] = "Une branche de bois, qui peut servir pour alimenter un feu ou assembler des objets.";
$lang["Item_29_Nom"] = "silex";
$lang["Item_29_Description"] = "Un morceau de silex, excellent pour allumer un feu.";
$lang["Item_30_Nom"] = "radio";
$lang["Item_30_Description"] = "Une radio, permettant de dialoguer avec d'autres radios sur l'ile. Une antenne radio doit être opérationnel pour que ce canal fonctionne.";
$lang["Item_31_Nom"] = "toile déchirée";
$lang["Item_31_Description"] = "Une grande bâche en toile un peu déchirée. Idéale pour construire un campement primitif.";
$lang["Item_32_Nom"] = "vieille marmitte";
$lang["Item_32_Description"] = "Une vieille marmitte qui, une fois installée dans un campement, permettra de préparer à manger.";
$lang["Item_33_Nom"] = "clé à molette";
$lang["Item_33_Description"] = "Une clé à molette. Augmente les dégats de 1 lors d'affrontement et augmente l’efficacité des réparations.";
$lang["Item_34_Nom"] = "batterie";
$lang["Item_34_Description"] = "Une batterie électrique. Elle semble en état de marche.";
$lang["Item_35_Nom"] = "caméra";
$lang["Item_35_Description"] = "Une caméra ayant souffert, mais qui semble encore en état de fonctionnement.";
$lang["Item_36_Nom"] = "peau de bête";
$lang["Item_36_Description"] = "Une peau de bête permettant d'assembler des objets.";
$lang["Item_37_Nom"] = "palmes";
$lang["Item_37_Description"] = "Une paire de palme permettant d'explorer des zones inaccessibles, comme la partie submergée d'une épave de bateau.";
$lang["Item_38_Nom"] = "sonde";
$lang["Item_38_Description"] = "Une sonde météorologique. Peut être combinée pour créer un ballon météo.";
$lang["Item_39_Nom"] = "ballon météo";
$lang["Item_39_Description"] = "Un ballon météorologique permettant d'améliorer l'antenne radio.";
$lang["Item_40_Nom"] = "steack de tortue pourri";
$lang["Item_40_Description"] = "Un steak de tortue à l'odeur pestilentiel. Peut rendre malade et peut contaminer une source d'eau pure.";



// ------------------------------- FIN ITEMS ------------------------------------- //

?>