<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\records;

use craft\commerce\db\Table;
use craft\db\ActiveRecord;
use craft\records\Element;
use yii\db\ActiveQueryInterface;

/**
 * Payment source record.
 *
 * @property string $description
 * @property Gateway $gateway
 * @property int $gatewayId
 * @property int $id
 * @property string $response
 * @property string $token
 * @property-read ActiveQueryInterface $user
 * @property int $customerId
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class PaymentSource extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return Table::PAYMENTSOURCES;
    }

    /**
     * Return the payment source's gateway
     *
     * @return ActiveQueryInterface The relational query object.
     */
    public function getGateway(): ActiveQueryInterface
    {
        return $this->hasOne(Gateway::class, ['id' => 'gatewayId']);
    }


    /**
     * Return the payment source's owner customer/user.
     *
     * @return ActiveQueryInterface The relational query object.
     */
    public function getUser(): ActiveQueryInterface
    {
        return $this->hasOne(Element::class, ['id' => 'customerId']);
    }
}
