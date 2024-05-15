<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class MigrateOldVersion213 extends Migration
{

    public function up()
    {

        $this->updateSessionsTable();
        $this->updateCreatedAtFields();
        $this->updateCountiesTable();
        $this->updateTranslationKeysTable();
        $this->updateRelFields();
        $this->updateUsersTable();
        $this->updateMediaTable();
        $this->updateCategoriesTable();
        $this->updateCustomFieldsTable();
        $this->updateContentTable();
        $this->updateCommentsTable();
        $this->updateModulesTable();
        $this->updateCouponsTable();

    }

    private function updateTranslationKeysTable()
    {
        if (!Schema::hasTable('translation_keys')) {
            Schema::create('translation_keys', function ($table) {
                $table->charset = 'utf8';
                $table->collation = 'utf8_bin';

                $table->bigIncrements('id');
                $table->string('translation_namespace')->nullable();
                $table->string('translation_group');
                $table->index('translation_group');
                $table->text('translation_key');
            });
        }

    }

    private function updateSessionsTable()
    {

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function ($table) {
                $table->string('id')->unique();
                $table->longText('payload');
                $table->integer('last_activity');
                $table->integer('user_id')->nullable();
                $table->string('ip_address', 255)->nullable();
                $table->text('user_agent')->nullable();
            });
        }
    }


    private function updateCommentsTable()
    {
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function ($table) {
                $table->increments('id');
                $table->integer('reply_to_comment_id')->nullable();
                $table->string('rel_type')->nullable();
                $table->string('rel_id')->nullable();
                $table->text('comment_name')->nullable();
                $table->text('comment_email')->nullable();
                $table->text('comment_website')->nullable();
                $table->text('comment_body')->nullable();
                $table->text('comment_subject')->nullable();
                $table->text('from_url')->nullable();
                $table->integer('is_moderated')->nullable();
                $table->integer('is_spam')->nullable();
                $table->integer('is_new')->nullable();
                $table->integer('for_newsletter')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('edited_by')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('session_id')->nullable();
                $table->string('user_ip')->nullable();
            });
        } else {
            Schema::table('comments', function ($table) {
                if (!Schema::hasColumn('comments', 'reply_to_comment_id')) {
                    $table->integer('reply_to_comment_id')->nullable();
                }

                if (!Schema::hasColumn('comments', 'is_spam')) {
                    $table->integer('is_spam')->nullable();
                }

                if (!Schema::hasColumn('comments', 'is_new')) {
                    $table->integer('is_new')->nullable();
                }

                if (!Schema::hasColumn('comments', 'for_newsletter')) {
                    $table->integer('for_newsletter')->nullable();
                }

                if (!Schema::hasColumn('comments', 'deleted_at')) {
                    $table->timestamp('deleted_at')->nullable();
                }


            });
        }
    }


    private function updateModulesTable()
    {
        if (Schema::hasTable('modules')) {
            $module = DB::table('modules')->where('module', 'spacer')->first();
            if ($module) {
                DB::table('modules')->where('module', 'spacer')->update(['as_element' => 0]);
            }
        }
    }

    private function updateCountiesTable()
    {
        if (Schema::hasTable('countries')) {

            if (!Schema::hasColumn('countries', 'phonecode')) {
                Schema::table('countries', function (Blueprint $table) {
                    $table->integer('phonecode')->nullable();
                });
            }
            if (!Schema::hasColumn('countries', 'name')) {
                Schema::table('countries', function (Blueprint $table) {
                    $table->string('name')->nullable();
                });
            }
            if (!Schema::hasColumn('countries', 'code')) {
                Schema::table('countries', function (Blueprint $table) {
                    $table->string('code')->nullable();
                });
            }

        }
    }


    private function updateContentTable()
    {
        if (Schema::hasTable('content')) {
            $fiedlsToConvert = [
                'is_home',
                'is_shop',
                'is_active',
                'is_deleted',
                'require_login',
            ];
            foreach ($fiedlsToConvert as $field) {
                $fields = DB::table('content')
                    ->whereNotNull($field)
                    ->where(function ($query) use ($field) {
                        $query->where($field, 'y')->orWhere($field, 'n');
                    })
                    ->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        $itemArr = (array)$item;
                        $setval = 1;
                        if (isset($itemArr[$field]) and $itemArr[$field] === 'n') {
                            DB::table('content')->where('id', $item->id)->update([$field => 0]);
                        } else if (isset($itemArr[$field]) and $itemArr[$field] === 'y') {
                            DB::table('content')->where('id', $item->id)->update([$field => 1]);
                        }
                    }
                }
            }
        }
    }


    function updateCategoriesTable()
    {
        if (Schema::hasTable('categories')) {
            $fiedlsToConvert = [
                'is_deleted',
            ];
            foreach ($fiedlsToConvert as $field) {
                $fields = DB::table('categories')->whereNotNull($field)
                    ->where(function ($query) use ($field) {
                        $query->where($field, 'y')->orWhere($field, 'n');
                    })
                    ->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        $itemArr = (array)$item;
                        if (isset($itemArr[$field]) and $itemArr[$field] === 'n') {
                            DB::table('categories')->where('id', $item->id)->update([$field => 0]);
                        } else if (isset($itemArr[$field]) and $itemArr[$field] === 'y') {
                            DB::table('categories')->where('id', $item->id)->update([$field => 1]);
                        }
                    }
                }
            }


            //ensure all categories have slug
            $categories = DB::table('categories')
                ->whereNull('url')
                ->where('rel_type', 'content')
                ->get();
            if ($categories) {
                foreach ($categories as $category) {
                    $categoryArr = (array)$category;
                    if (isset($categoryArr['id']) and isset($categoryArr['title'])) {
                        $slug = str_slug($categoryArr['title']);
                        $check = DB::table('categories')->where('id', $categoryArr['id'])->where('url', $slug)->first();
                        if (!$check) {
                            DB::table('categories')->where('id', $categoryArr['id'])->update(['url' => $slug]);
                        } else {
                            $slug = $slug . '-' . $categoryArr['id'];
                            DB::table('categories')->where('id', $categoryArr['id'])->update(['url' => $slug]);
                        }
                    }
                }
            }
        }
    }


    function updateUsersTable()
    {
        if (Schema::hasTable('users')) {
            $fiedlsToConvert = [
                'is_admin',
                'is_active',
                'is_public',
                'is_verified',
                'basic_mode',
            ];
            foreach ($fiedlsToConvert as $field) {

                $checkColumn = Schema::hasColumn('users', $field);
                if (!$checkColumn) {
                    continue;
                }


                $fields = DB::table('users')->whereNotNull($field)
                    ->where(function ($query) use ($field) {
                        $query->where($field, 'y')->orWhere($field, 'n');
                    })
                    ->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        $itemArr = (array)$item;
                        if (isset($itemArr[$field]) and $itemArr[$field] === 'n') {
                            DB::table('users')->where('id', $item->id)->update([$field => 0]);
                        } else if (isset($itemArr[$field]) and $itemArr[$field] === 'y') {
                            DB::table('users')->where('id', $item->id)->update([$field => 1]);
                        }
                    }
                }
            }
        }
    }

    private function updateMediaTable()
    {
        if (Schema::hasTable('media')) {
            $checkRel = Schema::hasColumn('media', 'rel');
            $checkHasNoRelType = Schema::hasColumn('media', 'rel_type');
            if ($checkRel) {
                if (!$checkHasNoRelType) {
                    Schema::table('media', function (Blueprint $table) {
                        $table->renameColumn('rel', 'rel_type');
                    });
                } else {
                    $fields = DB::table('media')->whereNull('rel_type')->whereNotNull('rel')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table('media')->where('id', $item->id)->update(['rel_type' => $item->rel]);
                        }
                    }
                }
            }
        }
    }


    private function updateRelFields()
    {
        $tableNames = [
            'comments',
            'media',
            'content_fields',
            'content_data',
            'custom_fields',
            'categories',
            'categories_items',
        ];

        foreach ($tableNames as $table) {
            if (Schema::hasTable($table)) {
                $checkRel = Schema::hasColumn($table, 'rel');
                $checkHasNoRelType = Schema::hasColumn($table, 'rel_type');
                if ($checkRel) {
                    if (!$checkHasNoRelType) {
                        Schema::table($table, function (Blueprint $table) {
                            $table->renameColumn('rel', 'rel_type');
                        });
                    } else {
                        $fields = DB::table($table)->whereNull('rel_type')->whereNotNull('rel')->get();
                        if ($fields) {
                            foreach ($fields as $item) {
                                DB::table($table)->where('id', $item->id)->update(['rel_type' => $item->rel]);
                            }
                        }

                        //remove the rel column
                        if (Schema::hasColumn($table, 'rel')) {
                            Schema::table($table, function (Blueprint $table) {
                                $table->dropColumn('rel');
                            });
                        }
                    }
                }
            }
        }
    }

    private function updateCustomFieldsTable()
    {
        if (Schema::hasTable('custom_fields')) {
            $checkRel = Schema::hasColumn('custom_fields', 'rel');
            $checkHasNoRelType = Schema::hasColumn('custom_fields', 'rel_type');
            if ($checkRel) {
                if (!$checkHasNoRelType) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->renameColumn('rel', 'rel_type');
                    });
                } else {
                    $fields = DB::table('custom_fields')->whereNull('rel_type')->whereNotNull('rel')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table('custom_fields')->where('id', $item->id)->update(['rel_type' => $item->rel]);
                        }
                    }
                }

                //remove the rel column
                if (Schema::hasColumn('custom_fields', 'rel')) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->dropColumn('rel');
                    });
                }
            }


            $checkCustomFieldType = Schema::hasColumn('custom_fields', 'custom_field_type');
            $checkCustomFieldName = Schema::hasColumn('custom_fields', 'custom_field_name');
            $checkCustomFieldNameValues = Schema::hasColumn('custom_fields', 'custom_field_values_plain');


            $checkCustomFieldNamePlain = Schema::hasColumn('custom_fields', 'custom_field_name_plain');
            $checkCustomFieldNameNamePlain = Schema::hasColumn('custom_fields', 'custom_field_name_plain');

            if (
                $checkCustomFieldNameValues
                and $checkCustomFieldNamePlain
            ) {
                //migrate those felds to the table custom fields values
                $allOldFields = DB::table('custom_fields')
                    ->whereNotNull('custom_field_values_plain')
                    ->get();
                if ($allOldFields) {
                    foreach ($allOldFields as $oldField) {
                        $values = $oldField->custom_field_values_plain;
                        if ($values) {
                            $values = str_replace(',,', '', $values);
                        }
                        $check = DB::table('custom_fields_values')
                            ->where('custom_field_id', $oldField->id)
                            ->where('value', $values)
                            ->first();
                        if (!$check) {
                            DB::table('custom_fields_values')->insert([
                                'custom_field_id' => $oldField->id,
                                'value' => $values,
                            ]);
                        }
                    }

                }
                if (Schema::hasColumn('custom_fields', 'custom_field_values_plain')) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->dropColumn('custom_field_values_plain');
                    });
                }
//                if (Schema::hasColumn('custom_fields', 'custom_field_name_plain')) {
//                    Schema::table('custom_fields', function (Blueprint $table) {
//                        $table->dropColumn('custom_field_name_plain');
//                    });
//                }

            }


            $checkCustomFieldNameValuesLegacyIsActive = Schema::hasColumn('custom_fields', 'custom_field_is_active');
            $checkCustomFieldNameValuesIsActive = Schema::hasColumn('custom_fields', 'is_active');
            if ($checkCustomFieldNameValuesLegacyIsActive and $checkCustomFieldNameValuesIsActive) {
                $allOldFields = DB::table('custom_fields')
                    ->whereNotNull('custom_field_is_active')
                    ->get();
                if ($allOldFields) {
                    foreach ($allOldFields as $oldField) {
                        $values = $oldField->custom_field_is_active;
                        if ($values === 'y') {
                            $values = 1;
                        } else {
                            $values = 0;
                        }
                        DB::table('custom_fields')->where('id', $oldField->id)->update(['is_active' => $values]);
                    }
                }
                if (Schema::hasColumn('custom_fields', 'custom_field_is_active')) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->dropColumn('custom_field_is_active');
                    });
                }
            }

            $checkCustomFieldNameLegacy = Schema::hasColumn('custom_fields', 'custom_field_type');
            $checkCustomFieldName = Schema::hasColumn('custom_fields', 'type');
            if ($checkCustomFieldNameLegacy and $checkCustomFieldName) {
                $allOldFields = DB::table('custom_fields')
                    ->whereNotNull('custom_field_type')
                    ->get();

                if ($allOldFields) {
                    foreach ($allOldFields as $oldField) {
                        $values = $oldField->custom_field_type;
                        DB::table('custom_fields')->where('id', $oldField->id)->update(['type' => $values]);
                    }
                }
                if (Schema::hasColumn('custom_fields', 'custom_field_type')) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->dropColumn('custom_field_type');
                    });
                }
            }
            $checkCustomFieldNameLegacy = Schema::hasColumn('custom_fields', 'custom_field_name');
            $checkCustomFieldName = Schema::hasColumn('custom_fields', 'name');
            if ($checkCustomFieldNameLegacy and $checkCustomFieldName) {
                $allOldFields = DB::table('custom_fields')
                    ->whereNotNull('custom_field_name')
                    ->get();

                if ($allOldFields) {
                    foreach ($allOldFields as $oldField) {
                        $values = $oldField->custom_field_name;
                        $nameKey = str_slug($values);
                        DB::table('custom_fields')->where('id', $oldField->id)->update([
                                'name' => $values,
                                'name_key' => $nameKey,
                            ]
                        );
                    }
                }
                if (Schema::hasColumn('custom_fields', 'custom_field_name')) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->dropColumn('custom_field_name');
                    });
                }
            }


        }
    }

    private function updateCreatedAtFields()
    {
        $tables = [
            'content',
            'comments',
            'custom_fields',
            'categories',
            'options',
            'media',
        ];
        foreach ($tables as $table) {

            if (!Schema::hasTable($table)) {
                continue;
            }
            if (!Schema::hasColumn($table, 'created_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }

            if (Schema::hasColumn($table, 'created_at')
                and Schema::hasColumn($table, 'created_on')) {
                //move all to created_at
                $all = DB::table($table)->whereNotNull('created_on')->get();
                if ($all) {
                    foreach ($all as $item) {
                        DB::table($table)->where('id', $item->id)->update(['created_at' => $item->created_on]);
                    }
                }
                //remove created_on column
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('created_on');
                });
            }

            if (!Schema::hasColumn($table, 'updated_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }


            if (Schema::hasColumn($table, 'updated_at')
                and Schema::hasColumn($table, 'updated_on')) {
                //move all to created_at
                $all = DB::table($table)->whereNotNull('updated_on')->get();
                if ($all) {
                    foreach ($all as $item) {
                        DB::table($table)->where('id', $item->id)->update(['updated_at' => $item->updated_at]);
                    }
                }
                //remove created_on column
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('updated_on');
                });

            }

        }

    }

    public function updateCouponsTable()
    {
        if (!Schema::hasTable('cart_coupons')) {
            Schema::create('cart_coupons', function (Blueprint $table) {
                $table->increments('id');
                $table->string('coupon_name')->nullable();
                $table->string('coupon_code')->nullable();;
                $table->string('discount_type')->nullable();;
                $table->integer('discount_value')->nullable();;
                $table->integer('total_amount')->nullable();;
                $table->integer('uses_per_coupon')->nullable();;
                $table->integer('uses_per_customer')->nullable();;
                $table->integer('is_active')->nullable();;
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cart_coupon_logs')) {
            Schema::create('cart_coupon_logs', function (Blueprint $table) {

                $table->increments('id');
                $table->integer('coupon_id')->nullable();;
                $table->string('customer_email')->nullable();;
                $table->string('customer_id')->nullable();;

                $table->string('coupon_code')->nullable();;
                $table->string('customer_ip')->nullable();;
                $table->integer('uses_count')->nullable();;
                $table->dateTime('use_date')->nullable();;
                $table->timestamps();

            });
        }
    }

}

