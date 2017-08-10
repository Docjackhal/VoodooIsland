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
		m--
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
	})


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