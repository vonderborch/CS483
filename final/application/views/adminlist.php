<div  id="body-adminlist-body">
    <h3><?php echo anchor("Admin/Create/", 'New Post'); ?> </h3>

    <h3>Posts</h3>

    <?php foreach($posts as $post) : ?>
    <div id="body-adminlist-item"><p>
        - <?php echo $post->title."  (".$post->date.", ".$post->author."): " ?>
        <?php echo anchor("Blog/Read/".$post->id, 'View'); ?>
        <?php echo anchor("Admin/Update/".$post->id, 'Update'); ?>
        <?php 
        if ($_SESSION["rank"] == 0 || $_SESSION["username"] == $post->author)
        {
            echo anchor("Admin/DeletePost/".$post->id, 'Delete', array('onclick' => "return confirm('Are you sure want to delete this post?')")); 
        }
        ?>
    
    </p></div>
    <?php endforeach; ?>
</div>