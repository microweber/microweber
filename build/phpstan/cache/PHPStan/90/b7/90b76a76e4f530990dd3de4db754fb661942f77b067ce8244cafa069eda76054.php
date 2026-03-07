<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/TaxId.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\TaxId
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ae8fe7b7b3c0f249918d02499b814b3ec876ef3b6324e31e1a615d9e5e9cc53e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\TaxId',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/TaxId.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\TaxId',
    'shortName' => 'TaxId',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * You can add one or multiple tax IDs to a <a href="https://stripe.com/docs/api/customers">customer</a> or account.
 * Customer and account tax IDs get displayed on related invoices and credit notes.
 *
 * Related guides: <a href="https://stripe.com/docs/billing/taxes/tax-ids">Customer tax identification numbers</a>, <a href="https://stripe.com/docs/invoicing/connect#account-tax-ids">Account tax IDs</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|string $country Two-letter ISO code representing the country of the tax ID.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|Customer|string $customer ID of the customer.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|(object{account?: Account|string, application?: Application|string, customer?: Customer|string, type: string}&StripeObject) $owner The account or customer the tax ID belongs to.
 * @property string $type Type of the tax ID, one of <code>ad_nrt</code>, <code>ae_trn</code>, <code>al_tin</code>, <code>am_tin</code>, <code>ao_tin</code>, <code>ar_cuit</code>, <code>au_abn</code>, <code>au_arn</code>, <code>aw_tin</code>, <code>az_tin</code>, <code>ba_tin</code>, <code>bb_tin</code>, <code>bd_bin</code>, <code>bf_ifu</code>, <code>bg_uic</code>, <code>bh_vat</code>, <code>bj_ifu</code>, <code>bo_tin</code>, <code>br_cnpj</code>, <code>br_cpf</code>, <code>bs_tin</code>, <code>by_tin</code>, <code>ca_bn</code>, <code>ca_gst_hst</code>, <code>ca_pst_bc</code>, <code>ca_pst_mb</code>, <code>ca_pst_sk</code>, <code>ca_qst</code>, <code>cd_nif</code>, <code>ch_uid</code>, <code>ch_vat</code>, <code>cl_tin</code>, <code>cm_niu</code>, <code>cn_tin</code>, <code>co_nit</code>, <code>cr_tin</code>, <code>cv_nif</code>, <code>de_stn</code>, <code>do_rcn</code>, <code>ec_ruc</code>, <code>eg_tin</code>, <code>es_cif</code>, <code>et_tin</code>, <code>eu_oss_vat</code>, <code>eu_vat</code>, <code>gb_vat</code>, <code>ge_vat</code>, <code>gn_nif</code>, <code>hk_br</code>, <code>hr_oib</code>, <code>hu_tin</code>, <code>id_npwp</code>, <code>il_vat</code>, <code>in_gst</code>, <code>is_vat</code>, <code>jp_cn</code>, <code>jp_rn</code>, <code>jp_trn</code>, <code>ke_pin</code>, <code>kg_tin</code>, <code>kh_tin</code>, <code>kr_brn</code>, <code>kz_bin</code>, <code>la_tin</code>, <code>li_uid</code>, <code>li_vat</code>, <code>ma_vat</code>, <code>md_vat</code>, <code>me_pib</code>, <code>mk_vat</code>, <code>mr_nif</code>, <code>mx_rfc</code>, <code>my_frp</code>, <code>my_itn</code>, <code>my_sst</code>, <code>ng_tin</code>, <code>no_vat</code>, <code>no_voec</code>, <code>np_pan</code>, <code>nz_gst</code>, <code>om_vat</code>, <code>pe_ruc</code>, <code>ph_tin</code>, <code>ro_tin</code>, <code>rs_pib</code>, <code>ru_inn</code>, <code>ru_kpp</code>, <code>sa_vat</code>, <code>sg_gst</code>, <code>sg_uen</code>, <code>si_tin</code>, <code>sn_ninea</code>, <code>sr_fin</code>, <code>sv_nit</code>, <code>th_vat</code>, <code>tj_tin</code>, <code>tr_tin</code>, <code>tw_vat</code>, <code>tz_vat</code>, <code>ua_vat</code>, <code>ug_tin</code>, <code>us_ein</code>, <code>uy_ruc</code>, <code>uz_tin</code>, <code>uz_vat</code>, <code>ve_rif</code>, <code>vn_tin</code>, <code>za_vat</code>, <code>zm_tin</code>, or <code>zw_tin</code>. Note that some legacy tax IDs have type <code>unknown</code>
 * @property string $value Value of the tax ID.
 * @property null|(object{status: string, verified_address: null|string, verified_name: null|string}&StripeObject) $verification Tax ID verification information.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 223,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tax_id\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 27,
            'startFilePos' => 3993,
            'endTokenPos' => 27,
            'endFilePos' => 4000,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AD_NRT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AD_NRT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ad_nrt\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 36,
            'startFilePos' => 4028,
            'endTokenPos' => 36,
            'endFilePos' => 4035,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AE_TRN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AE_TRN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ae_trn\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 45,
            'startFilePos' => 4062,
            'endTokenPos' => 45,
            'endFilePos' => 4069,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AL_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AL_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'al_tin\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 54,
            'startFilePos' => 4096,
            'endTokenPos' => 54,
            'endFilePos' => 4103,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AM_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AM_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'am_tin\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 63,
            'startFilePos' => 4130,
            'endTokenPos' => 63,
            'endFilePos' => 4137,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AO_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AO_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ao_tin\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 72,
            'startFilePos' => 4164,
            'endTokenPos' => 72,
            'endFilePos' => 4171,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AR_CUIT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AR_CUIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ar_cuit\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 81,
            'startFilePos' => 4199,
            'endTokenPos' => 81,
            'endFilePos' => 4207,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_AU_ABN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AU_ABN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'au_abn\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 90,
            'startFilePos' => 4234,
            'endTokenPos' => 90,
            'endFilePos' => 4241,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AU_ARN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AU_ARN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'au_arn\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 99,
            'startFilePos' => 4268,
            'endTokenPos' => 99,
            'endFilePos' => 4275,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AW_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AW_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'aw_tin\'',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 108,
            'startFilePos' => 4302,
            'endTokenPos' => 108,
            'endFilePos' => 4309,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AZ_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_AZ_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'az_tin\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 117,
            'startFilePos' => 4336,
            'endTokenPos' => 117,
            'endFilePos' => 4343,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BA_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BA_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ba_tin\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 126,
            'startFilePos' => 4370,
            'endTokenPos' => 126,
            'endFilePos' => 4377,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BB_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BB_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bb_tin\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 135,
            'startFilePos' => 4404,
            'endTokenPos' => 135,
            'endFilePos' => 4411,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BD_BIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BD_BIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bd_bin\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 144,
            'startFilePos' => 4438,
            'endTokenPos' => 144,
            'endFilePos' => 4445,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BF_IFU' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BF_IFU',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bf_ifu\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 153,
            'startFilePos' => 4472,
            'endTokenPos' => 153,
            'endFilePos' => 4479,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BG_UIC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BG_UIC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bg_uic\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 162,
            'startFilePos' => 4506,
            'endTokenPos' => 162,
            'endFilePos' => 4513,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BH_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BH_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bh_vat\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 171,
            'startFilePos' => 4540,
            'endTokenPos' => 171,
            'endFilePos' => 4547,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BJ_IFU' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BJ_IFU',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bj_ifu\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 180,
            'startFilePos' => 4574,
            'endTokenPos' => 180,
            'endFilePos' => 4581,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BO_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BO_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bo_tin\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 189,
            'startFilePos' => 4608,
            'endTokenPos' => 189,
            'endFilePos' => 4615,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BR_CNPJ' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BR_CNPJ',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'br_cnpj\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 198,
            'startFilePos' => 4643,
            'endTokenPos' => 198,
            'endFilePos' => 4651,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_BR_CPF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BR_CPF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'br_cpf\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 207,
            'startFilePos' => 4678,
            'endTokenPos' => 207,
            'endFilePos' => 4685,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BS_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BS_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bs_tin\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 216,
            'startFilePos' => 4712,
            'endTokenPos' => 216,
            'endFilePos' => 4719,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BY_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_BY_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'by_tin\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 225,
            'startFilePos' => 4746,
            'endTokenPos' => 225,
            'endFilePos' => 4753,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CA_BN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CA_BN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ca_bn\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 234,
            'startFilePos' => 4779,
            'endTokenPos' => 234,
            'endFilePos' => 4785,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_CA_GST_HST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CA_GST_HST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ca_gst_hst\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 243,
            'startFilePos' => 4816,
            'endTokenPos' => 243,
            'endFilePos' => 4827,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_CA_PST_BC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CA_PST_BC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ca_pst_bc\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 252,
            'startFilePos' => 4857,
            'endTokenPos' => 252,
            'endFilePos' => 4867,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_CA_PST_MB' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CA_PST_MB',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ca_pst_mb\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 261,
            'startFilePos' => 4897,
            'endTokenPos' => 261,
            'endFilePos' => 4907,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_CA_PST_SK' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CA_PST_SK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ca_pst_sk\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 270,
            'startFilePos' => 4937,
            'endTokenPos' => 270,
            'endFilePos' => 4947,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_CA_QST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CA_QST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ca_qst\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 279,
            'startFilePos' => 4974,
            'endTokenPos' => 279,
            'endFilePos' => 4981,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CD_NIF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CD_NIF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cd_nif\'',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 288,
            'startFilePos' => 5008,
            'endTokenPos' => 288,
            'endFilePos' => 5015,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CH_UID' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CH_UID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ch_uid\'',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 297,
            'startFilePos' => 5042,
            'endTokenPos' => 297,
            'endFilePos' => 5049,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CH_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CH_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ch_vat\'',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 306,
            'startFilePos' => 5076,
            'endTokenPos' => 306,
            'endFilePos' => 5083,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CL_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CL_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cl_tin\'',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 315,
            'startFilePos' => 5110,
            'endTokenPos' => 315,
            'endFilePos' => 5117,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CM_NIU' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CM_NIU',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cm_niu\'',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 324,
            'startFilePos' => 5144,
            'endTokenPos' => 324,
            'endFilePos' => 5151,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CN_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CN_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cn_tin\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 333,
            'startFilePos' => 5178,
            'endTokenPos' => 333,
            'endFilePos' => 5185,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CO_NIT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CO_NIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'co_nit\'',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 342,
            'startFilePos' => 5212,
            'endTokenPos' => 342,
            'endFilePos' => 5219,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CR_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CR_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cr_tin\'',
          'attributes' => 
          array (
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 351,
            'startFilePos' => 5246,
            'endTokenPos' => 351,
            'endFilePos' => 5253,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CV_NIF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_CV_NIF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cv_nif\'',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 360,
            'startFilePos' => 5280,
            'endTokenPos' => 360,
            'endFilePos' => 5287,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_DE_STN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_DE_STN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'de_stn\'',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 369,
            'startFilePos' => 5314,
            'endTokenPos' => 369,
            'endFilePos' => 5321,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_DO_RCN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_DO_RCN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'do_rcn\'',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 378,
            'startFilePos' => 5348,
            'endTokenPos' => 378,
            'endFilePos' => 5355,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_EC_RUC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_EC_RUC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ec_ruc\'',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 67,
            'startTokenPos' => 387,
            'startFilePos' => 5382,
            'endTokenPos' => 387,
            'endFilePos' => 5389,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_EG_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_EG_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'eg_tin\'',
          'attributes' => 
          array (
            'startLine' => 68,
            'endLine' => 68,
            'startTokenPos' => 396,
            'startFilePos' => 5416,
            'endTokenPos' => 396,
            'endFilePos' => 5423,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ES_CIF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ES_CIF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'es_cif\'',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 405,
            'startFilePos' => 5450,
            'endTokenPos' => 405,
            'endFilePos' => 5457,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ET_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ET_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'et_tin\'',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 414,
            'startFilePos' => 5484,
            'endTokenPos' => 414,
            'endFilePos' => 5491,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_EU_OSS_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_EU_OSS_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'eu_oss_vat\'',
          'attributes' => 
          array (
            'startLine' => 71,
            'endLine' => 71,
            'startTokenPos' => 423,
            'startFilePos' => 5522,
            'endTokenPos' => 423,
            'endFilePos' => 5533,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 71,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_EU_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_EU_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'eu_vat\'',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 432,
            'startFilePos' => 5560,
            'endTokenPos' => 432,
            'endFilePos' => 5567,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_GB_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_GB_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'gb_vat\'',
          'attributes' => 
          array (
            'startLine' => 73,
            'endLine' => 73,
            'startTokenPos' => 441,
            'startFilePos' => 5594,
            'endTokenPos' => 441,
            'endFilePos' => 5601,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 73,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_GE_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_GE_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ge_vat\'',
          'attributes' => 
          array (
            'startLine' => 74,
            'endLine' => 74,
            'startTokenPos' => 450,
            'startFilePos' => 5628,
            'endTokenPos' => 450,
            'endFilePos' => 5635,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 74,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_GN_NIF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_GN_NIF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'gn_nif\'',
          'attributes' => 
          array (
            'startLine' => 75,
            'endLine' => 75,
            'startTokenPos' => 459,
            'startFilePos' => 5662,
            'endTokenPos' => 459,
            'endFilePos' => 5669,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_HK_BR' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_HK_BR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'hk_br\'',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 76,
            'startTokenPos' => 468,
            'startFilePos' => 5695,
            'endTokenPos' => 468,
            'endFilePos' => 5701,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_HR_OIB' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_HR_OIB',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'hr_oib\'',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 477,
            'startFilePos' => 5728,
            'endTokenPos' => 477,
            'endFilePos' => 5735,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_HU_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_HU_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'hu_tin\'',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 78,
            'startTokenPos' => 486,
            'startFilePos' => 5762,
            'endTokenPos' => 486,
            'endFilePos' => 5769,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ID_NPWP' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ID_NPWP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'id_npwp\'',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 495,
            'startFilePos' => 5797,
            'endTokenPos' => 495,
            'endFilePos' => 5805,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_IL_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_IL_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'il_vat\'',
          'attributes' => 
          array (
            'startLine' => 80,
            'endLine' => 80,
            'startTokenPos' => 504,
            'startFilePos' => 5832,
            'endTokenPos' => 504,
            'endFilePos' => 5839,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 80,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_IN_GST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_IN_GST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'in_gst\'',
          'attributes' => 
          array (
            'startLine' => 81,
            'endLine' => 81,
            'startTokenPos' => 513,
            'startFilePos' => 5866,
            'endTokenPos' => 513,
            'endFilePos' => 5873,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_IS_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_IS_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'is_vat\'',
          'attributes' => 
          array (
            'startLine' => 82,
            'endLine' => 82,
            'startTokenPos' => 522,
            'startFilePos' => 5900,
            'endTokenPos' => 522,
            'endFilePos' => 5907,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_JP_CN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_JP_CN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'jp_cn\'',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 531,
            'startFilePos' => 5933,
            'endTokenPos' => 531,
            'endFilePos' => 5939,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_JP_RN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_JP_RN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'jp_rn\'',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 540,
            'startFilePos' => 5965,
            'endTokenPos' => 540,
            'endFilePos' => 5971,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_JP_TRN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_JP_TRN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'jp_trn\'',
          'attributes' => 
          array (
            'startLine' => 85,
            'endLine' => 85,
            'startTokenPos' => 549,
            'startFilePos' => 5998,
            'endTokenPos' => 549,
            'endFilePos' => 6005,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_KE_PIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_KE_PIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ke_pin\'',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 558,
            'startFilePos' => 6032,
            'endTokenPos' => 558,
            'endFilePos' => 6039,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_KG_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_KG_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'kg_tin\'',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 87,
            'startTokenPos' => 567,
            'startFilePos' => 6066,
            'endTokenPos' => 567,
            'endFilePos' => 6073,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_KH_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_KH_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'kh_tin\'',
          'attributes' => 
          array (
            'startLine' => 88,
            'endLine' => 88,
            'startTokenPos' => 576,
            'startFilePos' => 6100,
            'endTokenPos' => 576,
            'endFilePos' => 6107,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_KR_BRN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_KR_BRN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'kr_brn\'',
          'attributes' => 
          array (
            'startLine' => 89,
            'endLine' => 89,
            'startTokenPos' => 585,
            'startFilePos' => 6134,
            'endTokenPos' => 585,
            'endFilePos' => 6141,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_KZ_BIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_KZ_BIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'kz_bin\'',
          'attributes' => 
          array (
            'startLine' => 90,
            'endLine' => 90,
            'startTokenPos' => 594,
            'startFilePos' => 6168,
            'endTokenPos' => 594,
            'endFilePos' => 6175,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_LA_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_LA_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'la_tin\'',
          'attributes' => 
          array (
            'startLine' => 91,
            'endLine' => 91,
            'startTokenPos' => 603,
            'startFilePos' => 6202,
            'endTokenPos' => 603,
            'endFilePos' => 6209,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_LI_UID' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_LI_UID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'li_uid\'',
          'attributes' => 
          array (
            'startLine' => 92,
            'endLine' => 92,
            'startTokenPos' => 612,
            'startFilePos' => 6236,
            'endTokenPos' => 612,
            'endFilePos' => 6243,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_LI_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_LI_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'li_vat\'',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 621,
            'startFilePos' => 6270,
            'endTokenPos' => 621,
            'endFilePos' => 6277,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MA_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MA_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ma_vat\'',
          'attributes' => 
          array (
            'startLine' => 94,
            'endLine' => 94,
            'startTokenPos' => 630,
            'startFilePos' => 6304,
            'endTokenPos' => 630,
            'endFilePos' => 6311,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MD_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MD_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'md_vat\'',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 95,
            'startTokenPos' => 639,
            'startFilePos' => 6338,
            'endTokenPos' => 639,
            'endFilePos' => 6345,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ME_PIB' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ME_PIB',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'me_pib\'',
          'attributes' => 
          array (
            'startLine' => 96,
            'endLine' => 96,
            'startTokenPos' => 648,
            'startFilePos' => 6372,
            'endTokenPos' => 648,
            'endFilePos' => 6379,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MK_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MK_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mk_vat\'',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 657,
            'startFilePos' => 6406,
            'endTokenPos' => 657,
            'endFilePos' => 6413,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MR_NIF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MR_NIF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mr_nif\'',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 666,
            'startFilePos' => 6440,
            'endTokenPos' => 666,
            'endFilePos' => 6447,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MX_RFC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MX_RFC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mx_rfc\'',
          'attributes' => 
          array (
            'startLine' => 99,
            'endLine' => 99,
            'startTokenPos' => 675,
            'startFilePos' => 6474,
            'endTokenPos' => 675,
            'endFilePos' => 6481,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MY_FRP' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MY_FRP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'my_frp\'',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 684,
            'startFilePos' => 6508,
            'endTokenPos' => 684,
            'endFilePos' => 6515,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MY_ITN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MY_ITN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'my_itn\'',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 693,
            'startFilePos' => 6542,
            'endTokenPos' => 693,
            'endFilePos' => 6549,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MY_SST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_MY_SST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'my_sst\'',
          'attributes' => 
          array (
            'startLine' => 102,
            'endLine' => 102,
            'startTokenPos' => 702,
            'startFilePos' => 6576,
            'endTokenPos' => 702,
            'endFilePos' => 6583,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_NG_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_NG_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ng_tin\'',
          'attributes' => 
          array (
            'startLine' => 103,
            'endLine' => 103,
            'startTokenPos' => 711,
            'startFilePos' => 6610,
            'endTokenPos' => 711,
            'endFilePos' => 6617,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 103,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_NO_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_NO_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'no_vat\'',
          'attributes' => 
          array (
            'startLine' => 104,
            'endLine' => 104,
            'startTokenPos' => 720,
            'startFilePos' => 6644,
            'endTokenPos' => 720,
            'endFilePos' => 6651,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_NO_VOEC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_NO_VOEC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'no_voec\'',
          'attributes' => 
          array (
            'startLine' => 105,
            'endLine' => 105,
            'startTokenPos' => 729,
            'startFilePos' => 6679,
            'endTokenPos' => 729,
            'endFilePos' => 6687,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_NP_PAN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_NP_PAN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'np_pan\'',
          'attributes' => 
          array (
            'startLine' => 106,
            'endLine' => 106,
            'startTokenPos' => 738,
            'startFilePos' => 6714,
            'endTokenPos' => 738,
            'endFilePos' => 6721,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_NZ_GST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_NZ_GST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'nz_gst\'',
          'attributes' => 
          array (
            'startLine' => 107,
            'endLine' => 107,
            'startTokenPos' => 747,
            'startFilePos' => 6748,
            'endTokenPos' => 747,
            'endFilePos' => 6755,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 107,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_OM_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_OM_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'om_vat\'',
          'attributes' => 
          array (
            'startLine' => 108,
            'endLine' => 108,
            'startTokenPos' => 756,
            'startFilePos' => 6782,
            'endTokenPos' => 756,
            'endFilePos' => 6789,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_PE_RUC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_PE_RUC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pe_ruc\'',
          'attributes' => 
          array (
            'startLine' => 109,
            'endLine' => 109,
            'startTokenPos' => 765,
            'startFilePos' => 6816,
            'endTokenPos' => 765,
            'endFilePos' => 6823,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_PH_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_PH_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ph_tin\'',
          'attributes' => 
          array (
            'startLine' => 110,
            'endLine' => 110,
            'startTokenPos' => 774,
            'startFilePos' => 6850,
            'endTokenPos' => 774,
            'endFilePos' => 6857,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 110,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_RO_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_RO_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ro_tin\'',
          'attributes' => 
          array (
            'startLine' => 111,
            'endLine' => 111,
            'startTokenPos' => 783,
            'startFilePos' => 6884,
            'endTokenPos' => 783,
            'endFilePos' => 6891,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_RS_PIB' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_RS_PIB',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'rs_pib\'',
          'attributes' => 
          array (
            'startLine' => 112,
            'endLine' => 112,
            'startTokenPos' => 792,
            'startFilePos' => 6918,
            'endTokenPos' => 792,
            'endFilePos' => 6925,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_RU_INN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_RU_INN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ru_inn\'',
          'attributes' => 
          array (
            'startLine' => 113,
            'endLine' => 113,
            'startTokenPos' => 801,
            'startFilePos' => 6952,
            'endTokenPos' => 801,
            'endFilePos' => 6959,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_RU_KPP' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_RU_KPP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ru_kpp\'',
          'attributes' => 
          array (
            'startLine' => 114,
            'endLine' => 114,
            'startTokenPos' => 810,
            'startFilePos' => 6986,
            'endTokenPos' => 810,
            'endFilePos' => 6993,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 114,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SA_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SA_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sa_vat\'',
          'attributes' => 
          array (
            'startLine' => 115,
            'endLine' => 115,
            'startTokenPos' => 819,
            'startFilePos' => 7020,
            'endTokenPos' => 819,
            'endFilePos' => 7027,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 115,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SG_GST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SG_GST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sg_gst\'',
          'attributes' => 
          array (
            'startLine' => 116,
            'endLine' => 116,
            'startTokenPos' => 828,
            'startFilePos' => 7054,
            'endTokenPos' => 828,
            'endFilePos' => 7061,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 116,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SG_UEN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SG_UEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sg_uen\'',
          'attributes' => 
          array (
            'startLine' => 117,
            'endLine' => 117,
            'startTokenPos' => 837,
            'startFilePos' => 7088,
            'endTokenPos' => 837,
            'endFilePos' => 7095,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 117,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SI_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SI_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'si_tin\'',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 118,
            'startTokenPos' => 846,
            'startFilePos' => 7122,
            'endTokenPos' => 846,
            'endFilePos' => 7129,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SN_NINEA' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SN_NINEA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sn_ninea\'',
          'attributes' => 
          array (
            'startLine' => 119,
            'endLine' => 119,
            'startTokenPos' => 855,
            'startFilePos' => 7158,
            'endTokenPos' => 855,
            'endFilePos' => 7167,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'TYPE_SR_FIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SR_FIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sr_fin\'',
          'attributes' => 
          array (
            'startLine' => 120,
            'endLine' => 120,
            'startTokenPos' => 864,
            'startFilePos' => 7194,
            'endTokenPos' => 864,
            'endFilePos' => 7201,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 120,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SV_NIT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_SV_NIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sv_nit\'',
          'attributes' => 
          array (
            'startLine' => 121,
            'endLine' => 121,
            'startTokenPos' => 873,
            'startFilePos' => 7228,
            'endTokenPos' => 873,
            'endFilePos' => 7235,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 121,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_TH_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_TH_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'th_vat\'',
          'attributes' => 
          array (
            'startLine' => 122,
            'endLine' => 122,
            'startTokenPos' => 882,
            'startFilePos' => 7262,
            'endTokenPos' => 882,
            'endFilePos' => 7269,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 122,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_TJ_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_TJ_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tj_tin\'',
          'attributes' => 
          array (
            'startLine' => 123,
            'endLine' => 123,
            'startTokenPos' => 891,
            'startFilePos' => 7296,
            'endTokenPos' => 891,
            'endFilePos' => 7303,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 123,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_TR_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_TR_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tr_tin\'',
          'attributes' => 
          array (
            'startLine' => 124,
            'endLine' => 124,
            'startTokenPos' => 900,
            'startFilePos' => 7330,
            'endTokenPos' => 900,
            'endFilePos' => 7337,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 124,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_TW_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_TW_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tw_vat\'',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 909,
            'startFilePos' => 7364,
            'endTokenPos' => 909,
            'endFilePos' => 7371,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_TZ_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_TZ_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tz_vat\'',
          'attributes' => 
          array (
            'startLine' => 126,
            'endLine' => 126,
            'startTokenPos' => 918,
            'startFilePos' => 7398,
            'endTokenPos' => 918,
            'endFilePos' => 7405,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_UA_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_UA_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ua_vat\'',
          'attributes' => 
          array (
            'startLine' => 127,
            'endLine' => 127,
            'startTokenPos' => 927,
            'startFilePos' => 7432,
            'endTokenPos' => 927,
            'endFilePos' => 7439,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 127,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_UG_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_UG_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ug_tin\'',
          'attributes' => 
          array (
            'startLine' => 128,
            'endLine' => 128,
            'startTokenPos' => 936,
            'startFilePos' => 7466,
            'endTokenPos' => 936,
            'endFilePos' => 7473,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 128,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_UNKNOWN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_UNKNOWN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unknown\'',
          'attributes' => 
          array (
            'startLine' => 129,
            'endLine' => 129,
            'startTokenPos' => 945,
            'startFilePos' => 7501,
            'endTokenPos' => 945,
            'endFilePos' => 7509,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_US_EIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_US_EIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'us_ein\'',
          'attributes' => 
          array (
            'startLine' => 130,
            'endLine' => 130,
            'startTokenPos' => 954,
            'startFilePos' => 7536,
            'endTokenPos' => 954,
            'endFilePos' => 7543,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 130,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_UY_RUC' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_UY_RUC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'uy_ruc\'',
          'attributes' => 
          array (
            'startLine' => 131,
            'endLine' => 131,
            'startTokenPos' => 963,
            'startFilePos' => 7570,
            'endTokenPos' => 963,
            'endFilePos' => 7577,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 131,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_UZ_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_UZ_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'uz_tin\'',
          'attributes' => 
          array (
            'startLine' => 132,
            'endLine' => 132,
            'startTokenPos' => 972,
            'startFilePos' => 7604,
            'endTokenPos' => 972,
            'endFilePos' => 7611,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 132,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_UZ_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_UZ_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'uz_vat\'',
          'attributes' => 
          array (
            'startLine' => 133,
            'endLine' => 133,
            'startTokenPos' => 981,
            'startFilePos' => 7638,
            'endTokenPos' => 981,
            'endFilePos' => 7645,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 133,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_VE_RIF' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_VE_RIF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ve_rif\'',
          'attributes' => 
          array (
            'startLine' => 134,
            'endLine' => 134,
            'startTokenPos' => 990,
            'startFilePos' => 7672,
            'endTokenPos' => 990,
            'endFilePos' => 7679,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 134,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_VN_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_VN_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'vn_tin\'',
          'attributes' => 
          array (
            'startLine' => 135,
            'endLine' => 135,
            'startTokenPos' => 999,
            'startFilePos' => 7706,
            'endTokenPos' => 999,
            'endFilePos' => 7713,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 135,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ZA_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ZA_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'za_vat\'',
          'attributes' => 
          array (
            'startLine' => 136,
            'endLine' => 136,
            'startTokenPos' => 1008,
            'startFilePos' => 7740,
            'endTokenPos' => 1008,
            'endFilePos' => 7747,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 136,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ZM_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ZM_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'zm_tin\'',
          'attributes' => 
          array (
            'startLine' => 137,
            'endLine' => 137,
            'startTokenPos' => 1017,
            'startFilePos' => 7774,
            'endTokenPos' => 1017,
            'endFilePos' => 7781,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 137,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ZW_TIN' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'TYPE_ZW_TIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'zw_tin\'',
          'attributes' => 
          array (
            'startLine' => 138,
            'endLine' => 138,
            'startTokenPos' => 1026,
            'startFilePos' => 7808,
            'endTokenPos' => 1026,
            'endFilePos' => 7815,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 138,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'VERIFICATION_STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'VERIFICATION_STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 219,
            'endLine' => 219,
            'startTokenPos' => 1350,
            'startFilePos' => 10382,
            'endTokenPos' => 1350,
            'endFilePos' => 10390,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 219,
        'endLine' => 219,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'VERIFICATION_STATUS_UNAVAILABLE' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'VERIFICATION_STATUS_UNAVAILABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unavailable\'',
          'attributes' => 
          array (
            'startLine' => 220,
            'endLine' => 220,
            'startTokenPos' => 1359,
            'startFilePos' => 10437,
            'endTokenPos' => 1359,
            'endFilePos' => 10449,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 220,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
      'VERIFICATION_STATUS_UNVERIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'VERIFICATION_STATUS_UNVERIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unverified\'',
          'attributes' => 
          array (
            'startLine' => 221,
            'endLine' => 221,
            'startTokenPos' => 1368,
            'startFilePos' => 10495,
            'endTokenPos' => 1368,
            'endFilePos' => 10506,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 221,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'VERIFICATION_STATUS_VERIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'name' => 'VERIFICATION_STATUS_VERIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'verified\'',
          'attributes' => 
          array (
            'startLine' => 222,
            'endLine' => 222,
            'startTokenPos' => 1377,
            'startFilePos' => 10550,
            'endTokenPos' => 1377,
            'endFilePos' => 10559,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 222,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 52,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 150,
                'endLine' => 150,
                'startTokenPos' => 1043,
                'startFilePos' => 8263,
                'endTokenPos' => 1043,
                'endFilePos' => 8266,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 150,
            'endLine' => 150,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 150,
                'endLine' => 150,
                'startTokenPos' => 1050,
                'startFilePos' => 8280,
                'endTokenPos' => 1050,
                'endFilePos' => 8283,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 150,
            'endLine' => 150,
            'startColumn' => 51,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a new account or customer <code>tax_id</code> object.
 *
 * @param null|array{expand?: string[], owner?: array{account?: string, customer?: string, type: string}, type: string, value: string} $params
 * @param null|array|string $options
 *
 * @return TaxId the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 150,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'currentClassName' => 'Stripe\\TaxId',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 172,
                'endLine' => 172,
                'startTokenPos' => 1145,
                'startFilePos' => 8928,
                'endTokenPos' => 1145,
                'endFilePos' => 8931,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 172,
                'endLine' => 172,
                'startTokenPos' => 1152,
                'startFilePos' => 8942,
                'endTokenPos' => 1152,
                'endFilePos' => 8945,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Deletes an existing account or customer <code>tax_id</code> object.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return TaxId the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 172,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'currentClassName' => 'Stripe\\TaxId',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 193,
                'endLine' => 193,
                'startTokenPos' => 1235,
                'startFilePos' => 9633,
                'endTokenPos' => 1235,
                'endFilePos' => 9636,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 193,
                'endLine' => 193,
                'startTokenPos' => 1242,
                'startFilePos' => 9647,
                'endTokenPos' => 1242,
                'endFilePos' => 9650,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 48,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a list of tax IDs.
 *
 * @param null|array{ending_before?: string, expand?: string[], limit?: int, owner?: array{account?: string, customer?: string, type: string}, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<TaxId> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 193,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'currentClassName' => 'Stripe\\TaxId',
        'aliasName' => NULL,
      ),
      'retrieve' => 
      array (
        'name' => 'retrieve',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 210,
            'endLine' => 210,
            'startColumn' => 37,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 210,
                'endLine' => 210,
                'startTokenPos' => 1298,
                'startFilePos' => 10171,
                'endTokenPos' => 1298,
                'endFilePos' => 10174,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 210,
            'endLine' => 210,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves an account or customer <code>tax_id</code> object.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return TaxId
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 210,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxId',
        'implementingClassName' => 'Stripe\\TaxId',
        'currentClassName' => 'Stripe\\TaxId',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));