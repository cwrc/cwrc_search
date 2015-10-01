<?php

/**
 * Implementation of hook_preprocess_cwrc_search_results_wrapper().
 *
 * Adds theme hook suggestions based on the display for the search wrapper
 * template.
 */
function cwrc_search_preprocess_cwrc_search_results_wrapper(&$variables) {
  $settings = variable_get('islandora_solr_primary_display_table', array());
  $display = (isset($_GET['display']) ? $_GET['display'] : $settings['default']);
  $variables['theme_hook_suggestions'][] = $variables['theme_hook_original'] . '__' . $display;
}
