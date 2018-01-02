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

	window.setTimeout(updateInfosPartie,3000);
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
					window.setTimeout( callback, 3000 / 40 );			
				};
			}
		)();
	}
}

$(document).ready(function()
{
	updateInfosPartie();
});

traiterDonneesUpdatesInfosPartie = function(data)
{
	var personnages = data["Personnages"];
	for(IDPersonnage in personnages)
	{
		var personnage = personnages[IDPersonnage];
		var blocPrincipal = $("#blocPersonnage_"+IDPersonnage);

		$("#blocPersonnage_"+IDPersonnage+" .iconeRdy").attr("src","../images/"+((personnage["PretCycleSuivant"] == 'o')?"button_ready":"button_Nready")+".png");
	}
}