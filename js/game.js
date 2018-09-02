function updateHorloge()
{
	window.setTimeout(updateHorloge,1000);
	var horloge = document.getElementById("horloge_js");
	var currentTime = horloge.innerHTML;
	var h = parseInt(currentTime.substring(0,1));
	var m = parseInt(currentTime.substring(2,5));
	var s = parseInt(currentTime.substring(5,8));

	s--;
	if(s < 0)
	{
		m--;
		s = 59;
	}

	if(s < 10)
		s = '0'+s;
	if(m < 10)
		m = '0'+m;

	if(m < 0)
	{
		h--;
		m = 59;
	}

	var time = h+"h"+m+"m"+s+"s";
	horloge.innerHTML = time;
}
window.setTimeout(updateHorloge,1000);

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
				
				window.setTimeout( callback, 1000 / 40 );
			
			};
		}
	)();
}

function switchTchat(name)
{
	var tchats = $(".tchat");
	for(var i = 0; i < tchats.length; i++)
		tchats[i].style.display = "none";
	
	var tchat = document.getElementById("tchat_"+name);
	tchat.style.display = "block";
}

function envoyerMessage(idCanal)
{
	var zoneEvoie = document.getElementById("zone_envoi_message_"+idCanal);
	var message = zoneEvoie.value;
	zoneEvoie.value = "";
	
	$.ajax("ajax/envoiMessageTchat.php",
	{
		data:{"message":message,"idCanal":idCanal},
		cache:false,
		success:function(data)
		{
			//document.location.href="game.php?c="+idCanal;	
			var blocTchat = document.getElementById("enssemble_message_"+idCanal);
			var content = "";
			content += "<div class='entree_tchat'>";
				content += "<span class='tchat_date'>"+data['date']+": </span>";
				content += "<span class='tchat_auteur'>"+data['auteur']+": </span>";
				content += "<span class='tchat_message'>"+message+"</span>";
			content += "</div>";

			blocTchat.innerHTML = content+blocTchat.innerHTML;
			blocTchat.scrollTop='0px';
		},
		error:function(datas)
		{
			//alert(datas.responseText);
		}
	});
}

function SwitchPopupVoyage()
{
	var popup = document.getElementById("popup_validation_voyage");
	if(popup.style.display == "block")
		popup.style.display = "none";
	else
		popup.style.display = "block";
}

function SwitchPopupInterdictionVoyage()
{
	var popup = document.getElementById("popup_interdiction_voyage");
	if(popup.style.display == "block")
		popup.style.display = "none";
	else
		popup.style.display = "block";
}

function SwitchPopupValidationExploration()
{
	var popup = document.getElementById("popup_validation_exploration");
	if(popup.style.display == "block")
		popup.style.display = "none";
	else
		popup.style.display = "block";
}


function initialiserPopupZoomItem(IDTypeItem,IDItem)
{
	var popupZoomItem = $("#popup_zoomItem");

	var typeItem = game.typesItems[IDTypeItem];
	var item = game.inventaire[IDTypeItem][IDItem];

	$("#popup_zoomItem_titre").html(typeItem["NomFR"]);
	$("#popup_zoomItem_image").attr("src","images/items/item_"+IDTypeItem+".png");
	$("#popup_zoomItem_description").html(typeItem["DescriptionFR"]);

	popupZoomItem.css("display","block");
	popupZoomItem.css("opacity",0);
	popupZoomItem.animate({opacity: 1},300);
}

function fermerPopupMessage()
{
	$("#popup_message").animate({opacity: 0},300,function(){
		$(this).css("display","none");
	});
}

function fermerPopupEvenement()
{
	$("#popup_evenement").animate({opacity: 0},300,function(){
		$(this).css("display","none");
	});
}

function ouvrirPopupCondition(IDCondition)
{
	var popupCondition = $("#popup_condition");
	var parametreCondition = game.parametresConditions[IDCondition];

	$("#popup_condition .condition_titre").html(parametreCondition["NomFR"]);
	$("#popup_condition .condition_description").html(parametreCondition["DescriptionFR"]);
	$("#popup_condition .condition_icone").css("background-image","url(images/Conditions/condition_"+IDCondition+".png");

	popupCondition.css("display","block");
	popupCondition.css("opacity",0);
	popupCondition.animate({opacity: 1},300);
}

function fermerPopupCondition()
{
	$("#popup_condition").animate({opacity: 0},300,function(){
		$(this).css("display","none");
	});
}

function ouvrirPopupLieu(lieu)
{
	console.log(lieu);
	var IDTypeLieu = lieu.IDTypeLieu;
	var popupLieu = $("#popup_lieu");

	//Contenu commun
	popupLieu.find("#popup_lieu_titre").html(lieu.Nom);
	popupLieu.find("#popup_lieu_illustration").css("background-image","url('images/lieux/Visuels/visuels_"+IDTypeLieu+".png')");
	popupLieu.find("#popup_lieu_description").html(lieu.Description);

	//Contenu unique (lieux.js)
	var contenuInfos = genererContenuInfosLieu(lieu);
	var contenuActions = genererContenuActionLieu(lieu);

	popupLieu.find("#popup_lieu_infos").html(contenuInfos);
	popupLieu.find("#popup_lieu_actions").html(contenuActions);

	popupLieu.css("display","block");
	popupLieu.css("opacity",0);
	popupLieu.animate({opacity: 1},300);
}

// Binds
$(document).ready(function()
{
	// Inventaire
	var popupInventaire = $("#popup_inventaire");
	$("#bloc_menu_bas_0").click(function()
	{
		popupInventaire.css("display","block");
		popupInventaire.css("opacity",0);
		popupInventaire.animate({opacity: 1},300);
	});

	$("#popup_inventaire_close").click(function()
	{
		popupInventaire.animate({opacity: 0},300,function(){
			$(this).css("display","none");
		});
	});

	// Zoom Item
	var popupZoomItem = $("#popup_zoomItem");
	$(".bloc_inventaire").click(function()
	{
		var blocItem = $(this);
		var IDTypeItem = $(this).attr("IDTypeItem");
		var IDItem = $(this).attr("IDItem");

		initialiserPopupZoomItem(IDTypeItem,IDItem);
	});

	$("#popup_zoomItem_close").click(function()
	{
		popupZoomItem.animate({opacity: 0},300,function(){
			$(this).css("display","none");
		});
	});

	// Condition
	$(".bloc_condition").click(function()
	{
		var blocCondition = $(this);
		var IDCondition = $(this).attr("IDCondition");
		ouvrirPopupCondition(IDCondition);
	});

	$("#popup_condition_close").click(function()
	{
		fermerPopupCondition();
	});

	$("#popup_lieu_close").click(function()
	{
		$("#popup_lieu").animate({opacity: 0},300,function(){
			$(this).css("display","none");
		});
	});

	$("#btn_rdy_n").click(function()
	{
		var popupConfirmerCycle = $("#popup_confirmationCycle");
		popupConfirmerCycle.css("display","block");
		popupConfirmerCycle.css("opacity",0);
		popupConfirmerCycle.animate({opacity: 1},300);
	});

	$("#popup_confirmationCycle .btnConfirmerCycle").click(function()
	{
		$("#popup_confirmationCycle").animate({opacity: 0},300,function(){
		$(this).css("display","none");
		});
	});
});
