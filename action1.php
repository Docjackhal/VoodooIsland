<?php
switch($idAction)
{
	case 10: // Allumer le feu d'un campement (bois ou silex)
	{
		$IDItemUtilise = $_POST["IDItemUtilise"];
		$IDCampement = getIDLieuDeTypeDansRegion(3);
		if($IDCampement > -1)
		{
			$campement = getLieuDansRegion($IDCampement);
			$combustible = getItem($IDItemUtilise,"Inventaire");
			if($combustible != null)
			{
				if($campement["Parametres"]["NiveauFeu"] == 0)
				{
					$cout = COUT_ALLUMER_FEU;
					if($_SESSION["PaActuel"] >= $cout)
					{
						//Tout est ok
						updateMyCarac($mysqli,"Pa",-$cout);

						$_SESSION["PopupEvenement"] = array();
						$chancesSucces = ($IDItemUtilise == 28) ? CHANCE_ALLUMER_FEU_BOIS : CHANCE_ALLUMER_FEU_SILEX;
						$rand = mt_rand(1,100);
						if($rand <= $chancesSucces)
						{
							//Réussite
							$_SESSION["PopupEvenement"]["Titre"] = lang("Action_10_ReussiteAllumerFeu_Titre");
							$_SESSION["PopupEvenement"]["Message"] = lang("Action_10_ReussiteAllumerFeu_Description");

							$requete = "UPDATE ".$PT."parametresCampement SET NiveauFeu = 1 WHERE IDCampement = ".$IDCampement;
							$retour = mysqli_query($mysqli,$requete);
								if (!$retour) die('Requête invalide (Allumer feu) : ' . mysqli_error());
						}
						else
						{
							//Echec
							$_SESSION["PopupEvenement"]["Titre"] = lang("Action_10_EchecAllumerFeu_Titre");
							$_SESSION["PopupEvenement"]["Message"] = lang("Action_10_EchecAllumerFeu_Description");
						}

						//Perte de l'item
						supprimerItem($mysqli,$combustible["ID"]);
						$_SESSION["PopupEvenement"]["PertesItems"][] = $IDItemUtilise;	

						mysqli_commit($mysqli);	
					}	
					else
						$_SESSION["Message"] = "<span class='red'>".lang("ErreurAPPourAction")."</span>";					
				}
				else
					$_SESSION["Message"] = "<span class='red'>".lang("Lieu_3_ErreurFeuDejaAllume")."</span>";
			}
			else
				$_SESSION["Message"] = "<span class='red'>".lang("ErreurObjetPourAction")."</span>";
		}
		else
			$_SESSION["Message"] = "<span class='red'>Erreur : Campement introuvable.</span>";
	}
	break;
	case 11: // Ajouter du bois dans le feu
	{
		$IDCampement = getIDLieuDeTypeDansRegion(3);
		if($IDCampement > -1)
		{
			$campement = getLieuDansRegion($IDCampement);
			$combustible = getItem(28,"Inventaire");
			if($combustible != null)
			{
				if($campement["Parametres"]["NiveauFeu"] == 1)
				{
					$stockMax = STOCK_BUCHE_MAX_FEU;
					if($campement["Parametres"]["NiveauFeu"] < STOCK_BUCHE_MAX_FEU)
					{
						//Tout est ok
						$_SESSION["PopupEvenement"] = array();

						//Réussite
						$_SESSION["PopupEvenement"]["Titre"] = lang("Action_11_FeuAlimente_Titre");
						$_SESSION["PopupEvenement"]["Message"] = lang("Action_11_FeuAlimente_Description");

						$requete = "UPDATE ".$PT."parametresCampement SET StockBuche = StockBuche+1 WHERE IDCampement = ".$IDCampement;
						$retour = mysqli_query($mysqli,$requete);
							if (!$retour) die('Requête invalide (Alimenter feu) : ' . mysqli_error());
	
						//Perte de l'item
						supprimerItem($mysqli,$combustible["ID"]);
						$_SESSION["PopupEvenement"]["PertesItems"][] = 28;	

						mysqli_commit($mysqli);	
					}	
					else
						$_SESSION["Message"] = "<span class='red'>".lang("Lieu_3_StockBucheDejaMax")."</span>";					
				}
				else
					$_SESSION["Message"] = "<span class='red'>".lang("Lieu_3_FeuEteint")."</span>";
			}
			else
				$_SESSION["Message"] = "<span class='red'>".lang("ErreurObjetPourAction")."</span>";
		}
		else
			$_SESSION["Message"] = "<span class='red'>Erreur : Campement introuvable.</span>";
	}
	break;
	
}
?>