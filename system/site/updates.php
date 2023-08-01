<?php

if( ! $core ) exit;

head_html();

snippet('header');

?>
<main class="updates">

	<h1>Updates</h1>

	<?php snippet('updates-content') ?>

</main>
<?php

snippet('footer');

foot_html();
