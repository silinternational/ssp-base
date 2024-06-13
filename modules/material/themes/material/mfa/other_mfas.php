<?php

$otherOptions = $this->data['otherOptions'];
if (count($otherOptions) > 0) {
?>
<div layout-children="column" child-spacing="center">
    <!-- used type=button to avoid form submission on click since this is just used to display the ul -->
    <button id="others" type="button" class="mdl-button mdl-js-button">
        <span class="mdl-typography--caption">
            <?= $this->t('{material:mfa:use_others}') ?>
        </span>
    </button>
    <ul class="mdl-menu mdl-js-menu mdl-menu--top-left" data-mdl-for="others">
        <?php
        foreach ($otherOptions as $option) {
        ?>
        <li class="mdl-menu__item" onclick="location.href = '<?= $option['callback'] ?>'">
            <span class="mdl-list__item-primary-content">
                <img
                  class="mdl-list__item-icon"
                  src="<?= $option['image'] ?>"
                  alt="<?= $this->t('{material:mfa:' . $option['type'] . '_icon}') ?>"
                >
                <?= $this->t('{material:mfa:use_' . $option['label'] . '}') ?>
            </span>
        </li>
        <?php
        }
        ?>
    </ul>
</div>
<?php
}
?>
