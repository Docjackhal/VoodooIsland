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
		bouton +=	'<div class="divSubmit" onclick="document.getElementById("'+nomAction+'").submit()">'+texte+'</div>';
		bouton +='</form>';

	return bouton;
}