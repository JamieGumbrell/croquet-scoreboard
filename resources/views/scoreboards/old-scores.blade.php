<div class="md-container">
    <?php 
$scoreboard = $data['scoreboard']; 
$otherScoreboards = $data['otherScoreboards']; 
$countries = $data['country'];
$players = $data['players'];
$player1 = PlayerModel::getPlayerByName($scoreboard->name1, $scoreboard->country1);
$player2 = PlayerModel::getPlayerByName($scoreboard->name2, $scoreboard->country2);
?>
<style>
    <?php if($scoreboard->scoretype=="gateball" && ($scoreboard->ball_color=="primary" || $scoreboard->ball_color=="secondary")): ?>
        .score-1 .label, .games-1 .label, .score-3 .label, .games-3 .label, 
        .score-5 .label, .games-5 .label, .score-7 .label, .games-7 .label, .score-9 .label, .games-9 .label{
        background-color: #ff0000;
        color:white;
    }
    <?php elseif($scoreboard->ball_color=="primary"): ?>
    .score-1 .label, .games-1 .label{
        background-color: #0000ff;
        color:white;
    }

    .score-2 .label, .games-2 .label{
        background-color: #ff0000;
        color:white;
    }

    .score-3 .label{
        background-color: #000000;
        color:white;
    }

    .score-4 .label{
        background-color: #ffff00;
    }
    <?php elseif($scoreboard->ball_color=="secondary"): ?>
        .score-1 .label, .games-1 .label{
        background-color: green;
        color:white;
    }

    .score-2 .label, .games-2 .label{
        background-color: pink;
    }

    .score-3 .label{
        background-color: #8B4513;
        color:white;
    }

    .score-4 .label{
        background-color: white;
    }
    <?php endif; ?>
</style> 
<?php if($scoreboard->scoretype=="gateball"): ?>
    <div class="md-container">
<?php else: ?>
    <div class="sm-container">
<?php endif; ?>
    <?php $this->view('Preview',$data, false, false); ?>
</div>
<hr style="margin: 1.5em 0;">
<?php if(isset($players) && !empty($players)): ?>
    <input id="player-select"type="number" value="1" hidden readonly>
    <div class="player-select">
        <label class="type-label">Player 1</label>
        <select class="country-select input-field grid-8" name="player1" id="player1"  onchange="updatePlayers()">
            <option disabled selected value> -- Select a Player -- </option>
            <?php foreach($players as $p): ?>
                <option <?php if($player1->playerid == $p->playerid)echo 'selected'; ?> value="<?= $p->playerid; ?>"><?= $p->name; ?></option>
            <?php endforeach; ?>
        </select> 
    </div>
    <div class="player-select">
        <label class="type-label">Player 2</label>
        <select class="country-select input-field grid-11" name="player2" id="player2" onchange="updatePlayers()">
            <option disabled selected value> -- Select a Player -- </option>
            <?php foreach($players as $p): ?>
                <option <?php if($player2->playerid == $p->playerid)echo 'selected'; ?> value="<?= $p->playerid; ?>"><?= $p->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php else: ?>
    <input id="player-select"type="number" value="0" hidden readonly>
    <div class="player-select">
        <label class="type-label">Player 1</label>
        <input id="name-1" class="player-input input-field" type="text" value="<?php echo $scoreboard->name1;?>" onchange="updatePlayers()">
        <select class="country-select input-field" name="country1" id="country1" onchange="updatePlayers()">
            <option <?php if($scoreboard->country1 == "hid")echo 'selected'; ?> value="hid">--Hidden--</option>
            <?php foreach($countries as $country): ?>
                <option <?php if($scoreboard->country1 == $country->code)echo 'selected'; ?> value="<?= $country->code; ?>"><?= $country->name; ?></option>
            <?php endforeach; ?>
        </select>  
    </div>
    <div class="player-select">
        <label class="type-label">Player 2</label>
        <input id="name-2" class="player-input input-field grid-11" type="text" value="<?php echo $scoreboard->name2;?>" onchange="updatePlayers()">
        <select class="country-select input-field grid-12" name="country2" id="country2" onchange="updatePlayers()">
            <option <?php if($scoreboard->country2 == "hid")echo 'selected'; ?> value="hid">--Hidden--</option>
            <?php foreach($countries as $country): ?>
                <option <?php if($scoreboard->country2 == $country->code)echo 'selected'; ?> value="<?= $country->code; ?>"><?= $country->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>
    <div id="btn-switch"><button type="button" class="btn default-primary-color text-primary-color" onclick="switchNames()"><i class="fas fa-repeat"></i> Switch Players</button></div> 

<!--div class="switch-scoreboard">
    <label class="type-label">Switch Scoreboard</label>
    <select class="input-field" name="switch-scoreboard" id="switch-scoreboard">
        <?php foreach($otherScoreboards as $s): ?>
            <option value="<?= $s->scoreboardid; ?>"><?=  $s->title; ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn default-primary-color text-primary-color" onclick="switchScoreboard('<?= $scoreboard->uid; ?>')">Switch</button>
</div-->
<div class="control-panel" <?php if($scoreboard->savegames == 0): ?>style="flex-direction: column-reverse;"<?php endif;?>>
    <?php if($scoreboard->savegames == 0 && $scoreboard->scoretype != "gateball"): ?>
    <div class="games">
        <h2>Games</h2>
        <div class="games-1">
            <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('games-1')">-</button>
            <p class="label" id="games-1"><?php echo $scoreboard->games1;?></p>
            <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('games-1')">+</button>
        </div>
        <div class="games-2">
            <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('games-2')">-</button>
            <p class="label" id="games-2"><?php echo $scoreboard->games2;?></p>
            <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('games-2')">+</button>
        </div>
        <div class="reset">
            <button class="btn warning-color primary-text-color" onclick="resetGames()">Reset</button>
        </div>
    </div> 
    <?php endif; ?>   
    <div class="score">
        <h2>Score</h2>
        <div class="score-1">
            <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('score-1')">-</button>
            <p class="label" id="score-1"><?php echo $scoreboard->score1;?></p>
            <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('score-1')">+</button>
        </div>
        <div class="score-2">
            <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('score-2')">-</button>
            <p class="label" id="score-2"><?php echo $scoreboard->score2;?></p>
            <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('score-2')">+</button>
        </div>
        <?php if($scoreboard->scoretype=="single" || $scoreboard->scoretype=="gateball"): ?>
            <div class="score-3">
                <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('score-3')">-</button>
                <p class="label" id="score-3"><?php echo $scoreboard->score3;?></p>
                <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('score-3')">+</button>
            </div>
            <div class="score-4">
                <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('score-4')">-</button>
                <p class="label" id="score-4"><?php echo $scoreboard->score4;?></p>
                <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('score-4')">+</button>
        </div>
        <?php endif; ?>
        <?php if($scoreboard->scoretype=="gateball"): 
            for($i = 5; $i<=10; $i++): ?>
                <div class="score-<?=$i?>">
                    <button class="btn default-primary-color text-primary-color" onclick="scoreSubtract('score-<?=$i?>')">-</button>
                    <p class="label" id="score-<?=$i?>"><?php echo $scoreboard->{"score$i"};?></p>
                    <button class="btn default-primary-color text-primary-color" onclick="scoreAdd('score-<?=$i?>')">+</button>
                </div>
            <?php endfor;
        endif; ?>
        <div class="reset">
            <button class="btn warning-color primary-text-color" onclick="resetScore()">Reset</button>
        </div>
    </div>
    <?php if($scoreboard->savegames == 1 && $scoreboard->scoretype != "gateball"): ?>
    <div class="save-games">
        <div class="games-btn">
            <button class="btn success-color" onclick="saveGame()"><i class="fas fa-save"></i> Save Game</button>
            <button class="btn error-color" onclick="if(confirm('This will delete all the saved games. Are you sure?'))deleteAllGames()"><i class="fas fa-trash"></i> Delete All</button>
        </div>
        <h2>Game Scores</h2>
        <div class="games-list">
            <?php 
            $i = 0;
            foreach($data['games'] as $game): 
            $i++; ?>
                <div class="game-item">
                    <div class="g-no">Game <?php echo $i; ?>:</div>
                    <div class="g-score"><?php echo $game->score1."-".$game->score2; ?></div>
                    <div class="g-delete" onclick="if(confirm('Are you sure you want to delete this game?')) deleteGame(<?php echo $game->gameid;?>)"><i class="fas fa-trash"></i></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

</div>