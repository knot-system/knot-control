<?php

if( ! $core ) exit;

head_html();

snippet('header');

?>
<main class="error-404">

	<h1>Dashboard</h1>

<pre style="font-size: 0.8em;">
Me: <?= $core->user->get('me') ?>

Module: <?= var_dump($core->modules->get()) ?>
</pre>


</main>
<?php

snippet('footer');

foot_html();
