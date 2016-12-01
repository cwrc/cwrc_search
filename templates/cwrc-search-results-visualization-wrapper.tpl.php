<?php

/**
 * @file
 * Displays a wrapper for a search results visualization.
 */
?>
<div class="cwrc-search-wrapper">
  <div class="iframe-wrapper">
    <iframe class="<?php print $classes ?>" src="<?php print $embed_url; ?>"></iframe>
  </div>
  <div class="fullscreen-link-wrapper">
    <a href="<?php print $embed_url; ?>" target="_blank"><?php print t('Open fullscreen'); ?></a>
  </div>
</div>
