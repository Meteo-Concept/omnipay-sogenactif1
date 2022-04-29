<?php

namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Exception\InvalidRequestException;

trait ResponseTrait
{
    public function parseResponse()
    {
        $this->validate(
            'pathFile',
            'requestBinary',
            'responseBinary',
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
        $data = array_combine(CompletePurchaseRequest::FIELDS, $data);
        if (!$data)
            throw new InvalidRequestException("Invalid request, the data in the response could not be decoded");

        if ($data['code'] != 0) {
            throw new InvalidRequestException("Invalid request, the API says {$data['error']}");
        }

        return $data;
    }

    public function getTransactionReference()
    {
        return isset($this->data['transactionReference']) ? $this->data['transactionReference'] : null;
    }

    public function getTransactionStatus()
    {
        if ($this->isSuccessful())
            return static::STATUS_COMPLETED;
        else
            return static::STATUS_FAILED;
    }

    public function isSuccessful()
    {
        return $this->data['code'] == 0 && $this->data['bankResponseCode'] == '00';
    }

    public function getMessage()
    {
        if (isset(CompletePurchaseResponse::BANK_RESPONSE_CODES[$this->data['bankResponseCode']]))
            return CompletePurchaseResponse::BANK_RESPONSE_CODES[$this->data['bankResponseCode']];
        else
            return null;
    }


}