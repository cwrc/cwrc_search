<?php
/**
 * @file
 * cwrc_search.borealis_sb_default.inc
 */

/**
 * Implements hook_default_semantic_wrapper().
 */
function cwrc_search_default_semantic_wrapper() {
  $export = array();

  $semantic_wrapper = new stdClass();
  $semantic_wrapper->disabled = FALSE; /* Edit this to true to make a default semantic_wrapper disabled initially */
  $semantic_wrapper->api_version = 1;
  $semantic_wrapper->uid = 'islandora_solr_current_query';
  $semantic_wrapper->module = 'islandora_solr';
  $semantic_wrapper->delta = 'current_query';
  $semantic_wrapper->semantics = 'div';
  $export['islandora_solr_current_query'] = $semantic_wrapper;

  return $export;
}
