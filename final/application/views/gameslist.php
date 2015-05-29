<div id="body-gameslist-body">
    <h3>Games</h3>

    <?php foreach($games as $game) :?>
        <div id="body-gameslist-game">
            <h4>
                <?php echo anchor("Blog/Game/{$game->id}", $game->name); ?>
            </h4>
            <h5>
                <?php 
                    echo $game->date;
                ?>
                <?php
                if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4)
                {
                    $hasvoted = false;
                    if (isset($game->voters))
                    {
                        $voters = explode('&', $game->voters);

                        foreach ($voters as $voter => $vote)
                        {
                            if (isset($vote))
                            {
                                if ($vote == $_SESSION["id"])
                                {
                                    $hasvoted = true;
                                }
                            }
                        }
                    }

                    echo "Upvotes: ";
                    if ($hasvoted == false)
                    {
                        echo anchor('Blog/VoteGame/'.$game->id."/1", $game->upvotes);
                    }
                    else
                    {
                        echo $game->upvotes;
                    }
                    echo "  |  ";
                    echo "Downvotes: ";
                    if ($hasvoted == false)
                    {
                        echo anchor('Blog/VoteGame/'.$game->id."/-1", $game->downvotes);
                    }
                    else
                    {
                        echo $game->downvotes;
                    }
                }
                else {
                    echo $game->upvotes;
                    echo "  |  ";
                    echo $game->downvotes;
                }
                ?>
            </h5>
    
            <p>
                <?php 
                    echo $game->description;
                ?>
            </p>
        </div>
    <?php endforeach; ?>

</div>