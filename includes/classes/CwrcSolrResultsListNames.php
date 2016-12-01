<?php

/**
 * @file
 * Contains \CwrcSolrResultsListNames.
 */

module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrResults');

/**
 * List of names search display class.
 *
 * TODO: Best practices say that the class name should be prefixed with the
 * project name, i.e.: "CwrcSearch", instead of just "Cwrc".
 */
class CwrcSolrResultsListNames extends CwrcSolrResults {

  /**
   * {@inheritdoc}
   */
  public function printResults($solr_results) {
    // See also CwrcSolrResults::displayResults().
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
