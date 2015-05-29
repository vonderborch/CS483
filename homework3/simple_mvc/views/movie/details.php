<?php

//for easier conversion to string
$categories = array();
$actors = array();
foreach($view_bag->categories as $category)
{
    $categories[$category->name] = $category->name;
}
foreach($view_bag->actors as $actor)
{
    $name = sprintf('%s %s', $actor->first_name, $actor->last_name);
    $actors[$name] = $name;
}
?>
<h1><?php print $view_bag->title; ?></h1>
<p>
    <a href="<?php print mvc_build_url('Home', 'Index'); ?>">Back to movie list</a>.
</p>
<p>
    <?php print $view_bag->description; ?>
</p>
<table>
    <caption>Details</caption>
    <tr>
        <th>Year:</th>
        <td><?php print $view_bag->release_year; ?></td>
    </tr>
    <tr>
        <th>Categories:</th>
        <td><?php print implode(', ', $categories); ?></td>
    </tr>
    <tr>
        <th>Actors:</th>
        <td><?php print implode(', ', $actors); ?></td>
    </tr>
</table>