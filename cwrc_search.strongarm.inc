<?php
/**
 * @file
 * cwrc_search.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function cwrc_search_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'islandora_solr_primary_display';
  $strongarm->value = 'cwrc_search_all';
  $export['islandora_solr_primary_display'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'islandora_solr_primary_display_table';
  $strongarm->value = array(
    'weight' => array(
      'cwrc_search_all' => '-10',
      'cwrc_search_bibliographic_view' => '-9',
      'cwrc_search_documents' => '-8',
      'cwrc_search_list_names' => '-7',
      'cwrc_search_plotit' => '-6',
      'bookmark' => '-5',
      'default' => '-4',
      'grid' => '-3',
      'table' => '-2',
      'xquery' => '-1',
      'cwrc_report' => '0',
    ),
    'default' => 'cwrc_search_all',
    'enabled' => array(
      'cwrc_search_bibliographic_view' => 'cwrc_search_bibliographic_view',
      'cwrc_search_documents' => 'cwrc_search_documents',
      'cwrc_search_list_names' => 'cwrc_search_list_names',
      'cwrc_search_plotit' => 'cwrc_search_plotit',
      'xquery' => 'xquery',
      'cwrc_report' => 'cwrc_report',
      'cwrc_search_all' => 'cwrc_search_all',
      'bookmark' => 0,
      'default' => 0,
      'grid' => 0,
      'table' => 0,
    ),
  );
  $export['islandora_solr_primary_display_table'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'islandora_solr_secondary_display';
  $strongarm->value = array(
    'plotit' => 'plotit',
    'csv' => 0,
    'plotit_json' => 0,
    'rss' => 0,
  );
  $export['islandora_solr_secondary_display'] = $strongarm;

  return $export;
}
