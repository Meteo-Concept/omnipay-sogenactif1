<?php

namespace Omnipay\Sogenactif1\Message;

use Omnipay\Common\Message\AbstractRequest;

class Notification extends AbstractRequest implements \Omnipay\Common\Message\NotificationInterface
{
    use ResponseTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->parseResponse();
    }

    public function sendData($data)
    {
        // The notification is self-sufficient, it contains all the necessary data
        return $this;
    }
}