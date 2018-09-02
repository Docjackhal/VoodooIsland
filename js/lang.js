function getLang(libeleFragment)
{
	if(lang[libeleFragment] != undefined) 
		return lang[libeleFragment];
	else
	{
		var retour = "STR_NOT_FOUND: "+libeleFragment;
		console.log(retour);
		return retour;
	} 
}