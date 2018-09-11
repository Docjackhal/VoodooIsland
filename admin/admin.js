// Charge en js les datas du jeu (infos items, regions etc)
updateGlobalesDatas = function()
{
	console.log("UPDATE GLOBAL");
	$.ajax("adminAjax.php",
		{
			data:{"action":"UpdateGlobalDatas"},
			cache:false,
			success:function(data)
			{
				VI.datas = data;
				updateInfosPartie();		
			},
			error:function(datas)
			{
				alert("Ajax error: "+datas.responseText);
			}
		});
}

updateInfosPartie = function()
{
	console.log("UPDATE");
	$.ajax("adminAjax.php",
		{
			data:{"action":"Update"},
			cache:false,
			success:function(data)
			{
				console.log(data);
				traiterDonneesUpdatesInfosPartie(data);			
			},
			error:function(datas)
			{
				alert("Ajax error: "+datas.responseText);
			}
		});

	var timeToUpdate = 3000;
	window.setTimeout(updateInfosPartie,timeToUpdate);
	if (!window.requestAnimationFrame) 
	{
			window.requestAnimationFrame = ( function()
			{
				return window.webkitRequestAnimationFrame ||
				window.mozRequestAnimationFrame ||
				window.oRequestAnimationFrame ||
				window.msRequestAnimationFrame ||
				function( /* function FrameRequestCallback */ callback, /* DOMElement Element */ element )
				{				
					window.setTimeout( callback, timeToUpdate / 40 );			
				};
			}
		)();
	}
}

var VI = {};
$(document).ready(function()
{
	updateGlobalesDatas();
});

traiterDonneesUpdatesInfosPartie = function(data)
{
	//Carte
	var carteIle = $("#carteIle");
	carteIle.find(".zoneRegion").html("");

	//Personnages
	VI.Heros = data.Heros;
	var personnages = data["Personnages"];
	var persoPrets = 0;
	for(IDPersonnage in personnages)
	{
		var IDDivBlocPerso = "#blocPersonnage_"+IDPersonnage;
		var personnage = personnages[IDPersonnage];
		var blocPrincipal = $(IDDivBlocPerso);

		$(IDDivBlocPerso+" .iconeRdy").attr("src","../images/"+((personnage["PretCycleSuivant"] == 'o')?"button_ready":"button_Nready")+".png");

		if(personnage["PretCycleSuivant"] == 'o')
			persoPrets++;
		
		//Carac
		$(IDDivBlocPerso+" .PAActuel").html(personnage["PaActuel"]);
		$(IDDivBlocPerso+" .PAMax").html(personnage["PaMax"]);
		$(IDDivBlocPerso+" .MPActuel").html(personnage["MPActuel"]);
		$(IDDivBlocPerso+" .MPMax").html(personnage["MPMax"]);
		$(IDDivBlocPerso+" .PVActuel").html(personnage["PvActuel"]);
		$(IDDivBlocPerso+" .PVMax").html(personnage["PvMax"]);
		$(IDDivBlocPerso+" .PMActuel").html(personnage["PmActuel"]);
		$(IDDivBlocPerso+" .PMMax").html(personnage["PmMax"]);
		$(IDDivBlocPerso+" .nom_region_perso").html(VI.datas.Regions[personnage["RegionActuelle"]]["Nom"]);

		//Position sur Ile
		carteIle.find("#region_"+personnage["RegionActuelle"]).append(genererIconeHerosSurCarteIle(IDPersonnage));
	}

	if(persoPrets == 8)
		$("#btnPasserCycle").addClass("rdy");
	else
		$("#btnPasserCycle").remove("rdy");

	//Variables
	var variables = data["Variables"];
	$blocVariable = $("#listeVariablesPartie");
	for(IDVariable in variables)
	{
		var value = variables[IDVariable];
		var row = $("#rowVariable_"+IDVariable);
		if(value > -1)
		{
			row.find(".descriptionVariable").addClass("green").removeClass("red");
			row.find(".etatVariable").removeClass("grey");
			row.find(".etatVariable input").val(value);	
		}
	}

	//Lieux
	for(IDLieu in data.Lieux)
	{
		//Position sur Ile
		var lieu = data.Lieux[IDLieu];
		carteIle.find("#region_"+lieu["IDRegion"]).append(genererIconeLieuSurCarteIle(lieu["IDTypeLieu"]));
	}

}

switchOngletGestionPartie = function(onglet)
{
	$(".btnTopBarLocked").removeClass("btnTopBarLocked");
	$("#btnTopBar_"+onglet).addClass("btnTopBarLocked");

	$(".blocOnglet").css("display","none");
	$("#blocOnglet_"+onglet).css("display","block");
}

afficherPopupDonObjet = function(IDHeros)
{
	var popup = $("#popupDonObjet");
	if(VI.Heros != undefined)
		popup.find(".labelNomHeros").html(VI.Heros[IDHeros]["Prenom"]+" "+VI.Heros[IDHeros]["Nom"]);
	else
		popup.find(".labelNomHeros").html("Heros "+IDHeros);

	popup.find(".inputIDHeros").val(IDHeros);
	popup.css("display","block");
}

fermerPopupDonItem = function()
{
	$("#popupDonObjet").css("display","none");
}

genererIconeHerosSurCarteIle = function(IDHeros)
{
	var bloc = "<div class='iconeDansCarte iconeJoueurDansCarte iconeJoueurDansCarte_"+IDHeros+"' style='background-image:url(\"../images/Personnage_portrait/Personnage_Portrait_"+IDHeros+".png\");'></div>";
	return bloc;
}

genererIconeLieuSurCarteIle = function(IDTypeLieu)
{
	var bloc = "<div class='iconeDansCarte iconeLieuDansCarte iconeLieuDansCarte_"+IDTypeLieu+"' style='background-image:url(\"../images/lieux/Icones/icone_"+IDTypeLieu+".png\");'></div>";
	return bloc;
}
