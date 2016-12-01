<?php

/**
 * @file
 * Contains \CwrcSolrResultsAll.
 */

module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrResults');

/**
 * All CWRC Solr results.
 *
 * TODO: Best practices say that the class name should be prefixed with the
 * project name, i.e.: "CwrcSearch", instead of just "Cwrc".
 */
class CwrcSolrResultsAll extends CwrcSolrResults {

  /**
   * {@inheritdoc}
   */
  public function getLayouts() {
    $layout = isset($_GET['layout']) ? $_GET['layout'] : 'list';
    $parameters = drupal_get_query_parameters();
    return theme('cwrc_search_layouts_select', array(
      'layouts' => array(
        'list' => array(
          'label' => t('List'),
          'active' => ($layout == 'list'),
          'url' => url(current_path(), array('query' => array('layout' => 'list') + $parameters)),
          'key' => 'list',
        ),
        'grid' => array(
          'label' => t('Grid'),
          'active' => ($layout == 'grid'),
          'url' => url(current_path(), array('query' => array('layout' => 'grid') + $parameters)),
          'key' => 'grid',
        ),
      ),
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function printResults($solr_results) {
    // Check for grid/list parameter.
    $layout = isset($_GET['layout']) ? $_GET['layout'] : 'list';
    $build = array(
      '#theme' => 'item_list',
      '#type' => 'ul',
      '#items' => array(),
      '#attributes' => array(),
    );

    // Use default grid layout.
    if ($layout == 'grid') {
      $build['#attributes']['class'][] = 'grid-layout';
      foreach ($solr_results['response']['objects'] as $stub) {
        $content = array(
          '#theme' => 'cwrc_search_teaser_grid',
          '#object' => $stub['PID'],
        );
        $build['#items'][] = render($content);
      }

      // Use default list layout.
    }
    else {
      $build['#attributes']['class'][] = 'list-layout';
      foreach ($solr_results['response']['objects'] as $stub) {
        $content = array(
          '#theme' => 'cwrc_search_teaser_list',
          '#object' => $stub['PID'],
        );
        $build['#items'][] = render($content);
      }
    }

    return render($build);
  }

}
