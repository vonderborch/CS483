<div id="body-read-all">
    <div id="body-read-postdiv">
        <h1 id="body-read-title">
            <?php echo $post->title; ?>
        </h1>
        <h2 id="body-read-author">Author: 
            <?php echo safe_mailto($author->email, $post->author); ?>
        </h2>

        <div id="body-read-contents"><p>
            <?php echo $post->content; ?>
        </p></div>
    
        <hr />

        <div id="body-read-postbottom"><p>
            <?php echo $post->date; ?>
    
            <?php
            if (isset($_SESSION["username"]) && (($_SESSION["rank"] == 1 && $_SESSION["username"] == $post->author) || ($_SESSION["rank"] < 2)))
            {
                echo "  |  ";
                echo anchor('Blog/Update/'.$post->id, 'Update');
                echo "  |  ";
                echo anchor('Blog/DeletePost/'.$post->id, 'Delete', array('onclick' => "return confirm('Are you sure want to delete this post?')"));
            }
            ?>

        </p></div>

        <div id="body-read-postvoting"><p>
            <?php
            if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4)
            {
                $hasvoted = false;
                if (isset($post->voters))
                {
                    $voters = explode('&', $post->voters);

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
                    echo anchor('Blog/VotePost/'.$post->id."/1", $post->upvotes);
                }
                else
                {
                    echo $post->upvotes;
                }
                echo "  |  ";
                echo "Downvotes: ";
                if ($hasvoted == false)
                {
                    echo anchor('Blog/VotePost/'.$post->id."/-1", $post->downvotes);
                }
                else
                {
                    echo $post->downvotes;
                }
            }
            else {
                echo $post->upvotes;
                echo "  |  ";
                echo $post->downvotes;
            }
            ?>

        </p></div>
    </div>


    <hr />
    <div id="body-read-commentdiv">
        <h3 id="body-read-commentTitle">Comments</h3> <?php 
            if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4)
            {
                echo anchor('blog/CreateComment/'.$post->id, 'New Comment')."</br>";
            } ?>
        <h4>Number of Comments: <?php echo $commentcount; ?></h4>
        <?php foreach($comments as $comment) : ?>
            <div class="body-read-comment">
                <h4> Author: <?php echo $comment->author; ?>, <?php echo $comment->date; ?> </h4>
                <div class="body-read-commentvoting"><h5>
                    <?php
                    if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4)
                    {
                        $hasvoted = false;
                        if (isset($comment->voters))
                        {
                            $voters = explode('&', $comment->voters);

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
                            echo anchor('Blog/VoteComment/'.$post->id."/".$comment->id."/1", $comment->upvotes);
                        }
                        else
                        {
                            echo $comment->upvotes;
                        }
                        echo "  |  ";
                        echo "Downvotes: ";
                        if ($hasvoted == false)
                        {
                            echo anchor('Blog/VoteComment/'.$post->id."/".$comment->id."/-1", $comment->downvotes);
                        }
                        else
                        {
                            echo $comment->downvotes;
                        }
                    }
                    else {
                        echo $comment->upvotes;
                        echo "  |  ";
                        echo $comment->downvotes;
                    }
                    ?>
                </h5></div>

                <p><?php echo $comment->contents; ?></p>

                <h5><?php 
                    
                        if (isset($_SESSION["username"]) && ($_SESSION["username"] == $post->author || $_SESSION["username"] == $comment->author))
                        {
                            echo anchor('blog/DeleteComment/'.$post->id.'/'.$comment->id, 'Delete Comment', array('onclick' => "return confirm('Are you sure want to delete this comment?')"));
                        } ?>
                </h5>
            </div>
    
        <?php endforeach; ?>
    </div>

</div>