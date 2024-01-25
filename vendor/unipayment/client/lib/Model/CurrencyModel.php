<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * CurrencyModel Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class CurrencyModel implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'CurrencyModel';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'code' => 'string',
        'name' => 'string',
        'symbol' => 'string',
        'is_fiat' => 'bool'];


    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'code' => null,
        'name' => null,
        'symbol' => null,
        'is_fiat' => null];

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
        'code' => 'code',
        'name' => 'name',
        'symbol' => 'symbol',
        'is_fiat' => 'is_fiat'];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'code' => 'setCode',
        'name' => 'setName',
        'symbol' => 'setSymbol',
        'is_fiat' => 'setIsFiat'];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'code' => 'getCode',
        'name' => 'getName',
        'symbol' => 'getSymbol',
        'is_fiat' => 'getIsFiat'];

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
        $this->container['code'] = $data['code'] ?? null;
        $this->container['name'] = $data['name'] ?? null;
        $this->container['symbol'] = $data['symbol'] ?? null;
        $this->container['is_fiat'] = $data['is_fiat'] ?? null;
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
     * Gets code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->container['code'];
    }

    /**
     * Sets from
     *
     * @param string $code from
     *
     * @return $this
     */
    public function setCode(string $code): CurrencyModel
    {
        $this->container['code'] = $code;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     *
     * @param string $name name
     *
     * @return $this
     */
    public function setName(string $name): CurrencyModel
    {
        $this->container['name'] = $name;

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
    public function setSymbol(string $symbol): CurrencyModel
    {
        $this->container['symbol'] = $symbol;

        return $this;
    }

    /**
     * Gets is_fiat
     *
     * @return bool
     */
    public function getIsFiat(): bool
    {
        return $this->container['is_fiat'];
    }

    /**
     * Sets is_fiat
     *
     * @param bool $is_fiat is_fiat
     *
     * @return $this
     */
    public function setIsFiat(bool $is_fiat): CurrencyModel
    {
        $this->container['is_fiat'] = $is_fiat;

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
     * Return Container Map
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
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