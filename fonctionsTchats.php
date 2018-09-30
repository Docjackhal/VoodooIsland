<?php

//Renvoi l'histoire de tous les canaux de la region et du personnage, du plus vieux au plus récent
function getHistoriquesTchats($mysqli,$IDHeros,$IDRegion,$dateArriveeRegion,$possedeRadio,$IDPartie)
{
	global $PT;

	$historique = array();
	$limiteTchat = HISTORIQUE_TCHAT;
	$canauxAutorises = array("Partie","Region_".$IDRegion);
	if($possedeRadio)
		$canauxAutorises[] = "Radio";

	$destinatairesAutorises = array("Tous","Heros_".$IDHeros);

	foreach($canauxAutorises as $canal)
	{		
		$requete = "SELECT * FROM ".$PT."tchats WHERE IDPartie = ".$IDPartie." AND Canal = '".$canal."' AND Destinataires IN ('".join("','",$destinatairesAutorises)."') ORDER BY TimestampEnvoie DESC LIMIT ".$limiteTchat."";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Requête invalide (getHistoriquesTchats): '.$requete . mysqli_error($mysqli));


		$canal = ($canal == "Region_".$IDRegion) ? "Region" : $canal;

		$messagesDansCanal = array();
		while($message = mysqli_fetch_assoc($retour))
		{
			//die($message["TimestampEnvoie"]);
			if($canal != "Region" || $message["TimestampEnvoie"] >= strtotime($dateArriveeRegion))
				$messagesDansCanal[] = $message;
		}

		$historique[$canal] = $messagesDansCanal;
	}

	//Inversion de l'ordre des messages, du plus vieux au plus récent
	function cmp($a, $b)
	{
	    return $a["TimestampEnvoie"]>$b["TimestampEnvoie"];
	}
	foreach($historique as $canal => $messagesDansCanal)
	{
		usort($messagesDansCanal,cmp);
		$historique[$canal] = $messagesDansCanal;
	}


	return $historique;
}

//Renvoi les nouveaux messages pour un joueur depuis sa derniere update , sans limite d'historique, dans l'ordre du plus vieux au plus récent
function getNouveauxMessagesTchats($mysqli,$IDHeros,$IDRegion,$possedeRadio,$IDPartie,$dateDerniereUpdate)
{
	global $PT;

	$nouveauxMessages = array();
	$canauxAutorises = array("Partie","Region_".$IDRegion);
	if($possedeRadio)
		$canauxAutorises[] = "Radio";

	$destinatairesAutorises = array("Tous","Heros_".$IDHeros);

	$requete = "SELECT * FROM ".$PT."tchats WHERE IDPartie = ".$IDPartie." AND Canal IN ('".join("','",$canauxAutorises)."') AND Destinataires IN ('".join("','",$destinatairesAutorises)."') AND TimestampEnvoie >= '".$dateDerniereUpdate."' AND Auteur != 'Heros_".$IDHeros."' ORDER BY TimestampEnvoie ASC";

	//die($requete);
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide (getNouveauxMessagesTchats): '.$requete . mysqli_error($mysqli));

	while($message = mysqli_fetch_assoc($retour))
		$nouveauxMessages[] = $message;

	return $nouveauxMessages;
}

function getMessage($mysqli,$IDMessage)
{
	global $PT;

	$requete = "SELECT * FROM ".$PT."tchats WHERE ID = ".$IDMessage;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide (getMessage): '.$requete . mysqli_error($mysqli));

	return (mysqli_num_rows($retour) > 0) ? mysqli_fetch_assoc($retour) : null;
}

function genererTchat($canal)
{
	$content = "";
	if(isset($_SESSION['Tchats'][$canal]))
	{
		if(!empty($_SESSION['Tchats'][$canal]))
		{
			$content .= "<div class='enssemble_message' id='enssemble_message_".$canal."'>";
			//for($i = 0;$i<=10;$i++)
			foreach($_SESSION['Tchats'][$canal] as $message)
				$content .= genererBlocMessage($message);
			
			$content .= "</div>";
		}
		else
			$content .= "<div class='enssemble_message' id='enssemble_message_".$canal."'><div class='entree_tchat'>".lang("AucunMessage")."</div></div>";


		// Bloc poster message
		if(($canal != "Radio" || $_SESSION["AccesTchatRadio"]) && $canal != "Partie")
		{
			$content .= "<div class='bloc_envoi_message' id='bloc_envoi_message_".$canal."'>";
				$content .= "<input type='hidden' name='idCanal' value='".$canal."'></input>";
				$content .= "<textarea placeholder='".lang("EcrireNouveauMessage")."' type='text' name='message' maxlength='255' id='zone_envoi_message_".$canal."'></textarea>";
				$content .= "<div class='submit' onclick='envoyerMessage(\"".$canal."\");'>Envoyer</div>";
			$content .= "</div>";
		}
	}
	else
	{
		if($canal == "Radio")
		{
			$content .= "<div class='enssemble_message' id='enssemble_message_".$canal."'><div class='entree_tchat'><i>".lang("SilenceRadio")."</i></div></div>";

			$content .= "<div class='bloc_envoi_message' id='bloc_envoi_message_".$canal."'>";
				$content .= "<div class='red messageRemplaceEnvoie'>".lang("RadioPourUtiliserCanal")."</div>";
			$content .= "</div>";
		}
		else
			$content .= "<div class='enssemble_message' id='enssemble_message_".$canal."'><div class='entree_tchat'>".lang("AucunMessage")."</div></div>";
	}
	return $content;
}

//Renvoi une div contenant un message tchat
function genererBlocMessage($message,$estAdmin=false)
{
	$auteur = $message['Auteur'];
	$auteurAffiche = $auteur;
	$analyse = explode("_",$auteur);
	$messageAffiche = $message['Message'];
	if($analyse[0] == "Heros")
	{
		$IDHeros = $analyse[1];
		$auteurAffiche = $_SESSION["Heros"][$IDHeros]["Prenom"];
	}
	else if($auteur == "System")
	{
		$auteurAffiche = "";

		//Analyse du texte pour appliquer les fragments de langue et les variables
		$analyse = explode("#",$message["Message"]);
		$libele = $analyse[0];

		$messageAffiche = lang($libele);
		for($i=1;$i<count($analyse);$i++)
		{
			$variable = explode(":",$analyse[$i]);
			$texte = (libeleLangExiste($variable[1])) ? lang($variable[1]) : $variable[1];
			$messageAffiche = str_replace($variable[0], $texte, $messageAffiche);
		}
	}

	$content = "";
	$content .= "<div id='bloc_message_".$message["ID"]."' class='entree_tchat ".genererClasseMessageTchat($message,$estAdmin)."'>";
		$content .= "<span class='tchat_date'>(C".$message["IDCycle"].")".substr($message['DateEnvoie'],11).": </span>";
		$content .= "<span class='tchat_auteur'>".$auteurAffiche.": </span>";
		$content .= "<span class='tchat_message'>".$messageAffiche."</span>";
	$content .= "</div>";	
	return $content;
}

//Renvoi la liste des classes de mise en page pour un message de tchat
function genererClasseMessageTchat($message,$estAdmin)
{
	$listeClasse = "";
	$auteur = $message["Auteur"];
	if($auteur == "System")
		$listeClasse .= "messageSystem";
	else if($auteur == "Admin")
		$listeClasse .= "messageAdmin";
	else
	{
		$analyse = explode("_",$auteur);
		if($analyse[0] == "Heros")
		{
			$IDHeros = $analyse[1];
			if(!$estAdmin && $IDHeros == $_SESSION["IDPersonnage"])
				$listeClasse .= "messageDeSoi";
		}
	}

	return $listeClasse;
}

//Ajoute un nouveau message en bdd et le retourne en format html
function envoyerMessage($mysqli,$canal,$auteur,$message,$IDPartie,$IDCycle,$destinataires)
{
	global $PT;

	$date = date_create();
	$currentTimestamp = date_timestamp_get($date);

	$requete = "INSERT INTO ".$PT."tchats (Auteur,IDPartie,IDCycle,Message,Canal,DateEnvoie,TimestampEnvoie,Destinataires) VALUES ('".$auteur."','".$IDPartie."','".$IDCycle."','".$message."','".$canal."',NOW(),".$currentTimestamp.",'".$destinataires."')";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide (getNouveauxMessagesTchats): '.$requete . mysqli_error($mysqli));

	$IDMessage = mysqli_insert_id($mysqli);
	return getMessage($mysqli,$IDMessage);
}

//Envoi un message system sur la partie en cours sur le cycle en cours et le retourne en format html
function envoyerMessageSystem($mysqli,$canal,$fragmentLangue,$variablesLangue,$destinataires = "Tous")
{
	global $PT;

	//Mise en forme du message pour intégrer le systeme de tradution
	$message = $fragmentLangue;
	foreach($variablesLangue as $variable=>$value)
		$message.="#".$variable.":".$value;

	return envoyerMessage($mysqli,$canal,"System",$message,$_SESSION["IDPartieEnCours"],getIDCycleActuel(),$destinataires);
}

function adminGetDerniersMessagesTchat($mysqli,$IDPartie,$tailleHistorique)
{
	global $PT;

	$historique = array();
	$canaux = array("Partie","Radio","Region_1","Region_2","Region_3","Region_4","Region_5","Region_6","Region_7","Region_8","Region_9","Region_10");

	foreach($canaux as $canal)
	{		
		$requete = "SELECT * FROM ".$PT."tchats WHERE IDPartie = ".$IDPartie." AND Canal = '".$canal."' ORDER BY DateEnvoie DESC LIMIT ".$tailleHistorique."";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Requête invalide (adminGetDerniersMessagesTchat): '.$requete . mysqli_error($mysqli));

		$messagesDansCanal = array();
		while($message = mysqli_fetch_assoc($retour))
		{
			$message["Timestamp"] = strtotime($message["DateEnvoie"]);
			$message["HTML"] = genererBlocMessage($message,true);
			$messagesDansCanal[] = $message;
		}

		$historique[$canal] = $messagesDansCanal;
	}

	//Inversion de l'ordre des messages, du plus vieux au plus récent
	function cmp($a, $b)
	{
	    return $a["Timestamp"]>$b["Timestamp"];
	}
	foreach($historique as $canal => $messagesDansCanal)
	{
		usort($messagesDansCanal,cmp);
		$historique[$canal] = $messagesDansCanal;
	}

	return $historique;
}



?>