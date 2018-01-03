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
				alert("Ajax error");
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
				traiterDonneesUpdatesInfosPartie(data);			
			},
			error:function(datas)
			{
				alert("Ajax error");
			}
		});

	var timeToUpdate = 10000;
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
	var personnages = data["Personnages"];
	for(IDPersonnage in personnages)
	{
		var IDDivBlocPerso = "#blocPersonnage_"+IDPersonnage;
		var personnage = personnages[IDPersonnage];
		var blocPrincipal = $(IDDivBlocPerso);

		$(IDDivBlocPerso+" .iconeRdy").attr("src","../images/"+((personnage["PretCycleSuivant"] == 'o')?"button_ready":"button_Nready")+".png");
		
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
	}
}