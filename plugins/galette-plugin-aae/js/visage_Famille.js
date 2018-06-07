class Famille {

	constructor(options) {
		this.getPara(); // Parametres = utiles pour la génération du graph
		this.options = options; // Options = utiles pour le fonctionnement du script
		this.directParentCanvas = options.dom.directParentCanvas;
		this.parentCanvas = options.dom.parentCanvas;
		this.canvasId = options.dom.canvas;
		this.form = options.dom.form;
		this.canvas = document.getElementById(this.canvasId);

		this.idePerYear = {};
		this.firstYear = 3000;
		this.lastYear = 0;
		this.nbYears = 0;
		this.maxWidth = 0;
		this.selectedIDE = false;
		this.lastCoord = false;

		this.initForm();
	}

	initForm() {
		var that = this;

		// On affiche au clic sur le boutton
		$(this.form).find('#afficher').click(function(event) {
			event.preventDefault();
			$(that.form).find('#str-results').hide();
			that.load('afficher');
		});

		// On complète les noms tapés par l'utilisateur
		$(that.form).find('#str').keyup(function(event) {
			event.preventDefault();
			$(this).data('ide', false);
			if (event.keyCode == 38 || event.keyCode == 40) { // Flèches haut/bas
				var n = $(this).data('n');
				if (event.keyCode == 38) {n--; n = Math.max(n, 1);}
				else {n++; n = Math.min(n, $('#str-results li').length);}
				$(this).data('n', n);
				$('#str-results .selected').removeClass('selected');
				$('#str-results li').eq(n-1).addClass('selected');
			} else if(event.keyCode == 13) { // Entrée
				if ($('#str-results li.selected').length > 0) {
					var ide = $('#str-results li.selected').data('ide');
					that.load(ide);
				} else {
					$('#afficher').click();
				}
			} else {
				var str = $(this).val();
				if (str.length >= 2) {
					$.ajax({
						url : that.options.url.str.format(str),
						success : function(data) {
							$(this).data('n', 0);
							if (data && 'success' in data && data.success) {
								var html = $('');
								if (data.elements.length > 0) {
									$.each(data.elements, function() {
										var li = $('<li/>', {'data-ide': this.ide, html: $('<span/>', {html: '{0} {1}'.format(this.prenom, this.nom)})});
										html = html.add(li);
									});
								} else {
									html = $('<li/>', {addClass: 'collection-item', html: 'Aucun résultat.'});
								}
							} else {
								html = $('<li/>', {addClass: 'collection-item', html: 'Erreur. Aucun résultat.'});
							}
							$('#str-results').html(html);

							// Affichage et event
							$('#str-results').fadeIn();
							$('#str-results li').click(function() {
								that.load($(this).data('ide'));
							});
							//} else {$('#str-results').hide();}
						}
					});
				} else {$('#str-results').hide();}
			}
		});

		// On export l'image
		$('#export-small-screen, #export-large-screen').click(function() {
			try {
				var filename = 'arbre_{0}_{1}.png'.format(that.cible.prenom, that.cible.nom);
				ReImg.fromCanvas(that.canvas).downloadPng(filename);
			} catch (e) {
				console.log(e);
				Materialize.toast("Impossible d'exporter l'image...", 3000);
			}
		});

		that.load(); // La fonction regarde dans l'URL s'il y a un ide à exploiter
	}

	setForm(eleve) {
		$(this.form).find('#str').val(eleve.prenom + ' ' + eleve.nom).data('ide', true);
		$(this.form).find('label[for="str"]').addClass('active');
	}

	load(ide) {
		var that = this;

		$(this.parentCanvas).addClass('loading').removeClass('results');
		this.reset();

		// Charger un eleve en particulier ?
		var ide = ide || false;
		if (!ide || ($(this.form).find('#str').data('ide') == true && ide == 'afficher')) {ide = that.urlParam('ide');}

		if (ide && ide != 'afficher') {

			// Oui, Lancement du chargement de graph :
			var remonter = $(this.form).find('#remonter').is(':checked');
			$.ajax({
				url : this.options.url.graph.format(ide, remonter),
				type : 'GET',
				success : function(json) {
					var data;
					if (typeof json == 'string') {
						data = JSON.parse(json);
					} else {
						data = json;
					}
					// On va afficher le graph
					if (data.success === true) {
						that.loadEnd(data, true);
					} else {
						console.log('Other error !', data);
					}
				}
			});

		} else {

			// Non, Lancement du chargement de la complétion :
			var str = $(this.form).find('#str').val();
			if (str.length >= 2) {
				$.ajax({
					url : this.options.url.str.format(str),
					type : 'GET',
					success : function(json) {
						var data;
						if (typeof json == 'string') {
							data = JSON.parse(json);
						} else {
							data = json;
						}
						var html = $('<li/>', {addClass: 'collection-item', html: 'Erreur. Aucun résultat.'});
						if (data.success) {
							html = $('<li/>', {'addClass': 'collection-header', html: $('<h4/>', {html: '{0} résultat{1}'.format(data.elements.length, data.elements.length > 1 ? 's' : '')})});
							$.each(data.elements, function() {
								var li = $('<a/>', {'data-ide': this.ide, html: $('<span/>', {html: '{0} {1} ({2})'.format(this.prenom, this.nom, this.annee)}), addClass: 'collection-item', href: '#'});
								html = html.add(li);
							});
						}
						$(that.parentCanvas).find('#results').html(html);
						$(that.parentCanvas).find('#results a').click(function(event) {
							event.preventDefault();
							var ide = $(this).data('ide');
							famille.load(ide);
						});
						$(that.parentCanvas).removeClass('loading').addClass('results');
					}
				});
			} else {
				$(this.parentCanvas).removeClass('loading').removeClass('results');
			}
		}
	}

	loadEnd(data, histo) {
		var histo = histo || false;
		var that = this;

		that.ready = true;

		that.cible = data.cible;
		that.eleves = {};

		for (var ide in data.eleves) {
			if (data.eleves.hasOwnProperty(ide)) {
				if (data.eleves[ide]) {
					that.eleves[ide] = data.eleves[ide];
				} else {
					console.log("id_adh={0}".format(ide));
				}
			}
		}

		that.objectOriented();
		that.afficher();

		var titre = 'Arbre ENSG – {0} {1} ({2})'.format(data.cible.prenom, data.cible.nom, data.cible.annee);
		window.parent.document.title = titre;

		// Gestion de l'historique :
		if (histo) {
			history.pushState(JSON.parse(JSON.stringify(data)), '', "visage.php?ide={0}".format(data.cible.ide));
			window.onpopstate = function(event) {
				//console.log(event);
				that.reset();
				if (event.state && event.state.success) {
					that.loadEnd(event.state, false);
				}
			}
		}
	}

	objectOriented() {
		this.cibleYear = this.cible.annee;
		this.cible = new Eleve(this.cible, this.eleves, this.cibleYear);

		for (var ide in this.eleves) {
			if (this.eleves.hasOwnProperty(ide)) {
				this.eleves[ide] = new Eleve(this.eleves[ide], this.eleves, this.cibleYear);
				this.firstYear = Math.min(this.firstYear, this.eleves[ide].annee);
				this.lastYear = Math.max(this.lastYear, this.eleves[ide].annee);
				if (!this.idePerYear.hasOwnProperty(this.eleves[ide].annee)) {this.idePerYear[this.eleves[ide].annee] = [];}
				this.idePerYear[this.eleves[ide].annee].push(ide);
			}
		}
		this.nbYears = this.lastYear - this.firstYear + 1;
	}

	afficher(reinit) {
		var reinit = reinit || false;
		var that = this;

		if (reinit) {this.reinitEleves();}

		this.positionnerEleves();
		if (this.para.recentrer) {
			this.recentrer();
		}
		this.optimiserMinX();
		this.initCanvas();

		this.whenIsReady(function() {
			that.draw();
			$('#parentForm').addClass('active');
			$(that.parentCanvas).removeClass('loading');
			that.eleves[that.cible.ide].scrollTo(that.canvas, that.para);
		});
	}

	reinitEleves() {
		for (var ide in this.eleves) {
			if(this.eleves.hasOwnProperty(ide)) {
				this.eleves[ide].reinit();
			}
		}
	}

	positionnerEleves() {
		// First we set the position of the cible:
		this.maxWidth = Math.max(this.eleves[this.cible.ide].getWidthFillots(this.eleves), this.eleves[this.cible.ide].getWidthParrains(this.eleves));
		this.eleves[this.cible.ide].setX(this.maxWidth / 2);
		// Then his fillots (c'est recursif)
		this.eleves[this.cible.ide].setFillotsPosition(this.eleves);
		// Then his parrains (c'est recursif)
		this.eleves[this.cible.ide].setParrainsPosition(this.eleves);

		// On positionne les Y :
		for (var ide in this.eleves) {
			if (this.eleves.hasOwnProperty(ide)) {
				this.eleves[ide].setY(this.firstYear);
			}
		}
	}

	isReady() {
		var isReady = true;
		for (var ide in this.eleves) {
			if(this.eleves.hasOwnProperty(ide)) {
				isReady = isReady && this.eleves[ide].ready;
			}
		}
		return isReady;
	}

	whenIsReady(callback) {
		var that = this;
		setTimeout(function() {
			if (that.isReady()) {callback();} else {that.whenIsReady(callback);}
		}, 100);
	}

	recentrer() {
		// On recentre les parrains au centre de leurs fillots, par annee avant cible :
		for (var year = this.lastYear; year > this.cibleYear; year--) {
			if (this.idePerYear.hasOwnProperty(year)) {
				var ides = this.idePerYear[year];
				for (var i = 0; i < ides.length; i++) {
					this.eleves[ides[i]].recentrerFillots(this.eleves);
				}
			}
		}

		// On recentre les fillots au centre de leurs parrains, par annee après cible :
		for (var year = this.firstYear; year < this.cibleYear; year++) {
			if (this.idePerYear.hasOwnProperty(year)) {
				var ides = this.idePerYear[year];
				for (var i = 0; i < ides.length; i++) {
					this.eleves[ides[i]].recentrerParrains(this.eleves);
				}
			}
		}

		// On recentre la cible au milieu de tous ses liens :

		// D'abord on recentre la cible par rapport à ses fillots :
		this.eleves[this.cible.ide].recentrerFillots(this.eleves);

		// Maintenant on calcul la nouvelle position (en x) qu'il FAUDRAIT pour qu'il soit centré sur ses parrains :
		var x = this.eleves[this.cible.ide].recentrerParrains(this.eleves, true);
		// On calcul l'inverse du dx dont il FAUDRAIT le décaler :
		var dx = -1 * (x - this.eleves[this.cible.ide].x);

		// Enfin on décale toute l'ascendance de dx :
		for (var year = this.firstYear; year < this.cibleYear; year++) {
			if (this.idePerYear.hasOwnProperty(year)) {
				var ides = this.idePerYear[year];
				for (var i = 0; i < ides.length; i++) {
					this.eleves[ides[i]].x += dx;
				}
			}
		}
	}

	optimiserMinX() {
		var that = this;

		// optimiserMinX : on décale tout pour bien avoir minX = 0
		var minX = Number.MAX_SAFE_INTEGER, maxX = 0;

		// Recuperation du minX
		for (var ide in that.eleves) {
			if(that.eleves.hasOwnProperty(ide)) {
				minX = Math.min(minX, that.eleves[ide].x);
			}
		}
		// décalage en x
		var dx = -minX;
		for (var ide in that.eleves) {
			if(that.eleves.hasOwnProperty(ide)) {
				that.eleves[ide].x += dx;
				maxX = Math.max(maxX, that.eleves[ide].x);
			}
		}
		this.maxWidth = maxX;
	}

	initCanvas() {
		var that = this;
		// Init canvas :
		$(directParentCanvas).html('<canvas id="canvas" width="400px" height="400px"></canvas>');

		that.canvas = document.getElementById(this.canvasId);
		//that.canvasDirectParent = that.canvas.parentElement;

		that.canvas.setAttribute('display', 'inline');
		that.canvas.setAttribute('height', that.para.w * that.para.f * this.nbYears);
		var width = that.para.w * that.para.f * (this.maxWidth + 1) + parseInt(that.para.w * 0.75);
		if (width > 1000) {width += parseInt(that.para.w * 1.25);} // Deux fois les années
		that.canvas.setAttribute('width', width);

		that.canvas.addEventListener('contextmenu', function(event) {that.menuClick(event);});
		that.canvas.addEventListener('click', function(event) {that.canvasClick(event);});
		that.canvas.addEventListener('mousemove', function(event) {that.canvasMousemove(event);});
		that.canvas.addEventListener('mouseout', function(event) {that.canvasMouseout(event);});

		that.ctx = that.canvas.getContext('2d');
		that.ctx.textAlign = 'center';
	}

	menuClick(event) {
		var that = this;
		if (!that.ready) {return;}

		var coord = that.getEventCanvasCoord(event, that.canvas);
		var ide = that.getSelectedIDE(coord);
		if (ide) {
			$('#info').hide();
			that.inverserEleve(ide);
			event.preventDefault();
		}
	}

	getSelectedIDE(coord) {
		// Suffisament proche des anciennes coordonnées ?
		if (this.lastCoord) {
			var dist = Math.pow(this.lastCoord.x - coord.x, 2) + Math.pow(this.lastCoord.y - coord.y, 2);
			var distMax = Math.pow(this.para.w, 2);
			if (dist <= distMax) {return this.selectedIDE;}
		}

		// Non
		for (var ide in this.eleves) {
			if(this.eleves.hasOwnProperty(ide)) {
				var s = this.eleves[ide].tryToSelect(this.eleves, coord, this.para);
				if (s) {return ide;}
			}
		}
		return false;
	}

	canvasClick(event) {
		var that = this;
		if (!that.ready) {return;}

		if (document.body.clientWidth > 900) {
			var coord = that.getEventCanvasCoord(event, that.canvas);
			var ide = that.getSelectedIDE(coord);
			if (ide) {
				$('#info').hide();
				that.load(that.eleves[ide].ide);
				$('#popover').hide();
			}
		}
	}

	canvasMousemove(event) {
		var that = this;
		if (!that.ready) {return;}

		var coord = that.getEventCanvasCoord(event, that.canvas);
		var ide = that.getSelectedIDE(coord);

		if (ide) {

			that.canvas.style.cursor = 'pointer';

			if (ide != that.selectedIDE) {
				that.draw();
				that.selectedIDE = ide;
				that.lastCoord = coord;
				var eleve = that.eleves[ide];

				// I - Ajout des info

				// I-1) Titre
				$('#popoverTitle').html('{0} {1} ({2})'.format(eleve.prenom, eleve.nom.capitalize(), eleve.annee));

				// I-2) Informations
				
				var lienContacter = 'http://adherents.aae-ensg.eu/plugins/galette-plugin-aae/send_message.php?id_adh={0}'.format(eleve.ide);
				//var lienProfil = '#lien{0}'.format(eleve.ide);
				var table = $('<table/>');
				var line = $('<tr/>').appendTo(table);
				var imageContainer = $('<td/>', {addClass: 'image-container center-align'}).appendTo(line);
				var infoContainer = $('<td/>', {addClass: 'profil-container'}).appendTo(line);
				var image = $('<img/>', {src: eleve.src}).appendTo(imageContainer);
				//$('<p/>', {html: '{0} {1}'.format(eleve.prenom, eleve.nom), addClass: 'identite'}).appendTo(infoContainer);
				$('<p/>', {html: 'Promotion : {0}'.format(eleve.annee), addClass: 'promotion'}).appendTo(infoContainer);
				//$('<p/>', {html: 'Mail : <a href="mailto:{0}">{0}</a>'.format(eleve.mail||'toto@tata.fr'), addClass: 'promotion'}).appendTo(infoContainer);
				$('<a/>', {href: lienContacter, html: 'Contacter', addClass: 'display-block big-screen', target: '_blank'}).appendTo(infoContainer);
				//$('<a/>', {href: lienProfil, html: 'Consulter le profil AAE', addClass: 'display-block big-screen', target: '_blank'}).appendTo(infoContainer);
				$('<a/>', {href: '#', 'data-ide': eleve.ide, html: 'Voir l\'arbre', addClass: 'show-graph display-block big-screen'}).click(function(event) {
					event.preventDefault();
					that.load($(this).data('ide'));
				}).appendTo(infoContainer);
				// Other lines for litte screens :
				//var openProfil = $('<a/>', {href: lienProfil, html: 'Consulter le profil AAE', addClass: 'display-block center-align', target: '_blank'});
				var openGraph = $('<a/>', {href: '#', 'data-ide': eleve.ide, html: 'Voir l\'arbre', addClass: 'show-graph display-block center-align'}).click(function() {
					event.preventDefault();
					that.load($(this).data('ide'));
				});
				//$('<tr/>', {html: $('<td/>', {html: openProfil, colspan: 2}), addClass: 'little-screen cente'}).appendTo(table);
				$('<tr/>', {html: $('<td/>', {html: openGraph, colspan: 2}), addClass: 'little-screen cente'}).appendTo(table);
				$('#popoverContent').empty().append(table);

				// II - Positionement popover :
				$('#popover').removeClass('top').removeClass('right').removeClass('left').addClass('bottom');
				$('#popover .arrow').css({'margin-top': ''});

				// II-1) Calculate position brute :
				var x = eleve.getX(that.para, 0);
				var y = eleve.getY(that.para, 0);

				// II-2) Canevas correction :
				x += that.canvas.offsetLeft;
				y += that.canvas.offsetTop;

				// II-3) directParentCanvas scroll correction
				var scrollBase = $("#directParentCanvas").scrollLeft();
				x -= scrollBase;
				$("#directParentCanvas").unbind('scroll.popover').bind('scroll.popover', function(event) {
					var left = parseInt($('#popover').css('left'));
					left += scrollBase;
					scrollBase = $("#directParentCanvas").scrollLeft();
					left -= scrollBase;
					$('#popover').css('left', left);
				});

				// II-4) Top or not top ?
				var top = $(that.canvas).height() < y + $('#popover').height();

				// II-5) Element size correction :
				var xBottom = x - $('#popover').width() / 2;
				var yBottom = y + that.para.w / 2 + that.para.fontSize;

				// II-6) Posinement non corrigé plus affichage :
				$('#popover').css({left: xBottom, top: yBottom}).show();

				// III - Vérification positionnempent :
				var domRectPopover = document.getElementById('popover').getBoundingClientRect();
				var domRectCanvas = that.canvas.getBoundingClientRect();
				var yReTest = false;

				// III-1) Need a top position ?
				var inDocumentBottom = domRectPopover.bottom < domRectCanvas.bottom;
				var inViewportBottom = domRectPopover.bottom <= (window.innerHeight || document.documentElement.clientHeight);
				if (!inDocumentBottom || !inViewportBottom) {
					// On passe en mode top :
					var yTop = y - $('#popover').height() - that.para.w / 2 - 10; // 10 = arrow
					$('#popover').css({top: yTop}).addClass('top').removeClass('bottom');
				}

				// III-2) Need a left position ?
				var inDocumentRight = domRectPopover.right < domRectCanvas.right;
				var inViewportRight = domRectPopover.right <= (window.innerWidth || document.documentElement.clientWidth);
				if (!inDocumentRight || !inViewportRight) {
					// On passe en mode top :
					var xLeft = x - $('#popover').width() - that.para.w / 2 - 10; // 10 = arrow
					var yLeft = y - $('#popover').height() / 2;
					$('#popover').css({left: xLeft, top: yLeft}).addClass('left').removeClass('bottom').removeClass('top');

					// Update rect :
					yReTest = yLeft;
				}

				// III-3) Need a right position ?
				var inDocumentLeft = domRectPopover.left > domRectCanvas.left;
				var inViewportLeft = domRectPopover.left > 0;
				if (!inDocumentLeft || !inViewportLeft) {
					// On passe en mode top :
					var xRight = x + that.para.w / 2 + 10; // 10 = arrow
					var yRight = y - $('#popover').height() / 2;
					$('#popover').css({left: xRight, top: yRight}).addClass('right').removeClass('bottom').removeClass('top');

					// Update rect :
					yReTest = yRight;
				}

				// III-4) Need a restest ?
				if (yReTest !== false) {
					domRectPopover = document.getElementById('popover').getBoundingClientRect();
					domRectCanvas = that.canvas.getBoundingClientRect();
					inDocumentBottom = domRectPopover.bottom < domRectCanvas.bottom;
					inViewportBottom = domRectPopover.bottom < (window.innerHeight || document.documentElement.clientHeight);
					if (!inDocumentBottom || !inViewportBottom) {
						// Ne rentre pas de combien ?
						var delta = Math.max(domRectPopover.bottom - domRectCanvas.bottom, domRectPopover.bottom - (window.innerHeight || document.documentElement.clientHeight));
						yReTest -= delta;
						$('#popover').css({top: yReTest});
						$('#popover .arrow').css({'margin-top': delta - 11})
					}
				}
			}

		} else {

			$('#popover').hide();
			that.canvas.style.cursor = 'auto';
			that.lastCoord = false;
			if (that.selectedIDE) {
				that.selectedIDE = false;
				that.draw();
			}

		}
	}

	canvasMouseout(event) {
		var that = this;
		if (!that.ready) {return;}

		that.getSelectedIDE({x: -that.para.w, y: -that.para.w});
		$('#info').hide();
		that.canvas.style.cursor = 'auto';
	}

	getEventCanvasCoord(event, canvas) {
		var coord = {x: 0, y: 0};

		if (event.offsetX) {
			coord.x = event.offsetX;
		} else if (event.clientX) {
			coord.x = event.clientX - canvas.offsetLeft;
		}

		if (event.offsetY) {//chrome and IE
			coord.y = event.offsetY;
		} else if (event.clientY) {// FF
			coord.y = event.clientY - canvas.offsetTop;
		}

		return coord;
	}

	draw() {
		var that = this;

		// on affiche le nom de l'élève clairement
		that.setForm(that.cible);
		$('#popover').hide();

		// On nettoie l'affichage
		that.ctx.clearRect(0, 0, that.canvas.width, that.canvas.height);

		// Fond blanc pour l'exports
		that.ctx.fillStyle = '#FFFFFF';
		that.ctx.fillRect(0, 0, that.canvas.width, that.canvas.height); // #e2e2e2

		// Draw year numbers and lines: (Year size : 0.75w, il faudra décaler toute le reste !)
		var i = 0;
		that.ctx.font = parseInt(that.para.w * 0.75) + 'px Roboto';
		that.ctx.setLineDash([3, 5]);
		that.ctx.fillStyle = '#000000';
		for (var year = that.firstYear; year <= that.lastYear; year++) {

			// Le texte de l'année à droite : (orienté à -90°)
			that.ctx.save();
			that.ctx.translate(that.para.w * 0.8, (i + 0.5) * that.para.w * that.para.f);
			that.ctx.rotate(-Math.PI/2);
			that.ctx.fillText('{0}'.format(year), 0, 0);
			that.ctx.restore();

			// Le texte de l'année à gauche : (orienté à +90°)
			if (that.canvas.width > 1000) {
				that.ctx.save();
				that.ctx.translate(that.canvas.width - that.para.w * 0.25, (i + 0.5) * that.para.w * that.para.f);
				that.ctx.rotate(-Math.PI/2);
				that.ctx.fillText('{0}'.format(year), 0, 0);
				that.ctx.restore();
			}

			i++;

			// La ligne de démarquation
			if (i != this.nbYears) {
				var y = i * that.para.w * that.para.f;
				that.ctx.beginPath();
				that.ctx.moveTo(0, y);
				that.ctx.lineTo(that.canvas.width, y);
				that.ctx.stroke();
			}
		}

		// Draw relations between students:
		var sum = 0;
		that.ctx.setLineDash([]);
		for (var ide in that.eleves) {
			if(that.eleves.hasOwnProperty(ide)) {
				sum += that.eleves[ide].drawRelations(that.ctx, that.para, that.eleves);
			}
		}
		// console.log('Sum length : ', sum);

		// Draw students:
		that.ctx.font = that.para.fontSize + 'px Roboto';
		for (var ide in that.eleves) {
			if(that.eleves.hasOwnProperty(ide)) {
				that.eleves[ide].draw(that.ctx, that.para, that.cible.ide);
			}
		}
	}

	inverserEleve(ide) {
		if (this.cible.annee < this.eleves[ide].annee) {
			// On change l'ordre des fillots de l'élève :
			this.eleves[ide].ordre++;
			this.eleves[ide].ordonnerFillots(this.eleves);
		} else if (this.cible.annee > this.eleves[ide].annee) {
			// On change l'ordre des parrains de l'élève :
			this.eleves[ide].ordre++;
			this.eleves[ide].ordonnerParrains(this.eleves);
		} else {return;}

		// Reset et réaffichage :
		this.afficher();
	}

	reset() {
		// On masque les trucs qui sont apparus :
		$('#str-results').hide();
		$('#parentForm').removeClass('active');

		// On reset les attributs
		this.idePerYear = {};
		this.firstYear = 3000;
		this.lastYear = 0;
		this.nbYears = 0;
		this.maxWidth = 0;
		this.selectedIDE = false;

		this.ready = false;
		this.eleves = [];
		this.cible = false;
		this.canvas.setAttribute('display', 'none');

		this.getPara();
	}

	getPara() {
		var defPara = {
			w : 40,
			f : 2.5,
			fontSize : 14,
			recentrer : true,
		};

		var para = {
			recentrer : $('#recentrer').is(':checked'),
			w : parseInt($('#size').val()),
			fontSize : parseInt($('#fontSize').val()),
		};

		for (var key in defPara) {
			if (defPara.hasOwnProperty(key)) {
				if (!para.hasOwnProperty(key)) {para[key] = defPara[key];}
			}
		}

		this.para = para;
	}

	getEleveByPrenom(prenom) {
		var ides = [];
		for (var ide in this.eleves) {
			if (this.eleves.hasOwnProperty(ide)) {
				if (this.eleves[ide].prenom == prenom) {ides.push(ide);}
			}
		}
		return ides;
	}

	urlParam(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results == null) {return null;}
		else {return decodeURI(results[1]) || 0;}
	}


}
