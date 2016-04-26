<?php
/**
 * @file
 * cwrc_search.quicktabs.inc
 */

/**
 * Implements hook_quicktabs_default_quicktabs().
 */
function cwrc_search_quicktabs_default_quicktabs() {
  $export = array();

  $quicktabs = new stdClass();
  $quicktabs->disabled = FALSE; /* Edit this to true to make a default quicktabs disabled initially */
  $quicktabs->api_version = 1;
  $quicktabs->machine_name = 'cwrc_advanced_search';
  $quicktabs->ajax = 0;
  $quicktabs->hide_empty_tabs = FALSE;
  $quicktabs->default_tab = 0;
  $quicktabs->title = 'CWRC Advanced Search';
  $quicktabs->tabs = array(
    0 => array(
      'bid' => 'islandora_solr_delta_advanced',
      'hide_title' => 1,
      'title' => 'Advanced search',
      'weight' => '-100',
      'type' => 'block',
    ),
  );
  $quicktabs->renderer = 'quicktabs';
  $quicktabs->style = 'nostyle';
  $quicktabs->options = array();

  // Translatables
  // Included for use with string extractors like potx.
  t('Advanced search');
  t('CWRC Advanced Search');

  $export['cwrc_advanced_search'] = $quicktabs;

  return $export;
}
