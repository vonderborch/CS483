<div  id="body-admin-body">
    <p>
    <?php
        if ($_SESSION["rank"] < 2)
        {
            echo anchor('Admin/ListPosts', 'Administrate Posts');
        }

        echo "</br></br>";
    
        if ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 0)
        {
            echo anchor('Admin/AdminUsers', 'Administrate Users');
        }

        echo "</br></br>";
    
        if ($_SESSION["rank"] == 0)
        {
            echo anchor('Admin/AdminGames', 'Administrate Games');
        }
    ?>
    </p>
</div>