<?php
/**
 * @file
 * cwrc_search.features.fe_block_settings.inc
 */

/**
 * Implements hook_default_fe_block_settings().
 */
function cwrc_search_default_fe_block_settings() {
  $export = array();

  $export['version'] = '2.0';

  $export['islandora_saved_searches-save_search'] = array(
    'cache' => -1,
    'css_class' => '',
    'custom' => 0,
    'delta' => 'save_search',
    'i18n_block_language' => array(),
    'i18n_mode' => 0,
    'module' => 'islandora_saved_searches',
    'node_types' => array(),
    'pages' => '',
    'roles' => array(),
    'themes' => array(
      'de_theme' => array(
        'region' => 'sidebar_second',
        'status' => 1,
        'theme' => 'de_theme',
        'weight' => -35,
      ),
    ),
    'title' => 'Save this search',
    'visibility' => 0,
  );

  $export['islandora_solr-cwrc_basic_facets'] = array(
    'cache' => -1,
    'css_class' => '',
    'custom' => 0,
    'delta' => 'cwrc_basic_facets',
    'i18n_block_language' => array(),
    'i18n_mode' => 1,
    'module' => 'islandora_solr',
    'node_types' => array(),
    'pages' => '',
    'roles' => array(),
    'themes' => array(
      'de_theme' => array(
        'region' => 'sidebar_first',
        'status' => 1,
        'theme' => 'de_theme',
        'weight' => -40,
      ),
    ),
    'title' => 'Filters',
    'visibility' => 0,
  );

  $export['islandora_solr-cwrc_current_query'] = array(
    'cache' => -1,
    'css_class' => '',
    'custom' => 0,
    'delta' => 'cwrc_current_query',
    'i18n_block_language' => array(),
    'i18n_mode' => 0,
    'module' => 'islandora_solr',
    'node_types' => array(),
    'pages' => '',
    'roles' => array(),
    'themes' => array(
      'de_theme' => array(
        'region' => 'toolbar',
        'status' => 1,
        'theme' => 'de_theme',
        'weight' => -42,
      ),
    ),
    'title' => '<none>',
    'visibility' => 0,
  );

  $export['islandora_solr-cwrc_display_switch'] = array(
    'cache' => -1,
    'css_class' => '',
    'custom' => 0,
    'delta' => 'cwrc_display_switch',
    'i18n_block_language' => array(),
    'i18n_mode' => 0,
    'module' => 'islandora_solr',
    'node_types' => array(),
    'pages' => '',
    'roles' => array(),
    'themes' => array(
      'de_theme' => array(
        'region' => 'user_menu',
        'status' => 1,
        'theme' => 'de_theme',
        'weight' => -35,
      ),
    ),
    'title' => '<none>',
    'visibility' => 0,
  );

  $export['islandora_solr-display_switch'] = array(
    'cache' => -1,
    'css_class' => '',
    'custom' => 0,
    'delta' => 'display_switch',
    'i18n_block_language' => array(),
    'i18n_mode' => 0,
    'module' => 'islandora_solr',
    'node_types' => array(),
    'pages' => '',
    'roles' => array(),
    'themes' => array(
      'de_theme' => array(
        'region' => '',
        'status' => 0,
        'theme' => 'de_theme',
        'weight' => -5,
      ),
    ),
    'title' => '',
    'visibility' => 0,
  );

  $export['islandora_solr-sort'] = array(
    'cache' => -1,
    'css_class' => '',
    'custom' => 0,
    'delta' => 'sort',
    'i18n_block_language' => array(),
    'i18n_mode' => 0,
    'module' => 'islandora_solr',
    'node_types' => array(),
    'pages' => '',
    'roles' => array(),
    'themes' => array(
      'de_theme' => array(
        'region' => '',
        'status' => 0,
        'theme' => 'de_theme',
        'weight' => -40,
      ),
    ),
    'title' => '',
    'visibility' => 0,
  );

  return $export;
}
