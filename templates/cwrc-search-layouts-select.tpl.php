<?php

/**
 * @file
 * Displays a layout selection box.
 */
$display = (isset($_GET['display']) ? $_GET['display'] : NULL);
$label = ($display == "cwrc_search_bibliographic_view" ? t('Bibliographic Format') : t('Layout'));
?>
<form method="get">
  <div class="form-item form-type-select form-item-layout-select">
    <label for="layouts-select"><?php print $label; ?></label>
    <select class="layouts-select" name="layout">
      <?php foreach ($layouts as $key => $layout): ?>
        <option value="<?php print $layout['key']; ?>" class="<?php print $key; ?>" <?php print ($layout['active']) ? ' selected' : ''; ?>>
          <?php print $layout['label']; ?>
        </option>
      <?php endforeach ?>
    </select>
  </div>
  <input type="submit" id="edit-submit" value="Change" class="form-submit">
  <?php
  foreach ($_GET as $k => $v) {
    if ($k != 'q' && $k != 'layout' && is_string($v)) {
      print "<input type=\"hidden\" name=\"$k\" value=\"$v\">";
    }
  }
  ?>
</form>
