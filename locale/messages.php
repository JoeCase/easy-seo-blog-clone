<?php
	$_MESSAGES = array(
		0   => '',
		1   => 'Sucessfully inserted post',
		2   => 'Sucessfully updated post',
		100 => 'Unknown error',
		101 => 'Database connection error',  // new mysqli('...') error
		102 => 'Unable to save Database',    // ->prepare('INSERT ...') error 
		103 => 'Unable to save to Database', // ->bind error
		104 => 'Unable to save to Database'  // ->execute error
	);
?>