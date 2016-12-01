<?php

/**
 * @file
 * Contains \CwrcSolrResults.
 */

/**
 * CWRC Solr Results.
 *
 * TODO: Best practices say that the class name should be prefixed with the
 * project name, i.e.: "CwrcSearch", instead of just "Cwrc".
 */
class CwrcSolrResults extends IslandoraSolrResults {

  /**
   * Output the main body of the search results.
   *
   * Differences from default IslandoraSolrResults::displayResults()
   *
   * - Does NOT set breadcrumbs.
   * - Uses 'cwrc_solr_results_wrapper' which has more elements than default
   * wrapper and supports theme hook suggestions by display.
   * - Supports display-based "layouts" so displays can render lists of
   * supported layouts without customization.
   *
   * @param IslandoraSolrQueryProcessor $islandora_solr_query
   *   The IslandoraSolrQueryProcessor object which includes the current query
   *   settings and the raw Solr results.
   *
   * @return string
   *   Returns themed Solr results page, including wrapper and rendered search
   *   results.
   *
   * @see islandora_solr()
   * @see IslandoraSolrResults::displayResults()
   */
  public function displayResults($islandora_solr_query) {
    $this->islandoraSolrQueryProcessor = $islandora_solr_query;

    // Raw solr results.
    $islandora_solr_result = $this->islandoraSolrQueryProcessor->islandoraSolrResult;

    // Summary.
    $total = (int) $islandora_solr_result['response']['numFound'];
    $start = $islandora_solr_query->solrStart;
    $end = min(($islandora_solr_query->solrLimit + $start), $total);
    $summary = theme('cwrc_search_results_summary', array(
      'total' => $total,
      'start' => ($start + 1),
      'end' => $end,
    ));

    // Pager.
    islandora_solr_pager_init($total, $islandora_solr_query->solrLimit);
    $pager = theme('pager', array(
      'tags' => NULL,
      'element' => 0,
      'parameters' => NULL,
      'quantity' => 5,
    ));

    // Rendered results.
    $results = $this->printResults($islandora_solr_result);

    // Secondary displays (Islandora).
    $secondary = $this->addSecondaries($islandora_solr_query);

    // Layouts (CWRC).
    $layouts = $this->getLayouts();

    // Sort options (CWRC).
    $sort_form = $this->getSortForm();
    $sort_options = render($sort_form);

    return theme('cwrc_search_results_wrapper', array(
      'results' => $results,
      'pager' => $pager,
      'summary' => $summary,
      'layouts' => $layouts,
      'secondary' => $secondary,
      'sort_options' => $sort_options,
    ));
  }

  /**
   * Return a rendered list of links for available outputs.
   *
   * Should return a rendered list of links for available layouts, allows things
   * like "Grid" and "List" layouts to be display specific, as some displays
   * shouldn't support these (ie "list of names").
   *
   * Sub-classes must override this to enable layouts.
   */
  public function getLayouts() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function addSecondaries($islandora_solr_query) {
    // Overrides the default addSecondaries function to remove them, as they are
    // not useful for our purposes.
    return NULL;
  }

  /**
   * Function that returns the sort options form.
   *
   * Modified version of islandora_solr_sort() that is easier to modify on a
   * display-by-display basis.
   *
   * @see islandora_solr_sort()
   */
  public function getSortForm() {
    return drupal_get_form('cwrc_search_sort_form');
  }

  /**
   * Formats the passed in filter into a human readable form.
   *
   * Essentially a clone of IslandoraSolrResults::formatFilter(), but with
   * support for formatting object PIDs as labels, rather than just the raw
   * PIDs, which was ugly.
   *
   * @param string $filter
   *   The passed in filter.
   * @param object $islandora_solr_query
   *   The current Solr Query.
   *
   * @return string
   *   The formatted filter string for breadcrumbs and active query.
   */
  public function formatFilter($filter, $islandora_solr_query) {
    // @todo See how this interacts with multiple date filters.
    // Check if there are operators in the filter.
    $fq_split = preg_split('/ (OR|AND) /', $filter);
    if (count($fq_split) > 1) {
      $operator_split = preg_split(ISLANDORA_SOLR_QUERY_SPLIT_REGEX, $filter);
      $operator_split = array_diff($operator_split, $fq_split);
      $out_array = array();
      foreach ($fq_split as $fil) {
        $fil_split = preg_split(ISLANDORA_SOLR_QUERY_FIELD_VALUE_SPLIT_REGEX, $fil, 2);
        $field_name = $fil_split[0];
        $out_str = str_replace(array('"', 'info:fedora/'), '', $fil_split[1]);
        $out_array[] = $out_str;
      }
      $filter_string = '';
      foreach ($out_array as $out) {
        $filter_string .= $out;
        if (count($operator_split)) {
          $filter_string .= ' ' . array_shift($operator_split) . ' ';
        }
      }
      $filter_string = trim($filter_string);
    }
    else {
      // Split the filter into field and value.
      $filter_split = preg_split(ISLANDORA_SOLR_QUERY_FIELD_VALUE_SPLIT_REGEX, $filter, 2);
      $field_name = ltrim($filter_split[0], "-");
      // Trim brackets.
      $filter_split[1] = trim($filter_split[1], "\"");
      // If value is date.
      if (isset($islandora_solr_query->solrParams['facet.date']) && in_array(ltrim($filter_split[0], '-'), $islandora_solr_query->solrParams['facet.date'])) {
        // Check date format setting.
        foreach ($this->rangeFacets as $value) {
          if ($value['solr_field'] == $filter_split[0] && isset($value['solr_field_settings']['date_facet_format']) && !empty($value['solr_field_settings']['date_facet_format'])) {
            $format = $value['solr_field_settings']['date_facet_format'];
          }
        }
        // Split range filter string to return formatted date values.
        $filter_str = $filter_split[1];
        $filter_str = trim($filter_str, '[');
        $filter_str = trim($filter_str, ']');
        $filter_array = explode(' TO ', $filter_str);
        $filter_split[1] = format_date(strtotime(trim($filter_array[0])) + (60 * 60 * 24), 'custom', $format) . ' - ' . format_date(strtotime(trim($filter_array[1])) + (60 * 60 * 24), 'custom', $format);
      }
      $filter_string = $filter_split[1];
    }

    $filter_string = stripslashes($filter_string);

    // Determine whether or not to replace with PID.
    foreach (islandora_solr_get_fields('facet_fields', FALSE, FALSE) as $field) {
      if ($field['solr_field'] == $field_name
        && $field['solr_field_settings']['pid_object_label']) {
        try {
          $object = islandora_object_load(str_replace('info:fedora/', '', $filter_string));
          $filter_string = $object->label;
        }
        catch (Exception $e) {
        }
      }
    }
    return $filter_string;
  }

  /**
   * Displays facets based on a query response.
   *
   * Includes links to include or exclude a facet field in a search.
   *
   * @param IslandoraSolrQueryProcessor $islandora_solr_query
   *   The IslandoraSolrQueryProcessor object which includes the current query
   *   settings and the raw Solr results.
   *
   * @return string
   *   Rendered lists of facets including links to include or exclude a facet
   *   field.
   *
   * @see islandora_solr_islandora_solr_query_blocks()
   * @see islandora_solr_block_view()
   */
  public function displayFacets($islandora_solr_query) {
    // Determine what display we are using and get settings for that display.
    if (isset($_GET['display'])) {
      $active_display = $_GET['display'];
      $search_displays = cwrc_search_islandora_solr_primary_display();
      $display = isset($search_displays[$active_display]) ? $search_displays[$active_display] : NULL;
      if (isset($display['hide_facets']) && $display['hide_facets'] === TRUE) {
        return NULL;
      }
    }

    module_load_include('php', 'cwrc_search', 'includes/classes/CwrcSolrFacets');
    CwrcSolrFacets::init($islandora_solr_query);
    $output = '';
    $facet_order = $this->facetFieldArray;
    foreach ($facet_order as $facet_key => $facet_label) {
      $facet_obj = new CwrcSolrFacets($facet_key);
      $output .= $facet_obj->getFacet();
    }

    // As we add additional facets, we're repeatedly URL-encoding old facet
    // strings. when we double-encode quotation marks they're incomprehensible
    // to Solr.
    $output = str_replace('%2B', '%252B', $output);
    return $output;
  }

}
