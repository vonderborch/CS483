<div  id="body-addcomment-body">
    <?php echo form_open($action) ;?>
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