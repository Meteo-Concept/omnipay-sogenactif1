<?php
namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Sogenactif1 Request
 *
 * This is the base request class for all Sogenactif 1.0 requests.
 *
 * @see \Omnipay\Sogenactif1\Gateway
 */
abstract class AbstractSogenactif1Request extends AbstractRequest
{
    protected const availableCurrencies = [
        'EUR' => '978', 'USD' => '840', 'CHF' => '756', 'GBP' => '826',
        'CAD' => '124', 'JPY' => '392', 'MXP' => '484', 'TRY' => '949',
        'AUD' => '036', 'NZD' => '554', 'NOK' => '578', 'BRC' => '986',
        'ARP' => '032', 'KHR' => '116', 'TWD' => '901', 'SEK' => '752',
        'DKK' => '208', 'KRW' => '410', 'SGD' => '702', 'XPF' => '953',
        'XOF' => '952'
    ];

    protected const availableLanguages = [
        'fr' => 'Français',
        'de' => 'Deutsch',
        'en' => 'English',
        'sp' => 'Español',
        'it' => 'Italiano',
    ];

    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);
        $this->zeroAmountAllowed = false;
    }

    protected function validateAmountAndCurrency()
    {
        if ($this->getAmountInteger() === null || $this->getAmountInteger() <= 0) {
            throw new InvalidRequestException("Invalid amount requested");
        }

        if (!isset(self::availableCurrencies[$this->getCurrency()])) {
            throw new InvalidRequestException("Invalid currency selected: {$this->getCurrency()}");
        }
    }

    protected function extractCardData()
    {
        $card = $this->getCard();
        $params = "";

        if (isset($card)) {
            if ($card->getEmail()) {
                $params .= "customer_email=" . escapeshellcmd($card->getEmail()) . " ";
            }
            if ($card->getFirstName()) {
                $params .= "customer_firstname=" . escapeshellcmd($card->getFirstName()) . " ";
            }
            if ($card->getLastName()) {
                $params .= "customer_name=" . escapeshellcmd($card->getLastName()) . " ";
            }
        }

        return $params;
    }

    public function getPathFile()
    {
        return $this->getParameter('pathFile');
    }

    public function setPathFile($value)
    {
        return $this->setParameter('pathFile', $value);
    }

    public function getRequestBinaryPath()
    {
        $requestBinary = $this->getParameter('requestBinary');
        if (strstr($requestBinary, '/') === false) {
            return $this->getSogenactifBinaryDir() . '/' . $requestBinary;
        } else {
            return $requestBinary;
        }
    }

    public function setRequestBinaryPath($value)
    {
        return $this->setParameter('requestBinary', $value);
    }

    public function getResponseBinaryPath()
    {
        $responseBinary = $this->getParameter('responseBinary');
        if (strstr($responseBinary, '/') === false) {
            return $this->getSogenactifBinaryDir() . '/' . $responseBinary;
        } else {
            return $responseBinary;
        }
    }

    public function setResponseBinaryPath($value)
    {
        return $this->setParameter('responseBinary', $value);
    }

    public function getSogenactifBinaryDir()
    {
        $dir = null;
        if ($this->getParameter('sogenactifBinaryDir')) {
            $dir = realpath($this->getParameter('sogenactifBinaryDir'));
        } elseif ($this->getParameter('sogenactifDir')) {
            $dir = realpath($this->getParameter('sogenactifDir')) . '/bin';
        }

        return $dir;
    }

    public function setSogenactifBinaryDir($value)
    {
        return $this->setParameter('sogenactifBinaryDir', $value);
    }

    public function getSogenactifDir()
    {
        return $this->getParameter('sogenactifBinaryDir');
    }

    public function setSogenactifDir($value)
    {
        return $this->setParameter('sogenactifDir', $value);
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

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
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

    public function getPaymentMeans()
    {
        return $this->getParameter('paymentMeans');
    }

    public function setPaymentMeans($value)
    {
        return $this->setParameter('paymentMeans', $value);
    }

    public function getCaddie()
    {
        return $this->getParameter('caddie');
    }

    public function setCaddie($value)
    {
        return $this->setParameter('caddie', $value);
    }

    public function getReturnContext()
    {
        return $this->getParameter('returnContext');
    }

    public function setReturnContext($value)
    {
        return $this->setParameter('returnContext', $value);
    }
}
