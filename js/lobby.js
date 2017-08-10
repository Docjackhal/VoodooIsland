function afficherBioPersonnage(idPerso)
{
	closeAllBio();
	var bio = document.getElementById("bloc_infos_personnage_"+idPerso);
	bio.style.display = 'block';
	bio.style.left = (window.innerWidth/2) - (bio.offsetWidth/2) +"px";	
}

function closeAllBio()
{
	for(var i = 1; i <= 8; i++)
	{
		var bio = document.getElementById("bloc_infos_personnage_"+i);
		if(bio != null)
			bio.style.display = 'none';
	}
}

function submitForm(idForm)
{
	document.forms[idForm].submit();
}