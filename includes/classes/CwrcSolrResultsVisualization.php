<?php

module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrResults');

class CwrcSolrResultsVisualization extends CwrcSolrResults {
  /**
   * @see CwrcSolrResults::displayResults()
   */
  public function displayResults($islandora_solr_query) {
    // Determine what display we are using and get settings for that display.
    $active_display = $_GET['display'];
    $search_displays = cwrc_search_islandora_solr_primary_display();
    $display = $search_displays[$active_display];

    // Pass current query into iframe for visualization tool.
    $query = drupal_get_query_parameters();
    $query['solr_profile'] = $display['original_display'];
    return theme('cwrc_search_results_visualization_wrapper', array(
      'embed_url' => url(current_path(), array('query' => $query, 'absolute' => true)),
    ));
  }
}
