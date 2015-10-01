<?php

module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrResults');

class CwrcSolrResultsAll extends CwrcSolrResults {

  public function getLayouts() {
    $layout = isset($_GET['layout']) ? $_GET['layout'] : 'list';
    $parameters = drupal_get_query_parameters();
    return theme('cwrc_search_layouts_select', array(
      'layouts' => array(
        'list' => array(
          'label' => t('List'),
          'active' => ($layout == 'list'),
          'url' => url(current_path(), array('query' => array('layout' => 'list') + $parameters)),
        ),
        'grid' => array(
          'label' => t('Grid'),
          'active' => ($layout == 'grid'),
          'url' => url(current_path(), array('query' => array('layout' => 'grid') + $parameters)),
        ),
      ),
    ));
  }

  public function printResults($solr_results) {
    // Check for grid/list parameter.
    $layout = isset($_GET['layout']) ? $_GET['layout'] : 'list';

    // Use default grid layout.
    if ($layout == 'grid') {
      module_load_include('inc', 'islandora_solr_config', 'includes/grid_results');
      $grid = new IslandoraSolrResultsGrid();
      return $grid->printResults($solr_results);

    // Use default list layout.
    } else {
      module_load_include('inc', 'islandora_solr_search', 'includes/results');
      $list = new IslandoraSolrResults();
      return $list->printResults($solr_results);
    }
  }
}
