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
    use ResponseTrait;

    public const FIELDS = [
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
        return $this->parseResponse();
    }

    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $data);
    }
}
