<?php

namespace UniPayment\Client\Model;

use \ArrayAccess;
use \UniPayment\Client\ObjectSerializer;

/**
 * CreateInvoiceRequest Class Doc Comment
 *
 * @category Class
 * @package  UniPayment\Client
 */
class CreateInvoiceRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $modelName = 'CreateInvoiceRequest';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldTypes = [
        'app_id' => 'string',
        'title' => 'string',
        'description' => 'string',
        'lang' => 'string',
        'price_amount' => 'double',
        'price_currency' => 'string',
        'network' => 'string',
        'pay_currency' => 'string',
        'notify_url' => 'string',
        'redirect_url' => 'string',
        'order_id' => 'string',
        'ext_args' => 'string',
        'confirm_speed' => 'string'];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $fieldFormats = [
        'app_id' => null,
        'title' => null,
        'description' => null,
        'lang' => null,
        'price_amount' => 'double',
        'price_currency' => null,
        'network' => null,
        'pay_currency' => null,
        'notify_url' => null,
        'redirect_url' => null,
        'order_id' => null,
        'ext_args' => null,
        'confirm_speed' => null];

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
        'app_id' => 'app_id',
        'title' => 'title',
        'description' => 'description',
        'lang' => 'lang',
        'price_amount' => 'price_amount',
        'price_currency' => 'price_currency',
        'network' => 'network',
        'pay_currency' => 'pay_currency',
        'notify_url' => 'notify_url',
        'redirect_url' => 'redirect_url',
        'order_id' => 'order_id',
        'ext_args' => 'ext_args',
        'confirm_speed' => 'confirm_speed'];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'app_id' => 'setAppId',
        'title' => 'setTitle',
        'description' => 'setDescription',
        'lang' => 'setLang',
        'price_amount' => 'setPriceAmount',
        'price_currency' => 'setPriceCurrency',
        'network' => 'setNetwork',
        'pay_currency' => 'setPayCurrency',
        'notify_url' => 'setNotifyUrl',
        'redirect_url' => 'setRedirectUrl',
        'order_id' => 'setOrderId',
        'ext_args' => 'setExtArgs',
        'confirm_speed' => 'setConfirmSpeed'];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'app_id' => 'getAppId',
        'title' => 'getTitle',
        'description' => 'getDescription',
        'lang' => 'getLang',
        'price_amount' => 'getPriceAmount',
        'price_currency' => 'getPriceCurrency',
        'network' => 'getNetwork',
        'pay_currency' => 'getPayCurrency',
        'notify_url' => 'getNotifyUrl',
        'redirect_url' => 'getRedirectUrl',
        'order_id' => 'getOrderId',
        'ext_args' => 'getExtArgs',
        'confirm_speed' => 'getConfirmSpeed'];

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

    const CONFIRM_SPEED_LOW = 'low';
    const CONFIRM_SPEED_MEDIUM = 'medium';
    const CONFIRM_SPEED_HIGH = 'high';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getConfirmSpeedAllowableValues(): array
    {
        return [
            self::CONFIRM_SPEED_LOW,
            self::CONFIRM_SPEED_MEDIUM,
            self::CONFIRM_SPEED_HIGH,];
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
        $this->container['title'] = $data['title'] ?? null;
        $this->container['description'] = $data['description'] ?? null;
        $this->container['lang'] = $data['lang'] ?? null;
        $this->container['price_amount'] = $data['price_amount'] ?? null;
        $this->container['price_currency'] = $data['price_currency'] ?? null;
        $this->container['network'] = $data['network'] ?? null;
        $this->container['pay_currency'] = $data['pay_currency'] ?? null;
        $this->container['notify_url'] = $data['notify_url'] ?? null;
        $this->container['redirect_url'] = $data['redirect_url'] ?? null;
        $this->container['order_id'] = $data['order_id'] ?? null;
        $this->container['ext_args'] = $data['ext_args'] ?? null;
        $this->container['confirm_speed'] = $data['confirm_speed'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties(): array
    {
        $invalidProperties = [];

        $allowedValues = $this->getConfirmSpeedAllowableValues();
        if (!is_null($this->container['confirm_speed']) && !in_array($this->container['confirm_speed'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'confirm_speed', must be one of '%s'",
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
     * @param string $title title
     *
     * @return $this
     */
    public function setAppId(string $title): CreateInvoiceRequest
    {
        $this->container['app_id'] = $title;

        return $this;
    }

    /**
     * Gets title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->container['title'];
    }

    /**
     * Sets title
     *
     * @param string $title title
     *
     * @return $this
     */
    public function setTitle(string $title): CreateInvoiceRequest
    {
        $this->container['title'] = $title;

        return $this;
    }

    /**
     * Gets description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     *
     * @param string $description description
     *
     * @return $this
     */
    public function setDescription(string $description): CreateInvoiceRequest
    {
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->container['lang'];
    }

    /**
     * Sets lang
     *
     * @param string $lang lang
     *
     * @return $this
     */
    public function setLang(string $lang): CreateInvoiceRequest
    {
        $this->container['lang'] = $lang;

        return $this;
    }

    /**
     * Gets price_amount
     *
     * @return double
     */
    public function getPriceAmount()
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
    public function setPriceAmount(float $price_amount): CreateInvoiceRequest
    {
        $this->container['price_amount'] = $price_amount;

        return $this;
    }

    /**
     * Gets price_currency
     *
     * @return string
     */
    public function getPriceCurrency()
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
    public function setPriceCurrency(string $price_currency): CreateInvoiceRequest
    {
        $this->container['price_currency'] = $price_currency;

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
    public function setNetwork(string $network): CreateInvoiceRequest
    {
        $this->container['network'] = $network;

        return $this;
    }

    /**
     * Gets pay_currency
     *
     * @return string
     */
    public function getPayCurrency()
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
    public function setPayCurrency(string $pay_currency): CreateInvoiceRequest
    {
        $this->container['pay_currency'] = $pay_currency;

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
    public function setNotifyUrl(string $notify_url): CreateInvoiceRequest
    {
        $this->container['notify_url'] = $notify_url;

        return $this;
    }

    /**
     * Gets redirect_url
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->container['redirect_url'];
    }

    /**
     * Sets redirect_url
     *
     * @param string $redirect_url redirect_url
     *
     * @return $this
     */
    public function setRedirectUrl(string $redirect_url): CreateInvoiceRequest
    {
        $this->container['redirect_url'] = $redirect_url;

        return $this;
    }

    /**
     * Gets order_id
     *
     * @return string
     */
    public function getOrderId()
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
    public function setOrderId(string $order_id): CreateInvoiceRequest
    {
        $this->container['order_id'] = $order_id;

        return $this;
    }

    /**
     * Gets ext_args
     *
     * @return string
     */
    public function getExtArgs()
    {
        return $this->container['ext_args'];
    }

    /**
     * Sets ext_args
     *
     * @param string $ext_args ext_args
     *
     * @return $this
     */
    public function setExtArgs(string $ext_args): CreateInvoiceRequest
    {
        $this->container['ext_args'] = $ext_args;

        return $this;
    }

    /**
     * Gets confirm_speed
     *
     * @return string
     */
    public function getConfirmSpeed()
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
    public function setConfirmSpeed(string $confirm_speed): CreateInvoiceRequest
    {
        $allowedValues = $this->getConfirmSpeedAllowableValues();
        if (!in_array($confirm_speed, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'confirm_speed', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['confirm_speed'] = $confirm_speed;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
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
