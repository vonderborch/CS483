
// main graphics stuffs
var stage = null;
var graphics = null;
var stageMinX = 0;
var stageMinY = 0;
var stageMaxX = 500;
var stageMaxY = 500;

// globals
var state = 0; // 0 = main menu, 1 = game
var keyUp = false;
var keyDown = false;
var keyLeft = false;
var keyRight = false;
var clickX = 0;
var clickY = 0;
var mousepressed = false;

var difficulty = 3;
var multiplyballs = false;
var playerPanelSpeed = 4;
var aiPanelSpeed = 1;
var ballspeed = 3;

var playerScore = 0;
var aiScore = 0;

var sideUpControl = "Wall"; // Wall, AI, Player
var sideDownControl = "Wall"; // Wall, AI, Player
var sideLeftControl = "Player"; // Wall, AI, Player
var sideRightControl = "AI"; // Wall, AI, Player

var playerpanels = Array();
var aipanels = Array();
var balls = Array();
var buttons = Array();
var txts = Array();

function btn(graphics, id, x, y, width, height, font, text, color, presscolor) {
    var self = this;
    self.ID = id;
    self.X = x;
    self.Y = y;
    self.Width = width;
    self.Height = height;
    self.Font = font;
    self.Text = text;
    self.Color = color;
    self.PressedColor = presscolor;
    self.Graphics = graphics;
    self.Pressed = false;
    self.Released = false;

    self.Draw = function () {
        self.Graphics.font = self.Font;
        if (self.Pressed == true) {
            self.Graphics.fillStyle = self.PressedColor;
        }
        else {
            self.Graphics.fillStyle = self.Color;
        }
        self.Graphics.fillText(self.Text, self.X, self.Y);
    }

    self.Collision = function () {
        return (self.X < clickX && self.Y < clickY &&
                self.X + self.Width > clickX &&
                self.Y + self.Height > clickY);
    }

    self.Update = function () {
        self.Released = false;
        if (mousepressed == true) {
            if (self.Collision()) {
                self.Pressed = true;
                if (self.ClickEvent != null) {
                    self.ClickEvent();
                }
            }
        }
        else {
            if (self.Pressed == true) {
                self.Released = true;
            }
            self.Pressed = false;
        }
    }

    self.ClickEvent = null;
}

function txt(graphics, id, x, y, font, text, color) {
    var self = this;
    self.ID = id;
    self.X = x;
    self.Y = y;
    self.Font = font;
    self.Text = text;
    self.Color = color;
    self.Graphics = graphics;

    self.Draw = function () {
        self.Graphics.font = self.Font;
        self.Graphics.fillStyle = self.Color;
        self.Graphics.fillText(self.Text, self.X, self.Y);
    };
}

///
///
///
function playerpanel(graphics, id, x, y, width, height, color, horizontal, side) {
    var self = this;
    self.ID = id;
    self.Color = color;
    self.X = x;
    self.Y = y;
    self.Width = width;
    self.Height = height;
    self.Graphics = graphics;
    self.Level = 0;
    self.Live = true;
    self.Horizontal = horizontal;
    self.MaxX = x + width;
    self.MaxY = y + width;
    self.MidpointX = (self.MaxX) / 2;
    self.MidpointY = (self.MaxY) / 2;
    self.Side = side;

    self.Draw = function () {
        if (self.Live == true) {
            self.Graphics.fillStyle = self.Color;
            self.Graphics.fillRect(self.X, self.Y, self.Width, self.Height);
        }
    }

    self.UpdateMidPoint = function () {
        self.MaxX = self.X + self.Width;
        self.MaxY = self.Y + self.Height;
        self.MidpointX = (self.MaxX) / 2;
        self.MidpointY = (self.MaxY) / 2;
    }

    self.Update = function () {
        if (self.Live == true) {
            // input handling...
            if (self.Horizontal == false) {
                if (keyUp == true) {
                    self.Y -= playerPanelSpeed;
                }
                else if (keyDown == true) {
                    self.Y += playerPanelSpeed;
                }
            }
            else {
                if (keyLeft == true) {
                    self.X -= playerPanelSpeed;
                }
                else if (keyRight == true) {
                    self.X += playerPanelSpeed;
                }
            }

            // bounds checking
            while (self.X < stageMinX) {
                self.X++;
            }
            while (self.X + self.Width > stageMaxX) {
                self.X--;
            }
            while (self.Y < stageMinY) {
                self.Y++;
            }
            while (self.Y + self.Height > stageMaxY) {
                self.Y--;
            }
        }
    }
}

function FindDistance(x1, x2, y1, y2) {
    var dx = Math.abs(x1 - x2);
    var dy = Math.abs(y1 - y2);

    return Math.sqrt((dx * dx) + (dy * dy));
}

function RectangleVsCircleCollision(rx, ry, rw, rh, cx, cy, cr) {
    var dx = Math.abs(cx - rx - rw / 2);
    var dy = Math.abs(cy - ry - rh / 2);

    if (dx > (rw / 2 + cr)) {
        return false;
    }
    if (dy > (rh / 2 + cr)) {
        return false;
    }

    if (dx <= (rw / 2)) {
        return true;
    }
    if (dy <= (rh / 2)) {
        return true;
    }

    var d2x = dx - rw / 2;
    var d2y = dy - rh / 2;
    return ((d2x * d2x + d2y * d2y) <= (cr * cr));
}

function ball(graphics, id, x, y, radius, xDirection, yDirection, color) {
    var self = this;
    self.ID = id;
    self.X = x;
    self.Y = y;
    self.Radius = radius;
    self.XDirection = xDirection;
    self.YDirection = yDirection;
    self.Color = color;
    self.Graphics = graphics;
    self.Live = true;

    self.Draw = function () {
        if (self.Live == true) {
            //self.Graphics.fillStyle = self.Color;
            //self.Graphics.fillRect(self.X, self.Y, self.Radius, self.Radius);

            self.Graphics.fillStyle = self.Color;
            self.Graphics.beginPath();
            self.Graphics.arc(self.X, self.Y, self.Radius, 0, Math.PI * 2, true);
            self.Graphics.fill();
        }
    }

    self.Update = function () {
        if (self.Live == true) {
            // check for aipanel collisions
            for (var i = 0; i < aipanels.length; i++) {
                var collides = RectangleVsCircleCollision(aipanels[i].X, aipanels[i].Y, aipanels[i].Width, aipanels[i].Height, self.X, self.Y, self.Radius);

                if (collides == true) {
                    switch (aipanels[i].Side) {
                        case "Up":
                            self.YDirection = 1 * Math.random() * ballspeed;
                            break;
                        case "Down":
                            self.YDirection = -1 * Math.random() * ballspeed;
                            break;
                        case "Left":
                            self.XDirection = 1 * Math.random() * ballspeed;
                            break;
                        case "Right":
                            self.XDirection = -1 * Math.random() * ballspeed;
                            break;
                    }
                }
            }

            // check for playerpanel collisions
            for (var i = 0; i < playerpanels.length; i++) {
                var collides = RectangleVsCircleCollision(playerpanels[i].X, playerpanels[i].Y, playerpanels[i].Width, playerpanels[i].Height, self.X, self.Y, self.Radius);

                if (collides == true) {
                    switch (playerpanels[i].Side) {
                        case "Up":
                            self.YDirection = 1 * Math.random() * ballspeed;
                            break;
                        case "Down":
                            self.YDirection = -1 * Math.random() * ballspeed;
                            break;
                        case "Left":
                            self.XDirection = 1 * Math.random() * ballspeed;
                            break;
                        case "Right":
                            self.XDirection = -1 * Math.random() * ballspeed;
                            break;
                    }
                }
            }

            // check if out of bounds...
            if (self.X - self.Radius < stageMinX) {
                if (sideLeftControl == "Wall") {
                    self.XDirection = 1 * Math.random() * ballspeed;
                }
                else {
                    self.Live = false;
                    for (var i = 0; i < txts.length; i++) {
                        if (sideLeftControl == "Player" && txts[i].ID == "AScore") {
                            aiScore++;
                            txts[i].Text = "AI Score: " + aiScore.toString();
                        }
                        else if (sideLeftControl == "AI" && txts[i].ID == "PScore") {
                            playerScore++;
                            txts[i].Text = "Player Score: " + playerScore.toString();
                        }
                    }
                }
            }
            else if (self.X + self.Radius > stageMaxX) {
                if (sideRightControl == "Wall") {
                    self.XDirection = -1 * Math.random() * ballspeed;
                }
                else {
                    self.Live = false;
                    for (var i = 0; i < txts.length; i++) {
                        if (sideRightControl == "Player" && txts[i].ID == "AScore") {
                            aiScore++;
                            txts[i].Text = "AI Score: " + aiScore.toString();
                        }
                        else if (sideRightControl == "AI" && txts[i].ID == "PScore") {
                            playerScore++;
                            txts[i].Text = "Player Score: " + playerScore.toString();
                        }
                    }
                }
            }
            if (self.Y - self.Radius < stageMinY) {
                if (sideUpControl == "Wall") {
                    self.YDirection = 1 * Math.random() * ballspeed;
                }
                else {
                    self.Live = false;
                    for (var i = 0; i < txts.length; i++) {
                        if (sideUpControl == "Player" && txts[i].ID == "AScore") {
                            aiScore++;
                            txts[i].Text = "AI Score: " + aiScore.toString();
                        }
                        else if (sideUpControl == "AI" && txts[i].ID == "PScore") {
                            playerScore++;
                            txts[i].Text = "Player Score: " + playerScore.toString();
                        }
                    }
                }
            }
            else if (self.Y + self.Radius > stageMaxY) {
                if (sideDownControl == "Wall") {
                    self.YDirection = -1 * Math.random() * ballspeed;
                }
                else {
                    self.Live = false;
                    for (var i = 0; i < txts.length; i++) {
                        if (sideDownControl == "Player" && txts[i].ID == "AScore") {
                            aiScore++;
                            txts[i].Text = "AI Score: " + aiScore.toString();
                        }
                        else if (sideDownControl == "AI" && txts[i].ID == "PScore") {
                            playerScore++;
                            txts[i].Text = "Player Score: " + playerScore.toString();
                        }
                    }
                }
            }

            self.X += self.XDirection;
            self.Y += self.YDirection;
        }
    }
}

function aipanel(graphics, id, x, y, width, height, color, horizontal, side) {
    var self = this;
    self.ID = id;
    self.Color = color;
    self.X = x;
    self.Y = y;
    self.Width = width;
    self.Height = height;
    self.Graphics = graphics;
    self.Level = 0;
    self.Live = true;
    self.Horizontal = horizontal;
    self.MaxX = x + width;
    self.MaxY = y + width;
    self.MidpointX = (self.MaxX) / 2;
    self.MidpointY = (self.MaxY) / 2;
    self.Side = side;

    self.Draw = function () {
        if (self.Live == true) {
            self.Graphics.fillStyle = self.Color;
            self.Graphics.fillRect(self.X, self.Y, self.Width, self.Height);
        }
    }

    self.UpdateMidPoint = function () {
        self.MaxX = self.X + self.Width;
        self.MaxY = self.Y + self.Height;
        self.MidpointX = (self.MaxX) / 2;
        self.MidpointY = (self.MaxY) / 2;
    }

    self.Update = function () {
        if (self.Live == true) {
            // find nearest ball...
            var dist = 100000;
            var up = false;
            var down = false;
            var left = false;
            var right = false;
            for (var i = 0; i < balls.length; i++) {
                var tx = balls[i].X;
                var ty = balls[i].Y;
                var td = FindDistance(self.MidpointX, tx, self.MidpointY, ty);
                if (td < dist) {
                    dist = td;
                    up = false;
                    down = false;
                    left = false;
                    right = false;
                    if (tx < self.MidpointX) {
                        left = true;
                    }
                    if (tx > self.MidpointX) {
                        right = true;
                    }
                    if (ty < self.MidpointY) {
                        up = true;
                    }
                    if (ty > self.MidpointY) {
                        down = true;
                    }
                }
            }

            // know that we know which way we're supposed to go...
            if (self.Horizontal == false) {
                if (up == true) {
                    self.Y -= aiPanelSpeed;
                }
                if (down == true) {
                    self.Y += aiPanelSpeed;
                }
            }
            else {
                if (left == true) {
                    self.X -= aiPanelSpeed;
                }
                if (right == true) {
                    self.X += aiPanelSpeed;
                }
            }

            // bounds checking
            while (self.X < stageMinX) {
                self.X++;
            }
            while (self.X + self.Width > stageMaxX) {
                self.X--;
            }
            while (self.Y < stageMinY) {
                self.Y++;
            }
            while (self.Y + self.Height > stageMaxY) {
                self.Y--;
            }
        }
    }
}

function Setup() {
    stage = document.getElementById("stage");
    graphics = stage.getContext("2d");

    window.addEventListener("keydown", KeyDownPress);
    window.addEventListener("keyup", KeyUpPress);
    document.getElementById("stage").addEventListener("mousedown", ScreenClick);
    document.getElementById("stage").addEventListener("mouseup", ScreenClickUp);

    SetupMainMenu();

    // startup the controller!!!
    window.requestAnimationFrame(Controller);
}

function Controller() {
    // clear the screen
    graphics.clearRect(stageMinX, stageMinY, stageMaxX + 50, stageMaxY + 50);

    // update and draw based on the game state!
    switch (state) {
        case 0: // main menu
            UpdateMainMenu();
            DrawMainMenu();
            break;
        case 1: // game
            UpdateGame();
            DrawGame();
            break;
    }

    // request to be drawn again
    window.requestAnimationFrame(Controller);
}

function Cleanup() {
    // clear arrays
    buttons = new Array();
    txts = new Array();
    balls = new Array();
    playerpanels = new Array();
    aipanels = new Array();
}

function UpdateMainMenu() {
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].Update();

        if (buttons[i].Released) {
            switch (buttons[i].ID) {
                case "Start":
                    SetupGame();
                    state = 1;
                    break;
                case "DiffEasy":
                    difficulty = 1;
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentDifficulty") {
                            txts[i].Text = "Current Difficulty: Easy";
                        }
                    }
                    break;
                case "DiffNormal":
                    difficulty = 3;
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentDifficulty") {
                            txts[i].Text = "Current Difficulty: Normal";
                        }
                    }
                    break;
                case "DiffHard":
                    difficulty = 5;
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentDifficulty") {
                            txts[i].Text = "Current Difficulty: Hard";
                        }
                    }
                    break;
                case "ClassicMode":
                    // Wall, AI, Player
                    sideUpControl = "Wall";
                    sideDownControl = "Wall";
                    sideLeftControl = "Player";
                    sideRightControl = "AI";
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentMode") {
                            txts[i].Text = "Current Game Mode: Classic";
                        }
                    }
                    break;
                case "EvolvedMode":
                    // Wall, AI, Player
                    sideUpControl = "Player";
                    sideDownControl = "AI";
                    sideLeftControl = "Player";
                    sideRightControl = "AI";
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentMode") {
                            txts[i].Text = "Current Game Mode: Evolved";
                        }
                    }
                    break;
                case "YesMultiply":
                    multiplyballs = true;
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentMultiplication") {
                            txts[i].Text = "Currently Multiplying Balls? true";
                        }
                    }
                    break;
                case "NoMultiply":
                    multiplyballs = false;
                    for (var i = 0; i < txts.length; i++) {
                        if (txts[i].ID == "CurrentMultiplication") {
                            txts[i].Text = "Currently Multiplying Balls? false";
                        }
                    }
                    break;
            }
        }
    }

}

function DrawMainMenu() {
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].Draw();
    }

    for (var i = 0; i < txts.length; i++) {
        txts[i].Draw();
    }
}

function SetupMainMenu() {
    Cleanup();

    // text items
    txts.push(new txt(graphics, "GameName", 25, 25, "32px Lucida-Sans", "Super Pong", "black"));
    txts.push(new txt(graphics, "MultiplyBalls", 25, 200, "16px Tahoma", "Multiply Balls?", "black"));
    txts.push(new txt(graphics, "CurrentMultiplication", 25, 225, "16px Tahoma", "Currently Multiplying Balls? false", "black"));
    txts.push(new txt(graphics, "Mode", 25, 300, "16px Tahoma", "Game Mode:", "black"));
    txts.push(new txt(graphics, "CurrentMode", 25, 325, "16px Tahoma", "Current Game Mode: Classic", "black"));
    txts.push(new txt(graphics, "Difficulty", 25, 400, "16px Tahoma", "Difficulty:", "black"));
    txts.push(new txt(graphics, "CurrentDifficulty", 25, 425, "16px Tahoma", "Current Difficulty: Normal", "black"));
    txts.push(new txt(graphics, "aboutme", 25, 480, "12px Tahoma", "Developed by: Christian Webber", "black"));
    txts.push(new txt(graphics, "aboutver", 445, 480, "12px Tahoma", "v1.0.0", "black"));

    // start game option
    buttons.push(new btn(graphics, "Start", 25, 100, 75, 40, "24px Tahoma", "Start", "black", "red"));
    // ball multiplication option
    buttons.push(new btn(graphics, "NoMultiply", 200, 200, 40, 20, "16px Tahoma", "false", "black", "red"));
    buttons.push(new btn(graphics, "YesMultiply", 240, 200, 40, 20, "16px Tahoma", "true", "black", "red"));
    // game mode option
    buttons.push(new btn(graphics, "ClassicMode", 200, 300, 50, 20, "16px Tahoma", "Classic", "black", "red"));
    buttons.push(new btn(graphics, "EvolvedMode", 250, 300, 50, 20, "16px Tahoma", "Evolved", "black", "red"));
    // difficulty options
    buttons.push(new btn(graphics, "DiffEasy", 200, 400, 40, 20, "16px Tahoma", "Easy", "black", "red"));
    buttons.push(new btn(graphics, "DiffNormal", 240, 400, 60, 20, "16px Tahoma", "Normal", "black", "red"));
    buttons.push(new btn(graphics, "DiffHard", 300, 400, 40, 20, "16px Tahoma", "Hard", "black", "red"));
}

// returns random choice between -1 and 1, multiplied by the ball speed
function RandomPositivity() {
    return (Math.round(Math.random()) * 2 - 1) * ballspeed;
}

// returns a random direction
function RandomDirection() {
    return RandomPositivity() * Math.random();
}

function UpdateGame() {
    for (var i = 0; i < balls.length; i++) {
        balls[i].Update();

        if (balls[i].Live == false) {
            // spawn 2 new balls?
            if (multiplyballs == true) {
                balls[i].Live = true;
                balls[i].X = 250;
                balls[i].Y = 250;
                balls[i].XDirection = RandomDirection();
                balls[i].YDirection = RandomDirection();
                balls.push(new ball(graphics, "ball", 250, 250, 10, RandomDirection(), RandomDirection(), "rgb(0, 0, 255)"));
            }
                // or only 1 :(
            else {
                balls[i].Live = true;
                balls[i].X = 250;
                balls[i].Y = 250;
                balls[i].XDirection = RandomDirection();
                balls[i].YDirection = RandomDirection();
            }
        }
    }

    for (var i = 0; i < aipanels.length; i++) {
        aipanels[i].Update();
        aipanels[i].UpdateMidPoint();
    }

    for (var i = 0; i < playerpanels.length; i++) {
        playerpanels[i].Update();
        playerpanels[i].UpdateMidPoint();
    }
}

function DrawGame() {
    for (var i = 0; i < balls.length; i++) {
        balls[i].Draw();
    }

    for (var i = 0; i < aipanels.length; i++) {
        aipanels[i].Draw();
    }

    for (var i = 0; i < playerpanels.length; i++) {
        playerpanels[i].Draw();
    }

    for (var i = 0; i < txts.length; i++) {
        txts[i].Draw();
    }
}

function SetupGame() {
    Cleanup();

    // Wall, AI, Player
    //sideUpControl = "Wall";
    //sideDownControl = "Wall";
    //sideLeftControl = "Player";
    //sideRightControl = "AI";

    txts.push(new txt(graphics, "PScore", 25, 450, "20px Tahoma", "Player Score: 0", "black"));
    txts.push(new txt(graphics, "AScore", 25, 475, "20px Tahoma", "AI Score: 0", "black"));

    // scale speeds
    if (difficulty == 1) {
        aiPanelSpeed = 1;
        playerPanelSpeed = 5;
        ballspeed = 1;
    } else if (difficulty == 2) {
        aiPanelSpeed = 2;
        playerPanelSpeed = 4;
        ballspeed = 2;
    } else if (difficulty == 3) {
        aiPanelSpeed = 3;
        playerPanelSpeed = 3;
        ballspeed = 3;
    } else if (difficulty == 4) {
        aiPanelSpeed = 4;
        playerPanelSpeed = 2;
        ballspeed = 4;
    } else if (difficulty == 5) {
        aiPanelSpeed = 5;
        playerPanelSpeed = 1;
        ballspeed = 5;
    }

    // add up panel
    if (sideUpControl == "Player") {
        playerpanels.push(new playerpanel(graphics, "playerUp", 200, 0, 100, 25, "black", true, "Up"));
    } else if (sideUpControl == "AI") {
        aipanels.push(new aipanel(graphics, "playerUp", 200, 0, 100, 25, "red", true, "Up"));
    }
    // add down panel
    if (sideDownControl == "Player") {
        playerpanels.push(new playerpanel(graphics, "playerDown", 200, 475, 100, 25, "black", true, "Down"));
    } else if (sideDownControl == "AI") {
        aipanels.push(new aipanel(graphics, "playerDown", 200, 475, 100, 25, "red", true, "Down"));
    }
    // add left panel
    if (sideLeftControl == "Player") {
        playerpanels.push(new playerpanel(graphics, "playerLeft", 0, 200, 25, 100, "black", false, "Left"));
    } else if (sideLeftControl == "AI") {
        aipanels.push(new aipanel(graphics, "playerLeft", 0, 200, 25, 100, "red", false, "Left"));
    }
    // add right panel
    if (sideRightControl == "Player") {
        playerpanels.push(new playerpanel(graphics, "playerRight", 475, 200, 25, 100, "black", false, "Right"));
    } else if (sideRightControl == "AI") {
        aipanels.push(new aipanel(graphics, "playerRight", 475, 200, 25, 100, "red", false, "Right"));
    }

    // add ball
    balls.push(new ball(graphics, "ball", 250, 250, 10, RandomDirection(), RandomDirection(), "rgb(0, 0, 255)"));

    // reset scores
    playerScore = 0;
    aiScore = 0;
}

function KeyDownPress(evt) {
    // up
    if (evt.keyCode == 87 || evt.keyCode == 119) {
        keyUp = true;
    }

    // down
    if (evt.keyCode == 83 || evt.keyCode == 115) {
        keyDown = true;
    }

    // left
    if (evt.keyCode == 65 || evt.keyCode == 97) {
        keyLeft = true;
    }

    // right
    if (evt.keyCode == 68 || evt.keyCode == 100) {
        keyRight = true;
    }
}

function KeyUpPress(evt) {
    // up
    if (evt.keyCode == 87 || evt.keyCode == 119) {
        keyUp = false;
    }

    // down
    if (evt.keyCode == 83 || evt.keyCode == 115) {
        keyDown = false;
    }

    // left
    if (evt.keyCode == 65 || evt.keyCode == 97) {
        keyLeft = false;
    }

    // right
    if (evt.keyCode == 68 || evt.keyCode == 100) {
        keyRight = false;
    }


    // escape
    if (evt.keyCode == 27) {
        if (state == 1) {
            SetupMainMenu();
            state = 0;
        }
    }
}

function ScreenClick(evt) {
    clickX = evt.pageX;
    clickY = evt.pageY - 30;
    mousepressed = true;
}

function ScreenClickUp(evt) {
    clickX = evt.pageX;
    clickY = evt.pageY;
    mousepressed = false;
}
