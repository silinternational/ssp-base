<?php

namespace SimpleSAML\Module\material;

use SimpleSAML\Configuration;
use SimpleSAML\XHTML\TemplateControllerInterface;
use Twig\Environment;

class MaterialController implements TemplateControllerInterface
{
    /**
     * Modify the twig environment after its initialization (e.g. add filters or extensions).
     *
     * @param \Twig\Environment $twig The current twig environment.
     * @return void
     */
    public function setUpTwig(Environment &$twig): void
    {
    }

    /**
     * Add, delete or modify the data passed to the template.
     * This method will be called right before displaying the template.
     *
     * @param array $data The current data used by the template.
     * @return void
     */
    public function display(array &$data): void
    {
        $globalConfig = Configuration::getInstance();
        $data['theme_color_scheme'] = $globalConfig->getOptionalString('theme.color-scheme', null);
        $data['analytics_tracking_id'] = $globalConfig->getOptionalString('analytics.trackingId', '');
    }
}
