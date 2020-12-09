<?php
namespace FutureActivities\PayPal\Model;

use Magento\Sales\Model\Order;

class Rest extends \Magento\Payment\Model\Method\AbstractMethod
{
    const METHOD_CODE = 'paypal_rest';
    
    protected $_code = self::METHOD_CODE;
    protected $_isGateway = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = true;
    protected $_canRefund = true;
    
    protected $_minOrderTotal = 0;
    
    protected $_paypalSandbox = false;
    protected $_paypalSandboxClient;
    protected $_paypalSandboxSecret;
    protected $_paypalClient;
    protected $_paypalSecret;
    
    protected $_paypal;
    
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
        
        $this->_paypalSandbox = $this->getConfigData('sandbox_enabled');
        $this->_paypalSandboxClient = $this->getConfigData('sandbox_client_id');
        $this->_paypalSandboxSecret = $this->getConfigData('sandbox_secret');
        $this->_paypalClient = $this->getConfigData('client_id');
        $this->_paypalSecret = $this->getConfigData('secret');
        
        $this->paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                ($this->_paypalSandbox ? $this->_paypalSandboxClient : $this->_paypalClient),
                ($this->_paypalSandbox ? $this->_paypalSandboxSecret : $this->_paypalSecret)
            )
        );
        
        if (!$this->_paypalSandbox) {
            $this->paypal->setConfig(['mode' => 'live']);
        }
    }
    
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $order = $payment->getOrder();
        $billing = $order->getBillingAddress();
        
        try {
            $order->setState(Order::STATE_PENDING_PAYMENT)->setStatus(Order::STATE_PENDING_PAYMENT)->save();
            
            $payerId = $payment->getAdditionalInformation('payerID');
            $paymentId = $payment->getAdditionalInformation('paymentID');
            $paymentToken = $payment->getAdditionalInformation('payerToken');
            
            $paypalPayment = \PayPal\Api\Payment::get($paymentId, $this->paypal);
            $paypalExecution = new \PayPal\Api\PaymentExecution();
            $paypalExecution->setPayerId($payerId);
            
            $paypalAmount = new \PayPal\Api\Amount();
            $paypalAmount->setCurrency($order->getOrderCurrencyCode());
            $paypalAmount->setTotal($amount);
            
            $paypalTransaction = new \PayPal\Api\Transaction();
            $paypalTransaction->setReferenceId($order->getIncrementId());
            $paypalTransaction->setInvoiceNumber($order->getIncrementId());
            $paypalTransaction->setAmount($paypalAmount);
            
            $paypalExecution->addTransaction($paypalTransaction);
            
            $result = $paypalPayment->execute($paypalExecution, $this->paypal);
            $transactions = $result->getTransactions();
            $related_resources = $transactions[0]->getRelatedResources();
            $sale = $related_resources[0]->getSale();
            
            $payment->setTransactionId($sale->getId())->setIsTransactionClosed(0);
 
        } catch (\Exception $e) {
            $order->setState(Order::STATE_CLOSED)
                ->setStatus(Order::STATE_CLOSED)
                ->addStatusHistoryComment($e->getMessage())
                ->save();
            $this->debugData(['exception' => $e->getMessage()]);
            throw new \Magento\Framework\Validator\Exception(__($e->getMessage()));
        }
        
        return $this;
    }
 
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $order = $payment->getOrder();
        $transactionId = $payment->getParentTransactionId();
 
        try {
            $papalAmount = new \PayPal\Api\Amount();
            $papalAmount->setCurrency($order->getOrderCurrencyCode());
            $papalAmount->setTotal($amount);
            
            $paypalRefund = new \PayPal\Api\RefundRequest();
            $paypalRefund->setAmount($papalAmount);

            $sale = new \PayPal\Api\Sale();
            $sale->setId($transactionId);
            
            $refundedSale = $sale->refundSale($paypalRefund, $this->paypal);

        } catch (\Exception $e) {
            $this->debugData(['exception' => $e->getMessage()]);
            throw new \Magento\Framework\Validator\Exception(__($e->getMessage()));
        }
 
        $payment
            ->setTransactionId($transactionId . '-' . \Magento\Sales\Model\Order\Payment\Transaction::TYPE_REFUND)
            ->setParentTransactionId($transactionId)
            ->setIsTransactionClosed(1)
            ->setShouldCloseParentTransaction(1);
 
        return $this;
    }
    
    public function assignData(\Magento\Framework\DataObject $data)
    {
        parent::assignData($data);
        
        $additionalData = $data->getData(\Magento\Quote\Api\Data\PaymentInterface::KEY_ADDITIONAL_DATA);

        if (!is_array($additionalData)) {
            return $this;
        }

        foreach ($additionalData as $key => $value) {
            $this->getInfoInstance()->setAdditionalInformation($key, $value);
        }
        
        return $this;
    }
 
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null) 
    {
        $this->_minOrderTotal = $this->getConfigData('min_order_total');
        if ($quote && $quote->getBaseGrandTotal() < $this->_minOrderTotal)
            return false;
        
        return parent::isAvailable($quote);
    }
}
