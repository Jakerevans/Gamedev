"use strict";
/**
 * JavaScript Admin Functions - gamedev--frontend.min.js
 *
 * @author   Jake Evans
 * @category JavaScript
 * @package  Includes/Assets/Js
 * @version  6.0.0
 */

console.log('This is the JavaScript Object that holds all PHP Variables for use in the GameDev JavaScript');
console.log(gamedevPhpVariables); // All functions wrapped in jQuery(document ).ready()...

jQuery(document).ready(function ($) {
  //'use strict';

  /* BEGINNING SECTION TO CALL ALL FUNCTIONS IN FILE... */

    // Starts everything - apparently, this function, more specifically, the "var game = new Phaser.Game(config);" code, will call the preload() function once.
    startGame();

  /* ENDING SECTION TO CALL ALL FUNCTIONS IN FILE... */


  function startGame() {
    var config = {
      type: Phaser.AUTO,
      // Which renderer to use
      width: 500,
      // Canvas width in pixels
      height: 600,
      // Canvas height in pixels
      parent: 'jre-gamedev-for-game-insertion',
      physics: {
        default: "arcade",
        arcade: {
          gravity: { y: 0 } // Top down game, so no gravity
        }
      },
      // ID of the DOM element to add the canvas to
      scene: {
        preload: preload,
        create: create,
        update: update
      }
    };
    var game = new Phaser.Game(config);
    let showDebug = false;


    console.log('this is game from up top:')
    console.log(game)
  }

  function preload() {// Runs once, loads up assets like images and audio


    this.load.image('gameTiles', gamedevPhpVariables.GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL + "overworld.png");
    this.load.tilemapTiledJSON('level1', gamedevPhpVariables.GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL + "overworld.json");
    //THIS WORKS!!!!!!!!!
    //this.load.image("overworld-tiles", gamedevPhpVariables.GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL + "overworld.png");


     // An atlas is a way to pack multiple images together into one texture. I'm using it to load all
    // the this.player animations (walking left, walking right, etc.) in one image. For more info see:
    //  https://labs.phaser.io/view.html?src=src/animation/texture%20atlas%20animation.js
    // If you don't use an atlas, you can do the same thing with a spritesheet, see:
    //  https://labs.phaser.io/view.html?src=src/animation/single%20sprite%20sheet.js
    this.load.atlas("atlas", gamedevPhpVariables.GAMEDEV_ROOT_IMG_SPRITESHEETS_URL + 'atlas.png', gamedevPhpVariables.GAMEDEV_ROOT_IMG_SPRITESHEETS_URL + 'atlas.json');

  }

  function animComplete (animation, frame)
{
    //  Animation is over, let's fade the sprite out
    this.tweens.add({
        targets: gem,
        duration: 3000,
        alpha: 0
    });
}

  function create() {// Runs once, after all assets in preload are loaded


    const map = this.make.tilemap({ key: "level1" }); 
    const tileset = map.addTilesetImage('overworld','gameTiles');
    const backgroundLayer = map.createStaticLayer('backgroundLayer', tileset);
    const worldLayer = map.createStaticLayer("worldLayer", tileset, 0, 0);
    const aboveLayer = map.createStaticLayer("aboveLayer", tileset, 0, 0);

    worldLayer.setCollisionByProperty({ collides: true });

    // By default, everything gets depth sorted on the screen in the order we created things. Here, we
    // want the "Above this.player" layer to sit on top of the this.player, so we explicitly give it a depth.
    // Higher depths will sit on top of lower depth objects.
    aboveLayer.setDepth(10);

    // Object layers in Tiled let you embed extra info into a map - like a spawn point or custom
    // collision shapes. In the tmx file, there's an object layer with a point named "Spawn Point"
    const spawnPoint = map.findObject("objects", obj => obj.name === "Spawn Point");

    // Create a sprite with physics enabled via the physics system. The image used for the sprite has
    // a bit of whitespace, so I'm using setSize & setOffset to control the size of the this.player's body.
    this.player = this.physics.add
      .sprite(spawnPoint.x, spawnPoint.y, "atlas", "misa-front")
      .setSize(10, 16)
      .setOffset(3, 10);

    // Watch the this.player and worldLayer for collisions, for the duration of the scene:
    this.physics.add.collider(this.player, worldLayer);

    // Create the this.player's walking animations from the texture atlas. These are stored in the global
    // animation manager so any sprite can access them.
    const anims = this.anims;
    anims.create({
      key: "misa-left-walk",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-left-walk.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-right-walk",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-right-walk.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-front-walk",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-front-walk.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-back-walk",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-back-walk.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-front-lift",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-front-lift.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-back-lift",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-back-lift.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-right-lift",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-right-lift.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-left-lift",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-left-lift.", start: 0, end: 3, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });
    anims.create({
      key: "misa-sword-swing-down",
      frames: anims.generateFrameNames("atlas", { prefix: "misa-sword-swing-down.", start: 0, end: 4, zeroPad: 3 }),
      frameRate: 10,
      repeat: 0
    });


    const camera = this.cameras.main;
    camera.startFollow(this.player);
    camera.setBounds(0, 0, map.widthInPixels, map.heightInPixels);

    this.cursors = this.input.keyboard.createCursorKeys();

    /* Help text that has a "fixed" position on the screen
    this.add
      .text(16, 16, 'Arrow keys to move\nPress "D" to show hitboxes', {
        font: "18px monospace",
        fill: "#000000",
        padding: { x: 20, y: 10 },
        backgroundColor: "#ffffff"
      })
      .setScrollFactor(0)
      .setDepth(30);
      */

    // Debug graphics
    this.input.keyboard.once("keydown_D", event => {
      // Turn on physics debugging to show this.player's hitbox
      this.physics.world.createDebugGraphic();

      // Create worldLayer collision graphic above the this.player, but below the help text
      const graphics = this.add
        .graphics()
        .setAlpha(0.75)
        .setDepth(20);
      worldLayer.renderDebug(graphics, {
        tileColor: null, // Color of non-colliding tiles
        collidingTileColor: new Phaser.Display.Color(243, 134, 48, 255), // Color of colliding tiles
        faceColor: new Phaser.Display.Color(40, 39, 37, 255) // Color of colliding face edges
      });
    });

  
   


/*
THIS WORKS!!!!!!!!!
    // Load a map from a 2D array of tile indices
    const level = [
      [  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0, 0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0 ],
      [  0,   0,   2,   3,   0,   0,   0,   1,   2,   3,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   5,   6,   7,   0,   0,   0,   5,   6,   7,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   0,   0,  14,  13,  14,   0,   0,   0,   0,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   0,  14,  14,  14,  14,  14,   0,   0,   0,  15,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [  0,   0,   0,   0,   0,   0,   0,   0,   0,  15,  15,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [ 35,  36,  37,   0,   0,   0,   0,   0,  15,  15,  15,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ],
      [ 39,  39,  39,  39,  39,  39,  39,  39,  39,  39,  39,  0,   0,   0,   0,   0,   0,   0,   0,   0,   0,   0  ]
    ];

    // When loading from an array, make sure to specify the tileWidth and tileHeight
    const map = this.make.tilemap({ data: level, tileWidth: 16, tileHeight: 16 });
    const tiles = map.addTilesetImage("overworld-tiles");
    const layer = map.createStaticLayer(0, tiles, 0, 0);

  */
  }

  function update(time, delta) {// Runs once per frame for the duration of the scene

      const speed = 175;
      const prevVelocity = this.player.body.velocity.clone();

      // Stop any previous movement from the last frame
      this.player.body.setVelocity(0);

      // Horizontal movement
      if (this.cursors.left.isDown) {
        this.player.body.setVelocityX(-speed);
      } else if (this.cursors.right.isDown) {
        this.player.body.setVelocityX(speed);
      }

      // Vertical movement
      if (this.cursors.up.isDown) {
        this.player.body.setVelocityY(-speed);
      } else if (this.cursors.down.isDown) {
        this.player.body.setVelocityY(speed);
      }

      // Normalize and scale the velocity so that this.player can't move faster along a diagonal
      this.player.body.velocity.normalize().scale(speed);

      // Walking aniations.
      if (this.cursors.left.isDown) {
        this.player.anims.play("misa-left-walk", true);
      } else if (this.cursors.right.isDown) {
        this.player.anims.play("misa-right-walk", true);
      } else if (this.cursors.up.isDown) {
        this.player.anims.play("misa-back-walk", true);
      } else if (this.cursors.down.isDown) {
        this.player.anims.play("misa-front-walk", true);
      }

      // When directions keys are 'Keyup', stop all anims and display appropriate frame.
      this.input.keyboard.once("keyup_UP", event => {
        this.player.anims.stop();
        this.player.setTexture("atlas", "misa-back");
      });

      this.input.keyboard.once("keyup_DOWN", event => {
        this.player.anims.stop();
        this.player.setTexture("atlas", "misa-front");
      });

      this.input.keyboard.once("keyup_LEFT", event => {
        this.player.anims.stop();
        this.player.setTexture("atlas", "misa-left");
      });

      this.input.keyboard.once("keyup_RIGHT", event => {
        this.player.anims.stop();
        this.player.setTexture("atlas", "misa-right");
      });


      // The lifting animations
      this.input.keyboard.on("keydown_L", event => {

        // If we were moving, pick and idle frame to use
        if (prevVelocity.x < 0) {
          this.player.anims.play("misa-left-lift", true);
        } else if (prevVelocity.x > 0) {
          this.player.anims.play("misa-right-lift", true);
        } else if (prevVelocity.y < 0) {
          this.player.anims.play("misa-back-lift", true);
        } else if (prevVelocity.y > 0) {
          this.player.anims.play("misa-front-lift", true);
        }

      });

      // The lifting animations
      this.input.keyboard.on("keydown_ENTER", event => {

        // If we were moving, pick and idle frame to use
        if (prevVelocity.x < 0) {
          this.player.anims.play("misa-left-lift", true);
        } else if (prevVelocity.x > 0) {
          this.player.anims.play("misa-right-lift", true);
        } else if (prevVelocity.y < 0) {
          this.player.anims.play("misa-back-lift", true);
        } else if (prevVelocity.y > 0) {
          this.player.anims.play("misa-sword-swing-down", true);
        }

      });




  }
});