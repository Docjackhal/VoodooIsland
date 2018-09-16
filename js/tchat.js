// Binds
nomOngletTchatActif = "Region";
$(document).ready(function()
{
	//Initialiser les scroll des tchat en bas
	$('.enssemble_message').each(function(){
		$(this).scrollTop(this.scrollHeight);
	});

	updateTchat();
});


function switchTchat(canal)
{
	$(".tchat").css("display","none");
	$("#tchat_"+canal).css("display","block");

	$(".selection_channel").removeClass("selected");
	$("#selection_channel_"+canal).addClass("selected");

	var contentJQ = $("#enssemble_message_"+canal);
	contentJQ.scrollTop(contentJQ[0].scrollHeight);

	$("#selection_channel_"+canal).removeClass("newMessage");
	nomOngletTchatActif = canal;
}

function envoyerMessage(idCanal)
{
	var zoneEvoie = document.getElementById("zone_envoi_message_"+idCanal);
	var message = zoneEvoie.value;
	zoneEvoie.value = "";
	
	$.ajax("ajax/ajaxTchat.php?action=envoyerMessage",
	{
		data:{"message":message,"idCanal":idCanal},
		type:'POST',
		cache:false,
		success:function(data)
		{
			//document.location.href="game.php?c="+idCanal;	
			var blocTchat = document.getElementById("enssemble_message_"+idCanal);
			var content = data.DivNouveauMessage;
		
			$(blocTchat).append(content);
			$("#enssemble_message_"+idCanal).scrollTop(blocTchat.scrollHeight);
		},
		error:function(datas)
		{
			alert(datas.responseText);
		}
	});
}

updateTchat = function()
{
	console.log("UPDATE TCHAT");

	$.ajax("ajax/ajaxTchat.php",
		{
			data:{"action":"update"},
			cache:false,
			success:function(data)
			{
				console.log(data);
				traiterDonneesUpdatesTchat(data);			
			},
			error:function(datas)
			{
				alert("Ajax error: "+datas.responseText);
			}
		});


	var timeToUpdate = 3000;
	window.setTimeout(updateTchat,timeToUpdate);
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

traiterDonneesUpdatesTchat = function(data)
{
	var nouveauxMessages = data["Messages"];
	if(nouveauxMessages.length > 0)
	{
		for(i in nouveauxMessages)
		{
			var message = nouveauxMessages[i];
			var canal = message["Canal"];

			var contentJQ = $("#enssemble_message_"+canal);
			contentJQ.append(message["HTML"]);

			if(canal != nomOngletTchatActif)
				$("#selection_channel_"+canal).addClass("newMessage");
			else
			{
				var isScrollBottom = contentJQ.scrollTop() + contentJQ.innerHeight() >=contentJQ[0].scrollHeight;
				if(isScrollBottom)
				{
					contentJQ.scrollTop(contentJQ[0].scrollHeight);
				}
				else
					$("#selection_channel_"+canal).addClass("newMessage");
			}

		}
	}
}