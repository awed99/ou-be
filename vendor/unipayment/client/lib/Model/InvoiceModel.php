<?php

namespace UniPayment\Client\Model;

use ArrayAccess;
use UniPayment\Client\ObjectSerializer;

/**
 * InvoiceModel Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class InvoiceModel implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'InvoiceModel';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'network' => 'string',
        'address' => 'string',
        'app_id' => 'string',
        'invoice_id' => 'string',
        'order_id' => 'string',
        'price_amount' => 'double',
        'price_currency' => 'string',
        'pay_amount' => 'double',
        'pay_currency' => 'string',
        'exchange_rate' => 'double',
        'paid_amount' => 'double',
        'create_time' => '\DateTime',
        'expiration_time' => '\DateTime',
        'confirm_speed' => 'string',
        'status' => 'string',
        'error_status' => 'string',
        'invoice_url' => 'string'];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'network' => null,
        'address' => null,
        'app_id' => null,
        'invoice_id' => null,
        'order_id' => null,
        'price_amount' => 'double',
        'price_currency' => null,
        'pay_amount' => 'double',
        'pay_currency' => null,
        'exchange_rate' => 'double',
        'paid_amount' => 'double',
        'create_time' => 'date-time',
        'expiration_time' => 'date-time',
        'confirm_speed' => null,
        'status' => null,
        'error_status' => null,
        'invoice_url' => null];

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
        'network' => 'network',
        'address' => 'address',
        'app_id' => 'app_id',
        'invoice_id' => 'invoice_id',
        'order_id' => 'order_id',
        'price_amount' => 'price_amount',
        'price_currency' => 'price_currency',
        'pay_amount' => 'pay_amount',
        'pay_currency' => 'pay_currency',
        'exchange_rate' => 'exchange_rate',
        'paid_amount' => 'paid_amount',
        'create_time' => 'create_time',
        'expiration_time' => 'expiration_time',
        'confirm_speed' => 'confirm_speed',
        'status' => 'status',
        'error_status' => 'error_status',
        'invoice_url' => 'invoice_url'];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'network' => 'setNetwork',
        'address' => 'setAddress',
        'app_id' => 'setAppId',
        'invoice_id' => 'setInvoiceId',
        'order_id' => 'setOrderId',
        'price_amount' => 'setPriceAmount',
        'price_currency' => 'setPriceCurrency',
        'pay_amount' => 'setPayAmount',
        'pay_currency' => 'setPayCurrency',
        'exchange_rate' => 'setExchangeRate',
        'paid_amount' => 'setPaidAmount',
        'create_time' => 'setCreateTime',
        'expiration_time' => 'setExpirationTime',
        'confirm_speed' => 'setConfirmSpeed',
        'status' => 'setStatus',
        'error_status' => 'setErrorStatus',
        'invoice_url' => 'setInvoiceUrl'];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'network' => 'getNetwork',
        'address' => 'getAddress',
        'app_id' => 'getAppId',
        'invoice_id' => 'getInvoiceId',
        'order_id' => 'getOrderId',
        'price_amount' => 'getPriceAmount',
        'price_currency' => 'getPriceCurrency',
        'pay_amount' => 'getPayAmount',
        'pay_currency' => 'getPayCurrency',
        'exchange_rate' => 'getExchangeRate',
        'paid_amount' => 'getPaidAmount',
        'create_time' => 'getCreateTime',
        'expiration_time' => 'getExpirationTime',
        'confirm_speed' => 'getConfirmSpeed',
        'status' => 'getStatus',
        'error_status' => 'getErrorStatus',
        'invoice_url' => 'getInvoiceUrl'];

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
        $this->container['network'] = $data['network'] ?? null;
        $this->container['address'] = $data['address'] ?? null;
        $this->container['app_id'] = $data['app_id'] ?? null;
        $this->container['invoice_id'] = $data['invoice_id'] ?? null;
        $this->container['order_id'] = $data['order_id'] ?? null;
        $this->container['price_amount'] = $data['price_amount'] ?? null;
        $this->container['price_currency'] = $data['price_currency'] ?? null;
        $this->container['pay_amount'] = $data['pay_amount'] ?? null;
        $this->container['pay_currency'] = $data['pay_currency'] ?? null;
        $this->container['exchange_rate'] = $data['exchange_rate'] ?? null;
        $this->container['paid_amount'] = $data['paid_amount'] ?? null;
        $this->container['create_time'] = $data['create_time'] ?? null;
        $this->container['expiration_time'] = $data['expiration_time'] ?? null;
        $this->container['confirm_speed'] = $data['confirm_speed'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['error_status'] = $data['error_status'] ?? null;
        $this->container['invoice_url'] = $data['invoice_url'] ?? null;
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
     * Gets network
     *
     * @return string
     */
    public function getNetwork(): ?string
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
    public function setNetwork(string $network): InvoiceModel
    {
        $this->container['network'] = $network;

        return $this;
    }

    /**
     * Gets address
     *
     * @return string
     */
    public function getAddress(): ?string
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
    public function setAddress(string $address): InvoiceModel
    {
        $this->container['address'] = $address;

        return $this;
    }

    /**
     * Gets app_id
     *
     * @return string
     */
    public function getAppId(): string
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
    public function setAppId(string $app_id): InvoiceModel
    {
        $this->container['app_id'] = $app_id;

        return $this;
    }

    /**
     * Gets invoice_id
     *
     * @return string
     */
    public function getInvoiceId(): string
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
    public function setInvoiceId(string $invoice_id): InvoiceModel
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
    public function setOrderId(string $order_id): InvoiceModel
    {
        $this->container['order_id'] = $order_id;

        return $this;
    }

    /**
     * Gets price_amount
     *
     * @return double
     */
    public function getPriceAmount(): float
    {
        return $this->container['price_amount'];
    }

    /**
     * Sets price_amount
     *
     * @param double $price_amount price_amount
     *
     * @return $this
     */
    public function setPriceAmount(float $price_amount): InvoiceModel
    {
        $this->container['price_amount'] = $price_amount;

        return $this;
    }

    /**
     * Gets price_currency
     *
     * @return string
     */
    public function getPriceCurrency(): string
    {
        return $this->container['price_currency'];
    }

    /**
     * Sets price_currency
     *
     * @param string $price_currency price_currency
     *
     * @return $this
     */
    public function setPriceCurrency(string $price_currency): InvoiceModel
    {
        $this->container['price_currency'] = $price_currency;

        return $this;
    }

    /**
     * Gets pay_amount
     *
     * @return double
     */
    public function getPayAmount(): float
    {
        return $this->container['pay_amount'];
    }

    /**
     * Sets pay_amount
     *
     * @param double $pay_amount pay_amount
     *
     * @return $this
     */
    public function setPayAmount(float $pay_amount): InvoiceModel
    {
        $this->container['pay_amount'] = $pay_amount;

        return $this;
    }

    /**
     * Gets pay_currency
     *
     * @return string
     */
    public function getPayCurrency(): ?string
    {
        return $this->container['pay_currency'];
    }

    /**
     * Sets pay_currency
     *
     * @param string $pay_currency pay_currency
     *
     * @return $this
     */
    public function setPayCurrency(string $pay_currency): InvoiceModel
    {
        $this->container['pay_currency'] = $pay_currency;

        return $this;
    }

    /**
     * Gets exchange_rate
     *
     * @return double
     */
    public function getExchangeRate(): float
    {
        return $this->container['exchange_rate'];
    }

    /**
     * Sets exchange_rate
     *
     * @param double $exchange_rate exchange_rate
     *
     * @return $this
     */
    public function setExchangeRate(float $exchange_rate): InvoiceModel
    {
        $this->container['exchange_rate'] = $exchange_rate;

        return $this;
    }

    /**
     * Gets paid_amount
     *
     * @return double
     */
    public function getPaidAmount(): float
    {
        return $this->container['paid_amount'];
    }

    /**
     * Sets paid_amount
     *
     * @param double $paid_amount paid_amount
     *
     * @return $this
     */
    public function setPaidAmount(float $paid_amount): InvoiceModel
    {
        $this->container['paid_amount'] = $paid_amount;

        return $this;
    }

    /**
     * Gets create_time
     *
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime
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
    public function setCreateTime(\DateTime $create_time): InvoiceModel
    {
        $this->container['create_time'] = $create_time;

        return $this;
    }

    /**
     * Gets expiration_time
     *
     * @return \DateTime
     */
    public function getExpirationTime(): \DateTime
    {
        return $this->container['expiration_time'];
    }

    /**
     * Sets expiration_time
     *
     * @param \DateTime $expiration_time expiration_time
     *
     * @return $this
     */
    public function setExpirationTime(\DateTime $expiration_time): InvoiceModel
    {
        $this->container['expiration_time'] = $expiration_time;

        return $this;
    }

    /**
     * Gets confirm_speed
     *
     * @return string
     */
    public function getConfirmSpeed(): string
    {
        return $this->container['confirm_speed'];
    }

    /**
     * Sets confirm_speed
     *
     * @param string $confirm_speed confirm_speed
     *
     * @return $this
     */
    public function setConfirmSpeed(string $confirm_speed): InvoiceModel
    {
        $this->container['confirm_speed'] = $confirm_speed;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus(): string
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
    public function setStatus(string $status): InvoiceModel
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets error_status
     *
     * @return string
     */
    public function getErrorStatus(): string
    {
        return $this->container['error_status'];
    }

    /**
     * Sets error_status
     *
     * @param string $error_status error_status
     *
     * @return $this
     */
    public function setErrorStatus(string $error_status): InvoiceModel
    {
        $this->container['error_status'] = $error_status;

        return $this;
    }

    /**
     * Gets invoice_url
     *
     * @return string
     */
    public function getInvoiceUrl(): string
    {
        return $this->container['invoice_url'];
    }

    /**
     * Sets invoice_url
     *
     * @param string $invoice_url invoice_url
     *
     * @return $this
     */
    public function setInvoiceUrl(string $invoice_url): InvoiceModel
    {
        $this->container['invoice_url'] = $invoice_url;

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
