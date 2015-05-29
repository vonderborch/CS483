<!doctype html>
<html>
<head>
    <title>Sakila Movie Database</title>
    <link rel="stylesheet" type="text/css" href="<?php print mvc_build_style_url('site.css'); ?>" />    
    <script type="text/javascript" src="<?php print mvc_build_script_url('jquery-1.9.0.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php print mvc_build_script_url('jquery-ui-1.10.0.custom.min.js'); ?>"></script>
    
</head>
<body>
<h1><?php print $mvc_header; ?></h1>
<?php print $mvc_body; ?>

</body>
</html>