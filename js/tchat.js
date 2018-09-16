// Binds
$(document).ready(function()
{
	//Initialiser les scroll des tchat en bas
	$('.enssemble_message').each(function(){
		$(this).scrollTop(this.scrollHeight);
	});
});


function switchTchat(name)
{
	$(".tchat").css("display","none");
	$("#tchat_"+name).css("display","block");

	$(".selection_channel").removeClass("selected");
	$("#selection_channel_"+name).addClass("selected");
}

function envoyerMessage(idCanal)
{
	var zoneEvoie = document.getElementById("zone_envoi_message_"+idCanal);
	var message = zoneEvoie.value;
	zoneEvoie.value = "";
	
	$.ajax("../ajax/ajaxTchat.php?action=envoyerMessageAdmin",
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