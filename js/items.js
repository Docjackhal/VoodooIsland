
//Renvoi le contenu HTML d'un item dans sa popupZoomItem, selon son inventa
function getInfosEtActionsItem(IDTypeItem,IDItem)
{
	var infosEtActions = "";

	var typeItem = game.typesItems[IDTypeItem];
	var item = getItem(IDItem,IDTypeItem);
	var inventaire = item.TypeInventaire;

	var campementActif = getLieuDeTypeDecouvertDansRegion(3);

	if(typeItem.Categorie == "consommable")
		infosEtActions += "<div>"+(getLang("DureeCycleConsommable")).replace("%Number%","<b>"+item.NombreCycles+"</b>")+"</div>";

	if(campementActif && inventaire != "campement")
		infosEtActions += genererBoutonActionItem("transfertVersCampement",getLang("TransfertInventaireVersCampement"),12,"<input type='hidden' name='IDItem' value='"+IDItem+"'/><input type='hidden' name='IDTypeItem' value='"+IDTypeItem+"'/>");

	if( inventaire == "campement")
		infosEtActions += genererBoutonActionItem("transfertDepuisCampement",getLang("TransfertInventaireDepuisCampement"),13,"<input type='hidden' name='IDItem' value='"+IDItem+"'/><input type='hidden' name='IDTypeItem' value='"+IDTypeItem+"'/>");

	return infosEtActions;
}

/**
Genere un formulaire avec un bouton d'action pour un item
nomAction: le nom du formulaire (dois etre unique sur toute la popup item)
texte: le texte du bouton
action: l'id de laction cible dans action.php
inputsSub: html a ajouter si besoin d'inputs suplémentaires
*/
function genererBoutonActionItem(nomAction,texte,action,inputsSup)
{
	var bouton = '<form id='+nomAction+' method="post" action="action.php?action='+action+'">';
		if(inputsSup != undefined)
			bouton += inputsSup;
		bouton +=	'<div class="divSubmit" onclick="document.getElementById(\''+nomAction+'\').submit()"><span>'+texte+'</span></div>';
		bouton +='</form>';

	return bouton;
}

//Renvoi un item en cherchant dans les inventaires disponibles
function getItem(IDItem,IDTypeItem)
{
	if(game.inventaire[IDTypeItem] != null && game.inventaire[IDTypeItem][IDItem] != null)
        return game.inventaire[IDTypeItem][IDItem];
    else if(game.inventaireCampement[IDTypeItem] != null && game.inventaireCampement[IDTypeItem][IDItem] != null)
        return game.inventaireCampement[IDTypeItem][IDItem];
    else
    	return null;
}