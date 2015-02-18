<?php
/*
	1. Bude nutné napsat vlastní vytažení options z databáze pomocí nějakého url parametru.
	2. Vytažení všech postů se musí zkopírovat.
	
	url format easyblog.teameasyapps.com/feed/USER_RSS_ID
*/

?>
<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0">
	<channel>
		<title><?php echo $user['rss_title']; ?></title>
		<link>generated</link>
		<description><?php echo $user['rss_description']; ?></description>
		<language><?php echo $user['rss_lang']; ?></language>
		<copyright><?php echo $user['rss_copyright']; ?></copyright>
		<pubDate>generated from last post</pubDate>
		<?php //for(){ ?>
		<item>
			<title>database</title>
			<link>database</link>
			<description>database</description>
			<author>optional</author>
			<?php //for(){ ?>
				<category domain="link">tag name</category>
			<?php //} ?>
			<pubDate>database</pubDate>	
		</item>
		<?php //} ?>
	</channel>
</rss>