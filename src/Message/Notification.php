<?php

namespace Omnipay\Sogenactif1\Message;

class Notification implements \Omnipay\Common\Message\NotificationInterface
{
    use ResponseTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->parseResponse();
    }
}