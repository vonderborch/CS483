<div id="body-upsertuser-body">
    <?php echo form_open($action) ;?>

        <p>	
            <label for="username">Username: </label><br />
            <textarea id="username" name="username" 
                ><?php echo $username; ?></textarea>
        </p>

        <p>
            <label for="firstname">First Name: </label><br />
            <textarea id="firstname" name="firstname" 
                ><?php echo $firstname; ?></textarea>	
        </p>

        <p>
            <label for="lastname">Last Name: </label><br />
            <textarea id="lastname" name="lastname"
                ><?php echo $lastname; ?></textarea>	
        </p>

        <p>
            <label for="email">Email: </label><br />
            <textarea id="email" name="email"
                ><?php echo $email; ?></textarea>	
        </p>

        <p>
            <label for="password">Password: </label><br />
            <textarea id="password" name="password"
                ><?php echo $password; ?></textarea>	
        </p>

        <p>
            <label for="theme">Theme: </label><br />
            0 = default, 1 = alternate</br>
            <textarea id="theme" name="theme"
                ><?php echo $theme; ?></textarea>	
        </p>

        <p>
            <label for="rank">Rank: </label><br />
            <textarea id="rank" name="rank"
                ><?php echo $rank; ?></textarea>	
        </p>

        <p>
            <input type="submit" />
        </p>
    
    </form>
</div>