function load()
{
	var game = {};
	window.game = game;
	game.canvas = document.getElementById("canvas");
	game.canvas.height = game.canvas.offsetHeight;
	game.canvas.width = game.canvas.offsetWidth;
	game.height = game.canvas.height;
	game.width = game.canvas.width;
	game.ctx = game.canvas.getContext("2d");

	game.fade = 1;
	game.isFadeOn = false;
	game.isFadeOff = true;
	game.fadeSpeed = 2;
	game.mode = "local";
	game.regionSelected = -1;
	game.lieuSelected = -1;
	game.personnageSelected = -1;

	game.jqCanvas = $(game.canvas);

	game.clearCanvas = function(ctx)
	{
		ctx.fillStyle = "black";
		ctx.fillRect(0,0,game.width,game.height);
	};
	game.clearCanvas(game.ctx);

	$.ajax("ajax/ajaxGame.php",
		{
			data:{"action":"Chargement"},
			cache:false,
			success:function(data)
			{
				game.xhrDatas = data;
				mainInitialisation();				
			},
			error:function(datas)
			{
				alert("load error");
			}
		});
}

function mainInitialisation()
{
	game.region = game.xhrDatas['Region'];
	game.regions = game.xhrDatas['Regions'];
	game.idPersoActuel = game.xhrDatas['IDPersoActuel'];
	game.lieuxDecouverts = game.xhrDatas['LieuxDecouverts'];
	game.personnagesDansRegion = game.xhrDatas['PersonnagesDansRegion'];
	game.inventaire = game.xhrDatas['Inventaire'];
	game.typesItems = game.xhrDatas['TypesItems'];
	game.parametresConditions = game.xhrDatas['ParametresConditions'];

	game.imageRegion = new Image();
	game.imageRegion.src = "images/Regions/region_"+game.region['ID']+".png";

	game.imageMap = new Image();
	game.imageMap.src = "images/island_map.png";

	game.imagePortrait = new Image();
	game.imagePortrait.src = "images/Personnage_portrait/Personnage_Portrait_"+game.idPersoActuel+".png";

	// Initialisations des filtres
	game.imagesFiltre = {};
	game.imagesFiltreDisabled = {};
	for(var i = 1; i <= 10; i++)
	{
		game.imagesFiltre[i] = new Image();
		game.imagesFiltre[i].src = "images/filtresMap/filtre_"+i+".png";
		game.imagesFiltreDisabled[i] = new Image();
		game.imagesFiltreDisabled[i].src = "images/filtresMap/filtre_"+i+"_disabled.png";
	}

	initialisationPolygonesRegions();
	initialisationAccessibiliteRegions();
	initialisationLieuxVisibles();
	initialisationPersonnages();
	initialisationsEvents();

	var now = (+new Date);
	game.oldDate = now;
	run();
}

function run()
{
	var now = (+new Date);
	game.timeSinceLastFrame =  now - game.oldDate;
	game.oldDate = now;

	requestAnimationFrame(run);
	game.clearCanvas(game.ctx);
	game.clearCanvas(game.canvasDetectionLieux.ctx);
	game.clearCanvas(game.canvasDetectionPersonnages.ctx);


	if(game.mode == "local")
	{
		drawRegion(game.ctx);
		drawLieuxEtPersonnages(game.ctx)
	}
	else if(game.mode == "map")
	{
		drawMap(game.ctx);
		drawFiltre(game.ctx);
		drawPortrait(game.ctx);
	}
		

	if(game.isFadeOn)
		fadeOn(game.ctx);
	else if(game.isFadeOff)
		fadeOff(game.ctx);
}

function drawRegion(ctx)
{
	var image = game.imageRegion;
	ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);
}

function drawMap(ctx)
{
	var image = game.imageMap;
	ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);
}

function drawFiltre(ctx)
{
	if(game.regionSelected != -1)
	{
		var image = game.imagesFiltre[game.regionSelected];

		if(game.accessibilite[game.regionSelected] == 2)
			image = game.imagesFiltreDisabled[game.regionSelected];

		ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);

		if(game.regionSelected != game.region.ID)
			game.canvas.style.cursor = "pointer";

		// Draw nom
		var nom = game.regions[game.regionSelected]['Nom'];
		ctx.font = "bold 20pt  Calibri";
		ctx.fillText(nom,30,30);
	}
}

function drawPortrait(ctx)
{
	var width = 50;
	var height = 50;
	var image = game.imagePortrait;
	var posX = game.coordonneesPortraits[game.region.ID].x-width/2;
	var posY = game.coordonneesPortraits[game.region.ID].y-height/2;
	ctx.drawImage(image,0,0,image.width,image.height,posX,posY,width,height);
}

function fadeOff(ctx)
{
	ctx.fillStyle = "black";
	game.fade -= (game.timeSinceLastFrame/1000)*game.fadeSpeed;

	if(game.fade <= 0)
	{
		game.fade = 0;
		game.isFadeOff = false;
	}

	ctx.globalAlpha = game.fade;
	ctx.fillRect(0,0,game.width,game.height);
	ctx.globalAlpha = 1;
}

function fadeOn(ctx)
{
	ctx.fillStyle = "black";
	game.fade += (game.timeSinceLastFrame/1000)*game.fadeSpeed;

	if(game.fade >= 1)
	{
		game.fade = 1;
		game.isFadeOn = false;


		if(game.mode == "map")
		{
			document.getElementById("btn_voyager").innerHTML = "Voyager";
			document.getElementById("btn_explorer").style.display = "block";
			game.mode = "local";
			game.regionSelected = -1;
		}		
		else
		{
			document.getElementById("btn_voyager").innerHTML = "Annuler";
			document.getElementById("btn_explorer").style.display = "none";
			game.mode = "map";
		}
			
		game.isFadeOff = true;
	}

	ctx.globalAlpha = game.fade;
	ctx.fillRect(0,0,game.width,game.height);
	ctx.globalAlpha = 1;
}

function switchMode()
{
	game.isFadeOn = true;
}

function initialisationPolygonesRegions()
{
	game.polygonesRegions = {};

	// Region 1
	var region = {};
	region.polySides = 9;
	region.polyX = [52,97,222,242,310,332,314,241,125];
	region.polyY  = [254,233,386,427,458,499,551,535,411];
	game.polygonesRegions[1] = region;

	// Region 2
	var region = {};
	region.polySides = 9;
	region.polyX = [420,450,504,692,704,678,608,539,524];
	region.polyY = [570,459,437,433,527,555,505,507,575];
	game.polygonesRegions[2] = region;

	// Region 3
	var region = {};
	region.polySides = 9;
	region.polyX = [518,604,708,753,750,688,590,584,502];
	region.polyY = [82,99,157,233,273,261,204,175,124];
	game.polygonesRegions[3] = region;

	// Region 4
	var region = {};
	region.polySides = 9;
	region.polyX = [588,739,698,530,513,529,505,513,513,554];
	region.polyY = [205,274,433,433,382,340,313,295,243,228];
	game.polygonesRegions[4] = region;

	// Region 5
	var region = {};
	region.polySides = 9;
	region.polyX = [303,417,518,501,580,590,511,482,347];
	region.polyY = [150,70,84,122,172,206,240,220,202];
	game.polygonesRegions[5] = region;

	// Region 6
	var region = {};
	region.polySides = 9;
	region.polyX = [100,122,294,348,332,351,332,270,159];
	region.polyY = [230,197,153,207,229,260,287,279,315];
	game.polygonesRegions[6] = region;

	// Region 7
	var region = {};
	region.polySides = 8;
	region.polyX = [160,271,331,386,387,403,295,242];
	region.polyY = [317,280,289,341,384,402,449,425];
	game.polygonesRegions[7] = region;

	// Region 8
	var region = {};
	region.polySides = 10;
	region.polyX = [298,405,460,439,438,421,423,288,315,329];
	region.polyY = [448,405,450,480,529,550,576,570,548,502];
	game.polygonesRegions[8] = region;

	// Region 9
	var region = {};
	region.polySides = 7;
	region.polyX = [390,504,530,515,532,462,388];
	region.polyY = [344,312,346,387,433,450,382];
	game.polygonesRegions[9] = region;

	// Region 10
	var region = {};
	region.polySides = 9;
	region.polyX = [348,489,514,514,500,387,331,350,333];
	region.polyY = [202,223,245,300,315,343,285,255,223];
	game.polygonesRegions[10] = region;

	// Initialisation coordonées portraits
	game.coordonneesPortraits = {};
	game.coordonneesPortraits[1] = {'x':170,'y':383};
	game.coordonneesPortraits[2] = {'x':530,'y':464};
	game.coordonneesPortraits[3] = {'x':641,'y':167};
	game.coordonneesPortraits[4] = {'x':600,'y':315};
	game.coordonneesPortraits[5] = {'x':437,'y':148};
	game.coordonneesPortraits[6] = {'x':230,'y':220};
	game.coordonneesPortraits[7] = {'x':296,'y':350};
	game.coordonneesPortraits[8] = {'x':380,'y':490};
	game.coordonneesPortraits[9] = {'x':460,'y':380};
	game.coordonneesPortraits[10] = {'x':438,'y':293};
}

function pointDansPolygone(zone,x,y)
{
	//console.log([zone,x,y]);
	var j=zone.polySides-1;
	var oddNodes=false;

	for (var i=0; i<zone.polySides; i++){
		if((zone.polyY[i]<y && zone.polyY[j]>=y || zone.polyY[j]<y && zone.polyY[i]>=y) && (zone.polyX[i]<=x || zone.polyX[j]<=x))
			oddNodes ^= (zone.polyX[i]+(y-zone.polyY[i])/(zone.polyY[j]-zone.polyY[i])*(zone.polyX[j]-zone.polyX[i])<x);
		j=i;
	}

	return oddNodes;
}

function initialisationsEvents()
{
	game.canvas.addEventListener("mousemove", mouseMove, false);
	game.canvas.addEventListener("mouseup", mouseUp, false);
}

function mouseMove(e)
{
	game.mouseX = e.pageX-game.jqCanvas.offset().left-0.5;
	game.mouseY = e.pageY-game.jqCanvas.offset().top;

	if(game.mode == 'map')
		verificationSourisSurRegion();
	else if(game.mode == 'local')
		verificationSourisSurLieu();
}

function verificationSourisSurRegion()
{
	var x = game.mouseX;
	var y = game.mouseY;

	game.regionSelected = -1;

	for(var i in game.polygonesRegions)
	{
		var region = game.polygonesRegions[i];
		var point = pointDansPolygone(region,x,y);
		if(point == 1)
		{
			game.regionSelected = i;
			break;
		}
	}

	if(game.regionSelected == -1 || game.regionSelected == game.region.ID)
		game.canvas.style.cursor = "default";
}

function mouseUp(e)
{
	game.mouseX = e.pageX-game.jqCanvas.offset().left-0.5;
	game.mouseY = e.pageY-game.jqCanvas.offset().top;

	if(game.regionSelected != -1)
		clickSurRegion(game.regionSelected);
	else if(game.lieuSelected != -1)
		clickSurLieu(game.lieuSelected);
}

function clickSurRegion(region)
{
	if(region != -1 && region != game.region.ID)
	{
		if(game.accessibilite[region] == 1)
		{
			var popup = document.getElementById("popup_validation_voyage");
			var zoneNom = document.getElementById("name_region");
			var input = document.getElementById("inputIdRegion"); 
			if(zoneNom != null)
			{
				zoneNom.innerHTML = game.regions[region]['Nom'];
				input.value = region;
			}		
			popup.style.display = "block";
		}
		else if(game.accessibilite[region] == 2)
		{
			var popup = document.getElementById("popup_interdiction_voyage");
			popup.style.display = "block";
		}
	}
}

function clickSurLieu(lieu)
{
	ouvrirPopupLieu(lieu);
}

function initialisationAccessibiliteRegions()
{
	var region = game.region;
	//0 : region actuel, //1: region accessible, //2: region innaccesible
	game.accessibilite = {};
	for(var i = 1; i <= 10; i++ )
	{
		if(region.ID == i)
			game.accessibilite[i] = 0;
		else if(region['Lien1'] == i || region['Lien2'] == i || region['Lien3'] == i || region['Lien4'] == i || region['Lien5'] == i)
			game.accessibilite[i] = 1;
		else
			game.accessibilite[i] = 2;
	}
}

function initialisationLieuxVisibles()
{
	var urlImagesLieux = "images/lieux/Region_"+game.region.ID+"/";
	for(var IDLieu in game.lieuxDecouverts)
	{
		var lieu = game.lieuxDecouverts[IDLieu];
		lieu.image = new Image();
		lieu.image.src = urlImagesLieux+"lieu_"+lieu.IDTypeLieu+".png";
		lieu.imageHover = new Image();
		lieu.imageHover.src = urlImagesLieux+"lieu_"+lieu.IDTypeLieu+"_hover.png";
	}

	// Création d'un canvas qui servira a la detection de pixel non transparents pour le hover des régions
	game.canvasDetectionLieux = document.createElement("canvas");
	game.canvasDetectionLieux.width = game.canvas.width;
	game.canvasDetectionLieux.height = game.canvas.height;
	game.canvasDetectionLieux.ctx = game.canvasDetectionLieux.getContext("2d"); 
}

function verificationSourisSurLieu()
{
	var x = game.mouseX;
	var y = game.mouseY;

	game.lieuSelected = -1;
	game.personnageSelected = -1;

	// Detection au pixel non transparent des lieux et des personnages

	// Personnages
	for(var i in game.personnagesDansRegion)
		{
			var personnage = game.personnagesDansRegion[i];
			var image = personnage.image;

			if(image.width > 0)
			{
				var x2 = x-personnage.x;
				var y2 = y-personnage.y;

				if(x2 >= 0 && x2 <= game.canvasDetectionPersonnages.width && y2 >= 0 && y2 <= game.canvasDetectionPersonnages.height)
				{
					game.canvasDetectionPersonnages.ctx.drawImage(image, 0, 0, image.width,image.height,0,0,image.width,image.height);
					var id = game.canvasDetectionPersonnages.ctx.getImageData(0,0, image.width, image.height);  //Get the pi
					var alpha = id.data[(y2*image.width+x2)*4+3]; // Recupere l'alpha entre 0 et 255

					if(alpha > 0) // Detection
					{
						game.personnageSelected = personnage;
						game.canvas.style.cursor = "pointer";
						break;
					}
					else
						game.clearCanvas(game.canvasDetectionPersonnages.ctx);
				}
			}
		}

	// Si pas de personnage, on tente de detecter un lieu
	if(game.personnageSelected == -1)
	{
		for(var i in game.lieuxDecouverts)
		{
			var lieu = game.lieuxDecouverts[i];
			var image = lieu.image;

			if(image.width > 0)
			{
				game.canvasDetectionLieux.ctx.drawImage(image, 0, 0);
				var id = game.canvasDetectionLieux.ctx.getImageData(0,0, image.width, image.height);  //Get the pi
				var alpha = id.data[(y*image.width+x)*4+3]; // Recupere l'alpha entre 0 et 255

				if(alpha > 0) // Detection
				{
					game.lieuSelected = lieu;
					game.canvas.style.cursor = "pointer";
					break;
				}
				else
					game.clearCanvas(game.canvasDetectionLieux.ctx);
			}
		}
	}
	if(game.lieuSelected == -1 && game.personnageSelected == -1)
		game.canvas.style.cursor = "default";
/*	else
		console.log("Detection lieu "+game.lieuSelected.ID);*/
}

function initialisationPersonnages()
{
	var urlImagesPersonnages = "images/Regions/Region"+game.region['ID']+"/";

	var w = 800;
	var h = 614;

	for(var IDPersonnage in game.personnagesDansRegion)
	{
		var personnage = game.personnagesDansRegion[IDPersonnage];
		personnage.image = new Image();
		personnage.image.src = urlImagesPersonnages+"Perso_"+personnage.IDHeros+".png";

		// Coordonées
		personnage.x = 0;
		personnage.y = 0;
	}

	// Création d'un canvas qui servira a la detection de pixel non transparents pour le hover des régions
	game.canvasDetectionPersonnages = document.createElement("canvas");
	game.canvasDetectionPersonnages.width = w;
	game.canvasDetectionPersonnages.height = h;
	game.canvasDetectionPersonnages.ctx = game.canvasDetectionPersonnages.getContext("2d"); 
}

function drawPersonnage(ctx, IDPersonnage)
{
	var personnage = game.personnagesDansRegion[IDPersonnage];
	var image = personnage.image;
	
	ctx.globalAlpha = (game.personnageSelected != -1 && game.personnageSelected.IDHeros) ? 0.85 : 1;

	if(image.width > 0)
		ctx.drawImage(image,0,0,image.width,image.height,personnage.x,personnage.y,image.width,image.height);

	ctx.globalAlpha = 1;
}

function drawLieu(ctx, IDLieu)
{
	var lieu = game.lieuxDecouverts[IDLieu];
	var image =  lieu.image;
	ctx.globalAlpha = (game.lieuSelected != -1 && game.lieuSelected.ID == lieu.ID) ? 0.85 : 1;

	if(image.width > 0)
		ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);

	ctx.globalAlpha = 1;
}

function drawLieuxEtPersonnages(ctx)
{
	var configZindex = game.configZindexRegion["Region"+game.region['ID']];

	for(var i = 0; i < configZindex.length;i++)
	{
		var entite = configZindex[i];
		for(var y in entite)
		{
			var typeEntite = y;
			var numeroEntite = entite[y];
			if(typeEntite == "perso")
			{
				if(game.personnagesDansRegion[numeroEntite] != null)
					drawPersonnage(ctx,numeroEntite);
			}
			else if(typeEntite == "lieu")
			{
				for(IDLieu in game.lieuxDecouverts)
				{
					if(game.lieuxDecouverts[IDLieu].IDTypeLieu == numeroEntite)
					{
						drawLieu(ctx,IDLieu);
						break;
					}					
				}			
			}
		}		
	}
}

load();