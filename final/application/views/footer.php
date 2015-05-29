    </div> <!-- class="body" -->

    
    <hr />
    <div id="footer">
        <h6 id="footer-copyright">(c) 2015 - Christian Webber - All Rights Reserved</h6>
        <h6 id="footer-quickstuffs">
    <?php
        if (isset($_SESSION))
        {
            if (isset($_SESSION["username"]))
            {
                if ($_SESSION["rank"] < 2)
                {
                    echo anchor('Admin/ListPosts', 'Administrate Posts');
                }

                echo "  |  ";
    
                if ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 0)
                {
                    echo anchor('Admin/AdminUsers', 'Administrate Users');
                }

                echo "  |  ";
    
                if ($_SESSION["rank"] == 0)
                {
                    echo anchor('Admin/AdminGames', 'Administrate Games');
                }
            }
        }
    ?>
        </h6>
    </div>
</body>

</html>