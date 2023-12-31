<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\controllers;

/**
 * Class BaseStoreSettingsController
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class BaseStoreSettingsController extends BaseCpController
{
    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        // All system setting actions require access to commerce
        $this->requirePermission('commerce-manageStoreSettings');
    }
}
