<?php
namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Sogenactif1 Request
 *
 * This is the response class for all Sogenactif 1.0 requests.
 *
 * @see \Omnipay\Sogenactif1\Gateway
 */
class PurchaseRequest extends AbstractSogenactif1Request
{
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);
    }

    public function getData()
    {
        $this->validate(
            'pathFile',
            'transactionsPrefix',
            'merchantId',
            'merchantCountry',
            'amount',
            'currency',
            'requestBinary',
            'responseBinary',
        );

        $this->validateAmountAndCurrency();

        if ($this->getReturnUrl() === null) {
            throw new \InvalidRequestException("The return URL must be given");
        }

        $params = "pathfile=" . escapeshellcmd($this->getPathFile()) . " ";
        $params .= "transaction_id=" . date('His') . " ";
        $params .= "order_id=" . escapeshellcmd($ths->getTransactionPrefix() . $this->getTransactionId()) . " ";
        $params .= "merchant_id=" . escapeshellcmd($this->getMerchantId()) . " ";
        $params .= "amount=" . escapeshellcmd($this->getAmountInteger()) . " ";
        $params .= "currency_code=" . escapeshellcmd(self::availableCurrencies[$this->getCurrency()]) . " ";
        $params .= "merchant_country=" . escapeshellcmd($this->getMerchantCountry()) . " ";
        if ($this->getCancelUrl()) {
            $params .= "cancel_return_url=" . escapeshellcmd($this->getCancelUrl()) . " ";
        }
        $params .= "normal_return_url=" . escapeshellcmd($this->getReturnUrl()) . " ";
        if ($this->getNotifyUrl()) {
            $params .= "automatic_response_url=" . escapeshellcmd($this->getReturnUrl()) . " ";
        }

        $params .= "language=" . escapeshellcmd($this->getLanguage() && isset(self::availableLanguages[$this->getLanguage()]) ? $this->getLanguage() : "fr") . " ";
        $params .= "payment_means=" . escapeshellcmd($this->getPaymentMeans()) . " ";
        if ($this->getCaddie()) {
            $params .= "caddie=" . escapeshellcmd($this->getCaddie()) . " ";
        }
        $params .= $this->extractCardData();
        // TODO: parameter 'data' for splitted payment, etc.
        if ($this->getReturnContext()) {
            $params .= "return_context=" . escapeshellcmd($this->getReturnContext()) . " ";
        }

        return $params;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed             $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $requestBinary = $this->getRequestBinaryPath();
        $output = [];
        $returnValue = 0;
        exec("$requestBinary $data", $output, $returnValue);

        if ($returnValue != 0)
            throw new \RuntimeException("Cannot make the request, is the API folder correctly installed? Are the permissions set correctly? Return code: $returnValue, inner exception says:" . implode('\n',$output));

        $data = explode('!', array_pop($output));

        $code = $data[1];
        $errorMessage = $data[2];

        if ($code != 0)
            throw new InvalidRequestException("Invalid request, the API says: " . $errorMessage);

        $doc = new \DOMDocument();
        $doc->loadHTML($data[3]);

        $form = $doc->getElementsByTagName("form")->item(0);
        $formUrl = $form->getAttribute("action");
        $formMethod = $form->getAttribute("method");

        $params = ["CB.x" => 0, "CB.y" => 0]; // hijack the form params because the submit buttons are images
        $allInputs = $form->getElementsByTagName("input");
        foreach ($allInputs as $input) {
            $name = $input->getAttribute("name");
            $value = $input->getAttribute("value");
            if ($name && $value) {
                $params[$name] = $value;
            }
        }

        return new PurchaseResponse($this, $doc, $code, $errorMessage, $params, $formUrl, $formMethod);
    }
}
