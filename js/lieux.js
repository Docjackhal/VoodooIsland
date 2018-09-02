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
			if(nbPoissons > 20)
				density = 3;
			else if(nbPoissons > 10)
				density = 2;
			else if(nbPoissons > 0)
				density = 1;

			contenu += "<div>"+(getLang("Lieu_"+IDTypeLieu+"_Contenu1")).replace("%Density%","<b>"+getLang("Lieu_"+IDTypeLieu+"_Density_"+density)+"</b>")+"</div>"
			break;
		}
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
				if(game.variables[4] == undefined) // Toile non posée
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
			break;
		}
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
			break;
		}
		case 2: //Emplacement de campement
		{
			//Objets requis: 3,31,32
			$pellePossedee = (nbItemsDansInventaire(3)>0);
			$toilePossedee = (nbItemsDansInventaire(31)>0);
			$marmittePossedee = (nbItemsDansInventaire(32)>0);

			if(lieu.IDParametrageLieu == -1 && $pellePossedee) 
				contenu += genererBoutonAction("creuser",getLang("Lieu_"+IDTypeLieu+"_Creuser")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutInstallation+"</div>",7);
			else if(lieu.IDParametrageLieu == -2 && game.variables[4] == undefined && $toilePossedee)
				contenu += genererBoutonAction("toiler",getLang("Lieu_"+IDTypeLieu+"_InstallerToile")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutInstallation+"</div>",8);
			else if(game.variables[4] == 1 && $marmittePossedee)
				contenu += genererBoutonAction("marmitter",getLang("Lieu_"+IDTypeLieu+"_InstallerMarmitte")+" <div class='iconeCoutAP'>"+lieu.Parametres.CoutInstallation+"</div>",9);

			break;
		}
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
		bouton +=	'<div class="divSubmit" onclick="document.getElementById(\''+nomAction+'\').submit()">'+texte+'</div>';
		bouton +='</form>';

	return bouton;
}