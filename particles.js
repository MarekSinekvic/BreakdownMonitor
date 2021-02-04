var canv = document.getElementById("canvas");
var ctx = canv.getContext("2d");
canv.width = window.innerWidth;
canv.height = window.innerHeight;

var particles0 = new (class {
  constructor() {
    this.pos = [];
    this.vel = [];
    this.size = [];
    this.pressure = {
      pos: [0, 0],
      force: 0
    };
    this.quality = 3;
  }
  update() {
    for (var i = 0; i < this.quality; i++) {
      this.create(
        [
          Math.random() * canv.clientWidth,
          -canv.clientHeight + Math.random() * canv.clientHeight - 1
        ],
        [-0.1 + Math.random() * 0.2, -0.1 + Math.random() * 0.2]
      );
    }
    for (var i = 0; i < this.pos.length; i++) {
      this.pos[i][0] += this.vel[i][0];
      this.pos[i][1] += this.vel[i][1];
      let box = document.getElementById("box").getBoundingClientRect();
      if (
        inter(
          this.pos[i][0],
          this.pos[i][1],
          1,
          1,
          box.x,
          box.y,
          box.width,
          box.height
        )
      ) {
        this.delete(i);
        continue;
      }
      if (this.pos[i][1] > canv.clientHeight) {
        this.delete(i);
        continue;
      }
      if (this.pos[i][0] < 0) {
        this.delete[i];
        continue;
      }
      if (this.pos[i][0] > canv.clientWidth) {
        this.delete[i];
        continue;
      }
      this.vel[i][1] += 0.02;
      let r = distance(
        this.pos[i][0],
        this.pos[i][1],
        this.pressure.pos[0],
        this.pressure.pos[1]
      );
      this.vel[i][0] +=
        ((r[1] / r[0]) * this.pressure.force) / Math.pow(r[0], 2);
      this.vel[i][1] +=
        ((r[2] / r[0]) * this.pressure.force) / Math.pow(r[0], 2);
      ctx.fillStyle = "white";
      ctx.strokeStyle = "white";
      circle(this.pos[i][0], this.pos[i][1], this.size[i]);
    }
  }
  create(pos, vel, size = 1) {
    let i = this.pos.length;
    this.pos[i] = pos;
    this.vel[i] = vel;
    this.size[i] = size;
  }
  delete(i) {
    this.pos.splice(i, 1);
    this.vel.splice(i, 1);
    this.size.splice(i, 1);
  }
})();

var particles1 = new (class {
  constructor() {
    this.pos = [];
    this.vel = [];
    this.size = [];
    this.quality = 2;
  }
  update() {
    for (var i = 0; i < this.quality; i++) {
      this.create(
        [
          Math.random() * canv.clientWidth,
          canv.clientHeight + Math.random() * canv.clientHeight
        ],
        [-0.1 + Math.random() * 0.2, -0.1 + Math.random() * 0.2]
      );
    }
    for (var i = 0; i < this.pos.length; i++) {
      this.pos[i][0] += this.vel[i][0];
      this.pos[i][1] += this.vel[i][1];

      this.vel[i][1] -= 0.02;
      if (this.pos[i][1] < 0) {
        this.delete(i);
      }

      ctx.strokeStyle = "gray";
      ctx.fillStyle = "gray";
      ctx.shadowColor = "gray";
      ctx.shadowBlur = 7;
      circle(this.pos[i][0], this.pos[i][1], this.size[i]);
    }
  }
  create(pos, vel, size = 2) {
    let i = this.pos.length;
    this.pos[i] = pos;
    this.vel[i] = vel;
    this.size[i] = size;
  }
  delete(i) {
    this.pos.splice(i, 1);
    this.vel.splice(i, 1);
    this.size.splice(i, 1);
  }
})();

setInterval(function() {
  canv.width = window.innerWidth;
  canv.height = window.innerHeight;
  ctx.clearRect(0, 0, canv.width, canv.height);
  particles0.update();
  if (mouse.click == 1) {
    particles0.pressure.pos = mouse.pos;
    particles0.pressure.force = 1000;
  }
  if (mouse.click == 0) {
    particles0.pressure.force = 0;
  }
  // particles1.update();
}, 1000 / 50);
