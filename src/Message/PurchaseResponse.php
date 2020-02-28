<?php
namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Sogenactif1 Response
 *
 * This is the response class for all Sogenactif 1.0 requests.
 *
 * @see \Omnipay\Sogenactif1\Gateway
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $code;

    protected $error;

    protected $url;

    protected $method;

    protected $encodedData;

    public function __construct(RequestInterface $request, $data, $code, string $error, array $encodedData, string $url, string $method)
    {
        parent::__construct($request, $data);
        $this->code = $code;
        $this->error = $error;
        $this->url = $url;
        $this->method = $method;
        $this->encodedData = $encodedData;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getTransactionId()
    {
        return $request->getTransactionId();
    }

    public function getRedirectMethod()
    {
        return $this->method;
    }

    public function getRedirectUrl()
    {
        return $this->url;
    }

    public function getRedirectData()
    {
        return $this->encodedData;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->error;
    }
}
