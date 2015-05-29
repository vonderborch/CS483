<div id="body-index-body">
    <h3>Welcome to my blog!</h3>

    <div id="body-index-posts">
        <?php 
        $counter = 0;
        foreach($posts as $post) : 
            if ($counter > 9) break;?>
            <div class="body-index-post">
                <h1>
                    <?php echo anchor("blog/Read/{$post->id}", $post->title); ?>
                </h1>
                <h2>
                    <?php 
                        echo $post->author;
                        echo "  |  ";
                        echo $post->date;
                        echo "  |  +";
                        echo $post->upvotes;
                        echo ", -";
                        echo $post->downvotes;
                        echo "  |  ";
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
                </h2>
    
                <p>
                    <?php echo substr($post->content, 0, 255); 
                    if (strlen($post->content) >= 255)
                    {
                        echo "...";
                    }

                    ?>
                </p>
            </div>
        <?php 
        $counter = $counter + 1;
        endforeach; ?>
    </div>
</div>