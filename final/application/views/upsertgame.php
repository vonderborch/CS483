<div id="body-upsertgame-body">
    <?php echo form_open($action) ;?>

        <p>	
            <label for="name">Name</label><br />
            <textarea id="name" name="name"
                ><?php echo $name; ?></textarea>	
        </p>

        <p>
            <label for="description">Description</label><br />
            <textarea id="description" name="description"
                ><?php echo $description; ?></textarea>	
        </p>

        <p>
            <label for="engine">HTML Engine: </label><br />
            <textarea id="engine" name="engine"
                ><?php echo $engine; ?></textarea>	
        </p>

        <p>
            <input type="submit" />
        </p>
    
    </form>
</div>