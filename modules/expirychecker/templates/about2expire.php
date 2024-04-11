<?php
/* @var $this \SimpleSAML\XHTML\Template */

$this->data['header'] = sprintf(
    'Your password will expire in %s %s',
    $this->data['daysLeft'],
    $this->data['dayOrDays']
);
$this->data['autofocus'] = 'yesbutton';

$this->includeAtTemplateBase('includes/header.php');

$dateString = msgfmt_format_message(
    $this->getLanguage(),
    '{0,date,long}',
    [$this->data['expiresAtTimestamp']]
);

?>
<p>
  The password for your <?= htmlentities($this->data['accountName']); ?>
  account will expire on <b><?= htmlentities($dateString); ?></b>.
</p>
<p>
  Would you like to update your password now?
</p>

<form action="<?= htmlentities($this->data['formTarget']); ?>">
  
    <?php foreach ($this->data['formData'] as $name => $value): ?>
        <input type="hidden"
               name="<?= htmlentities($name); ?>"
               value="<?= htmlentities($value); ?>" />
    <?php endforeach; ?>
    
    <button type="submit" id="yesbutton" name="changepwd"
            style="padding: 4px 8px;">
        Yes, update password
    </button>
    
    <button type="submit" id="nobutton" name="continue"
            style="padding: 4px 8px;">
        No, continue where I was going
    </button>
</form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
