<?php

/**
 * @file
 * Displays a search results page.
 */
?>
<div class="cwrc-search-wrapper">
  <?php if (isset($secondary)) { ?>
    <!--<div class="secondary-displays">
      <?php print $secondary; ?>
    </div>-->
  <?php } ?>

  <?php if (isset($layouts)) { ?>
    <div class="layouts-wrapper">
      <?php print $layouts; ?>
    </div>
  <?php } ?>

  <?php if (isset($sort_options)) { ?>
    <div class="sorts-wrapper">
      <?php print $sort_options; ?>
    </div>
  <?php } ?>

  <div class="summary">
    <?php print $summary; ?>
  </div>
  <div class="results">
    <?php if (is_array($results) && count($results) == 0) { ?>
      <span class="no-results"><?php print t('Sorry, but your search returned no results.'); ?></span>

    <?php } elseif (is_array($results)) { ?>
      <ul class="results">
        <?php foreach ($results as $result) { ?>
          <li class="result">
            <?php print $result; ?>
          </li>
        <?php } ?>
      </ul>

    <?php } else { ?>
      <?php print $results; ?>
    <?php } ?>
  </div>
  <div class="pager">
    <?php print $pager; ?>
  </div>
</div>
