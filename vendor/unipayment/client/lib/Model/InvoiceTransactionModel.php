<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * InvoiceTransactionModel Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class InvoiceTransactionModel implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'InvoiceTransactionModel';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'hash' => 'string',
        'network' => 'string',
        'symbol' => 'string',
        'from' => 'string',
        'to' => 'string',
        'amount' => 'double',
        'confirmation_count' => 'int',
        'is_confirmed' => 'bool'];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'hash' => null,
        'network' => null,
        'symbol' => null,
        'from' => null,
        'to' => null,
        'amount' => 'double',
        'confirmation_count' => 'int32',
        'is_confirmed' => null];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function fieldTypes(): array
    {
        return self::$fieldTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function fieldFormats(): array
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
        'hash' => 'hash',
        'network' => 'network',
        'symbol' => 'symbol',
        'from' => 'from',
        'to' => 'to',
        'amount' => 'amount',
        'confirmation_count' => 'confirmation_count',
        'is_confirmed' => 'is_confirmed'];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'hash' => 'setHash',
        'network' => 'setNetwork',
        'symbol' => 'setSymbol',
        'from' => 'setFrom',
        'to' => 'setTo',
        'amount' => 'setAmount',
        'confirmation_count' => 'setConfirmationCount',
        'is_confirmed' => 'setIsConfirmed'];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'hash' => 'getHash',
        'network' => 'getNetwork',
        'symbol' => 'getSymbol',
        'from' => 'getFrom',
        'to' => 'getTo',
        'amount' => 'getAmount',
        'confirmation_count' => 'getConfirmationCount',
        'is_confirmed' => 'getIsConfirmed'];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap(): array
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters(): array
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters(): array
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName(): string
    {
        return self::$modelName;
    }

    /**
     * Associative array for storing property values
     *
     * @var array
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param array|null $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['hash'] = $data['hash'] ?? null;
        $this->container['network'] = $data['network'] ?? null;
        $this->container['symbol'] = $data['symbol'] ?? null;
        $this->container['from'] = $data['from'] ?? null;
        $this->container['to'] = $data['to'] ?? null;
        $this->container['amount'] = $data['amount'] ?? null;
        $this->container['confirmation_count'] = $data['confirmation_count'] ?? null;
        $this->container['is_confirmed'] = $data['is_confirmed'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties(): array
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
    public function valid(): bool
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->container['hash'];
    }

    /**
     * Sets hash
     *
     * @param string $hash hash
     *
     * @return $this
     */
    public function setHash(string $hash): InvoiceTransactionModel
    {
        $this->container['hash'] = $hash;

        return $this;
    }

    /**
     * Gets network
     *
     * @return string
     */
    public function getNetwork(): string
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
    public function setNetwork(string $network): InvoiceTransactionModel
    {
        $this->container['network'] = $network;

        return $this;
    }

    /**
     * Gets symbol
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->container['symbol'];
    }

    /**
     * Sets symbol
     *
     * @param string $symbol symbol
     *
     * @return $this
     */
    public function setSymbol(string $symbol): InvoiceTransactionModel
    {
        $this->container['symbol'] = $symbol;

        return $this;
    }

    /**
     * Gets from
     *
     * @return string
     */
    public function getFrom(): string
    {
        return $this->container['from'];
    }

    /**
     * Sets from
     *
     * @param string $from from
     *
     * @return $this
     */
    public function setFrom(string $from): InvoiceTransactionModel
    {
        $this->container['from'] = $from;

        return $this;
    }

    /**
     * Gets to
     *
     * @return string
     */
    public function getTo(): string
    {
        return $this->container['to'];
    }

    /**
     * Sets to
     *
     * @param string $to to
     *
     * @return $this
     */
    public function setTo(string $to): InvoiceTransactionModel
    {
        $this->container['to'] = $to;

        return $this;
    }

    /**
     * Gets amount
     *
     * @return double
     */
    public function getAmount(): float
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
    public function setAmount(float $amount): InvoiceTransactionModel
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets confirmation_count
     *
     * @return int
     */
    public function getConfirmationCount(): int
    {
        return $this->container['confirmation_count'];
    }

    /**
     * Sets confirmation_count
     *
     * @param int $confirmation_count confirmation_count
     *
     * @return $this
     */
    public function setConfirmationCount(int $confirmation_count): InvoiceTransactionModel
    {
        $this->container['confirmation_count'] = $confirmation_count;

        return $this;
    }

    /**
     * Gets is_confirmed
     *
     * @return bool
     */
    public function getIsConfirmed(): bool
    {
        return $this->container['is_confirmed'];
    }

    /**
     * Sets is_confirmed
     *
     * @param bool $is_confirmed is_confirmed
     *
     * @return $this
     */
    public function setIsConfirmed(bool $is_confirmed): InvoiceTransactionModel
    {
        $this->container['is_confirmed'] = $is_confirmed;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
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
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed $value Value to be set
     *
     * @return void
     */
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
