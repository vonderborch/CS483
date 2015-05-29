<div  id="body-adminlistgames-body">
    <h3><?php echo anchor("Admin/CreateGame/", 'New Game'); ?> </h3>

    <h3>Games</h3>

    <?php foreach($games as $game) : ?>
    <div id="body-adminlistgames-item"><p>
        - <?php echo $game->name."  (".$game->date."): " ?>
        <?php echo anchor("Blog/Game/".$game->id, 'View'); ?>
        <?php echo anchor("Admin/UpdateGame/".$game->id, 'Update'); ?>
        <?php 
            echo anchor("Admin/DeleteGame/".$game->id, 'Delete', array('onclick' => "return confirm('Are you sure want to delete this post?')")); 
        ?>
    
    </p></div>
    <?php endforeach; ?>
</div>