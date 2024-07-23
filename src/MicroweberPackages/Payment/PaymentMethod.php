<?php

namespace MicroweberPackages\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
abstract class PaymentMethod
{

    public function title(): string
    {
        return '';
    }

    public function view(): string
    {
        return '';
    }

    public function process($data = [])
    {
        return [];
    }

    public function getSettingsForm($form): array
    {
        return [];
    }


}
