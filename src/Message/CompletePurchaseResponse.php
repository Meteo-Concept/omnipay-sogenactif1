<?php
namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Sogenactif1 CompletePurchaseResponse
 *
 * This is the response class for the returns from Sogenactif 1.0 off-site gateway.
 *
 * @see \Omnipay\Sogenactif1\Gateway
 */
class CompletePurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    const bankResponseCodes = [
        '00' => 'Success',
        '02' => 'Contact the card emitter',
        '03' => 'Invalid acceptor',
        '04' => 'Retain the card',
        '05' => 'Do not process the order',
        '07' => 'Retain the card, special conditions apply',
        '08' => 'Approve after identification',
        '12' => 'Invalid transaction',
        '13' => 'Invalid amount',
        '14' => 'Invalid holder number',
        '15' => 'Unknown card emitter',
        '30' => 'Formatting error',
        '31' => 'Buyer id unknown',
        '33' => 'Expired card',
        '34' => 'Suspected fraud',
        '41' => 'Lost card',
        '43' => 'Stolen card',
        '51' => 'Insufficient funding or credit limit reached',
        '54' => 'Expired card',
        '56' => 'Unknown card',
        '57' => 'Forbidden transaction for this card holder',
        '58' => 'Forbidden transaction for this terminal',
        '59' => 'Suspected fraud',
        '60' => 'The card acceptor must contact the buyer',
        '61' => 'Limit of money extraction reached',
        '63' => 'Security rules breached',
        '68' => 'No response received within time limts',
        '90' => 'System momentarily down',
        '91' => 'Unreachable card emitter',
        '96' => 'System malfunction',
        '97' => 'Expired request',
        '98' => 'Unreachable server',
        '99' => 'Problem on the request initiating end',
        'A1' => '3-D Secure authentication data missing',
    ];

    public function isSuccessful()
    {
        return $this->data['code'] == 0 && $this->data['bankResponseCode'] == '00';
    }

    public function isRedirect()
    {
        return false;
    }

    public function getTransactionId()
    {
        return $this->request->getTransactionId();
    }

    public function getTransactionReference()
    {
        return isset($this->data['transactionReference']) ? $this->data['transactionReference'] : null;
    }

    public function getCode()
    {
        return $this->data['code'];
    }

    public function getReasonCode()
    {
        return isset($this->data['bankResponseCode']) ? $this->data['bankResponseCode'] : null;
    }

    public function getMessage()
    {
        if (isset(self::bankResponseCodes[$this->data['bankResponseCode']]))
            return self::bankResponseCodes[$this->data['bankResponseCode']];
        else
            return null;
    }
}
