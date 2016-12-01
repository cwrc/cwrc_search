<?php

/**
 * @file
 * Displays search results as a list of teasers.
 */
?>
<div class="<?php print $classes ?>">

  <div class="top-wrapper">
    <div class="left-wrapper <?php print $cmodel_class; ?>">
      <a href="<?php print $object_url; ?>" title="<?php print $cmodel_label ?>" class="<?php print (isset($thumbnail)) ? "has-image" : "no-image" ?>">
        <?php if (isset($thumbnail)) { ?>
          <?php print $thumbnail; ?>
        <?php } else { ?>
          <div class="no-image"><div class="no-image-icon"></div></div>
        <?php } ?>
      </a>
    </div>

    <div class="right-wrapper">

    <div class="label">
      <a href="<?php print $object_url; ?>"><?php print $dc_title; ?></a>
    </div>

    <?php if (isset($project_logo)) { ?>
      <div class="project-info">
        <a href="<?php print $project_url; ?>" title="<?php print $project_name; ?>">
          <?php print $project_logo; ?>
        </a>
      </div>
    <?php } ?>

    <?php if (isset($dc_creator) || isset($dc_date)) { ?>
      <div class="creation-info">
        <div class="creator">
          <?php if (isset($dc_creator)) { ?>
            <?php print $dc_creator . (isset($dc_date) ? ', ' : ''); ?>
          <?php } ?>
        </div>
        <div class="date">
          <?php if (isset($dc_date)) { ?>
            <?php print $dc_date; ?>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
    </div>
  </div>

  <?php if (isset($dc_description)) { ?>
    <div class="summary">
      <?php print $dc_description; ?>
    </div>
  <?php } ?>

</div>
