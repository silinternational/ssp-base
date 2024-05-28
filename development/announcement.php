<?php
/**
 * In order to have an announcement appear on the material theme's discovery page
 * or login page, include something like this ...
 *
 * return [
 *     'start_datetime' => "2017-12-20 01:02:03", // "Y-m-d H:i:s",
 *     'end_datetime' => "2017-12-24 01:02:03", // "Y-m-d H:i:s",
 *     'announcement_text' => '<h3>Notice:</h3><div>Christmas is almost here!</div>',
 * ];
 */

return [
    'start_datetime' => "2016-12-20 01:02:03",  // optional
    'end_datetime' => "2099-12-30 01:02:03",    // optional
    'announcement_text' => "<h4>Information</h4>
<div>This is a <em>test</em> announcement.</div>",
];
