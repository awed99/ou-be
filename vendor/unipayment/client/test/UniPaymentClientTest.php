<?php
/**
 * UniPaymentClientTest
 */

namespace UniPayment\Client;

require_once(__DIR__ . '/../vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use UniPayment\Client\Model\CancelWithdrawalRequest;
use UniPayment\Client\Model\CreateInvoiceRequest;
use UniPayment\Client\Model\CreatePayoutRequest;
use UniPayment\Client\Model\CreateWithdrawalRequest;
use UniPayment\Client\Model\PayoutRequestItem;
use UniPayment\Client\Model\QueryInvoiceRequest;

/**
 * UniPaymentClientTest Class
 */
class UniPaymentClientTest extends TestCase
{
    private $uniPaymentClient;

    private $clientId = '399fc017-6305-4073-ab1b-7ca5d37be985';
    private $clientSecret = '94eWn6GdAWbWMmdTbTRa1e9HAZgs851Ry';
    private $appId = 'e76535a0-32cf-448a-81e6-607c321dcaf9';
    private $order_id = 'test_order_id';
    private $isSandbox = true;
    private $invoiceId = 'P8wMaXZJZx5VfgZ8nEZk7j';
    private $notify = '{"ipn_type":"invoice","event":"invoice_created","app_id":"2a9bd90b-fe95-4659-83cb-04de662fbbac","invoice_id":"SrAARgNrPgvveiBQtNc4gk","order_id":"6330f1f118df1","price_amount":100.0,"price_currency":"USD","network":null,"address":null,"pay_currency":"USDT","pay_amount":0.0,"exchange_rate":0.0,"paid_amount":0.0,"confirmed_amount":0.0,"refunded_price_amount":0.0,"create_time":"2022-09-26T00:27:29.6697063Z","expiration_time":"2022-09-26T00:32:29.6698139Z","status":"New","error_status":"None","ext_args":null,"transactions":null,"notify_id":"0443e623-492a-474a-bd22-b866d6b7beb9","notify_time":"0001-01-01T00:00:00"}';

    /**
     * Setup before running any test cases
     */
    public static function setUpBeforeClass(): void
    {
    }

    /**
     * Setup before running each test case
     */
    public function setUp(): void
    {
        $this->uniPaymentClient = new UniPaymentClient();

        $this->uniPaymentClient->getConfig()->setClientId($this->clientId);
        $this->uniPaymentClient->getConfig()->setClientSecret($this->clientSecret);
        $this->uniPaymentClient->getConfig()->setIsSandbox($this->isSandbox);
        $this->uniPaymentClient->getConfig()->setDebug(true);

        $this->order_id = uniqid();
    }

    /**
     * Clean up after running each test case
     */
    public function tearDown(): void
    {
    }

    /**
     * Clean up after running all test cases
     */
    public static function tearDownAfterClass(): void
    {
    }

    /**
     * Test case for createInvoice
     */
    public function testCreateInvoice()
    {
        $createInvoiceRequest = new CreateInvoiceRequest();
        $createInvoiceRequest->setAppId($this->appId);
        $createInvoiceRequest->setPriceAmount("100");
        $createInvoiceRequest->setPriceCurrency("USD");
        $createInvoiceRequest->setPayCurrency("USDT");
        $createInvoiceRequest->setOrderId($this->order_id);
        $createInvoiceRequest->setConfirmSpeed("low");
        $createInvoiceRequest->setRedirectUrl("https://google.com");
        $createInvoiceRequest->setNotifyUrl("https://google.com");
        $createInvoiceRequest->setTitle("Test Invoice");
        $createInvoiceRequest->setDescription("Test Desc");
        $createInvoiceRequest->setLang("en");
        $response = $this->uniPaymentClient->createInvoice($createInvoiceRequest);
        $this->assertEquals('OK', $response->getCode());
        $this->assertNotNull($response->getData()->getInvoiceUrl());
    }

    /**
     * Test case for queryInvoices
     * @throws ApiException
     */
    public function testQueryInvoices()
    {
        $queryInvoiceRequest = new QueryInvoiceRequest();
        $queryInvoiceRequest->setInvoiceId($this->invoiceId);

        $response = $this->uniPaymentClient->queryInvoices($queryInvoiceRequest);
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getInvoiceById
     * @throws ApiException
     */
    public function testGetInvoiceById()
    {
        $response = $this->uniPaymentClient->getInvoiceById($this->invoiceId);
        $this->assertEquals('OK', $response->getCode());
        $this->assertNotNull($response->getData()->getInvoiceUrl());
        //$this->assertEquals($this->order_id, $response->getData()->getOrderId());
    }

    /**
     * Test case for getInvoiceById
     * @throws ApiException
     */
    public function testQueryIps()
    {
        $response = $this->uniPaymentClient->queryIps();
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getCurrencies
     * @throws ApiException
     */
    public function testGetCurrencies()
    {
        $response = $this->uniPaymentClient->getCurrencies();
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getExchangeRateByFiatCurrency
     * @throws ApiException
     */
    public function testGetExchangeRateByFiatCurrency()
    {
        $response = $this->uniPaymentClient->getExchangeRateByFiatCurrency('USD');
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getExchangeRateByCurrencyPair
     * @throws ApiException
     */
    public function testGetExchangeRateByCurrencyPair()
    {
        $response = $this->uniPaymentClient->getExchangeRateByCurrencyPair('USD', 'BTC');
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for checkIpn
     * @throws ApiException
     */
    public function testcheckIpn()
    {
        $response = $this->uniPaymentClient->checkIpn($this->notify);
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getWalletBalances
     * @throws ApiException
     */
    public function testGetWalletBalances()
    {
        $response = $this->uniPaymentClient->getWalletBalances();
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for createWithdrawal
     * @throws ApiException
     */
    public function testCreateWithdrawal()
    {
        $createWithdrawalRequest = new CreateWithdrawalRequest();
        $createWithdrawalRequest->setAmount(1.01);
        $createWithdrawalRequest->setNetwork("NETWORK_BSC");
        $createWithdrawalRequest->setAutoConfirm(false);
        $createWithdrawalRequest->setIncludeFee(true);
        $createWithdrawalRequest->setAssetType("USDT");
        $response = $this->uniPaymentClient->createWithdrawal($createWithdrawalRequest);
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getWithdrawalById
     * @throws ApiException
     */
    public function testGetWithdrawalById()
    {
        $response = $this->uniPaymentClient->getWithdrawalById("a4911dd9-2663-425b-952f-822a764099c1");
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for queryWithdrawals
     * @throws ApiException
     */
    public function testQueryWithdrawals()
    {
        $response = $this->uniPaymentClient->queryWithdrawals();
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for cancelWithdrawal
     * @throws ApiException
     */
    public function testCancelWithdrawal()
    {
        $cancelWithdrawalRequest = new CancelWithdrawalRequest();
        //Withdrawal status should be pending when cancelling
        $cancelWithdrawalRequest->setId("a4911dd9-2663-425b-952f-822a764099c1");
        $response = $this->uniPaymentClient->cancelWithdrawal($cancelWithdrawalRequest);
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for createPayout
     * @throws ApiException
     */
    public function testCreatePayout()
    {
        $createPayoutRequest = new CreatePayoutRequest();
        $createPayoutRequest->setAssetType("BSC");
        $createPayoutRequest->setNetwork("NETWORK_BSC");

        $payoutRequestItem = new PayoutRequestItem();
        $payoutRequestItem->setAmount(0.01);
        $payoutRequestItem->setAddress("0xBe807Dddb074639cD9fA61b47676c064fc50D62C");
        $createPayoutRequest->setItems(array($payoutRequestItem));
        $response = $this->uniPaymentClient->createPayout($createPayoutRequest);
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for queryPayouts
     * @throws ApiException
     */
    public function testQueryPayouts()
    {
        $response = $this->uniPaymentClient->queryPayouts();
        print_r($response);
        $this->assertEquals('OK', $response->getCode());
    }

    /**
     * Test case for getPayoutById
     * @throws ApiException
     */
    public function testGetPayoutById()
    {
        $response = $this->uniPaymentClient->getPayoutById("5d43f367-7766-41c0-90f2-4788b5316308");
        $this->assertEquals('OK', $response->getCode());
    }

}
