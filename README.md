# FutureActivities PayPal

A PayPal payment method that supports the new PayPal REST API, designed to be used
by the Magento REST API.

This also supports refunds from within the Magento admin.

## How to use

This is a Magento REST API only checkout method. PUT an order to the following endpoint:

    /rest/V1/carts/mine/order
    
With the following data:

    {
        "paymentMethod": {
            "method": "paypal_rest",
    		"additional_data": PayPalResponse
        }
    }

Where `PayPalResponse` is the response object from the [paypal-checkout](https://github.com/paypal/paypal-checkout) javascript buttons, and should include at a minimum:

- payerID
- paymentID

## Settings

You need to create a PayPal REST app first at developer.paypal.com.

When enabling this payment method you need to set the Client ID and secret values, for both the sandbox and live versions.
