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
	game.lieuSelected = -1;


	game.jqCanvas = $(game.canvas);

	game.clearCanvas = function(ctx)
	{
		ctx.fillStyle = "black";
		ctx.fillRect(0,0,game.width,game.height);
	}
	game.clearCanvas(game.ctx)

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

	game.region = game.xhrDatas['Region']
	game.regions = game.xhrDatas['Regions'];
	game.idPersoActuel = game.xhrDatas['IDPersoActuel'];

	game.imageLieu = new Image();
	game.imageLieu.src = "images/lieux/lieu_"+game.region['ID']+".png";

	game.imageMap = new Image();
	game.imageMap.src = "images/island_map.png";

	game.imagePortrait = new Image();
	game.imagePortrait.src = "images/Personnage_portrait/Personnage_Portrait_"+game.idPersoActuel+".png";

	// Initialisations des filtres
	game.imagesFiltre = {};
	game.imagesFiltreDisabled = {}
	for(var i = 1; i <= 10; i++)
	{
		game.imagesFiltre[i] = new Image();
		game.imagesFiltre[i].src = "images/filtresMap/filtre_"+i+".png";
		game.imagesFiltreDisabled[i] = new Image();
		game.imagesFiltreDisabled[i].src = "images/filtresMap/filtre_"+i+"_disabled.png";
	}

	initialisationPolygonesLieux();
	initialisationsEvents();
	initialisationAccessibiliteLieux();

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

	if(game.mode == "local")
		drawRegion(game.ctx);
	else if(game.mode == "map")
	{
		drawMap(game.ctx);
		drawFiltre(game.ctx);
		drawPortrait(game.ctx)
	}
		

	if(game.isFadeOn)
		fadeOn(game.ctx);
	else if(game.isFadeOff)
		fadeOff(game.ctx);
}

function drawRegion(ctx)
{
	var image = game.imageLieu;
	ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);
}

function drawMap(ctx)
{
	var image = game.imageMap;
	ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);
}

function drawFiltre(ctx)
{
	if(game.lieuSelected != -1)
	{
		var image = game.imagesFiltre[game.lieuSelected];

		if(game.accessibilite[game.lieuSelected] == 2)
			image = game.imagesFiltreDisabled[game.lieuSelected];

		ctx.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);

		if(game.lieuSelected != game.region.ID)
			game.canvas.style.cursor = "pointer";

		// Draw nom
		var nom = game.regions[game.lieuSelected]['Nom'];
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
			game.mode = "local";
		}		
		else
		{
			document.getElementById("btn_voyager").innerHTML = "Annuler";
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

function initialisationPolygonesLieux()
{
	game.polygonesLieux = {}

	// Region 1
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [52,97,222,242,310,332,314,241,125];
	lieu.polyY  = [254,233,386,427,458,499,551,535,411];
	game.polygonesLieux[1] = lieu;

	// Region 2
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [420,450,504,692,704,678,608,539,524];
	lieu.polyY = [570,459,437,433,527,555,505,507,575];
	game.polygonesLieux[2] = lieu;

	// Region 3
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [518,604,708,753,750,688,590,584,502];
	lieu.polyY = [82,99,157,233,273,261,204,175,124];
	game.polygonesLieux[3] = lieu;

	// Region 4
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [588,739,698,530,513,529,505,513,513,554];
	lieu.polyY = [205,274,433,433,382,340,313,295,243,228];
	game.polygonesLieux[4] = lieu;

	// Region 5
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [303,417,518,501,580,590,511,482,347];
	lieu.polyY = [150,70,84,122,172,206,240,220,202];
	game.polygonesLieux[5] = lieu;

	// Region 6
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [100,122,294,348,332,351,332,270,159];
	lieu.polyY = [230,197,153,207,229,260,287,279,315];
	game.polygonesLieux[6] = lieu;

	// Region 7
	var lieu = {};
	lieu.polySides = 8;
	lieu.polyX = [160,271,331,386,387,403,295,242];
	lieu.polyY = [317,280,289,341,384,402,449,425];
	game.polygonesLieux[7] = lieu;

	// Region 8
	var lieu = {};
	lieu.polySides = 10;
	lieu.polyX = [298,405,460,439,438,421,423,288,315,329];
	lieu.polyY = [448,405,450,480,529,550,576,570,548,502];
	game.polygonesLieux[8] = lieu;

	// Region 9
	var lieu = {};
	lieu.polySides = 7;
	lieu.polyX = [390,504,530,515,532,462,388];
	lieu.polyY = [344,312,346,387,433,450,382];
	game.polygonesLieux[9] = lieu;

	// Region 10
	var lieu = {};
	lieu.polySides = 9;
	lieu.polyX = [348,489,514,514,500,387,331,350,333];
	lieu.polyY = [202,223,245,300,315,343,285,255,223];
	game.polygonesLieux[10] = lieu;

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
		verificationSourisSurLieu();
}

function verificationSourisSurLieu()
{
	var x = game.mouseX;
	var y = game.mouseY;

	game.lieuSelected = -1;

	for(var i in game.polygonesLieux)
	{
		var lieu = game.polygonesLieux[i];
		var point = pointDansPolygone(lieu,x,y);
		if(point == 1)
		{
			game.lieuSelected = i;
			break;
		}
	}

	if(game.lieuSelected == -1 || game.lieuSelected == game.region.ID)
		game.canvas.style.cursor = "default";
}

function mouseUp(e)
{
	game.mouseX = e.pageX-game.jqCanvas.offset().left-0.5;
	game.mouseY = e.pageY-game.jqCanvas.offset().top;

	if(game.lieuSelected != -1)
	{
		clickSurLieu();
	}
}

function clickSurLieu()
{
	if(game.lieuSelected != -1 && game.lieuSelected != game.region.ID)
	{
		if(game.accessibilite[game.lieuSelected] == 1)
		{
			var popup = document.getElementById("popup_validation_voyage");
			var zoneNom = document.getElementById("name_region");
			var input = document.getElementById("inputIdRegion"); 
			if(zoneNom != null)
			{
				zoneNom.innerHTML = game.regions[game.lieuSelected]['Nom'];
				input.value = game.lieuSelected;
			}		
			popup.style.display = "block";
		}
		else if(game.accessibilite[game.lieuSelected] == 2)
		{
			var popup = document.getElementById("popup_interdiction_voyage");
			popup.style.display = "block";
		}
	}
}

function initialisationAccessibiliteLieux()
{
	var region = game.region;
	//0 : lieu actuel, //1: lieu accessible, //2: Lieu innaccesible
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


load();