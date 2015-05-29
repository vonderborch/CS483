<div id="body-upsert-body">
    <?php echo form_open($action) ;?>

        <p>	
            <label for="title">Title</label><br />
            <textarea id="title" name="title"
                ><?php echo $title; ?></textarea>	
        </p>

        <p>
            <label for="content">Content</label><br />
            <textarea id="content" name="content"
                ><?php echo $content; ?></textarea>	
        </p>

        <p>
            <input type="submit" />
        </p>
    
    </form>
</div>