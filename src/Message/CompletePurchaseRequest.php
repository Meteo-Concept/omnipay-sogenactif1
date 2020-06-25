<?php
namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Sogenactif1 CompletePurchaseRequest
 *
 * This is the request class for the return from Sogenactif 1.0
 *
 * @see \Omnipay\Sogenactif1\Gateway
 */
class CompletePurchaseRequest extends AbstractSogenactif1Request
{
    protected const fields = [
         'empty',
         'code',
         'error',
         'merchantId',
         'merchantCountry',
         'amount',
         // this field is called transaction_id in Sogenactif1 terminology
         // but we map it to transactionReference for consistency within Omnipay
         'transactionReference',
         'paymentMeans',
         'transmissionDate',
         'paymentTime',
         'paymentDate',
         'responseCode',
         'paymentCertificate',
         'authorisationId',
         'currencyCode',
         'cardNumber',
         'cvvFlag',
         'cvvResponseCode',
         'bankResponseCode',
         'complementaryCode',
         'complementaryInfo',
         'returnContext',
         'caddie',
         'receiptComplement',
         'merchantLanguage',
         'language',
         'customerId',
         // this field is called order_id in Sogenactif1 terminology
         // we use it as the Omnipay transactionId
         'orderId',
         'customerEmail',
         'customerIpAddress',
         'captureDay',
         'captureMode',
         'data',
         'orderValidity',
         'transactionCondition',
         'statementReference',
         'cardValidity',
         'scoreValue',
         'scoreColor',
         'scoreInfo',
         'scoreThreshold',
         'scoreProfile',
         'empty_2',
         'threedLsCode',
         'threedRelegationCode',
    ];

    public function getData()
    {
        $this->validate(
            'pathFile',
            'amount',
            'requestBinary',
            'responseBinary',
            'transactionId',
        );

        $encodedData = $this->httpRequest->request->get('DATA');

        if (!isset($encodedData)) {
            // well that's a bit disappointing, isn't it?
            throw new InvalidRequestException('Invalid request, no parameter DATA in the body');
        }

        $params = "message=$encodedData pathfile={$this->getPathFile()}";

        $responseBinary = $this->getResponseBinaryPath();
        $output = [];
        $returnValue = 0;
        exec("$responseBinary $params", $output, $returnValue);

        if ($returnValue != 0)
            throw new \RuntimeException("Cannot make the request, is the API folder correctly installed? Are the permissions set correctly? Return code from the binary is $returnValue and Inner exception says: " . implode('\n', $output));

        $data = explode('!', array_pop($output));
        $data = array_combine(self::fields, $data);
        if (!$data)
            throw new InvalidRequestException("Invalid request, the data in the response could not be decoded");

        if ($data['code'] != 0) {
            throw new InvalidRequestException("Invalid request, the API says {$data['error']}");
        }

        $prefixedTransaction = $this->getTransactionPrefix() . $this->getTransactionId();
        if ($prefixedTransaction != $data['orderId']) {
            throw new InvalidRequestException("Invalid request, the transaction id does not match (expected {$prefixedTransaction}, got {$data['orderId']})");
        }

        if (isset($data['amount']) && $this->getAmountInteger() != $data['amount']) {
            throw new InvalidRequestException("Invalid request, the order id matches but the amount does NOT (expected {$this->getAmountInteger()} got {$data['amount']})");
        }

        return $data;
    }

    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $data);
    }
}
