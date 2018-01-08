<?php
namespace FutureActivities\PayPal\Model;

use FutureActivities\PayPal\Api\Data\CredentialsResultInterface;

class CredentialsResult implements CredentialsResultInterface
{
    protected $sandbox = null;
    protected $production = null;

    /**
     * Set the result type
     * 
     * @param string $type
     * @return string
     */
    public function setSandbox($id)
    {
        $this->sandbox = $id;
        
        return $id;
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