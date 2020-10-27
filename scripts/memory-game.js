// Sets the tiles for a English to Character memory game.
function setEngToCharTiles() {
	var ni = '\u4F60';
	var nin = '\u60A8';
	var hao = '\u597D';
	var wo = '\u6211';
	var hen = '\u5F88';
	var ma = '\u5417'; 

	tileList1 = [ni, nin, hao, wo, hen, ma];
	tileList2 = ['You', 'You (Polite)', 'Good', 'I, me', 'Very', 'Interrogative word'];
	gameTiles = tileList1.concat(tileList2);
	
	document.getElementById("mainTitle").innerHTML = "English and Characters";
	removeGameButtons();
}

// Sets the tiles for a English to Pinyin memory game.
function setEngToPinTiles() {
	var you = 'n'+'i\u030C';
	var youP = 'n'+'\xED'+'n';
	var good = 'h'+'a\u030C'+'o';
	var me = 'w'+'o\u030C';
	var very = 'h'+'e\u030C'+'n';
	var inter = 'ma';

	tileList1 = ['You', 'You (Polite)', 'Good', 'I, me', 'Very', 'Interrogative word'];
	tileList2 = [you, youP, good, me, very, inter];
	gameTiles = tileList1.concat(tileList2);
	
	document.getElementById("mainTitle").innerHTML = "English and Pinyin";
	removeGameButtons();
}

// Sets the tiles for a Character to Pinyin memory game.
function setCharToPinTiles() {
	var ni = '\u4F60';
	var nin = '\u60A8';
	var hao = '\u597D';
	var wo = '\u6211';
	var hen = '\u5F88';
	var ma = '\u5417';

	var you = 'n'+'i\u030C';
	var youP = 'n'+'\xED'+'n';
	var good = 'h'+'a\u030C'+'o';
	var me = 'w'+'o\u030C';
	var very = 'h'+'e\u030C'+'n';
	var inter = 'ma';

	tileList1 = [ni, nin, hao, wo, hen, ma];
	tileList2 = [you, youP, good, me, very, inter];
	gameTiles = tileList1.concat(tileList2);
	
	document.getElementById("mainTitle").innerHTML = "Characters and Pinyin";
	removeGameButtons();
}
// Removes the buttons from the page, then runs the game.
function removeGameButtons() {
	// Remove the buttons.
	document.getElementById("EngToChar").remove();
	document.getElementById("EngToPin").remove();
	document.getElementById("CharToPin").remove();
	
	// Add the tags to run the game.
	var buttonMenu = document.getElementById("gameCont");
	buttonMenu.innerHTML = "<div id = 'memory_board'></div>";
	buttonMenu.innerHTML += "<input id = 'SelectGame' class = 'btnGameSelect' onclick = 'restoreButtons()' type = 'button' value = 'Select Memory Game'></input>";
	newBoard();
}

// Show the buttons to reselect a memory game.
function restoreButtons() {
	var buttonMenu = document.getElementById("gameCont");
	buttonMenu.innerHTML = "<input id = 'EngToChar' class = 'btnGame' onclick = 'setEngToCharTiles()' type = 'button' value = 'English and Characters'></input>";
	buttonMenu.innerHTML += "<input id = 'EngToPin' class = 'btnGame' onclick = 'setEngToPinTiles()' type = 'button' value = 'English and Pinyin'></input>";
	buttonMenu.innerHTML += "<input id = 'CharToPin' class = 'btnGame' onclick = 'setCharToPinTiles()' type = 'button' value = 'Characters and Pinyin'></input>";
	
	// Clear reset title and memory board.
	document.getElementById("mainTitle").innerHTML = "Select Memory Game";
	document.getElementById("memory_board").innerHTML = "";
}

// SETTING THE ELEMENTS FOR THE MEMORY GAME.
// The tiles are declared as a game is selected.
var tileList1 = [];
var tileList2 = [];
var gameTiles = [];

var tilesSelected = []; 
// An array that will contain memory tile IDs that the user selects.
var memory_tile_ids = [];  
var totalTilesFlipped = 0;

// Method to shuffle the tiles. The code was found through research on the internet.
Array.prototype.shuffle_tiles = function() {
	var i = this.length, j, temp;
	while (--i > 0) {
		j = Math.floor(Math.random() * (i + 1));
		temp = this[j];
		this[j] = this[i];
		this[i] = temp;
	}
}

// Generates a new board.
function newBoard() {
	totalTilesFlipped = 0;
	var tiles_generated = '';
	gameTiles.shuffle_tiles(); 
	
	// Add each tile onto the page with the functionality.
	for (var i = 0; i < gameTiles.length; i++) {
		// When a tile is clicked on, run the flipTile method.
		tiles_generated  += "<div id = 'tile_" + i + "' onclick = 'flipTile(this,\"" + gameTiles[i] + "\")'></div>";
	}
	
	// Show the tiles on the page.
	document.getElementById('memory_board').innerHTML = tiles_generated;
}

// Performs the actions involved when flipping a single tile.
function flipTile(tile, value) {
	// If the tile is unflipped and a pair of tiles haven't been selected yet.
	if (tile.innerHTML == "" && tilesSelected.length < 2) {
		// Display the tile value.
		tile.style.background = 'White';
		tile.innerHTML = value;
		
		// Check the current number of active tiles flipped.
		switch (tilesSelected.length) {
			// If it's the first tile (of the pair) that was flipped.
			case 0:
				// Retrieve the tile's information.
				var indexSelected = getItemPosition(value);
				tilesSelected.push(indexSelected);
				memory_tile_ids.push(tile.id);
				break;
			// If a tile was already flipped (2 tiles have been flipped at this point).
			case 1:
				var indexSelected = getItemPosition(value);
				tilesSelected.push(indexSelected);
				memory_tile_ids.push(tile.id);

				// Check if the tile values' indexes match (from their respective arrays).
				// In other words, if the tiles match.
				if (tilesSelected[0] == tilesSelected[1]) {
					// Increment the total tile flipped and clear the local storage
					// of both selected tiles (for the next tiles to be selected).
					totalTilesFlipped += 2;
					tilesSelected = [];
					memory_tile_ids = [];

					// Generate a new board once all the tiles have been flipped.
					if (totalTilesFlipped == gameTiles.length) {
						alert("Well done! Generating new board...");
						document.getElementById('memory_board').innerHTML = "";               
						newBoard();
					}
				// Flip the tiles back if they don't match.
				} else {
					// The time it takes for the cards to flip back over.
					setTimeout(flip_tiles_back, 700);
				}
				break;
		}
	}
}

// Flip the tiles back.
function flip_tiles_back() {
	var tile_1 = document.getElementById(memory_tile_ids[0]);
	var tile_2 = document.getElementById(memory_tile_ids[1]);
	tile_1.innerHTML = "";
	tile_2.innerHTML = "";
	tilesSelected = [];
	memory_tile_ids = [];
}

// Gets the tile value's index in its respective array.
function getItemPosition(tileValue) {
	var index = tileList1.indexOf(tileValue);
	if (index < 0) {
		index = tileList2.indexOf(tileValue);
	}
	return index;
}