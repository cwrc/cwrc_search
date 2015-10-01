<ul class="layouts-select">
  <?php foreach ($layouts as $key => $layout): ?>
    <li class="<?php print $key; ?><?php print ($layout['active']) ? ' active' : ''; ?>">
      <a href="<?php print $layout['url']; ?>" class="layout-option<?php print ($layout['active']) ? ' active' : ''; ?>">
        <?php print $layout['label']; ?>
      </a>
    </li>
  <?php endforeach ?>
</ul>
