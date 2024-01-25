<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * QueryInvoiceRequest Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class QueryInvoiceRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'QueryInvoiceRequest';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'app_id' => 'string',
        'invoice_id' => 'string',
        'order_id' => 'string',
        'status' => 'string',
        'page_no' => 'int',
        'page_size' => 'int',
        'is_asc' => 'bool',
        'start' => 'string',
        'end' => 'string',
        'asc' => 'bool',
        'empty' => 'bool'];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'app_id' => null,
        'invoice_id' => null,
        'order_id' => null,
        'status' => null,
        'page_no' => 'int32',
        'page_size' => 'int32',
        'is_asc' => null,
        'start' => null,
        'end' => null,
        'asc' => null,
        'empty' => null];

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
        'app_id' => 'appId',
        'invoice_id' => 'invoiceId',
        'order_id' => 'orderId',
        'status' => 'status',
        'page_no' => 'pageNo',
        'page_size' => 'pageSize',
        'is_asc' => 'isAsc',
        'start' => 'start',
        'end' => 'end',
        'asc' => 'asc',
        'empty' => 'empty'];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'app_id' => 'setAppId',
        'invoice_id' => 'setInvoiceId',
        'order_id' => 'setOrderId',
        'status' => 'setStatus',
        'page_no' => 'setPageNo',
        'page_size' => 'setPageSize',
        'is_asc' => 'setIsAsc',
        'start' => 'setStart',
        'end' => 'setEnd',
        'asc' => 'setAsc',
        'empty' => 'setEmpty'];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'app_id' => 'getIAppId',
        'invoice_id' => 'getInvoiceId',
        'order_id' => 'getOrderId',
        'status' => 'getStatus',
        'page_no' => 'getPageNo',
        'page_size' => 'getPageSize',
        'is_asc' => 'getIsAsc',
        'start' => 'getStart',
        'end' => 'getEnd',
        'asc' => 'getAsc',
        'empty' => 'getEmpty'];

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
        $this->container['app_id'] = $data['app_id'] ?? null;
        $this->container['invoice_id'] = $data['invoice_id'] ?? null;
        $this->container['order_id'] = $data['order_id'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['page_no'] = $data['page_no'] ?? null;
        $this->container['page_size'] = $data['page_size'] ?? null;
        $this->container['is_asc'] = $data['is_asc'] ?? null;
        $this->container['start'] = $data['start'] ?? null;
        $this->container['end'] = $data['end'] ?? null;
        $this->container['asc'] = $data['asc'] ?? null;
        $this->container['empty'] = $data['empty'] ?? null;
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
     * Gets app_id
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->container['app_id'];
    }

    /**
     * Sets app_id
     *
     * @param string $app_id app_id
     *
     * @return $this
     */
    public function setAppId(string $app_id): QueryInvoiceRequest
    {
        $this->container['app_id'] = $app_id;

        return $this;
    }

    /**
     * Gets invoice_id
     *
     * @return string
     */
    public function getInvoiceId()
    {
        return $this->container['invoice_id'];
    }

    /**
     * Sets invoice_id
     *
     * @param string $invoice_id invoice_id
     *
     * @return $this
     */
    public function setInvoiceId(string $invoice_id): QueryInvoiceRequest
    {
        $this->container['invoice_id'] = $invoice_id;

        return $this;
    }

    /**
     * Gets order_id
     *
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->container['order_id'];
    }

    /**
     * Sets order_id
     *
     * @param string $order_id order_id
     *
     * @return $this
     */
    public function setOrderId(string $order_id): QueryInvoiceRequest
    {
        $this->container['order_id'] = $order_id;

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
    public function setStatus(string $status): QueryInvoiceRequest
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets page_no
     *
     * @return int
     */
    public function getPageNo()
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
    public function setPageNo(int $page_no): QueryInvoiceRequest
    {
        $this->container['page_no'] = $page_no;

        return $this;
    }

    /**
     * Gets page_size
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->container['page_size'];
    }

    /**
     * Sets page_size
     *
     * @param int $page_size page_size
     *
     * @return $this
     */
    public function setPageSize(int $page_size): QueryInvoiceRequest
    {
        $this->container['page_size'] = $page_size;

        return $this;
    }

    /**
     * Gets is_asc
     *
     * @return bool
     */
    public function getIsAsc()
    {
        return $this->container['is_asc'];
    }

    /**
     * Sets is_asc
     *
     * @param bool $is_asc is_asc
     *
     * @return $this
     */
    public function setIsAsc(bool $is_asc): QueryInvoiceRequest
    {
        $this->container['is_asc'] = $is_asc;

        return $this;
    }

    /**
     * Gets start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->container['start'];
    }

    /**
     * Sets start
     *
     * @param string $start start
     *
     * @return $this
     */
    public function setStart(string $start): QueryInvoiceRequest
    {
        $this->container['start'] = $start;

        return $this;
    }

    /**
     * Gets end
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->container['end'];
    }

    /**
     * Sets end
     *
     * @param string $end end
     *
     * @return $this
     */
    public function setEnd(string $end): QueryInvoiceRequest
    {
        $this->container['end'] = $end;

        return $this;
    }

    /**
     * Gets asc
     *
     * @return bool
     */
    public function getAsc()
    {
        return $this->container['asc'];
    }

    /**
     * Sets asc
     *
     * @param bool $asc asc
     *
     * @return $this
     */
    public function setAsc(bool $asc): QueryInvoiceRequest
    {
        $this->container['asc'] = $asc;

        return $this;
    }

    /**
     * Gets empty
     *
     * @return bool
     */
    public function getEmpty()
    {
        return $this->container['empty'];
    }

    /**
     * Sets empty
     *
     * @param bool $empty empty
     *
     * @return $this
     */
    public function setEmpty(bool $empty): QueryInvoiceRequest
    {
        $this->container['empty'] = $empty;

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
