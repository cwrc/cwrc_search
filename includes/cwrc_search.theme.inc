<?php

/**
 * @file
 * Theme functions.
 */

/**
 * Implements hook_preprocess_cwrc_search_results_wrapper().
 */
function cwrc_search_preprocess_cwrc_search_results_wrapper(&$variables) {
  // Adds theme hook suggestions based on the display for the search wrapper
  // template.
  $settings = variable_get('islandora_solr_primary_display_table', array());
  $display = (isset($_GET['display']) ? $_GET['display'] : $settings['default']);
  $variables['theme_hook_suggestions'][] = $variables['theme_hook_original'] . '__' . $display;
}
