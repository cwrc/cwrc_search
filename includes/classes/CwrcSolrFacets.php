<?php

/**
 * @file
 * Contains \CwrcSolrFacets.
 */

/**
 * CWRC Solr facets.
 *
 * TODO: Best practices say that the class name should be prefixed with the
 * project name, i.e.: "CwrcSearch", instead of just "Cwrc".
 */
class CwrcSolrFacets extends IslandoraSolrFacets {

  /**
   * Prepare and render facet.
   *
   * Method called after a facet object is created. This will prepare the
   * results based on the type and user settings for this facet. It also does a
   * call to render the prepared data. This method also returns the rendered
   * endresult.
   *
   * @return string
   *   Returns the title and rendered facet.
   */
  public function getFacet() {
    $this->findFacetType();
    $this->getFacetResults();
    if (empty($this->results)) {
      return '';
    }
    $this->processFacets();
    if (empty($this->content)) {
      return '';
    }

    // Only show workflow date current when using cwrc_report display.
    if ($this->facet_field == 'workflow_date_current_dt'
      && (!isset($_GET['display'])
      || $_GET['display'] != 'cwrc_report')) {
      return '';
    }

    // Only show decade facet when century facet is active.
    if ($this->facet_field == 'cwrc_facet_date_10_ms') {
      $show = FALSE;
      if (isset($_GET['f'])) {
        foreach ($_GET['f'] as $fq) {
          $pieces = explode(':', $fq);
          if ($pieces[0] == 'cwrc_facet_date_100_ms') {
            $show = TRUE;
          }
        }
      }

      if ($show === FALSE) {
        return '';
      }
    }

    $elements = array(
      'title' => $this->title,
      'content' => $this->content,
      'pid' => $this->facet_field,
    );
    return theme('islandora_solr_facet_wrapper', $elements);
  }

  /**
   * Prepare facet fields for text rendering.
   */
  public function prepareFacetFields() {
    // For the ancestors_ms field we remove non-project/research space PIDs.
    if ($this->facet_field == 'ancestors_ms') {
      foreach ($this->results as $pid => $count) {
        if (!_cwrc_projects_is_project($pid)
          && !_cwrc_projects_is_research_space($pid)) {
          unset($this->results[$pid]);
        }
      }
    }

    return parent::prepareFacetFields();
  }

  /**
   * Render text facets.
   *
   * Based on a prepared array of results, this method will process and render
   * a facet as normal text. It includes bucket value, count, include link and
   * exclude link. If configured it also adds a 'read more' link to expose more
   * results.
   *
   * @param array $results
   *   An array with the prepared facet results.
   */
  public function renderText($results) {
    // For the ancestors_ms field we sort into projects and research spaces.
    if ($this->facet_field == 'ancestors_ms') {
      $facet_field = $this->facet_field;
      $islandora_solr_query = self::$islandoraSolrQuery;
      $buckets = array();
      $replace_bucket = (isset($this->settings['solr_field_settings']['pid_object_label']) && $this->settings['solr_field_settings']['pid_object_label'] ? TRUE : FALSE);

      $projects = array();
      $research_spaces = array();
      foreach ($results as $value) {
        if (_cwrc_projects_is_project($value['bucket'])) {
          $projects[] = $value;
        }
        else {
          $research_spaces[] = $value;
        }
      }
      $soft_limit = count($projects);

      foreach (array_merge($projects, $research_spaces) as $values) {
        $bucket = $values['bucket'];
        $filter = $values['filter'];
        $count = $values['count'];

        // Replace link bucket with object label based on facet field settings.
        if ($replace_bucket) {
          $query_processor = new IslandoraSolrQueryProcessor();
          $label = NULL;
          $pid = str_replace('info:fedora/', '', $bucket);
          if (islandora_is_valid_pid($pid)) {
            $query_processor->buildQuery("PID:\"$pid\"");
            $query_processor->solrParams['fl'] = 'PID, ' . variable_get('islandora_solr_object_label_field', 'fgs_label_s');
            $query_processor->executeQuery();
            if (!empty($query_processor->islandoraSolrResult) && !empty($query_processor->islandoraSolrResult['response']['objects'])) {
              $label = (!empty($query_processor->islandoraSolrResult['response']['objects'][0]['object_label']) ?
              $query_processor->islandoraSolrResult['response']['objects'][0]['object_label'] : NULL);
            }
            // Fall back to islandora object if PID is not in solr.
            // eg: content models.
            else {
              if ($object = islandora_object_load($pid)) {
                $label = $object->label;
              }
            }
          }
          $bucket = ($label ? $label : $bucket);
        }

        // Current URL query.
        $fq = isset($islandora_solr_query->solrParams['fq']) ? $islandora_solr_query->solrParams['fq'] : array();
        // 1: Check minimum count.
        // 2: Check if the filter isn't active.
        if ($count < self::$minimum_count || array_search($filter, $fq) !== FALSE) {
          continue;
        }
        // Current path including query, for example islandora/solr/query.
        // $_GET['q'] didn't seem to work here.
        $path = current_path();
        // Parameters set in URL.
        $params = $islandora_solr_query->internalSolrParams;
        // Set filter key if there are no filters included.
        if (!isset($params['f'])) {
          $params['f'] = array();
        }
        // Merge recursively to add new filter parameter.
        $query_plus = array_merge_recursive($params, array('f' => array($filter)));
        $query_minus = array_merge_recursive($params, array('f' => array('-' . $filter)));

        // Set basic attributes.
        $attributes = array(
          'link' => array(
            'path' => $path,
          ),
          'plus' => array(
            'path' => $path,
          ),
          'minus' => array(
            'path' => $path,
          ),
        );
        $attributes['link']['attr'] = $attributes['minus']['attr'] = $attributes['plus']['attr'] = array('rel' => 'nofollow');

        $attributes['link']['attr']['href'] = $attributes['plus']['attr']['href'] = url($path, array('query' => $query_plus));
        $attributes['link']['query'] = $attributes['plus']['query'] = $query_plus;
        $attributes['minus']['attr']['href'] = url($path, array('query' => $query_minus));
        $attributes['minus']['query'] = $query_minus;

        $attributes['plus']['attr']['class'] = array('plus');
        $attributes['minus']['attr']['class'] = array('minus');

        module_load_include('inc', 'islandora', 'includes/utilities');

        $hooks = islandora_build_hook_list(ISLANDORA_SOLR_FACET_BUCKET_CLASSES_HOOK_BASE);
        drupal_alter($hooks, $attributes, $islandora_solr_query);

        // XXX: We are not using l() because of active classes:
        // @see http://drupal.org/node/41595
        // Create link.
        $link['link'] = '<a' . drupal_attributes($attributes['link']['attr']) . '>' . $bucket . '</a>';
        $link['count'] = $count;
        $link['link_plus'] = '<a' . drupal_attributes($attributes['plus']['attr']) . '>+</a>';
        $link['link_minus'] = '<a' . drupal_attributes($attributes['minus']['attr']) . '>-</a>';
        $buckets[] = $link;
      }

      // Show more link.
      if (count($buckets) > $soft_limit) {
        $buckets_visible = array_slice($buckets, 0, $soft_limit);
        $buckets_hidden = array_slice($buckets, $soft_limit);
        $this->content .= theme('islandora_solr_facet', array(
          'buckets' => $buckets_visible,
          'hidden' => FALSE,
          'pid' => $facet_field,
        ));
        $this->content .= theme('islandora_solr_facet', array(
          'buckets' => $buckets_hidden,
          'hidden' => TRUE,
          'pid' => $facet_field,
        ));
        $this->content .= $this->showMore();
      }
      elseif (!empty($buckets)) {
        $this->content .= theme('islandora_solr_facet', array(
          'buckets' => $buckets,
          'hidden' => FALSE,
          'pid' => $facet_field,
        ));
      }
    }
    else {
      parent::renderText($results);
    }
  }

}
