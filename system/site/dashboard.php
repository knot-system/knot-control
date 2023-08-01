<?php

if( ! $core ) exit;

head_html();

snippet('header');

?>
<main class="error-404">

	<h1>Dashboard</h1>

	<pre>Me: <?= $core->user->get('me') ?></pre>


</main>
<?php

snippet('footer');

foot_html();
