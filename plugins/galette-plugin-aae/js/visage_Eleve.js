class Eleve { 

	constructor(data, eleves) {
		this.ide = parseInt(data.ide);
		this.nom = data.nom;
		this.prenom = data.prenom;
		this.annee = parseInt(data.annee);
		this.setFillots(data.fillots, eleves);
		this.setParrains(data.parrains, eleves);
		this.src = data.src;
		this.isPlaced = false;
		this.selected = false;
		this.ordre = 0;

		this.x = null;
		this.y = null;
		this.parrainPrincipal = false;
		this.fillotPrincipal = false;
		this.widthFillots = false;
		this.widthParrains = false;
		this.image = new Image();
		this.ready = false;
		this.loadImage();
	}

	getWidthFillots(eleves, ide) {
		var ide = ide || false; if (!ide) {return 0;}

		if (eleves[this.ide].parrainPrincipal === false) {
			// On fixe le parrain principal
			eleves[this.ide].parrainPrincipal = ide;

			// On calcul la width :
			var width = 0;
			if (this.fillots.length > 0) {
				// Si l'élève a des fillots, somme de leur width
				for (var i = 0; i < this.fillots.length; i++) {
					width += eleves[this.fillots[i]].getWidthFillots(eleves, this.ide);
				}
			}
			eleves[this.ide].widthFillots = Math.max(width, 1); // Minimum 1, toujours
		}
		if (eleves[this.ide].parrainPrincipal == ide) {
			// Width deja calculé : on la retourne
			return eleves[this.ide].widthFillots;
		} else {
			// On ne compte pas pour ce parrain
			return 0;
		}
	}

	getWidthParrains(eleves, ide) {
		var ide = ide || false;
		if (eleves[this.ide].fillotPrincipal === false) {
			// On fixe le fillot principal
			eleves[this.ide].fillotPrincipal = ide;

			// On calcul la width :
			var width = 0;
			if (this.parrains.length > 0) {
				// Si l'élève a des parrains, somme de leur width
				for (var i = 0; i < this.parrains.length; i++) {
					width += eleves[this.parrains[i]].getWidthParrains(eleves);
				}
			} else {
				// Sinon 1
				width = 1;
			}
			eleves[this.ide].widthParrains = Math.max(width, 1); // Minimum 1, toujours
		}
		if (eleves[this.ide].fillotPrincipal == ide) {
			// Width deja calculé : on la retourne
			return eleves[this.ide].widthParrains;
		} else {
			// On ne compte pas pour ce fillot
			return 0;
		}
	}

	setFillots(fillots, eleves) {
		this.f1 = false;
		this.f2 = false;
		this.f3 = false;
		this.fillots = [];

		for (var i = 0; i < fillots.length; i++) {
			if (eleves.hasOwnProperty(fillots[i])) {this.fillots.push(fillots[i]);}
		}

		if (this.fillots.length >= 1) this.f1 = this.fillots[0];
		if (this.fillots.length >= 2) this.f2 = this.fillots[1];
		if (this.fillots.length >= 3) this.f3 = this.fillots[2];
	}

	setParrains(parrains, eleves) {
		this.p1 = false;
		this.p2 = false;
		this.p3 = false;
		this.parrains = [];

		for (var i = 0; i < parrains.length; i++) {
			if (eleves.hasOwnProperty(parrains[i])) {this.parrains.push(parrains[i]);}
		}

		if (this.parrains.length >= 1) this.p1 = this.parrains[0];
		if (this.parrains.length >= 2) this.p2 = this.parrains[1];
		if (this.parrains.length >= 3) this.p3 = this.parrains[2];
	}

	setFillotsPosition(eleves) {
		// On positionne les fillots au centre de leur "zone" :
		// [             zone parrain (6)            ]
		// [ zone f1 (2) | zone f2 (1) | zone f3 (3) ]

		var wf1, wf2, wf3;
		if (this.f1) {wf1 = eleves[this.f1].getWidthFillots(eleves, this.ide);}
		if (this.f2) {wf2 = eleves[this.f2].getWidthFillots(eleves, this.ide);}
		if (this.f3) {wf3 = eleves[this.f3].getWidthFillots(eleves, this.ide);}

		if (this.f1 && wf1 != 0) {
			// On a f1.x = this.x - this.getWidthFillots() / 2 + f1.getWidthFillots() / 2
			eleves[this.f1].setX(this.x - this.widthFillots / 2 + wf1 / 2);
			eleves[this.f1].setFillotsPosition(eleves);
		}
		if (this.f2 && wf2 != 0) {
			// On a f2.x = this.x - this.getWidthFillots() / 2 + f1.getWidthFillots() - f2.getWidthFillots() / 2
			eleves[this.f2].setX(this.x - this.widthFillots / 2 + wf1 + wf2 / 2);
			eleves[this.f2].setFillotsPosition(eleves);
		}
		if (this.f3 && wf3) {
			// On a f3.x = this.x + this.getWidthFillots() / 2 - f3.getWidthFillots() / 2
			eleves[this.f3].setX(this.x + this.widthFillots / 2 - wf3 / 2);
			eleves[this.f3].setFillotsPosition(eleves);
		}
	}

	setParrainsPosition(eleves) {
		// On positionne les parrains au centre de leur "zone" :
		// [ zone p1 (2) | zone p2 (1) | zone p3 (3) ]
		// [              zone fillot (6)            ]

		var wp1, wp2, wp3;
		if (this.p1) {wp1 = eleves[this.p1].getWidthParrains(eleves, this.ide);}
		if (this.p2) {wp2 = eleves[this.p2].getWidthParrains(eleves, this.ide);}
		if (this.p3) {wp3 = eleves[this.p3].getWidthParrains(eleves, this.ide);}

		if (this.p1 && wp1 != 0) {
			// On a p1.x = this.x - this.getWidthParrains() / 2 + p1.getWidthParrains() / 2
			eleves[this.p1].setX(this.x - this.widthParrains / 2 + wp1 / 2);
			eleves[this.p1].setParrainsPosition(eleves);
		}
		if (this.p2 && wp2 != 0) {
			// On a p2.x = this.x - this.getWidthParrains() / 2 + p1.getWidthParrains() - p2.getWidthParrains() / 2
			eleves[this.p2].setX(this.x - this.widthParrains / 2 + wp1 + wp2 / 2);
			eleves[this.p2].setParrainsPosition(eleves);
		}
		if (this.p3 && wp3) {
			// On a p3.x = this.x + this.getWidthParrains() / 2 - p3.getWidthParrains() / 2
			eleves[this.p3].setX(this.x + this.widthParrains / 2 - wp3 / 2);
			eleves[this.p3].setParrainsPosition(eleves);
		}
	}

	recentrerParrains(eleves, retourner) {
		var retourner = retourner || false;
		// On recentrer l'eleve par rapport à ses parrains
		var totX = 0, totLiens = 0;

		if (this.parrains.length > 0) {
			for (var i = 0; i < this.parrains.length; i++) {
				if (eleves[this.parrains[i]].getWidthParrains(eleves, this.ide) != 0) {
					totX += eleves[this.parrains[i]].x;
					totLiens++;
				}
			}
		}

		if (retourner == false) {
			if (totX > 0 && totLiens > 0) {eleves[this.ide].x = totX / totLiens;}
		} else {
			return (totX > 0 && totLiens > 0 ? totX / totLiens : eleves[this.ide].x);
		}
	}

	recentrerFillots(eleves, retourner) {
		var retourner = retourner || false;
		// On recentrer l'eleve par rapport à ses fillots
		var totX = 0, totLiens = 0;

		if (this.fillots.length > 0) {
			for (var i = 0; i < this.fillots.length; i++) {
				if (eleves[this.fillots[i]].getWidthFillots(eleves, this.ide) != 0) {
					totX += eleves[this.fillots[i]].x;
					totLiens++;
				}
			}
		}

		if (retourner == false) {
			if (totX > 0 && totLiens > 0) {eleves[this.ide].x = totX / totLiens;}
		} else {
			return (totX > 0 && totLiens > 0 ? totX / totLiens : eleves[this.ide].x);
		}
	}

	ordonnerFillots(eleves) {
		if (eleves[this.ide].fillots.length == 2) {
			eleves[this.ide].ordre = eleves[this.ide].ordre % 2;
			if (eleves[this.ide].ordre == 0) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[0];
				eleves[this.ide].f2 = eleves[this.ide].fillots[1];
			} else if (eleves[this.ide].ordre == 1) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[1];
				eleves[this.ide].f2 = eleves[this.ide].fillots[0];
			}
		} else if (eleves[this.ide].fillots.length == 3) {
			eleves[this.ide].ordre = eleves[this.ide].ordre % 6;
			if (eleves[this.ide].ordre == 0) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[0];
				eleves[this.ide].f2 = eleves[this.ide].fillots[1];
				eleves[this.ide].f3 = eleves[this.ide].fillots[2];
			} else if (eleves[this.ide].ordre == 1) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[0];
				eleves[this.ide].f2 = eleves[this.ide].fillots[2];
				eleves[this.ide].f3 = eleves[this.ide].fillots[1];
			} else if (eleves[this.ide].ordre == 2) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[1];
				eleves[this.ide].f2 = eleves[this.ide].fillots[0];
				eleves[this.ide].f3 = eleves[this.ide].fillots[2];
			} else if (eleves[this.ide].ordre == 3) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[2];
				eleves[this.ide].f2 = eleves[this.ide].fillots[0];
				eleves[this.ide].f3 = eleves[this.ide].fillots[1];
			} else if (eleves[this.ide].ordre == 4) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[1];
				eleves[this.ide].f2 = eleves[this.ide].fillots[2];
				eleves[this.ide].f3 = eleves[this.ide].fillots[0];
			} else if (eleves[this.ide].ordre == 5) {
				eleves[this.ide].f1 = eleves[this.ide].fillots[2];
				eleves[this.ide].f2 = eleves[this.ide].fillots[1];
				eleves[this.ide].f3 = eleves[this.ide].fillots[0];
			}
		}
	}

	ordonnerParrains(eleves) {
		if (eleves[this.ide].parrains.length == 2) {
			eleves[this.ide].ordre = eleves[this.ide].ordre % 2;
			if (eleves[this.ide].ordre == 0) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[0];
				eleves[this.ide].p2 = eleves[this.ide].parrains[1];
			} else if (eleves[this.ide].ordre == 1) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[1];
				eleves[this.ide].p2 = eleves[this.ide].parrains[0];
			}
		} else if (eleves[this.ide].parrains.length == 3) {
			eleves[this.ide].ordre = eleves[this.ide].ordre % 6;
			if (eleves[this.ide].ordre == 0) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[0];
				eleves[this.ide].p2 = eleves[this.ide].parrains[1];
				eleves[this.ide].p3 = eleves[this.ide].parrains[2];
			} else if (eleves[this.ide].ordre == 1) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[0];
				eleves[this.ide].p2 = eleves[this.ide].parrains[2];
				eleves[this.ide].p3 = eleves[this.ide].parrains[1];
			} else if (eleves[this.ide].ordre == 2) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[1];
				eleves[this.ide].p2 = eleves[this.ide].parrains[0];
				eleves[this.ide].p3 = eleves[this.ide].parrains[2];
			} else if (eleves[this.ide].ordre == 3) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[2];
				eleves[this.ide].p2 = eleves[this.ide].parrains[0];
				eleves[this.ide].p3 = eleves[this.ide].parrains[1];
			} else if (eleves[this.ide].ordre == 4) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[1];
				eleves[this.ide].p2 = eleves[this.ide].parrains[2];
				eleves[this.ide].p3 = eleves[this.ide].parrains[0];
			} else if (eleves[this.ide].ordre == 5) {
				eleves[this.ide].p1 = eleves[this.ide].parrains[2];
				eleves[this.ide].p2 = eleves[this.ide].parrains[1];
				eleves[this.ide].p3 = eleves[this.ide].parrains[0];
			}
		}
	}

	tryToSelect(eleves, coord, para) {
		var dx = this.getX(para) - coord.x;
		var dy = this.getY(para) - coord.y;
		var dist = Math.abs(dx * dx + dy * dy);
		eleves[this.ide].selected = (2 * dist <= para.w * para.w);
		return eleves[this.ide].selected;
	}

	reinit() {
		this.isPlaced = false;
		this.selected = false;

		this.x = null;
		this.y = null;
		this.parrainPrincipal = false;
		this.fillotPrincipal = false;
		this.widthFillots = false;
		this.widthParrains = false;
	}

	loadImage() {
		var that = this;
		that.image.addEventListener("load", function() {
			that.ready = true;
		});
		that.image.src = that.src;
	}

	setX(x) {
		this.x = x;
		this.isPlaced = true;
	}

	setY(firstYear) {
		this.y = this.annee - firstYear + 0.5;
	}

	getX(para, p) {
		var p = p || 0;
		return (this.x + 0.5) * para.w * para.f + p + parseInt(para.w * 0.75);
	}

	getY(para, p) {
		var p = p || 0;
		return this.y * para.w * para.f + p;
	}

	draw(ctx, para, ideCible) {
		var that = this;
		// Draw eleve : fond rouge (si selectionné ou cible) ou noir
		if (this.selected || this.ide == ideCible) {ctx.fillStyle = '#b30019'; ctx.strokeStyle = '#b30019';}
		ctx.drawCircle(that.getX(para), that.getY(para), para.w / 2 + 2);
		if (this.selected || this.ide == ideCible) {ctx.fillStyle = '#000000'; ctx.strokeStyle = '#000000';}
		// Draw eleve : image arrondie
		ctx.drawRoundedSquareCenteredImage(that.image, that.getX(para, -para.w / 2), that.getY(para, -para.w / 2), para.w, (para.w / 2) * 1.1);
		// Draw eleve : prenom
		ctx.fillText('{0} {1}'.format(that.prenom, that.nom), that.getX(para), that.getY(para, para.w / 2 + para.fontSize));
	}

	drawRelations(ctx, para, eleves) {
		var that = this;
		var sum = 0;
		// Draw relation between this and fillots
		$.each(that.fillots, function(i, f) {
			var fillot = eleves[f];
			ctx.beginPath();
			ctx.moveTo(that.getX(para), that.getY(para));
			if (((that.fillotPrincipal === false && fillot.parrainPrincipal != that.ide) || (that.getWidthParrains(eleves, fillot.ide) == 0 && fillot.parrainPrincipal != that.ide)) && Math.abs(that.x - fillot.x) > 1) {
				// Relation secondaire, courbe
				var Xm = (that.getX(para) + fillot.getX(para)) / 2;
				var Ym = (that.getY(para) + fillot.getY(para)) / 2;
				var Y1 = (that.getY(para) + 1.2 * fillot.getY(para)) / 2.2;
				var Y2 = (that.getY(para) * 1.2 + fillot.getY(para)) / 2.2;
				ctx.quadraticCurveTo(that.getX(para), Y1, Xm, Ym);
				ctx.quadraticCurveTo(fillot.getX(para), Y2, fillot.getX(para), fillot.getY(para));
			} else {
				ctx.lineTo(fillot.getX(para), fillot.getY(para));
			}
			ctx.stroke();
			sum += Math.sqrt(Math.pow(that.getX(para) - fillot.getX(para), 2) + Math.pow(that.getY(para) - fillot.getY(para), 2));
		});
		return sum;
	}

	scrollTo(canvas, para) {
		// I - Scroll in page
 
		// Calculate eleve position:
		var x = this.getX(para, -para.w / 2);
		var y = this.getY(para, -para.w / 2);

		// Screen size :
		var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

		// Remove the half of the screen:
		x -= width / 2;
		y -= height / 2;

		// Add canvas offset:
		x += $(canvas).offset().left;
		y += $(canvas).offset().top;

		// Scroll to:
		$("body, html").animate({
			scrollLeft: x,
			scrollTop: y
		}, 600);

		// II - Scroll in div

		// Calculate eleve position:
		var x = this.getX(para, -para.w / 2);

		// Div size :
		var width = $("#directParentCanvas").width();

		// Remove the half of the div:
		x -= width / 2;
		y -= height / 2;

		// Scroll to:
		$("#directParentCanvas").animate({
			scrollLeft: x,
		}, 600);
	}

}