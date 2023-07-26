<?php


function head_html(){

	global $core;

	$core->theme->print_headers();

	$body_classes = array();

	$color_scheme = get_config('theme-color-scheme');
	if( $color_scheme ) $body_classes[] = 'theme-color-scheme-'.$color_scheme;

	$template = $core->route->get( 'template' );
	if( $template ) $body_classes[] = 'template-'.$template;

?><!DOCTYPE html>
<!--
  ___ ___                                 __                     .___ _________                __                .__   
 /   |   \  ____   _____   ____   _______/  |_  ____ _____     __| _/ \_   ___ \  ____   _____/  |________  ____ |  |  
/    ~    \/  _ \ /     \_/ __ \ /  ___/\   __\/ __ \\__  \   / __ |  /    \  \/ /  _ \ /    \   __\_  __ \/  _ \|  |  
\    Y    (  <_> )  Y Y  \  ___/ \___ \  |  | \  ___/ / __ \_/ /_/ |  \     \___(  <_> )   |  \  |  |  | \(  <_> )  |__
 \___|_  / \____/|__|_|  /\___  >____  > |__|  \___  >____  /\____ |   \______  /\____/|___|  /__|  |__|   \____/|____/
       \/              \/     \/     \/            \/     \/      \/          \/            \/                         
-->
<html lang="en">
<head>
<?php
	$core->theme->print_metatags( 'header' );
?>


<?php
	$core->theme->print_stylesheets();
?>

<?php
	$core->theme->print_scripts();

	?>
	
</head>
<body<?= get_class_attribute($body_classes) ?>><?php

}

function foot_html(){

	global $core;

	$core->theme->print_metatags( 'footer' );
?>

<?php
	$core->theme->print_scripts( 'footer' );

?>


</body>
</html>
<?php
}
