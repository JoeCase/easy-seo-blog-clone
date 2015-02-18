<?php 
	require_once 'actions/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Easy SEO Blog - message</title>

    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/main.css" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/post.css" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/layouts.css" rel="stylesheet">    

</head>
<body style="padding: 50px 25px;">
	<div>
		<?php echo $_REQUEST['msg']; ?>
	</div>
</body>
</html>