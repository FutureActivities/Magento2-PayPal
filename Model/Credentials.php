<?php
namespace FutureActivities\PayPal\Model;

use FutureActivities\PayPal\Api\CredentialsInterface;
use \Nzime\Api\Model\PageResult;
 
class Credentials implements CredentialsInterface
{
    const XML_PATH_SANDBOX_ENABLED = 'payment/paypal_rest/sandbox_enabled';
    const XML_PATH_SANDBOX_CLIENT = 'payment/paypal_rest/sandbox_client_id';
    const XML_PATH_LIVE_CLIENT = 'payment/paypal_rest/client_id';
    
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }
   
    /**
     * Returns the sandbox and live client IDs
     * 
     * @api
     * @return FutureActivities\PayPal\Api\Data\CredentialsResultInterface
     */
    public function client() 
    {
        $result = new CredentialsResult();
        $result->setSandboxEnabled($this->scopeConfig->getValue(self::XML_PATH_SANDBOX_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $result->setSandbox($this->scopeConfig->getValue(self::XML_PATH_SANDBOX_CLIENT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $result->setProduction($this->scopeConfig->getValue(self::XML_PATH_LIVE_CLIENT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        
        return $result;
    }
}