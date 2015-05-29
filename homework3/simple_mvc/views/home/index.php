<table>
<?php foreach($view_bag as $movie):?>
    <tr>
        <td><a href="<?php print mvc_build_url("movie", "details", $movie->film_id );?>">details</a></td>
        <td><?php print ucFirst(strtolower($movie->title)); ?></td>
        <td><?php print $movie->description; ?></td>
        <td><?php print $movie->rating; ?></td>
        <td><?php print $movie->release_year; ?></td>
    </tr>
<?php endforeach; mvc_build_script_url("jquery-1.9.0.min.js"); ?>
</table>