<?php

module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrResults');

/**
 * "List of names" search display class.
 */
class CwrcSolrResultsListNames extends CwrcSolrResults {

  /**
   * @see CwrcSolrResults::displayResults()
   */
  public function printResults($solr_results) {

    $results = array();

    // Very simple display here, just list names with labels.
    foreach ($solr_results['response']['objects'] as $solr_result) {
      $results[] = theme('cwrc_search_list_name', array(
        'name' => $solr_result['object_label'],
        'url' => url($solr_result['object_url']),
      ));
    }

    // Return themed search results.
    return $results;
  }
}
