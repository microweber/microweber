<?php

namespace MicroweberPackages\FormBuilder\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;

/**
 * @method static \MicroweberPackages\FormBuilder\FormBuilderEngine fromArray(array $fields)
 * @method static \MicroweberPackages\FormBuilder\FormBuilderEngine fromJson(string $json)
 * @method static \MicroweberPackages\FormBuilder\FormBuilderEngine fromJsonFile(string $path)
 * @method static void registerFieldType(string $type, callable|\MicroweberPackages\FormBuilder\Contracts\FieldTypeInterface $resolver)
 * @method static bool hasFieldType(string $type)
 * @method static string[] getRegisteredTypes()
 * @method static FieldTypeRegistry getRegistry()
 *
 * @see \MicroweberPackages\FormBuilder\FormBuilder
 */
class FormBuilder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MicroweberPackages\FormBuilder\FormBuilder::class;
    }
}
