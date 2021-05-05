<?php
session_start();
include 'db.php';

class Card
{
	public $name;
	public $type;
	public $number;
	public $count;
	public $hit_dice;
	public $health;
	public $cost;
	public $ranged;
}

$mysqli = new mysqli($dbServer,$dbUser,$dbPass,$db);
	
	if (mysqli_connect_errno()) 
	{
		echo "Connect failed: " . mysqli_connect_error();
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.15.1/dist/phaser-arcade-physics.min.js"></script>
</head>
<body>

    <script>

    //click to enlarge card

    var config = {
        type: Phaser.AUTO,
        width: 1000,
        height: 720,
        cardW: 375,
        cardH: 562.5,
        cardS: 0.21,
        scene: {
            preload: preload,
            create: create
        }
    };

    var game = new Phaser.Game(config);

    function preload ()
    {
        this.load.spritesheet("FFDeck", "/ComicAtomic/img/FFDeck.png", {frameWidth: config.cardW, frameHeight: config.cardH});
        this.load.spritesheet("MMDeck", "/ComicAtomic/img/MMDeck.png", {frameWidth: config.cardW, frameHeight: config.cardH});
        this.load.image('board', '/ComicAtomic/img/board2.png'); 
        this.load.image('FFLogo', '/ComicAtomic/img/FFLogo.png');
        this.load.image('MMLogo', '/ComicAtomic/img/MMLogo.png');
        this.load.image('logo', '/ComicAtomic/img/logo.png'); 
        this.load.image('dice', '/ComicAtomic/img/rollDice.png'); 
        this.load.image('end', '/ComicAtomic/img/endTurn.png'); 
    }

    function create ()
    {
        this.isTurn = false;
        this.info = null
        this.prev = null
        this.originX
        this.originY
        this.ready = false;
        this.p1 = null;
        this.p2 = null;
        this.facBtn1
        this.facBtn2
        this.input.mouse.disableContextMenu();
        this.deckOfCards = Array();

        for(x = 0; x < 32; x++)
        {
            this.deckOfCards[x] = this.add.container();
            this.deckOfCards[x].name = null;
            this.deckOfCards[x].type = null
            this.deckOfCards[x].hit_dice = null;
            this.deckOfCards[x].health = null;
            this.deckOfCards[x].cost = null;
            this.deckOfCards[x].ranged = null;           
           
        }

        pickFaction(this);

        this.deck = Phaser.Utils.Array.NumberArray(6,32);
        this.hand = Array();
        this.energy = 0;

        this.die1 = this.add.text(885, 100, '','Arial, 80, , , , , white');
       
        this.board = 
        [
            [0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0],
        ]

        for(x = 0; x < 6; x++)
        {
            for(y = 0; y < 8; y++)
            {
                this.board[x][y] = null;
            }
        }

        for(x = 0; x < 5; x++)
        {
           this.hand[x] = null;
        }
    

        Phaser.Utils.Array.Shuffle(this.deck);
        this.cardIdx = 0;
    }

    function runGame(game)
    {    
        game.discard = game.add.text(930, 650, '$','Arial, 80, , , , , white');
        game.bk = game.add.image(500,360,'board');
        fillHand(game);
        setUp(game);
        endTurnBtn(game);
        rollButton(game)
        movement(game);

        game.ready = true;
        boardUpdateSend(null, null, null, game, 'c')
    }

    function pickFaction(game)
    {        
        game.conn = new WebSocket('ws://192.168.0.182:1338');

        //establish connection
        game.conn.onopen = function(e) 
        {
            console.log("connection established!");
        }

        //recieve message
        game.conn.onmessage = function(e) 
        {       
            var msg = JSON.parse(e.data);
            
            boardUpdateRecieve(msg, game);
        };

        //set up FF card button
        var facImg1 = game.add.image(0,0, "FFLogo").setScale(.5);
        game.facBtn1 = game.add.container(300,350, [ facImg1 ]);
        game.facBtn1.setSize(facImg1.width * .5, facImg1.height * .5);
        game.facBtn1.setInteractive();
        
        game.facBtn1.on('pointerover', function () 
        {
            facImg1.setTint(0x7878ff);
        });

        game.facBtn1.on('pointerout', function () 
        {
            facImg1.clearTint();
        });

        game.facBtn1.on("pointerdown", function()
        {
            game.p1 = "FFDeck";
            game.p2 = "MMDeck";
            game.facBtn1.destroy();
            game.facBtn2.destroy();
            boardUpdateSend(null,null,null,game,'f')
            buildDeck(game, "FF");
            runGame(game)
        });


        //set up MM card button
        var facImg2 = game.add.image(0,0, "MMLogo").setScale(.5);
        game.facBtn2 = game.add.container(700,350, [ facImg2 ]);

        game.facBtn2.setSize(facImg2.width * .5, facImg2.height * .5);

        game.facBtn2.setInteractive();
        
        game.facBtn2.on('pointerover', function () 
        {
            facImg2.setTint(0x7878ff);
        });

        game.facBtn2.on('pointerout', function () 
        {
            facImg2.clearTint();
        });

        game.facBtn2.on("pointerdown", function()
        {
            game.p2 = "FFDeck";
            game.p1 = "MMDeck";
            game.facBtn1.destroy();
            game.facBtn2.destroy();
            boardUpdateSend(null,null,null,game,'f')
            buildDeck(game, "MM");
            runGame(game)
        });
    }

    function movement(game)
    {
    
            //moving the card
            game.input.on('drag', function (pointer, gameObject, dragX, dragY) 
            {
                gameObject.x = dragX;
                gameObject.y = dragY;
            });

            //movement ends
            game.input.on('dragend', function (pointer, gameObject)
            {
                //check for energy overlap
                if(checkOverlap(gameObject, game.discard) && gameObject.placed == false && game.isTurn == true)
                {
                    gameObject.destroy();
                    game.hand[gameObject.val] = null;
                    game.energy += 1;
                    game.discard.text = game.energy;
                        
                    x = Math.round((game.originX) / 120);
                    y = Math.round((game.originY) / 79);
                    game.board[x - 2][y - 1] = null;
                }

                //if the card isnt on the board or energy
                else if(!checkOverlap(gameObject, game.bk) && game.isTurn == false)
                {
                    gameObject.x = game.originX;
                    gameObject.y = game.originY;
                }

                //getting placed on the board
                else 
                {

                    console.log(gameObject.type)
                    if(gameObject.type == "Event" && game.isTurn == true)
                    {
                        gameObject.destroy();
                        game.hand[gameObject.val] = null;
                    }

                    else
                    {
                        var x = Math.round((gameObject.x) / 120);
                        var y = Math.round((gameObject.y) / 79);

                        //spot was empty
                        if(game.board[x - 2][y - 1] == null && (gameObject.placed == true || (gameObject.placed == false && gameObject.cost <= game.energy)) && game.isTurn == true)
                        {
                            game.board[x - 2][y - 1] = gameObject;
                            gameObject.x = 120 * x - 40;
                            gameObject.y = 79 * y + 4;
                            boardUpdateSend((x - 6) * -1, (y - 8) * -1, gameObject, game, 'p')
                            
                            x = Math.round((game.originX) / 120);
                            y = Math.round((game.originY) / 79);

                            if(gameObject.placed == true)
                            {
                                game.board[x - 2][y - 1] = null;
                                boardUpdateSend((x - 6) * -1, (y - 8) * -1, gameObject, game, 'k')
                            
                            }
                            
                            else
                            {
                                gameObject.placed = true;
                                game.energy -= gameObject.cost;
                                game.discard.text = game.energy;
                                game.hand[gameObject.val] = null;                
                            }
                        }        

                        //spot was filled
                        else
                        {        
                            gameObject.x = game.originX;
                            gameObject.y = game.originY;
                        }
                    }
                }
            });

            //set origin of card
            game.input.on('dragstart', function (pointer, gameObject)
            {
                game.originX = gameObject.x;
                game.originY = gameObject.y;
            });

            //check if clicked on
            let lastTime = 0;
            game.input.on('gameobjectdown', function (pointer, gameObject)
            {
                let clickDelay = game.time.now - lastTime;
                lastTime = game.time.now

                if(pointer.rightButtonDown() && gameObject.placed == true && game.isTurn == true)
                {
                    x = Math.round((gameObject.x) / 120);
                    y = Math.round((gameObject.y) / 79);
                    gameObject.health--;
                    gameObject.first.setTint(0xff4a3d)
                    
                    console.log(gameObject.last.text)
                    if(gameObject.last.text == ' ')
                    {
                        gameObject.last.text = '0';
                    }
                    gameObject.last.text = parseInt(gameObject.last.text) + 1;
                    console.log(gameObject.last.text)

                    boardUpdateSend((x - 6) * -1, (y - 8) * -1, gameObject, game, 'd')

                    console.log(gameObject.health)
                    if(gameObject.health == 0)
                    {
                        if(game.prev == gameObject){game.prev = null}
                        game.originX = gameObject.x;
                        game.originY = gameObject.y;
                        gameObject.destroy();
                        game.energy += 1;
                        game.discard.text = game.energy;
                        game.board[x - 2][y -1] = null;
                        boardUpdateSend((x - 6) * -1, (y - 8) * -1, gameObject, game, 'k')
                    }
                }

                else if(clickDelay < 350 && game.isTurn == true)
                {
                    if(game.info == null)
                    {
                        var card = game.add.image(0, 0, gameObject.fact, gameObject.name);
                        game.info = game.add.container(500, 350, [ card ]);
                        card.angle -= 90;
                        game.info.setSize(card.width * .5, card.height * .5);
                        game.info.setInteractive();
                    }
                }

                else if(clickDelay > 350 && gameObject.placed == true && game.isTurn == true)
                {
                    if(game.prev != null)
                    {
                        if(game.prev.last.text == ' ') {game.prev.first.clearTint()}
                        else{game.prev.first.setTint(0xff4a3d)}
                    }

                    gameObject.first.setTint(0x7878ff)
                    game.prev = gameObject
                }
            });

            game.input.keyboard.on('keydown_SPACE', function(event)
            {
                if(game.info != null)
                {
                    game.info.destroy();
                    if(game.prev.last.text == ' ') {game.prev.first.clearTint()}
                    else{game.prev.first.setTint(0xff4a3d)}
                    game.info = null;
                }   

                else if(game.prev != null)
                {
                    if(game.prev.last.text == ' ') {game.prev.first.clearTint()}
                    else{game.prev.first.setTint(0xff4a3d)}
                    prev = null;
                }
            })
        
    }

    function setUp(game)
    {
        var num;
        var x;
        var y;

        for(i = 0; i < 6; i++)
        {
            x = Math.floor(Math.random() * 5) + 2;
            y = Math.floor(Math.random() * 4) + 5;

            while(game.board[x - 2][y - 1] != null)
            {
                x = Math.floor(Math.random() * 5) + 2;
                y = Math.floor(Math.random() * 4) + 5;
            }

            var text = game.add.text(0, 0, ' ','Arial, 80, , , , , black');
            var card = game.add.image(0, 0, game.p1, i);
            card.setScale(config.cardS);
            card.angle += -90;

            var set = game.add.container((120 * x) - 40, (79 * y) + 4, [ card,text ]);
            
            set.fact = game.p1;
            set.name = i;
            set.placed = true;
            set.health = game.deckOfCards[i].health;
            set.cost = game.deckOfCards[i].cost;
            set.dice = game.deckOfCards[i].hit_dice;

            set.setSize(card.width * config.cardS, card.height * config.cardS);

            set.setInteractive();
            game.board[x - 2][y - 1] = set;

            game.input.setDraggable(set);
        }
    }

    function fillHand(game)
    {
        if(game.cardIdx < 33)
        {
            for(x = 0; x < 5; x++)
            {
                if(game.hand[x] == null)
                {
                    var text = game.add.text(0, 0, ' ','', '80', '', '', '', '', 'black');
                    var card = game.add.image(0, 0, game.p1, game.deck[game.cardIdx]);
                    var originX = 0;
                    var originY = 0;
                    card.setScale(config.cardS);
                    card.angle += -90;

                    game.hand[x] = game.add.container(70, 290 + (90 * x), [ card, text ]);
                    
                    game.hand[x].type = game.deckOfCards[game.deck[game.cardIdx]].type
                    game.hand[x].fact = game.p1
                    game.hand[x].name = game.deck[game.cardIdx];
                    game.hand[x].val = x;
                    game.hand[x].placed = false;
                    game.hand[x].health = game.deckOfCards[game.deck[game.cardIdx]].health;
                    game.hand[x].cost = game.deckOfCards[game.deck[game.cardIdx]].cost;
                    game.hand[x].dice = game.deckOfCards[game.deck[game.cardIdx]].hit_dice;

                    game.cardIdx++;

                    game.hand[x].setSize(card.width * config.cardS, card.height * config.cardS);

                    game.hand[x].setInteractive();

                    game.input.setDraggable(game.hand[x]);
                }
            }
        }   
    }

    function checkOverlap(sp1, sp2)
    {
        var bounds1 = sp1.getBounds();
        var bounds2 = sp2.getBounds();   

        return Phaser.Geom.Intersects.RectangleToRectangle(bounds1, bounds2);
    }

    function endTurnBtn(game)
    {    
        var sprite = game.add.image(0, 0, 'end').setScale(.1);
        var endBtn = game.add.container(930, 613, [ sprite ]);
            
        endBtn.setSize(sprite.width * .1, sprite.height * .1);

        endBtn.setInteractive();

        endBtn.on('pointerover', function () {

            sprite.setTint(0x7878ff);

        });

        endBtn.on('pointerout', function () {

            sprite.clearTint();

        });

        endBtn.on('pointerdown', function () {

            game.isTurn = false;
            game.conn.send(JSON.stringify('2'));
            fillHand(game);

        });
    }

    function rollButton(game)
    {    
        var sprite = game.add.image(0, 0, 'dice').setScale(.1);
        var endBtn = game.add.container(930, 50, [ sprite ]);
            
        endBtn.setSize(sprite.width * .1, sprite.height * .1);

        endBtn.setInteractive();

        endBtn.on('pointerover', function () {

            sprite.setTint(0x7878ff);

        });

        endBtn.on('pointerout', function () {

            sprite.clearTint();

        });

        endBtn.on('pointerdown', function () 
        {
            if(game.prev != null)
                {
                var dmg = 0;
                for(x = 0; x < game.prev.dice; x++)
                {
                    if(Math.round(Math.random() * 6) + 1 > 2)
                    {
                        dmg += 1;
                    }
                }
                game.die1.text = dmg + " Damage!!";
            }
        });
    }

    function boardUpdateRecieve(msg, game)
    {
        var x;
        var y;

        if(msg[0] == 'p')
        {
            x = msg[1];
            y = msg[2];

            var words = game.add.text(0, 0, '',' ', '80', '', '', '', '', 'black');
            var card = game.add.image(0, 0, game.p2, (msg.charCodeAt(3) - 97));
            card.setScale(config.cardS);
            card.angle += -90;

            var set = game.add.container((120 * x) - 40, (79 * y) + 4, [ card,words ]);

            set.setSize(card.width * config.cardS, card.height * config.cardS);
            set.health = msg[4];
            set.fact = game.p2;
            set.name = msg.charCodeAt(3) - 97
            set.placed = true;
            set.last.text = msg[5]

            if(set.last.text != ' ')
            {
                set.first.setTint(0xff4a3d);
            }

            set.setInteractive();

            game.board[x - 2][y - 1] = set;
        }

        else if(msg[0] == 'k')
        {
            x = msg[1];
            y = msg[2];
            game.board[x - 2][y - 1].destroy();
            game.board[x - 2][y - 1] = null;
        }

        else if(msg[0] == 'r')
        {    
            for(x = 0; x < 6; x++)
            {
                for(y = 0; y < 8; y++)
                {       
                    if(game.board[x][y] != null)
                    {                 
                        var msg = 'p';

                        msg += ((x - 6) * -1) + 1;
                        msg += ((y  - 8) * -1);
                        msg += String.fromCharCode(game.board[x][y].name + 97)
                        
                        msg += game.board[x][y].health;
                        msg += game.board[x][y].last.text

                        game.conn.send(JSON.stringify(msg));
                    }
                }
            }
        }

        else if(msg[0] == 'c')
        {
            if(game.ready == true)
            {
                game.conn.send(JSON.stringify('r'));
                
                if(Math.round(Math.random() * 1) == 0){game.isTurn = true}
               
                else {game.conn.send(JSON.stringify('2'));}

                console.log(game.isTurn)
     
                for(x = 0; x < 6; x++)
                {
                    for(y = 0; y < 8; y++)
                    {       
                        if(game.board[x][y] != null)
                        {                 
                            var msg = 'p';

                            msg += ((x - 6) * -1) + 1;
                            msg += ((y  - 8) * -1);
                            msg += String.fromCharCode(game.board[x][y].name + 97)
                            
                            msg += game.board[x][y].health;
                            msg += game.board[x][y].last.text

                            game.conn.send(JSON.stringify(msg));
                        }
                    }
                }
            }
        }
    
        else if(msg[0] == 'd')
        {
            x = msg[1];
            y = msg[2];
                
            if(game.board[x - 2][y - 1].last.text == ' ')
            {
                game.board[x - 2][y - 1].last.text = '0';
            }

            game.board[x - 2][y - 1].last.text = parseInt(game.board[x - 2][y - 1].last.text) + 1;
            console.log(x,y)
            game.board[x - 2][y - 1].first.setTint(0xff4a3d)
        }

        else if (msg[0] == 'f')
        {
            if(msg[1] == "F")
            {
                game.facBtn1.destroy()
            }

            else{
                game.facBtn2.destroy()
            }
        }

        else if(msg[0] == '2')
        {
            game.isTurn = true;
        }
    }

    function boardUpdateSend(x, y, gameObject, game, action)
    { 
        if(action == 'p')
        {
            var msg = action;

            msg += x + 3;
            msg += y + 1;
            msg += String.fromCharCode(gameObject.name + 97);
            console.log(gameObject.name)
            msg += gameObject.health;
            msg += gameObject.last.text;

            game.conn.send(JSON.stringify(msg));
        }

        else if(action == 'k')
        {
            var msg = action;
            msg += x + 3;
            msg += y + 1;
            game.conn.send(JSON.stringify(msg));
        }

        else if(action == 'r')
        {
            var msg = action;
            game.conn.send(JSON.stringify(msg));
        }

        else if(action == 'c')
        {
            var msg = action;
            game.conn.send(JSON.stringify(msg));
        }
    
        else if(action == 'd')
        {
            var msg = action;
            msg += x + 3;
            msg += y + 1;
            game.conn.send(JSON.stringify(msg));
        }

        else if(action == 'f')
        {
            var msg = action;
            msg += game.p1[0];
            game.conn.send(JSON.stringify(msg))
        }
    }
    
    function buildDeck(game, facName)
    {
        if (facName=="FF")
        {

            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Brooklyn Blur'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for brooklynblur
            game.deckOfCards[0].name = "<?php echo $rows['name']?>"
            game.deckOfCards[0].type = "<?php echo $rows['type']?>"
            game.deckOfCards[0].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[0].health = "<?php echo $rows['health']?>"
            game.deckOfCards[0].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[0].ranged = "<?php echo $rows['ranged']?>"
            game.deckOfCards[9]=game.deckOfCards[0]
            game.deckOfCards[10]=game.deckOfCards[0]
            game.deckOfCards[11]=game.deckOfCards[0]
            game.deckOfCards[12]=game.deckOfCards[0]
            game.deckOfCards[13]=game.deckOfCards[0]

            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Minuteman'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for minuteman
            game.deckOfCards[1].name = "<?php echo $rows['name']?>"
            game.deckOfCards[1].type = "<?php echo $rows['type']?>"
            game.deckOfCards[1].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[1].health = "<?php echo $rows['health']?>"
            game.deckOfCards[1].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[1].ranged = "<?php echo $rows['ranged']?>"
            game.deckOfCards[2]=game.deckOfCards[1]
            game.deckOfCards[14]=game.deckOfCards[1]
            game.deckOfCards[15]=game.deckOfCards[1]
            game.deckOfCards[16]=game.deckOfCards[1]
            game.deckOfCards[17]=game.deckOfCards[1]

            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Riveteer'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for riveteer
            game.deckOfCards[3].name = "<?php echo $rows['name']?>"
            game.deckOfCards[3].type = "<?php echo $rows['type']?>"
            game.deckOfCards[3].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[3].health = "<?php echo $rows['health']?>"
            game.deckOfCards[3].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[3].ranged = "<?php echo $rows['ranged']?>"
            game.deckOfCards[18]=game.deckOfCards[3]
            game.deckOfCards[19]=game.deckOfCards[3]
            game.deckOfCards[20]=game.deckOfCards[3]
            game.deckOfCards[21]=game.deckOfCards[3]
            game.deckOfCards[22]=game.deckOfCards[3]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Abraham'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for abraham
            game.deckOfCards[5].name = "<?php echo $rows['name']?>"
            game.deckOfCards[5].type = "<?php echo $rows['type']?>"
            game.deckOfCards[5].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[5].health = "<?php echo $rows['health']?>"
            game.deckOfCards[5].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[5].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Crushmore'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for crushmore
            game.deckOfCards[6].name = "<?php echo $rows['name']?>"
            game.deckOfCards[6].type = "<?php echo $rows['type']?>"
            game.deckOfCards[6].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[6].health = "<?php echo $rows['health']?>"
            game.deckOfCards[6].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[6].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Liberty Belle'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for liberty belle
            game.deckOfCards[7].name = "<?php echo $rows['name']?>"
            game.deckOfCards[7].type = "<?php echo $rows['type']?>"
            game.deckOfCards[7].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[7].health = "<?php echo $rows['health']?>"
            game.deckOfCards[7].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[7].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Winged Wonder'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for winged wonder
            game.deckOfCards[8].name = "<?php echo $rows['name']?>"
            game.deckOfCards[8].type = "<?php echo $rows['type']?>"
            game.deckOfCards[8].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[8].health = "<?php echo $rows['health']?>"
            game.deckOfCards[8].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[8].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Let Freedom Ring'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for let freedom ring
            game.deckOfCards[23].name = "<?php echo $rows['name']?>"
            game.deckOfCards[23].type = "<?php echo $rows['type']?>"
            game.deckOfCards[24] = game.deckOfCards[23]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Justice For All'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for justice for all
            game.deckOfCards[25].name = "<?php echo $rows['name']?>"
            game.deckOfCards[25].type = "<?php echo $rows['type']?>"
            game.deckOfCards[26] = game.deckOfCards[25]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Cost of Freedom'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for cost of freedom
            game.deckOfCards[27].name = "<?php echo $rows['name']?>"
            game.deckOfCards[27].type = "<?php echo $rows['type']?>"
            game.deckOfCards[28] = game.deckOfCards[27]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Shock & Awesome'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for shock & awesome
            game.deckOfCards[29].name = "<?php echo $rows['name']?>"
            game.deckOfCards[29].type = "<?php echo $rows['type']?>"
            game.deckOfCards[30] = game.deckOfCards[29]

            //fort info
            game.deckOfCards[4].name = "Hall of Freedom"
            game.deckOfCards[4].type = "Fort"
            game.deckOfCards[4].health = 8;
            game.deckOfCards[31] = game.deckOfCards[4]
            game.deckOfCards[32] = game.deckOfCards[4]

        }

        else
        {
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'El Nino'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for el niño
            game.deckOfCards[0].name = "<?php echo $rows['name']?>"
            game.deckOfCards[0].type = "<?php echo $rows['type']?>"
            game.deckOfCards[0].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[0].health = "<?php echo $rows['health']?>"
            game.deckOfCards[0].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[0].ranged = "<?php echo $rows['ranged']?>"
            game.deckOfCards[9]=game.deckOfCards[0]
            game.deckOfCards[10]=game.deckOfCards[0]
            game.deckOfCards[11]=game.deckOfCards[0]
            game.deckOfCards[12]=game.deckOfCards[0]
            game.deckOfCards[13]=game.deckOfCards[0]

            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Pinata'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for piñata
            game.deckOfCards[1].name = "<?php echo $rows['name']?>"
            game.deckOfCards[1].type = "<?php echo $rows['type']?>"
            game.deckOfCards[1].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[1].health = "<?php echo $rows['health']?>"
            game.deckOfCards[1].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[1].ranged = "<?php echo $rows['ranged']?>"
            game.deckOfCards[2]=game.deckOfCards[1]
            game.deckOfCards[14]=game.deckOfCards[1]
            game.deckOfCards[15]=game.deckOfCards[1]
            game.deckOfCards[16]=game.deckOfCards[1]
            game.deckOfCards[17]=game.deckOfCards[1]

            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Margarita'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for margarita
            game.deckOfCards[3].name = "<?php echo $rows['name']?>"
            game.deckOfCards[3].type = "<?php echo $rows['type']?>"
            game.deckOfCards[3].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[3].health = "<?php echo $rows['health']?>"
            game.deckOfCards[3].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[3].ranged = "<?php echo $rows['ranged']?>"
            game.deckOfCards[18]=game.deckOfCards[3]
            game.deckOfCards[19]=game.deckOfCards[3]
            game.deckOfCards[20]=game.deckOfCards[3]
            game.deckOfCards[21]=game.deckOfCards[3]
            game.deckOfCards[22]=game.deckOfCards[3]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Quetzalcoatl'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for quetzalcoatl
            game.deckOfCards[5].name = "<?php echo $rows['name']?>"
            game.deckOfCards[5].type = "<?php echo $rows['type']?>"
            game.deckOfCards[5].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[5].health = "<?php echo $rows['health']?>"
            game.deckOfCards[5].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[5].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'El Muerto'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for el muerto
            game.deckOfCards[6].name = "<?php echo $rows['name']?>"
            game.deckOfCards[6].type = "<?php echo $rows['type']?>"
            game.deckOfCards[6].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[6].health = "<?php echo $rows['health']?>"
            game.deckOfCards[6].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[6].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Doom Quixote'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for doom quixote
            game.deckOfCards[7].name = "<?php echo $rows['name']?>"
            game.deckOfCards[7].type = "<?php echo $rows['type']?>"
            game.deckOfCards[7].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[7].health = "<?php echo $rows['health']?>"
            game.deckOfCards[7].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[7].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Matadora'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for matadora
            game.deckOfCards[8].name = "<?php echo $rows['name']?>"
            game.deckOfCards[8].type = "<?php echo $rows['type']?>"
            game.deckOfCards[8].hit_dice = "<?php echo $rows['hit_dice']?>"
            game.deckOfCards[8].health = "<?php echo $rows['health']?>"
            game.deckOfCards[8].cost = "<?php echo $rows['cost']?>"
            game.deckOfCards[8].ranged = "<?php echo $rows['ranged']?>"
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Hispanic Panic'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for hispanic panic
            game.deckOfCards[23].name = "<?php echo $rows['name']?>"
            game.deckOfCards[23].type = "<?php echo $rows['type']?>"
            game.deckOfCards[24] = game.deckOfCards[23]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Cinco De Mayo'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for cinco de mayo
            game.deckOfCards[25].name = "<?php echo $rows['name']?>"
            game.deckOfCards[25].type = "<?php echo $rows['type']?>"
            game.deckOfCards[26] = game.deckOfCards[25]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Just Juan More'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for just juan more
            game.deckOfCards[27].name = "<?php echo $rows['name']?>"
            game.deckOfCards[27].type = "<?php echo $rows['type']?>"
            game.deckOfCards[28] = game.deckOfCards[27]
            
            <?php
                $sql="SELECT * FROM cards WHERE cards.name = 'Mexican Mayhem'";
                $result=$mysqli->query($sql);
                $rows=$result->fetch_assoc();
            ?>

            //database call for shock & awesome
            game.deckOfCards[29].name = "<?php echo $rows['name']?>"
            game.deckOfCards[29].type = "<?php echo $rows['type']?>"
            game.deckOfCards[30] = game.deckOfCards[29]

            //fort info
            game.deckOfCards[4].name = "Fiesta Fortress"
            game.deckOfCards[4].type = "Fort"
            game.deckOfCards[4].health = 8;
            game.deckOfCards[31] = game.deckOfCards[4]
            game.deckOfCards[32] = game.deckOfCards[4]
        }              
    }
    </script>

</body>
</html>