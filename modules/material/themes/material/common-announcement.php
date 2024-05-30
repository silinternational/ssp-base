<?php

use Sil\SspUtils\AnnouncementUtils;

// TODO: remove this
$announcementHtml = AnnouncementUtils::getAnnouncement();

if (! empty($announcementHtml)) {
?>
  <div class="mdl-typography--subhead mdl-typography--text-center alert margin" layout-children="column">
      <?= $announcementHtml ?>
  </div>
<?php
}
?>
