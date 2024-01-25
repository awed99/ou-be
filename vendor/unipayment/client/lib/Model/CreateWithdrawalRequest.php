<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * CreateWithdrawalRequest Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class CreateWithdrawalRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'CreateWithdrawalRequest';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'network' => 'string',
        'address' => 'string',
        'asset_type' => 'string',
        'amount' => 'double',
        'dest_tag' => 'string',
        'notify_url' => 'string',
        'note' => 'string',
        'auto_confirm' => 'bool',
        'include_fee' => 'bool'
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'network' => null,
        'address' => null,
        'asset_type' => null,
        'amount' => 'double',
        'dest_tag' => null,
        'notify_url' => null,
        'note' => null,
        'auto_confirm' => null,
        'include_fee' => null
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
        'network' => 'network',
        'address' => 'address',
        'asset_type' => 'asset_type',
        'amount' => 'amount',
        'dest_tag' => 'dest_tag',
        'notify_url' => 'notify_url',
        'note' => 'note',
        'auto_confirm' => 'auto_confirm',
        'include_fee' => 'include_fee'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'network' => 'setNetwork',
        'address' => 'setAddress',
        'asset_type' => 'setAssetType',
        'amount' => 'setAmount',
        'dest_tag' => 'setDestTag',
        'notify_url' => 'setNotifyUrl',
        'note' => 'setNote',
        'auto_confirm' => 'setAutoConfirm',
        'include_fee' => 'setIncludeFee'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'network' => 'getNetwork',
        'address' => 'getAddress',
        'asset_type' => 'getAssetType',
        'amount' => 'getAmount',
        'dest_tag' => 'getDestTag',
        'notify_url' => 'getNotifyUrl',
        'note' => 'getNote',
        'auto_confirm' => 'getAutoConfirm',
        'include_fee' => 'getIncludeFee'
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
        $this->container['network'] = isset($data['network']) ? $data['network'] : null;
        $this->container['address'] = isset($data['address']) ? $data['address'] : null;
        $this->container['asset_type'] = isset($data['asset_type']) ? $data['asset_type'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['dest_tag'] = isset($data['dest_tag']) ? $data['dest_tag'] : null;
        $this->container['notify_url'] = isset($data['notify_url']) ? $data['notify_url'] : null;
        $this->container['note'] = isset($data['note']) ? $data['note'] : null;
        $this->container['auto_confirm'] = isset($data['auto_confirm']) ? $data['auto_confirm'] : null;
        $this->container['include_fee'] = isset($data['include_fee']) ? $data['include_fee'] : null;
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
     * Gets network
     *
     * @return string
     */
    public function getNetwork()
    {
        return $this->container['network'];
    }

    /**
     * Sets network
     *
     * @param string $network network
     *
     * @return $this
     */
    public function setNetwork($network)
    {
        $this->container['network'] = $network;

        return $this;
    }

    /**
     * Gets address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->container['address'];
    }

    /**
     * Sets address
     *
     * @param string $address address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->container['address'] = $address;

        return $this;
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
     * Gets amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param double $amount amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets dest_tag
     *
     * @return string
     */
    public function getDestTag()
    {
        return $this->container['dest_tag'];
    }

    /**
     * Sets dest_tag
     *
     * @param string $dest_tag dest_tag
     *
     * @return $this
     */
    public function setDestTag($dest_tag)
    {
        $this->container['dest_tag'] = $dest_tag;

        return $this;
    }

    /**
     * Gets notify_url
     *
     * @return string
     */
    public function getNotifyUrl()
    {
        return $this->container['notify_url'];
    }

    /**
     * Sets notify_url
     *
     * @param string $notify_url notify_url
     *
     * @return $this
     */
    public function setNotifyUrl($notify_url)
    {
        $this->container['notify_url'] = $notify_url;

        return $this;
    }

    /**
     * Gets note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->container['note'];
    }

    /**
     * Sets note
     *
     * @param string $note note
     *
     * @return $this
     */
    public function setNote($note)
    {
        $this->container['note'] = $note;

        return $this;
    }

    /**
     * Gets auto_confirm
     *
     * @return bool
     */
    public function getAutoConfirm()
    {
        return $this->container['auto_confirm'];
    }

    /**
     * Sets auto_confirm
     *
     * @param bool $auto_confirm auto_confirm
     *
     * @return $this
     */
    public function setAutoConfirm($auto_confirm)
    {
        $this->container['auto_confirm'] = $auto_confirm;

        return $this;
    }

    /**
     * Gets include_fee
     *
     * @return bool
     */
    public function getIncludeFee()
    {
        return $this->container['include_fee'];
    }

    /**
     * Sets include_fee
     *
     * @param bool $include_fee include_fee
     *
     * @return $this
     */
    public function setIncludeFee($include_fee)
    {
        $this->container['include_fee'] = $include_fee;

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
