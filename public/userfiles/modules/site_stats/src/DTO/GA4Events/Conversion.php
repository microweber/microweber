<?php
namespace MicroweberPackages\Modules\SiteStats\DTO\GA4Events;

use AlexWestergaard\PhpGa4\Helper\EventHelper;

class Conversion extends EventHelper
{
    protected null|string $transaction_id;
    protected null|string $send_to;
    protected null|string $email;

    public function getName(): string
    {
        return 'conversion';
    }

    public function getParams(): array
    {
        return [
            'transaction_id',
            'send_to',
            'email'
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setTransactionId(null|string $id)
    {
        $this->transaction_id = $id;
        return $this;
    }

    public function setSendTo(null|string $send_to)
    {
        $this->send_to = $send_to;
        return $this;
    }

    public function setEmail(null|string $email)
    {
        $this->email = $email;
        return $this;
    }
}
