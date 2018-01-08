<?php
namespace FutureActivities\PayPal\Model;

use FutureActivities\PayPal\Api\Data\CredentialsResultInterface;

class CredentialsResult implements CredentialsResultInterface
{
    protected $sandboxEnabled = false;
    protected $sandbox = null;
    protected $production = null;

    /**
     * Set the data
     * 
     * @param boolean $value
     * @return null
     */
    public function setSandboxEnabled($value)
    {
        $this->sandboxEnabled = $value;
    }
        
    /**
     * Get the result 
     * 
     * @return boolean
     */
    public function getSandboxEnabled() 
    {
        return $this->sandboxEnabled;
    }
    
    /**
     * Set the result
     * 
     * @param string $id
     * @return string
     */
    public function setSandbox($id)
    {
        $this->sandbox = $id;
    }
    
    /**
     * Get the result type
     * 
     * @return string
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * Set the data
     * 
     * @param string $id
     * @return null
     */
    public function setProduction($id) 
    {
        $this->production = $id;
    }
        
    /**
     * Get the result ID
     * 
     * @return string
     */
    public function getProduction()
    {
        return $this->production;
    }
    
}