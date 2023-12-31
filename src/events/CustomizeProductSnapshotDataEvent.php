<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\events;

use craft\commerce\elements\Product;
use yii\base\Event;

/**
 * Class CustomizeProductSnapshotDataEvent
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class CustomizeProductSnapshotDataEvent extends Event
{
    /**
     * @var Product The product
     */
    public Product $product;

    /**
     * @var array The captured data
     */
    public array $fieldData;
}
