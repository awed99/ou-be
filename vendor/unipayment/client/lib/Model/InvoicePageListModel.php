<?php
/**
 * InvoicePageListModel
 */

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * InvoicePageListModel Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class InvoicePageListModel implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'InvoicePageListModel';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'models' => '\UniPayment\Client\Model\InvoiceModel[]',
        'total' => 'int',
        'page_no' => 'int',
        'page_count' => 'int'];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'models' => null,
        'total' => 'int32',
        'page_no' => 'int32',
        'page_count' => 'int32'];

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
        'models' => 'models',
        'total' => 'total',
        'page_no' => 'page_no',
        'page_count' => 'page_count'];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'models' => 'setModels',
        'total' => 'setTotal',
        'page_no' => 'setPageNo',
        'page_count' => 'setPageCount'];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'models' => 'getModels',
        'total' => 'getTotal',
        'page_no' => 'getPageNo',
        'page_count' => 'getPageCount'];

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
        $this->container['models'] = $data['models'] ?? null;
        $this->container['total'] = $data['total'] ?? null;
        $this->container['page_no'] = $data['page_no'] ?? null;
        $this->container['page_count'] = $data['page_count'] ?? null;
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
     * Gets models
     *
     * @return InvoiceModel[]
     */
    public function getModels(): array
    {
        return $this->container['models'];
    }

    /**
     * Sets models
     *
     * @param InvoiceModel[] $models models
     *
     * @return $this
     */
    public function setModels(array $models): InvoicePageListModel
    {
        $this->container['models'] = $models;

        return $this;
    }

    /**
     * Gets total
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->container['total'];
    }

    /**
     * Sets total
     *
     * @param int $total total
     *
     * @return $this
     */
    public function setTotal($total): InvoicePageListModel
    {
        $this->container['total'] = $total;

        return $this;
    }

    /**
     * Gets page_no
     *
     * @return int
     */
    public function getPageNo(): int
    {
        return $this->container['page_no'];
    }

    /**
     * Sets page_no
     *
     * @param int $page_no page_no
     *
     * @return $this
     */
    public function setPageNo(int $page_no): InvoicePageListModel
    {
        $this->container['page_no'] = $page_no;

        return $this;
    }

    /**
     * Gets page_count
     *
     * @return int
     */
    public function getPageCount(): int
    {
        return $this->container['page_count'];
    }

    /**
     * Sets page_count
     *
     * @param int $page_count page_count
     *
     * @return $this
     */
    public function setPageCount(int $page_count): InvoicePageListModel
    {
        $this->container['page_count'] = $page_count;

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
