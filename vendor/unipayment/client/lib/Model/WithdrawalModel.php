<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * WithdrawalModel Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class WithdrawalModel implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'WithdrawalModel';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'id' => 'string',
        'network' => 'string',
        'asset_type' => 'string',
        'amount' => 'double',
        'address' => 'string',
        'fee' => 'double',
        'status' => 'string',
        'txn_hash' => 'string',
        'create_time' => '\DateTime'
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'id' => null,
        'network' => null,
        'asset_type' => null,
        'amount' => 'double',
        'address' => null,
        'fee' => 'double',
        'status' => null,
        'txn_hash' => null,
        'create_time' => 'date-time'
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
        'id' => 'id',
        'network' => 'network',
        'asset_type' => 'asset_type',
        'amount' => 'amount',
        'address' => 'address',
        'fee' => 'fee',
        'status' => 'status',
        'txn_hash' => 'txn_hash',
        'create_time' => 'create_time'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'id' => 'setId',
        'network' => 'setNetwork',
        'asset_type' => 'setAssetType',
        'amount' => 'setAmount',
        'address' => 'setAddress',
        'fee' => 'setFee',
        'status' => 'setStatus',
        'txn_hash' => 'setTxnHash',
        'create_time' => 'setCreateTime'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'id' => 'getId',
        'network' => 'getNetwork',
        'asset_type' => 'getAssetType',
        'amount' => 'getAmount',
        'address' => 'getAddress',
        'fee' => 'getFee',
        'status' => 'getStatus',
        'txn_hash' => 'getTxnHash',
        'create_time' => 'getCreateTime'
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

    const STATUS_PENDING = 'Pending';
    const STATUS_CANCEL = 'Cancel';
    const STATUS_CONFIRM = 'Confirm';
    const STATUS_REJECT = 'Reject';
    const STATUS_APPROVE = 'Approve';
    const STATUS_SUCCESS = 'Success';
    const STATUS_FAIL = 'Fail';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CANCEL,
            self::STATUS_CONFIRM,
            self::STATUS_REJECT,
            self::STATUS_APPROVE,
            self::STATUS_SUCCESS,
            self::STATUS_FAIL,
        ];
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
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['network'] = isset($data['network']) ? $data['network'] : null;
        $this->container['asset_type'] = isset($data['asset_type']) ? $data['asset_type'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['address'] = isset($data['address']) ? $data['address'] : null;
        $this->container['fee'] = isset($data['fee']) ? $data['fee'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['txn_hash'] = isset($data['txn_hash']) ? $data['txn_hash'] : null;
        $this->container['create_time'] = isset($data['create_time']) ? $data['create_time'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'status', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

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
     * Gets id
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param string $id id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
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
     * Gets fee
     *
     * @return double
     */
    public function getFee()
    {
        return $this->container['fee'];
    }

    /**
     * Sets fee
     *
     * @param double $fee fee
     *
     * @return $this
     */
    public function setFee($fee)
    {
        $this->container['fee'] = $fee;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($status) && !in_array($status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'status', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets txn_hash
     *
     * @return string
     */
    public function getTxnHash()
    {
        return $this->container['txn_hash'];
    }

    /**
     * Sets txn_hash
     *
     * @param string $txn_hash txn_hash
     *
     * @return $this
     */
    public function setTxnHash($txn_hash)
    {
        $this->container['txn_hash'] = $txn_hash;

        return $this;
    }

    /**
     * Gets create_time
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->container['create_time'];
    }

    /**
     * Sets create_time
     *
     * @param \DateTime $create_time create_time
     *
     * @return $this
     */
    public function setCreateTime($create_time)
    {
        $this->container['create_time'] = $create_time;

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
