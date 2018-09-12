<?php

//Renvoi l'histoire de tous les canaux de la region et du personnage, du plus récent au plus vieux (a inverser pour affichage)
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
		$requete = "SELECT * FROM ".$PT."tchats WHERE IDPartie = ".$IDPartie." AND Canal = '".$canal."' AND Destinataires IN ('".join("','",$destinatairesAutorises)."') ORDER BY DateEnvoie DESC LIMIT ".$limiteTchat."";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Requête invalide (getHistoriquesTchats): '.$requete . mysqli_error($mysqli));


		$canal = ($canal == "Region_".$IDRegion) ? "Region" : $canal;

		$messagesDansCanal = array();
		while($message = mysqli_fetch_assoc($retour))
		{
			if($canal != "Region" || strtotime($message["DateEnvoie"]) >= strtotime($dateArriveeRegion))
				$messagesDansCanal[] = $message;
		}

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

	$requete = "SELECT * FROM ".$PT."tchats WHERE IDPartie = ".$IDPartie." AND Canal IN ('".join("','",$canauxAutorises)."') AND Destinataires IN ('".join("','",$destinatairesAutorises)."') AND DateEnvoie > ".$dateDerniereUpdate." ORDER BY DateEnvoie ASC";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide (getNouveauxMessagesTchats): '.$requete . mysqli_error($mysqli));

	while($message = mysqli_fetch_assoc($retour))
		$nouveauxMessages[] = $message;

	return $nouveauxMessages;
}

function genererTchat($canal)
{
	$content = "";
	if(!empty($_SESSION['Tchats'][$canal]))
	{
		$content .= "<div class='enssemble_message' id='enssemble_message_".$canal."'>";
		foreach($_SESSION['Tchats'][$canal] as $message)
		{
			$content .= "<div class='entree_tchat'>";
				$content .= "<span class='tchat_date'>".substr($message['DateEnvoie'],11).": </span>";
				$content .= "<span class='tchat_auteur'>".$message['Auteur'].": </span>";
				$content .= "<span class='tchat_message'>".$message['Message']."</span>";
			$content .= "</div>";
		}
		$content .= "</div>";

		// Bloc poster message
		if(($canal != "Radio" || $_SESSION["AccesTchatRadio"]) && $canal != "Partie")
		{
			$content .= "<div class='bloc_envoi_message' id='bloc_envoi_message_".$canal."'>";
				$content .= "<input type='hidden' name='idCanal' value='".$canal."'></input>";
				$content .= "<textarea placeholder='".lang("EcrireNouveauMessage")."' type='text' name='message' maxlength='255' id='zone_envoi_message_".$canal."'></textarea>";
				$content .= "<div class='submit' onclick='envoyerMessage(".$canal.")'>Envoyer</div>";
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



?>