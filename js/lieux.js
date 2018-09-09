function genererContenuInfosLieu(lieu)
{
	var IDTypeLieu = parseInt(lieu.IDTypeLieu);
	var contenu = "";

	switch(IDTypeLieu)
	{
		case 1://Zone de peche
		{
			var nbPoissons = lieu.Parametres.NbPoissons;
			var density = 0;
			var classe='red';
			if(nbPoissons > 20)
			{
				density = 3;
				classe = "green";
			}
			else if(nbPoissons > 10)
			{
				density = 2;
				classe = "green";
			}
			else if(nbPoissons > 0)
			{
				density = 1;
				classe = "orange";
			}			

			contenu += "<div>"+(getLang("Lieu_"+IDTypeLieu+"_Contenu1")).replace("%Density%","<b class='"+classe+"'>"+getLang("Lieu_"+IDTypeLieu+"_Density_"+density)+"</b>")+"</div>"			
		}
		break;
		case 2://Emplacement de campement
		{
			//Objets requis: 3,31,32
			$pellePossedee = (nbItemsDansInventaire(3)>0);
			$toilePossedee = (nbItemsDansInventaire(31)>0);
			$marmittePossedee = (nbItemsDansInventaire(32)>0);

			if(lieu.IDParametrageLieu == -1)
			{
				if($pellePossedee)
					contenu += "<div><span class='green'>"+getLang("Lieu_2_PelleObtenue")+"</span></div>";
				else
					contenu += "<div><span class='red'>"+getLang("Lieu_2_PelleManquante")+"</span></div>";
			}
			else if(lieu.IDParametrageLieu == -2) // Emplacement creusé
			{
				if(game.variables[4] == undefined || game.variables[4] == -1) // Toile non posée
				{
					if($toilePossedee)
						contenu += "<div><span class='green'>"+getLang("Lieu_2_ToileObtenue")+"</span></div>";
					else
						contenu += "<div><span class='red'>"+getLang("Lieu_2_ToileManquante")+"</span></div>";
				}
				else
				{
					if($marmittePossedee)
						contenu += "<div><span class='green'>"+getLang("Lieu_2_MarmitteObtenue")+"</span></div>";
					else
						contenu += "<div><span class='red'>"+getLang("Lieu_2_MarmitteManquante")+"</span></div>";
				}			
			}
		}
		break;
		case 3: // Campement
		{
			var niveauFeu = lieu.Parametres.NiveauFeu;
			var stockBuche = lieu.Parametres.StockBuche;
			var stockBucheMax = lieu.Parametres.StockBucheMax;

			if(niveauFeu == 0)
				contenu += "<div class='red'>"+getLang("Lieu_3_FeuEteint")+"</div>";
			else
			{
				if(stockBuche > 0)
					contenu += "<div class='green'>"+getLang("Lieu_3_FeuAllume")+"</div>";
				else
					contenu += "<div class='orange'>"+getLang("Lieu_3_FeuAllumeBientotEteint")+"</div>";
			}

			if(stockBuche == 0)
					contenu += "<div class='red'>"+getLang("Lieu_3_AucuneBuche")+"</div>";
			else if(stockBuche == 1)
				contenu += "<div class='orange'>"+getLang("Lieu_3_UneBuche")+"</div>";
			else
				contenu += "<div class='green'>"+(getLang("Lieu_3_PlusieursBuches").replace("%Number%",stockBuche+"/"+stockBucheMax))+"</div>";

			//Accès inventaire
			contenu += '<div class="divSubmit" onclick="ouvrirPopupInventaireCampement();"><span>'+getLang("Lieu_3_InventaireCampement")+'</span></div>';
		}
		break;
	}

	return contenu;
}

function genererContenuActionLieu(lieu)
{
	var IDTypeLieu = parseInt(lieu.IDTypeLieu);
	var contenu = "";

	switch(IDTypeLieu)
	{
		case 1://Zone de peche
		{
			var nbPoissons = lieu.Parametres.NbPoissons;
			if(nbPoissons>0)
				contenu += genererBoutonAction("pecher",getLang("Lieu_"+IDTypeLieu+"_Pecher")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutPeche+"</div>",6);		
		}
		break;
		case 2: //Emplacement de campement
		{
			//Objets requis: 3,31,32
			$pellePossedee = (nbItemsDansInventaire(3)>0);
			$toilePossedee = (nbItemsDansInventaire(31)>0);
			$marmittePossedee = (nbItemsDansInventaire(32)>0);

			if(lieu.IDParametrageLieu == -1 && $pellePossedee) 
				contenu += genererBoutonAction("creuser",getLang("Lieu_"+IDTypeLieu+"_Creuser")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutInstallation+"</div>",7);
			else if(lieu.IDParametrageLieu == -2 && (game.variables[4] == undefined || game.variables[4] == -1) && $toilePossedee)
				contenu += genererBoutonAction("toiler",getLang("Lieu_"+IDTypeLieu+"_InstallerToile")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutInstallation+"</div>",8);
			else if(game.variables[4] == 1 && $marmittePossedee)
				contenu += genererBoutonAction("marmitter",getLang("Lieu_"+IDTypeLieu+"_InstallerMarmitte")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutInstallation+"</div>",9);
		}
		break;
		case 3: // Campement
		{
			var niveauFeu = lieu.Parametres.NiveauFeu;
			var stockBuche = lieu.Parametres.StockBuche;

			var silexs = nbItemsDansInventaire(29);
			var bois = nbItemsDansInventaire(28);

			if(niveauFeu == 0)
			{
				if(silexs > 0)
					contenu += genererBoutonAction("allumerFeuSilex",getLang("Lieu_"+IDTypeLieu+"_AllumerFeuSilex")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutAllumageFeu+"</div> ("+lieu.Parametres.ChanceAllumerFeuSilex+"%) ",10,"<input type='hidden' name='IDItemUtilise' value='29'/>");
				if(bois > 0)
					contenu += genererBoutonAction("allumerFeuBois",getLang("Lieu_"+IDTypeLieu+"_AllumerFeuBois")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutAllumageFeu+"</div> ("+lieu.Parametres.ChanceAllumerFeuBois+"%) ",10,"<input type='hidden' name='IDItemUtilise' value='30'/>");
				if(silexs == 0 && bois == 0)
					contenu += "<div class='red'>"+getLang("Lieu_3_RienPourAllumerFeu")+"</div>";
			}

			if(stockBuche < 5 && niveauFeu > 0)
			{
				if(bois > 0)
					contenu += genererBoutonAction("ajouterCombustible",getLang("Lieu_"+IDTypeLieu+"_AjouterCombustible")+" <div class='iconeCoutAP'>0</div>",11);
				else
					contenu += "<div class='red'>"+getLang("Lieu_3_PasDeBoisPourFeu")+"</div>";
			}

		}
		break;
	}

	return contenu;
}

/**
Genere un formulaire avec un bouton d'action
nomAction: le nom du formulaire (dois etre unique sur toute la popup lieu)
texte: le texte du bouton
action: l'id de laction cible dans action.php
inputsSub: html a ajouter si besoin d'inputs suplémentaires
*/
function genererBoutonAction(nomAction,texte,action,inputsSup)
{
	var bouton = '<form id='+nomAction+' method="post" action="action.php?action='+action+'">';
		if(inputsSup != undefined)
			bouton += inputsSup;
		bouton +=	'<div class="divSubmit" onclick="document.getElementById(\''+nomAction+'\').submit()"><span>'+texte+'</span></div>';
		bouton +='</form>';

	return bouton;
}