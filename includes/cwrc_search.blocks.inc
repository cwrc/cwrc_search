<?php

/**
 * @file
 * Islandora solr query blocks.
 */

/**
 * Display switch block that shows tabs for alternate displays.
 *
 * Show display switching tabs in a block, mimics behaviour of default display
 * switcher, but implements a "blacklist" to hide undesireable displays from the
 * list like "XQeury" and "CWRC Reports". Also hides itself when using a
 * black-listed display.
 */
function cwrc_search_solr_display_tabs() {
  global $_islandora_solr_queryclass;
  $build = array();

  // Check if the page is dealing with search results.
  if (islandora_solr_results_page($_islandora_solr_queryclass)) {

    // Blacklist displays from appearing in tabs, allow other modules to alter.
    $blacklist = array('xquery', 'cwrc_report');
    drupal_alter('cwrc_search_display_blacklist', $blacklist);

    // Get primary displays and display settings.
    $profiles = module_invoke_all('islandora_solr_primary_display');
    $settings = variable_get('islandora_solr_primary_display_table', array());

    // No display settings means nothing to do here.
    if (!empty($settings)) {
      // Merge displays and settings into an array that makes sense.
      foreach ($profiles as $key => $profile) {
        // Is this display enabled and not blacklisted?
        if (!isset($settings['enabled'][$key])
          || $settings['enabled'][$key] === 0
          || in_array($key, $blacklist)) {

          unset($profiles[$key]);

          // Add the weight information.
        }
        elseif (isset($settings['weight'][$key])) {
          $profiles[$key]['weight'] = $settings['weight'][$key];
        }
        else {
          $profiles[$key]['weight'] = 0;
        }
      }

      // No profiles means no need for anything else.
      if (count($profiles) == 0) {
        return $build;
      }

      // Sort by weight.
      uasort($profiles, function ($a, $b) {
        return $a['weight'] > $b['weight'];
      });

      // Get query parameters.
      $parameters = drupal_get_query_parameters();
      $display = (isset($parameters['display']) ? $parameters['display'] : $settings['default']);

      // Do not present tab navigation if this display is not in the tabs.
      if (!isset($profiles[$display])) {
        return $build;
      }

      // Build render array list items.
      $build['display_tabs'] = array(
        '#theme' => 'item_list',
        '#prefix' => '<div id="tabs" class="clearfix">',
        '#suffix' => '</div>',
        '#items' => array(),
        '#type' => 'ul',
        '#attributes' => array('class' => array('tabs', 'primary')),
      );

      // List items.
      $query = $parameters;
      foreach ($profiles as $key => $profile) {
        $query['display'] = $key;
        if ($display == $key) {
          $attributes = array(
            'class' => array('active'),
          );
        }
        else {
          $attributes = array();
        }
        $options = array(
          'query' => $query,
        );
        $url = url(current_path(), $options);
        // TODO: Only string literals should be passed to t() where possible.
        $build['display_tabs']['#items'][] = "<a href=$url" . drupal_attributes($attributes) . ">" . t($profile['name']) . "</a>";
      }
    }
  }

  // Return rendered markup.
  return render($build);
}
