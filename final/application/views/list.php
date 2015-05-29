<div id="body-list-body"><p>
    <h3>Posts</h3>
    <?php foreach($posts as $post) : ?>
        <?php echo anchor("blog/Read/{$post->id}", $post->title); ?> 
          :  
        <?php echo $post->date; ?> 
        , 
        <?php echo $post->author; ?> 
        , 
        <?php 
            $comment = new CommentModel();
            $ccount = $comment->GetNumberOfCommentsForPost($post->id);

            if ($ccount == 0) {
                echo "No comments";
            }
            elseif ($ccount == 1) {
                echo "1 comment";
            }
            else {
                echo $ccount." comments";
            }
        
         ?> 
        </br></br>
    
    <?php endforeach; ?>
</p></div>