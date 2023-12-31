<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\models;

use craft\commerce\base\Model;

/**
 * Currency Model
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class Currency extends Model
{
    /**
     * @var string Alphabetic code
     */
    public string $alphabeticCode;

    /**
     * @var string Currency
     */
    public string $currency;

    /**
     * @var string Entity
     */
    public string $entity;

    /**
     * @var int Number of minor unites
     */
    public int $minorUnit;

    /**
     * @var int Numeric code
     */
    public int $numericCode;

    public function __toString(): string
    {
        return $this->alphabeticCode;
    }
}
