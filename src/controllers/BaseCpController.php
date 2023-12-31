<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\controllers;

use craft\commerce\web\assets\commercecp\CommerceCpAsset;
use yii\web\ForbiddenHttpException;

/**
 * Class BaseCp
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class BaseCpController extends BaseController
{
    /**
     * @inheritdoc
     * @throws ForbiddenHttpException
     */
    public function init(): void
    {
        parent::init();

        // All system setting actions require access to commerce
        $this->requirePermission('accessPlugin-commerce');

        $this->getView()->registerAssetBundle(CommerceCpAsset::class);
    }
}
