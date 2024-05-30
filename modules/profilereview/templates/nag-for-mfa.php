<?php
$this->data['header'] = 'Set up 2-Step Verification';
$this->includeAtTemplateBase('includes/header.php');

$mfaLearnMoreUrl = $this->data['mfaLearnMoreUrl'];
?>
    <p>
        Did you know you could greatly increase the security of your account by enabling 2-Step Verification?
    </p>
    <p>
        We highly encourage you to do this for your own safety.
    </p>
    <form method="post">
        <button name="update" style="padding: 4px 8px;">
            Set up 2-step verification
        </button>

        <button name="continue" style="padding: 4px 8px;">
            Remind me later
        </button>

    <?php if (! empty($mfaLearnMoreUrl)): ?>
    <p><a href="<?= htmlentities($mfaLearnMoreUrl) ?>"
          target="_blank">Learn more</a></p>
    <?php endif; ?>
    </form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
