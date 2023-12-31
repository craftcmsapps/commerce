<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\events;

use craft\commerce\models\Email;
use yii\base\Event;

/**
 * Class EmailEvent
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class EmailEvent extends Event
{
    /**
     * @var Email Email
     */
    public Email $email;

    /**
     * @var bool Whether the email is brand new.
     */
    public bool $isNew = false;
}
