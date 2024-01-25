<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * BalanceModel Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class BalanceModel implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'BalanceModel';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'asset_type' => 'string',
        'balance' => 'double',
        'frozen_balance' => 'double',
        'available' => 'double'
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'asset_type' => null,
        'balance' => 'double',
        'frozen_balance' => 'double',
        'available' => 'double'
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function fieldTypes()
    {
        return self::$fieldTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function fieldFormats()
    {
        return self::$fieldFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'asset_type' => 'asset_type',
        'balance' => 'balance',
        'frozen_balance' => 'frozen_balance',
        'available' => 'available'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'asset_type' => 'setAssetType',
        'balance' => 'setBalance',
        'frozen_balance' => 'setFrozenBalance',
        'available' => 'setAvailable'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'asset_type' => 'getAssetType',
        'balance' => 'getBalance',
        'frozen_balance' => 'getFrozenBalance',
        'available' => 'getAvailable'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$modelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['asset_type'] = isset($data['asset_type']) ? $data['asset_type'] : null;
        $this->container['balance'] = isset($data['balance']) ? $data['balance'] : null;
        $this->container['frozen_balance'] = isset($data['frozen_balance']) ? $data['frozen_balance'] : null;
        $this->container['available'] = isset($data['available']) ? $data['available'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets asset_type
     *
     * @return string
     */
    public function getAssetType()
    {
        return $this->container['asset_type'];
    }

    /**
     * Sets asset_type
     *
     * @param string $asset_type asset_type
     *
     * @return $this
     */
    public function setAssetType($asset_type)
    {
        $this->container['asset_type'] = $asset_type;

        return $this;
    }

    /**
     * Gets balance
     *
     * @return double
     */
    public function getBalance()
    {
        return $this->container['balance'];
    }

    /**
     * Sets balance
     *
     * @param double $balance balance
     *
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->container['balance'] = $balance;

        return $this;
    }

    /**
     * Gets frozen_balance
     *
     * @return double
     */
    public function getFrozenBalance()
    {
        return $this->container['frozen_balance'];
    }

    /**
     * Sets frozen_balance
     *
     * @param double $frozen_balance frozen_balance
     *
     * @return $this
     */
    public function setFrozenBalance($frozen_balance)
    {
        $this->container['frozen_balance'] = $frozen_balance;

        return $this;
    }

    /**
     * Gets available
     *
     * @return double
     */
    public function getAvailable()
    {
        return $this->container['available'];
    }

    /**
     * Sets available
     *
     * @param double $available available
     *
     * @return $this
     */
    public function setAvailable($available)
    {
        $this->container['available'] = $available;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed $value Value to be set
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
