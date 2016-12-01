<?php

/**
 * @file
 * Displays a search result summary.
 */
?>
<div class="results-summary">
  <?php if ($total == 0): ?>
  <?php print t('There are zero results.'); ?>
  <?php else: ?>
  <?php print t('Showing results @start to @end of @total total.', array(
    '@start' => $start,
    '@end' => $end,
    '@total' => $total,
  )); ?>
  <?php endif; ?>
</div>
