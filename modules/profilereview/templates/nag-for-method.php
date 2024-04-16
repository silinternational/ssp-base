<?php
$this->data['header'] = 'Set up Recovery Methods';
$this->includeAtTemplateBase('includes/header.php');
?>
    <p>
        Did you know you can provide alternate email addresses for password recovery?
    </p>
    <p>
        We highly encourage you to do this to ensure continuous access and improved security.
    </p>
    <form method="post">
        <button name="update" style="padding: 4px 8px;">
            Set up Recovery Methods
        </button>

        <button name="continue" style="padding: 4px 8px;">
            Remind me later
        </button>
    </form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
