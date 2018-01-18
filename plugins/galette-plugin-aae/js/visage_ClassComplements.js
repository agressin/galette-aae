CanvasRenderingContext2D.prototype.drawRoundedImage = function(img, x, y, width, height, radius) {
	this.save();
	// Rounded
	this.beginPath();
	this.moveTo(x + radius, y);
	this.lineTo(x + width - radius, y);
	this.quadraticCurveTo(x + width, y, x + width, y + radius);
	this.lineTo(x + width, y + height - radius);
	this.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
	this.lineTo(x + radius, y + height);
	this.quadraticCurveTo(x, y + height, x, y + height - radius);
	this.lineTo(x, y + radius);
	this.quadraticCurveTo(x, y, x + radius, y);
	this.closePath();
	// Rounded end
	this.clip();
	this.drawImage(img, x, y, width, height);
	this.restore();
}

CanvasRenderingContext2D.prototype.drawRoundedSquareCenteredImage = function(img, x, y, w, radius) {
	this.save();
	// Rounded
	this.beginPath();
	this.moveTo(x + radius, y);
	this.lineTo(x + w - radius, y);
	this.quadraticCurveTo(x + w, y, x + w, y + radius);
	this.lineTo(x + w, y + w - radius);
	this.quadraticCurveTo(x + w, y + w, x + w - radius, y + w);
	this.lineTo(x + radius, y + w);
	this.quadraticCurveTo(x, y + w, x, y + w - radius);
	this.lineTo(x, y + radius);
	this.quadraticCurveTo(x, y, x + radius, y);
	this.closePath();
	// Rounded end
	this.clip();
	this.drawSquareCenteredImage(img, x, y, w);
	this.restore();
}

CanvasRenderingContext2D.prototype.drawSquareCenteredImage = function(img, x, y, w) {
	var width = img.width;
	var height = img.height;

	if (width != height) {
		var sx = 0, sy = 0;

		if (width > height) {sx = (width - height) / 2;}
		if (width < height) {sy = (height - width) / 2;}
		var sw = Math.min(width, height);

		this.drawImage(img, sx, sy, sw, sw, x, y, w, w);
	} else {
		this.drawImage(img, x, y, w, w);
	}
}

CanvasRenderingContext2D.prototype.drawCircle = function(x, y, d) {
	this.beginPath();
	this.arc(x, y, d, 0, 2 * Math.PI);
	this.closePath();
	this.fill();
}

String.prototype.format = String.prototype.format ||
function () {
	"use strict";
	var str = this.toString();
	if (arguments.length) {
		var t = typeof arguments[0];
		var key;
		var args = ("string" === t || "number" === t) ?
			Array.prototype.slice.call(arguments)
			: arguments[0];

		for (key in args) {
			str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
		}
	}

	return str;
};

String.prototype.capitalize = function() {
	var str = this.toLowerCase();
	return str.replace( /(^|[\s-])([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

String.prototype.limit = function(length) {
	var length = length || 15;
	return this.length > length ? '{0}...'.format(this.substring(0, length)) : this;
};