var mouse = {
	click: 0,
	move: false,
	pos: []
}
var keyboard = {
	char: '  ',
	code: -1
}
var wheel = {
	y: 0
}

document.getElementById("canvas").addEventListener("mousemove", function (e) {
	mouse.pos[0] = e.offsetX;
	mouse.pos[1] = e.offsetY;
});
document.getElementById("canvas").addEventListener("mousedown", function (e) {
	mouse.click = e.button + 1;
});
document.getElementById("canvas").addEventListener("mouseup", function (e) {
	mouse.click = 0;
});
document.addEventListener("keypress", function (e) {
	keyboard.char = e.key;
});
document.addEventListener("keydown", function (e) {
	keyboard.code = e.keyCode;
});
document.addEventListener("keyup", function (e) {
	keyboard.char = '  ';
	keyboard.code = -1;
});
document.getElementById("canvas").addEventListener("mousewheel", function (e) {
	wheel.y = e.deltaY;
});

function circle(x, y, r) {
	ctx.beginPath();
	ctx.arc(x, y, r, 0, 2 * Math.PI, );
	ctx.fill();
	ctx.stroke();
}

function sin(x) {
	return Math.sin(x * Math.PI / 180);
}

function cos(x) {
	return Math.cos(x * Math.PI / 180);
}

function asin(x) {
	return Math.asin(x) * Math.PI / 180;
}

function acos(x) {
	return Math.acos(x) * Math.PI / 180;
}

function timer() {
	let ms = 0;
	return function (time) {
		ms++;
		if (ms > time) {
			ms = 0;
			return true;
		} else {
			return false
		}
	}
}

function circle2(x, y, rad) {
	for (var i = 0; i < 360; i++) {
		let vector = [
			Math.sin(i * Math.PI / 180) * rad,
			Math.cos(i * Math.PI / 180) * rad
		];
		ctx.fillRect(x + vector[0], y + vector[1], 0.75, 0.75);
	}
}

function contextmenu(x, y, obj, clr = 'black') { //obj = array of objects
	for (var i = 0; i < obj.length; i++) {
		if (obj[i].type == 1) {
			var r = range_init(obj[i].rdata);
		}
	}
	let value = 0;
	return function () {
		ctx.strokeStyle = clr;
		ctx.fillStyle = clr;
		ctx.font = "11.5px Verdana";
		let isClicked = false;
		let height = 21 * obj.length;
		let width = 0;
		for (var i = 0; i < obj.length; i++) {
			ctx.strokeRect(x, y + i * 21, ctx.measureText(obj[i].txt).width + 5 + 5, 21);
			ctx.fillStyle = "White";
			ctx.fillRect(x, y + i * 21, ctx.measureText(obj[i].txt).width + 5 + 5, 21);
			ctx.fillStyle = "black";
			ctx.fillText(obj[i].txt, x + 5, y + 13 + i * 21);
			if (mouse.click == 1) {
				if (aim(x, y + i * 21, ctx.measureText(obj[i].txt).width + 5 + 5, 21)) {
					obj[i].a();
					isClicked = true;
				}
			}
			if (ctx.measureText(obj[i].txt).width + 5 + 5 > width) {
				width = ctx.measureText(obj[i].txt).width + 5 + 5;
			}
		}
		ctx.strokeStyle = "black";
		ctx.fillStyle = "black";
		return [isClicked, height, width];
	}
}

function range_init(t = {
	x: 0,
	y: 0,
	xoffset: 0,
	data: {},
	width: 100,
	clr: "gray",
	height: 20
}) { //data = {min:0,max:0,value:0} //global = {x:0,y:0,width:0,clr:"gray",height:20}
	let clicked = false;
	let onScroll = false;
	return function () {
		ctx.strokeStyle = t.clr;
		ctx.fillStyle = t.clr;
		ctx.strokeRect(t.x, t.y, 1, t.height);
		ctx.strokeRect(t.x + 1, t.y + t.height / 2 - 1, t.width + 20, 1);
		if (aim(t.x + t.xoffset, t.y, 20, t.height)) {
			if (mcL) {
				clicked = true;
			}
			onScroll = true;
		} else {
			onScroll = false;
		}
		if (muL) {
			clicked = false;
		}
		if (clicked) {
			t.data.value = (mx - 10 - t.x) * (t.data.max - t.data.min) / t.width + t.data.min;
			t.xoffset = (mx - 10 - t.x);
		}
		if (t.xoffset + 20 > t.width + 20) {
			t.xoffset = t.width;
			t.data.value = t.data.max;
		}
		if (t.xoffset < 0) {
			t.xoffset = 0;
			t.data.value = t.data.min;
		}
		ctx.strokeRect(t.x + t.xoffset, t.y, 20, t.height);
		ctx.strokeRect(t.x + t.width + 1 + 20, t.y, 1, t.height);
		return [t.data.value, onScroll];
	}
}

function aim(x, y, sizeX, sizeY) {
	if (mouse.pos[0] >= x && mouse.pos[1] >= y && mouse.pos[0] <= x + sizeX && mouse.pos[1] <= y + sizeY) {
		out = true;
	} else {
		out = false;
	}
	return out;
}

function inter(x, y, sizeX, sizeY, x1, y1, sizeX1, sizeY1) {
	if (x + sizeX > x1 && x < x1 + sizeX1 && y + sizeY > y1 && y < y1 + sizeY1) {
		return true;
	} else {
		return false
	}
}

function timer(time, rep = false) {
	let ms = 0;
	if (rep) {
		ms = time;
	}
	return function () {
		ms++;
		if (ms > time) {
			ms = 0;
			return true
		} else {
			return false
		}
	}
}

function inlet(tt) {
	var txt = tt;
	var active = false;
	var time = [timer(35, true), timer(5, true)];
	ctx.font = "13px Arial"
	var inl = {
		pause: true
	};
	var cursor = {
		pos: ctx.measureText(tt).width,
		offset: tt.length,
		blinked: false
	}
	return function (x, y) {
		ctx.strokeRect(x, y, 100, 15);
		ctx.fillStyle = "white";
		ctx.fillRect(x, y, 100, 15);
		ctx.fillStyle = "black";
		ctx.fillText(txt, x + 2, y + 13);
		if (mouse.click == 1) {
			if (aim(x, y, 100, 15)) {
				active = true;
			} else {
				active = false;
			}
		}
		if (active) {
			if (cursor.blinked) {
				line2(x + 2 + cursor.pos, y, x + 2 + cursor.pos, y + 15);
			}
			if (time[0]()) {
				cursor.blinked = !cursor.blinked;
			}
			if (keyboard.char != '  ' && keyboard.code != 13 && inl.pause) {
				txt = txt.substr(0, cursor.offset) + keyboard.char + txt.substr(cursor.offset, txt.length);
				cursor.pos += ctx.measureText(keyboard.char).width;
				inl.pause = false;
				cursor.offset++;
			}
			if (keyboard.code == 8 && cursor.offset > 0 && inl.pause) {
				cursor.pos -= ctx.measureText(txt.charAt(cursor.offset)).width;
				txt = txt.substr(0, cursor.offset - 1) + txt.substr(cursor.offset, txt.length);
				inl.pause = false;
				cursor.offset--;
			}
			if (!inl.pause) {
				if (keyboard.code == -1) {
					inl.pause = true;
				}
			}

			if (keyboard.code == 39 && time[1]()) /*d*/ {
				if (cursor.pos < ctx.measureText(txt).width) {
					cursor.pos += ctx.measureText(txt.charAt(cursor.offset)).width;
					cursor.offset++;
					cursor.blinked = true;
					//console.log(cursor.pos + " | " + txt.charAt(cursor.offset) + " = " + cursor.offset);
				} else {
					cursor.pos = ctx.measureText(txt).width;
				}
			}
			if (keyboard.code == 37 && time[1]()) /*a*/ {
				if (cursor.pos > 0) {
					cursor.pos -= ctx.measureText(txt.charAt(cursor.offset - 1)).width;
					cursor.offset--;
					cursor.blinked = true;
					//console.log(cursor.pos + " | " + txt.charAt(cursor.offset) + " = " + cursor.offset);
				} else {
					cursor.pos = 0;
				}
			}
			if (cursor.offset > txt.length) {
				cursor.offset = txt.length
			}
			if (cursor.offset < 0) {
				cursor.offset = 0;
			}
			if (cursor.pos > ctx.measureText(txt).width) {
				cursor.pos = ctx.measureText(txt).width
			}
			if (cursor.pos < 0) {
				cursor.pos = 0;
			}
		}
		if (keyboard.code == 13) {
			return [txt, true];
		} else {
			return [txt, false]
		}
	}
}

function line(x, y, x1, y1) {
	ctx.beginPath();
	ctx.moveTo(x, y);
	ctx.lineTo(x1, y1);
	ctx.stroke();
}

function line2(x, y, x1, y1, lw = 1) {
	let r = Math.sqrt(Math.pow(x - x1, 2) + Math.pow(y - y1, 2));
	let dx = (x1 - x) / r;
	let dy = (y1 - y) / r;
	if (r == 0) {
		ctx.fillRect(x, y, 1, 1);
	} else {
		for (var i = 0; i < r; i += lw) {
			ctx.fillRect(x + dx * i, y + dy * i, 1, 1);
		}
	}
}

function animOne(obj, ind, inv = false, invimg = '') { //img, x, y, sleep, frames:[[]]
	if (timer(obj.sleep, ind)) {
		currFrame[ind]++;
	}
	if (currFrame[ind] >= obj.frames.length) {
		currFrame[ind] = 0;
	}
	let cut = {
		img: obj.img,
		x: obj.x,
		y: obj.y,
		cutx: obj.frames[currFrame[ind]][0],
		cuty: obj.frames[currFrame[ind]][1],
		cutw: obj.frames[currFrame[ind]][2],
		cuth: obj.frames[currFrame[ind]][3],
	};
	if (inv) {
		cut.cutx = imginv.width - cut.cutx;
	}
	imgcut(cut);
}

function fillArr(arr, count, value = 0) {
	for (var i = 0; i < count; i++) {
		arr[i] = value;
	}
	return arr;
}

function img(src) {
	const img = new Image();
	img.src = src;
	return img;
}

function imgcut(obj) { //img, x, y, cutx, cuty, cutw, cuth
	ctx.drawImage(obj.img, obj.cutx, obj.cuty, obj.cutw, obj.cuth, obj.x, obj.y, obj.cutw, obj.cuth);
}

function imgcut0(img, x, y, cutx, cuty, cutw, cuth) { //img, x, y, cutx, cuty, cutw, cuth
	ctx.drawImage(img, cutx, cuty, cutw, cuth, x, y, cutw, cuth);
}

function invertimg(img, x, y) {
	img.crossOrigin = 'anonymous';
	ctx.drawImage(img, x, y);
	for (var i = 0; i < img.height; i++) {
		for (var j = 0; j < img.width; j++) {
			var data = ctx.getImageData(x + j, y + i, 1, 1).data;
			console.log(data);
			// ctx.fillStyle = "rgb("+data[0] + "," + data[1] + "," + data[2] + ")";
			// ctx.fillRect(x+img.width-j,y+i,1,1);
		}
	}
}

function particles(x, y, count, gravity, size, livetime, clr, direction, addons = {
		trail: {
			isOned: false,
			clr: "red",
			len: 5,
		},
		size_by: {
			isOned: false,
			value: 1,
			force: 0,
			attach_to_velocity: false
		}
	},
	looped = true) {
	var obj = [];
	var max_size = size;
	for (var i = 0; i < count; i++) {
		obj[i] = {
			x: x,
			y: y,
			vx: direction[0][0] + Math.random() * (direction[0][1] - direction[0][0]),
			vy: direction[1][0] + Math.random() * (direction[1][1] - direction[1][0]),
			timer: livetime[0] + Math.random() * (livetime[1] - livetime[0]),
			trail: {
				x: [],
				y: [],
				curr: 0
			}
		};
	}
	return function () {
		this.getObject = function (id) {
			return obj[id];
		};
		this.setObject = function (_obj, id) {
			obj[id] = _obj;
			return true;
		}
		this.getProperty = function () {
			return {
				x: x,
				y: y,
				count: count,
				gravity: gravity,
				size: size,
				livetime: livetime,
				clr: clr,
				direction: direction,
				addons: addons
			};
		}
		this.setProperty = function (prop) {
			x = prop.x;
			y = prop.y;
			count = prop.count;
			gravity = prop.gravity;
			size = prop.size;
			livetime = prop.livetime;
			clr = prop.clr;
			direction = prop.direction;
			addons = prop.addons;
		}
		for (var i = 0; i < obj.length; i++) {
			//PHISIC
			obj[i].vy += gravity;
			obj[i].x += obj[i].vx;
			obj[i].y += obj[i].vy;
			ctx.fillStyle = clr;
			ctx.fillRect(obj[i].x, obj[i].y, size, size);

			//SIZE_BY_VALUE
			if (addons.size_by.isOned) {
				if (addons.size_by.attach_to_velocity) {
					size = ((Math.abs(obj[i].vx) + Math.abs(obj[i].vy)) / 2) * addons.size_by.force;
					if (size < 0) {
						size = 0;
					}
				} else {
					size = max_size - addons.size_by.value * addons.size_by.force;
					if (size < 0) {
						size = 0;
					}
				}
			}

			//TRAILS
			if (addons.trail.isOned) {
				obj[i].trail.curr++;
				if (obj[i].trail.curr > addons.trail.len) {
					obj[i].trail.curr = 0;
				}
				obj[i].trail.x[obj[i].trail.curr] = obj[i].x;
				obj[i].trail.y[obj[i].trail.curr] = obj[i].y;
				for (var j = 0; j < obj[i].trail.x.length - 1; j++) {
					ctx.fillStyle = addons.trail.clr;
					ctx.strokeStyle = addons.trail.clr;
					ctx.fillRect(obj[i].trail.x[j], obj[i].trail.y[j], size, size);
					ctx.lineWidth = size;
					line2(obj[i].trail.x[j - 1] + size / 2, obj[i].trail.y[j - 1] + size / 2, obj[i].trail.x[j] + size / 2, obj[i].trail.y[j] + size / 2);
					ctx.fillStyle = "black";
					ctx.strokeStyle = "black";
				}
			}

			//TIMER
			obj[i].timer--;
			if (obj[i].timer < 0) {
				if (looped) {
					obj[i] = {
						x: x,
						y: y,
						vx: direction[0][0] + Math.random() * (direction[0][1] - direction[0][0]),
						vy: direction[1][0] + Math.random() * (direction[1][1] - direction[1][0]),
						timer: livetime[0] + Math.random() * (livetime[1] - livetime[0]),
						trail: {
							x: [],
							y: [],
							curr: 0
						}
					};
				} else {
					obj.splice(0, obj.length);
					count = 0;
				}
			}
		}
		ctx.fillStyle = "black";
	}
}

var gui = new class GUI {
	constructor() {
		this.fillColor = "red";
		this.strokeColor = "black";
	}

	hpbar(x, y, hp, max, width, height = 15) {
		ctx.fillStyle = this.fillColor;
		ctx.strokeStyle = this.strokeColor;
		ctx.fillRect(x, y, width / max * hp, height);
		ctx.strokeRect(x, y, width, height);
	}

	range(x, y, max, curr, width = 20, height = 50) {

		var slider = {
			y: curr,
			isMove: false
		};

		return function () {
			line(x, y, x + width, y);
			line(x + width / 2, y, x + width / 2, y + height);
			line(x, y + height, x + width, y + height);

			for (var i = 0; i < height; i += height / max) {
				line(x + width / 5, y + i, x + width - width / 5 + 1, y + i);
			}

			ctx.strokeRect(x, y - 5 + slider.y, width, 10);

			if (mouse.click == 1 && aim(x, slider.y, width, 10)) {
				slider.isMove = true;
			}
			if (mouse.click == 0) {
				slider.isMove = false;
			}
			if (slider.isMove) {
				slider.y = mouse.pos[1];
				if (slider.y > height) {
					slider.y = height;
				}
				if (slider.y + y < y) {
					slider.y = 0;
				}
			}
			ctx.font = "10px Arial";
			ctx.fillText(Math.floor(slider.y / (height / max)), x + width / 2 - ctx.measureText(Math.floor(slider.y / (height / max))).width / 2, y + height + 9);
			return Math.floor(slider.y / (height / max));
		}
	}


}

function editor() {
	var menu = {
		c: [],
		obj: [{
			txt: "hpbar",
			a: function () {

			}
		}],
		active: false,
		pos: [],
		update: function () {
			if (mouse.click == 3) {
				this.c = contextmenu(mouse.pos[0], mouse.pos[1], this.obj);
				this.active = true;
			}
			if (this.active) {
				this.c();
				if (mouse.click == 1) {
					this.active = false;
				}
			}
		}
	};
	var objects = {

	};
	return function () {
		menu.update();
	}
}

function distance(x, y, x0, y0) {
	return [Math.sqrt(Math.pow(x - x0, 2) + Math.pow(y - y0, 2)), x - x0, y - y0];
}

var vector2d = new class vectors {
	constructor() {

	}
	vector(x, y) {
		return {
			x: x,
			y: y
		}
	}
	multiply(vecs) {
		let out = {
			x: 0,
			y: 0
		};
		for (var i = 0; i < vecs.length; i++) {
			for (var j = 0; j < vecs.length; j++) {
				if (i == j) continue;
				out.x += vecs[i].x * vecs[j].x;
				out.y += vecs[i].y * vecs[j].y;
			}
		}
		return out;
	}
}