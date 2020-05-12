<?php
namespace Omnipay\Sogenactif1;

use Omnipay\Common\AbstractGateway;

/**
 * Sogenactif1 Gateway
 *
 * This gateway is used for the old Sogenactif 1.0 protocol designed by Société Générale for web
 * stores before they changed to Sogenactif 2.0 (based on Woldline Sips 2.0 by Atos),
 * and more recently rolled out another solution called Sogecommerce
 * (https://sogecommerce.societegenerale.eu/doc/en-EN/).
 *
 * If you're interested in Sogenactif 2.0, check out this extension: https://github.com/ambroisemaupate/omnipay-sips2.
 *
 * Sogenactif 1.0 uses closed-source binaires delivered by the bank to the
 * merchant along with a certificate and some example wrapper scripts. To
 * perform a purchase, the merchant store must call the binary 'request' and
 * pass it the relevant information about the order. This displays some payment
 * options on the website. When the user clicks one of the payment option logo,
 * they are redirected to an off-site gateway to enter their payment
 * information. Upon completion of the payment, the user is redirected to the
 * merchant store with by a POST request. At this point the binary 'response'
 * must be called to interpret the content of the response from the gateway.
 *
 * The binaries handles the encryption and signing of the exchanges with the
 * bank payment gateway. The cryptographic secrets are contained in the
 * certificate delivered along with the binaries.
 *
 * This driver uses the example scripts as a base. Only completePurchase() is
 * supported for now.
 *
 * ### Example
 * <code>
 * // Create a gateway for the Sogenactif1 Gateway
 * // (routes to GatewayFactory::create)
 * $gateway = Omnipay::create('Sogenactif1');
 *
 * // Initialise the gateway
 * $gateway->initialize(array(
 *     'testMode' => true,
 * ));
 *
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->completePurchase(array(
 *     'amount'                   => '10.00',
 *     'currency'                 => 'EUR',
 *     'transactionId'            => 1,
 * ));
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Purchase transaction was successful!\n";
 *     $sale_id = $response->getTransactionReference();
 *     echo "Transaction reference = " . $sale_id . "\n";
 * }
 * </code>
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Sogenactif1';
    }

    public function getDefaultParameters()
    {
        $parameters = parent::getDefaultParameters();
        $parameters = array_merge($parameters, array(
            'pathFile' => null,
            'merchantId' => '014213245611111',
            'merchantCountry' => 'fr',
            'requestBinary' => 'request',
            'responseBinary' => 'response',
            'sogenactifDir' => null,
            'sogenactifBinaryDir' => null,
            'language' => 'fr',
            'paymentMeans' => 'CB,2,VISA,2,MASTERCARD,2',
            'transactionPrefix' => '',
        ));
        return $parameters;
    }

    public function getPathFile()
    {
        return $this->getParameter('pathFile');
    }

    public function setPathFile($value)
    {
        return $this->setParameter('pathFile', $value);
    }

    public function getTransactionPrefix()
    {
        return $this->getParameter('transactionPrefix');
    }

    public function setTransactionPrefix($value)
    {
        return $this->setParameter('transactionPrefix', preg_replace('/[^A-Za-z0-9\-]/', '', $value));
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantCountry()
    {
        return $this->getParameter('merchantCountry');
    }

    public function setMerchantCountry($value)
    {
        return $this->setParameter('merchantCountry', $value);
    }

    public function getRequestBinary()
    {
        return $this->getParameter('requestBinary');
    }

    public function setRequestBinary($value)
    {
        return $this->setParameter('requestBinary', $value);
    }

    public function getResponseBinary()
    {
        return $this->getParameter('responseBinary');
    }

    public function setResponseBinary($value)
    {
        return $this->setParameter('responseBinary', $value);
    }

    public function getSogenactifDir()
    {
        return $this->getParameter('sogenactifDir');
    }

    public function setSogenactifDir($value)
    {
        return $this->setParameter('sogenactifDir', $value);
    }

    public function getSogenactifBinaryDir()
    {
        return $this->getParameter('sogenactifBinaryDir');
    }

    public function setSogenactifBinaryDir($value)
    {
        return $this->setParameter('sogenactifBinaryDir', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getPaymentMeans()
    {
        return $this->getParameter('paymentMeans');
    }

    public function setPaymentMeans($value)
    {
        return $this->setParameter('paymentMeans', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sogenactif1\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sogenactif1\Message\CompletePurchaseRequest', $parameters);
    }
}
