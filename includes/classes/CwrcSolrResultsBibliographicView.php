<?php

/**
 * @file
 * Contains \CwrcSolrResultsBibliographicView.
 */

module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrResults');
module_load_include('inc', 'csl', 'includes/csl');

/**
 * Bibliographic results view of CWRC Solr results.
 *
 * TODO: Best practices say that the class name should be prefixed with the
 * project name, i.e.: "CwrcSearch", instead of just "Cwrc".
 */
class CwrcSolrResultsBibliographicView extends CwrcSolrResults {

  /**
   * {@inheritdoc}
   */
  public function getLayouts() {
    $names = $this->getCslLayouts();
    $layout = (isset($_GET['layout']) && isset($names[$_GET['layout']])) ? $_GET['layout'] : drupal_html_class(CSL::GetDefaultName());
    $parameters = drupal_get_query_parameters();

    $layouts = array();
    foreach ($names as $key => $name) {
      $layouts[$name] = array(
        'label' => $name,
        'active' => ($layout == $key),
        'url' => url(current_path(), array('query' => array('layout' => $key) + $parameters)),
        'key' => $key,
      );
    }

    return theme('cwrc_search_layouts_select', array('layouts' => $layouts));
  }

  /**
   * {@inheritdoc}
   */
  public function printResults($solr_results) {
    // See also CwrcSolrResults::displayResults().
    $results = array();

    $layouts = $this->getCslLayouts();
    $layout = (isset($_GET['layout']) && isset($layouts[$_GET['layout']])) ? $_GET['layout'] : drupal_html_class(CSL::GetDefaultName());

    // Very simple display here, just list names with labels.
    foreach ($solr_results['response']['objects'] as $solr_result) {
      $mods = islandora_datastream_load('MODS', $solr_result['PID']);
      $rendered = citeproc_bibliography_from_mods(citeproc_style($layouts[$layout]), $mods->content);
      $results[] = l($rendered, 'islandora/object/' . $solr_result['PID'], array('html' => TRUE));
    }

    // Return themed search results.
    return $results;
  }

  /**
   * Returns a list of CSL layout names.
   *
   * @return array
   *   An array of CSL layout names.
   */
  protected function getCslLayouts() {
    $names = array();
    foreach (CSL::GetNames() as $name) {
      $encoded = drupal_html_class($name);
      $names[$encoded] = $name;
    }
    return $names;
  }

}
