<?php
echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">'."\n";
echo '<html><head>'."\n";
echo '<title>404 Not Found</title>'."\n";
echo '</head><body>'."\n";
echo '<h1>Not Found</h1>'."\n";
echo '<p>The requested URL http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' was not found on this server.</p>'."\n";
echo '<hr>'."\n";
echo $_SERVER["SERVER_SIGNATURE"]."\n";
echo '</body></html>';
exit();

?>