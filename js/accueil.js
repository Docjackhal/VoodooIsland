function switchPopupInscription()
{
	var popup = document.getElementById("popup_inscription");
	
	console.log(popup.style.display);
	if(popup.style.display == "block")
		popup.style.display = "none";
	else
		popup.style.display = "block";
}