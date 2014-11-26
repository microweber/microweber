<?php


namespace Weber\Utils;

use Weber\Module;
use Illuminate\Support\Facades\Schema;

use Cache;

class Installer
{
    function __construct()
    {

    }

    public function run()
    {

        $this->install_db();
        Cache::flush();
        mw()->modules->install();
    }

    public function install_db()
    {
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function ($table) {
                $table->string('id')->unique();
                $table->text('payload');
                $table->integer('last_activity');
            });
        }


        $this->make_base();
        $this->make_options();
        $this->make_forms();
        $this->make_shop();
        $this->make_comments();


    }

    function make_base()
    {
        $table_name = 'modules';
        $table_name2 = 'elements';
        $table_name3 = 'module_templates';
        $table_name4 = 'system_licenses';

        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['expires_on'] = 'dateTime';


        $fields_to_add['created_by'] = 'integer';

        $fields_to_add['edited_by'] = 'integer';

        $fields_to_add['name'] = 'longText';
        $fields_to_add['parent_id'] = 'integer';
        $fields_to_add['module_id'] = 'longText';

        $fields_to_add['module'] = 'longText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['icon'] = 'longText';
        $fields_to_add['author'] = 'longText';
        $fields_to_add['website'] = 'longText';
        $fields_to_add['help'] = 'longText';
        $fields_to_add['type'] = 'longText';

        $fields_to_add['installed'] = 'integer';
        $fields_to_add['ui'] = 'integer';
        $fields_to_add['position'] = 'integer';
        $fields_to_add['as_element'] = 'integer';
        $fields_to_add['ui_admin'] = 'integer';
        $fields_to_add['ui_admin_iframe'] = 'integer';
        $fields_to_add['is_system'] = 'integer';

        $fields_to_add['version'] = 'string';

        $fields_to_add['notifications'] = 'integer';

        $db = mw()->database;
        $db->build_table($table_name, $fields_to_add);

        $fields_to_add['layout_type'] = 'string';
        $db->build_table($table_name2, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';

        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['module_id'] = 'longText';
        $fields_to_add['name'] = 'longText';
        $fields_to_add['module'] = 'longText';
        $db->build_table($table_name3, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['rel'] = 'longText';
        $fields_to_add['rel_name'] = 'longText';
        $fields_to_add['local_key'] = 'longText';
        $fields_to_add['local_key_hash'] = 'longText';
        $fields_to_add['registered_name'] = 'longText';
        $fields_to_add['company_name'] = 'longText';
        $fields_to_add['domains'] = 'longText';
        $fields_to_add['status'] = 'longText';
        $fields_to_add['product_id'] = 'integer';
        $fields_to_add['service_id'] = 'integer';
        $fields_to_add['billing_cycle'] = 'longText';
        $fields_to_add['reg_on'] = 'dateTime';
        $fields_to_add['due_on'] = 'dateTime';

        $db->build_table($table_name4, $fields_to_add);


        $table_name = 'users';

        $fields_to_add = array();

        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['expires_on'] = 'dateTime';
        $fields_to_add['last_login'] = 'dateTime';
        $fields_to_add['last_login_ip'] = 'longText';

        $fields_to_add['created_by'] = 'integer';

        $fields_to_add['edited_by'] = 'integer';

        $fields_to_add['username'] = 'longText';

        $fields_to_add['password'] = 'longText';
        $fields_to_add['email'] = 'longText';

        $fields_to_add['is_active'] = "string";
        $fields_to_add['is_admin'] = "string";
        $fields_to_add['is_verified'] = "string";
        $fields_to_add['is_public'] = "string";

        $fields_to_add['basic_mode'] = "string";

        $fields_to_add['first_name'] = 'longText';
        $fields_to_add['last_name'] = 'longText';
        $fields_to_add['thumbnail'] = 'longText';

        $fields_to_add['parent_id'] = 'integer';

        $fields_to_add['api_key'] = 'longText';

        $fields_to_add['user_information'] = 'longText';
        $fields_to_add['subscr_id'] = 'longText';
        $fields_to_add['role'] = 'longText';
        $fields_to_add['medium'] = 'longText';

        $fields_to_add['oauth_uid'] = 'longText';
        $fields_to_add['oauth_provider'] = 'longText';
        $fields_to_add['oauth_token'] = 'longText';
        $fields_to_add['oauth_token_secret'] = 'longText';

        $fields_to_add['profile_url'] = 'longText';
        $fields_to_add['website_url'] = 'longText';
        $fields_to_add['password_reset_hash'] = 'longText';

        mw()->database->build_table($table_name, $fields_to_add);

        mw()->database->add_table_index('username', $table_name, array('username(255)'));
        mw()->database->add_table_index('email', $table_name, array('email(255)'));


        $table_name = 'log';

        $fields_to_add = array();

        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['rel'] = 'longText';

        $fields_to_add['rel_id'] = 'longText';
        $fields_to_add['position'] = 'integer';

        $fields_to_add['field'] = 'longText';
        $fields_to_add['value'] = 'longText';
        $fields_to_add['module'] = 'longText';

        $fields_to_add['data_type'] = 'longText';
        $fields_to_add['title'] = 'longText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['content'] = 'longText';
        $fields_to_add['user_ip'] = 'longText';
        $fields_to_add['session_id'] = 'longText';
        $fields_to_add['is_system'] = "string";

        mw()->database->build_table($table_name, $fields_to_add);


        $table_name = 'content';

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('expires_on', 'dateTime');

        $fields_to_add[] = array('created_by', 'integer');

        $fields_to_add[] = array('edited_by', 'integer');


        $fields_to_add[] = array('content_type', 'longText');
        $fields_to_add[] = array('url', 'longText');
        $fields_to_add[] = array('content_filename', 'longText');
        $fields_to_add[] = array('title', 'longText');
        $fields_to_add[] = array('parent', 'integer');
        $fields_to_add[] = array('description', 'longText');
        $fields_to_add[] = array('content_meta_title', 'longText');

        $fields_to_add[] = array('content_meta_keywords', 'longText');
        $fields_to_add[] = array('position', 'integer');

        $fields_to_add[] = array('content', 'longText');
        $fields_to_add[] = array('content_body', 'longText');

        $fields_to_add[] = array('is_active', "integer");
        $fields_to_add[] = array('is_home', "integer");
        $fields_to_add[] = array('is_pinged', "integer");
        $fields_to_add[] = array('is_shop', "integer");
        $fields_to_add[] = array('is_deleted', "integer");
        $fields_to_add[] = array('draft_of', 'integer');

        $fields_to_add[] = array('require_login', "integer");

        $fields_to_add[] = array('status', 'longText');

        $fields_to_add[] = array('subtype', 'longText');
        $fields_to_add[] = array('subtype_value', 'longText');


        $fields_to_add[] = array('custom_type', 'longText');
        $fields_to_add[] = array('custom_type_value', 'longText');


        $fields_to_add[] = array('original_link', 'longText');
        $fields_to_add[] = array('layout_file', 'longText');
        $fields_to_add[] = array('layout_name', 'longText');
        $fields_to_add[] = array('layout_style', 'longText');
        $fields_to_add[] = array('active_site_template', 'longText');
        $fields_to_add[] = array('session_id', 'string');
        $fields_to_add[] = array('posted_on', 'dateTime');

        mw()->database->build_table($table_name, $fields_to_add);


        mw()->database->add_table_index('url', $table_name, array('url(255)'));
        mw()->database->add_table_index('title', $table_name, array('title(255)'));


        $table_name = 'content_data';


        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('content_id', 'string');
        $fields_to_add[] = array('field_name', 'longText');
        $fields_to_add[] = array('field_value', 'longText');
        $fields_to_add[] = array('session_id', 'string');
        $fields_to_add[] = array('rel', 'longText');

        $fields_to_add[] = array('rel_id', 'longText');
        mw()->database->build_table($table_name, $fields_to_add);


        $table_name = 'content_fields';

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('rel', 'longText');

        $fields_to_add[] = array('rel_id', 'longText');
        $fields_to_add[] = array('field', 'longText');
        $fields_to_add[] = array('value', 'longText');
        mw()->database->build_table($table_name, $fields_to_add);

        mw()->database->add_table_index('rel', $table_name, array('rel(55)'));
        mw()->database->add_table_index('rel_id', $table_name, array('rel_id(255)'));

        $table_name = 'content_fields_drafts';
        $fields_to_add[] = array('session_id', 'string');
        $fields_to_add[] = array('is_temp', "integer");
        $fields_to_add[] = array('url', 'longText');


        mw()->database->build_table($table_name, $fields_to_add);

        mw()->database->add_table_index('rel', $table_name, array('rel(55)'));
        mw()->database->add_table_index('rel_id', $table_name, array('rel_id(255)'));


        $table_name = 'media';

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('session_id', 'string');
        $fields_to_add[] = array('rel', 'longText');

        $fields_to_add[] = array('rel_id', "string");
        $fields_to_add[] = array('media_type', 'longText');
        $fields_to_add[] = array('position', 'integer');
        $fields_to_add[] = array('title', 'longText');
        $fields_to_add[] = array('description', 'longText');
        $fields_to_add[] = array('embed_code', 'longText');
        $fields_to_add[] = array('filename', 'longText');


        mw()->database->build_table($table_name, $fields_to_add);

        mw()->database->add_table_index('rel', $table_name, array('rel(55)'));
        mw()->database->add_table_index('rel_id', $table_name, array('rel_id(255)'));
        mw()->database->add_table_index('media_type', $table_name, array('media_type(55)'));


        $table_name = 'custom_fields';

        $fields_to_add = array();
        $fields_to_add[] = array('rel', 'longText');
        $fields_to_add[] = array('rel_id', 'longText');
        $fields_to_add[] = array('position', 'integer');
        $fields_to_add[] = array('type', 'longText');
        $fields_to_add[] = array('name', 'longText');
        $fields_to_add[] = array('value', 'longText');
        $fields_to_add[] = array('values', 'longText');
        $fields_to_add[] = array('num_value', 'float');


        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('session_id', 'string');


        $fields_to_add[] = array('custom_field_name', 'longText');
        $fields_to_add[] = array('custom_field_name_plain', 'longText');


        $fields_to_add[] = array('custom_field_value', 'longText');


        $fields_to_add[] = array('custom_field_type', 'longText');
        $fields_to_add[] = array('custom_field_values', 'longText');
        $fields_to_add[] = array('custom_field_values_plain', 'longText');

        $fields_to_add[] = array('field_for', 'longText');
        $fields_to_add[] = array('custom_field_field_for', 'longText');
        $fields_to_add[] = array('custom_field_help_text', 'longText');
        $fields_to_add[] = array('options', 'longText');


        $fields_to_add[] = array('custom_field_is_active', "integer");
        $fields_to_add[] = array('custom_field_required', "integer");
        $fields_to_add[] = array('copy_of_field', 'integer');


        mw()->database->build_table($table_name, $fields_to_add);

        mw()->database->add_table_index('rel', $table_name, array('rel(55)'));
        mw()->database->add_table_index('rel_id', $table_name, array('rel_id(55)'));
        mw()->database->add_table_index('custom_field_type', $table_name, array('custom_field_type(55)'));


        $table_name = 'menus';

        $fields_to_add = array();
        $fields_to_add[] = array('title', 'longText');
        $fields_to_add[] = array('item_type', 'string');
        $fields_to_add[] = array('parent_id', 'integer');
        $fields_to_add[] = array('content_id', 'integer');
        $fields_to_add[] = array('categories_id', 'integer');
        $fields_to_add[] = array('position', 'integer');
        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('is_active', "integer");
        $fields_to_add[] = array('description', 'longText');
        $fields_to_add[] = array('url', 'longText');
        mw()->database->build_table($table_name, $fields_to_add);

        $table_name = 'categories';

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('data_type', 'longText');
        $fields_to_add[] = array('title', 'longText');
        $fields_to_add[] = array('parent_id', 'integer');
        $fields_to_add[] = array('description', 'longText');
        $fields_to_add[] = array('content', 'longText');
        $fields_to_add[] = array('content_type', 'longText');
        $fields_to_add[] = array('rel', 'longText');

        $fields_to_add[] = array('rel_id', 'integer');

        $fields_to_add[] = array('position', 'integer');
        $fields_to_add[] = array('is_deleted', "integer");
        $fields_to_add[] = array('users_can_create_subcategories', "integer");
        $fields_to_add[] = array('users_can_create_content', "integer");
        $fields_to_add[] = array('users_can_create_content_allowed_usergroups', 'longText');

        $fields_to_add[] = array('categories_content_type', 'longText');
        $fields_to_add[] = array('categories_silo_keywords', 'longText');


        mw()->database->build_table($table_name, $fields_to_add);

        mw()->database->add_table_index('rel', $table_name, array('rel(55)'));
        mw()->database->add_table_index('rel_id', $table_name, array('rel_id'));
        mw()->database->add_table_index('parent_id', $table_name, array('parent_id'));

        $table_name = 'categories_items';

        $fields_to_add = array();
        $fields_to_add[] = array('parent_id', 'integer');
        $fields_to_add[] = array('rel', 'longText');

        $fields_to_add[] = array('rel_id', 'integer');
        $fields_to_add[] = array('content_type', 'longText');
        $fields_to_add[] = array('data_type', 'longText');

        mw()->database->build_table($table_name, $fields_to_add);
        mw()->database->add_table_index('rel_id', $table_name, array('rel_id'));
        mw()->database->add_table_index('parent_id', $table_name, array('parent_id'));


        $table_name = 'notifications';

        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['rel_id'] = 'integer';
        $fields_to_add['rel_type'] = 'string';
        $fields_to_add['notif_count'] = 'integer';
        $fields_to_add['is_read'] = 'integer';
        $fields_to_add['title'] = 'longText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['content'] = 'longText';
        $fields_to_add['installed_on'] = 'dateTime';

        mw()->database->build_table($table_name, $fields_to_add);
    }

    function make_options()
    {
        $table_name = 'options';

        $fields_to_add = array();

        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';

        $fields_to_add['option_key'] = 'longText';
        $fields_to_add['option_value'] = 'longText';
        $fields_to_add['option_key2'] = 'longText';
        $fields_to_add['option_value2'] = 'longText';
        $fields_to_add['position'] = 'integer';

        $fields_to_add['option_group'] = 'longText';
        $fields_to_add['name'] = 'longText';
        $fields_to_add['help'] = 'longText';
        $fields_to_add['field_type'] = 'longText';
        $fields_to_add['field_values'] = 'longText';

        $fields_to_add['module'] = 'longText';
        $fields_to_add['is_system'] = 'integer';

        mw()->database->build_table($table_name, $fields_to_add);
    }

    function make_forms()
    {
        $table_name = 'forms_data';


        //$fields_to_add['updated_on']= 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        //$fields_to_add['edited_by']= 'integer';
        $fields_to_add['rel'] = 'longText';
        $fields_to_add['rel_id'] = 'longText';
        //$fields_to_add['position']= 'integer';
        $fields_to_add['list_id'] = 'integer';
        $fields_to_add['form_values'] = 'longText';
        $fields_to_add['module_name'] = 'longText';

        $fields_to_add['url'] = 'longText';
        $fields_to_add['user_ip'] = 'longText';

         mw()->database->build_table($table_name, $fields_to_add);

         mw()->database->add_table_index('rel', $table_name, array('rel(55)'));
         mw()->database->add_table_index('rel_id', $table_name, array('rel_id(255)'));
         mw()->database->add_table_index('list_id', $table_name, array('list_id'));

        $table_name = 'forms_lists';

        $fields_to_add = array();

        //$fields_to_add['updated_on']= 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['title'] = 'longText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['custom_data'] = 'longText';

        $fields_to_add['module_name'] = 'longText';
        $fields_to_add['last_export'] = 'dateTime';
        $fields_to_add['last_sent'] = 'dateTime';

         mw()->database->build_table($table_name, $fields_to_add);

         mw()->database->add_table_index('title', $table_name, array('title(55)'));


        //  $table_sql = MW_INCLUDES_DIR . 'install' . DS . 'countries.sql';

        //  mw()->database->import_sql_file($table_sql);
    }

    function make_shop()
    {

        $table_name = 'cart';

        $fields_to_add = array();
        $fields_to_add['title'] = 'longText';
        $fields_to_add['is_active'] = "string";
        $fields_to_add['rel_id'] = 'integer';
        $fields_to_add['rel'] = 'string';
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['price'] = 'float';
        $fields_to_add['currency'] = 'string';
        $fields_to_add['session_id'] = 'string';
        $fields_to_add['qty'] = 'integer';
        $fields_to_add['other_info'] = 'longText';
        $fields_to_add['order_completed'] = "string";
        $fields_to_add['order_id'] = 'string';
        $fields_to_add['skip_promo_code'] = "string";
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['custom_fields_data'] = 'longText';

         mw()->database->build_table($table_name, $fields_to_add);

        //  mw()->database->add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
         mw()->database->add_table_index('rel', $table_name, array('rel'));
         mw()->database->add_table_index('rel_id', $table_name, array('rel_id'));

         mw()->database->add_table_index('session_id', $table_name, array('session_id'));

        $table_name = 'cart_orders';

        $fields_to_add = array();

        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['country'] = 'string';
        $fields_to_add['promo_code'] = 'longText';
        $fields_to_add['amount'] = 'float';
        $fields_to_add['transaction_id'] = 'longText';
        $fields_to_add['shipping_service'] = 'longText';
        $fields_to_add['shipping'] = 'float';
        $fields_to_add['currency'] = 'string';

        $fields_to_add['currency_code'] = 'string';

        $fields_to_add['first_name'] = 'longText';

        $fields_to_add['last_name'] = 'longText';

        $fields_to_add['email'] = 'longText';

        $fields_to_add['city'] = 'longText';

        $fields_to_add['state'] = 'longText';

        $fields_to_add['zip'] = 'longText';
        $fields_to_add['address'] = 'longText';
        $fields_to_add['address2'] = 'longText';
        $fields_to_add['phone'] = 'longText';

        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['session_id'] = 'string';
        $fields_to_add['order_completed'] = "string";
        $fields_to_add['is_paid'] = "string";
        $fields_to_add['url'] = 'longText';
        $fields_to_add['user_ip'] = 'string';
        $fields_to_add['items_count'] = 'integer';
        $fields_to_add['custom_fields_data'] = 'longText';

        $fields_to_add['payment_gw'] = 'string';
        $fields_to_add['payment_verify_token'] = 'string';
        $fields_to_add['payment_amount'] = 'float';
        $fields_to_add['payment_currency'] = 'string';

        $fields_to_add['payment_status'] = 'string';

        $fields_to_add['payment_email'] = 'longText';
        $fields_to_add['payment_receiver_email'] = 'longText';

        $fields_to_add['payment_name'] = 'longText';

        $fields_to_add['payment_country'] = 'longText';

        $fields_to_add['payment_address'] = 'longText';

        $fields_to_add['payment_city'] = 'longText';
        $fields_to_add['payment_state'] = 'longText';
        $fields_to_add['payment_zip'] = 'longText';

        $fields_to_add['payer_id'] = 'longText';

        $fields_to_add['payer_status'] = 'longText';
        $fields_to_add['payment_type'] = 'longText';
        $fields_to_add['order_status'] = 'string';

        $fields_to_add['payment_shipping'] = 'float';

        $fields_to_add['is_active'] = "string";
        $fields_to_add['rel_id'] = 'integer';
        $fields_to_add['rel'] = 'string';
        $fields_to_add['price'] = 'float';
        $fields_to_add['other_info'] = 'longText';
        $fields_to_add['order_id'] = 'string';
        $fields_to_add['skip_promo_code'] = "string";

         mw()->database->build_table($table_name, $fields_to_add);

         mw()->database->add_table_index('session_id', $table_name, array('session_id'));


        $table_name = 'cart_shipping';

        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['is_active'] = "string";

        $fields_to_add['shipping_cost'] = 'float';
        $fields_to_add['shipping_cost_max'] = 'float';
        $fields_to_add['shipping_cost_above'] = 'float';

        $fields_to_add['shipping_country'] = 'longText';
        $fields_to_add['position'] = 'integer';
        $fields_to_add['shipping_type'] = 'longText';


        $fields_to_add['shipping_price_per_size'] = 'float';
        $fields_to_add['shipping_price_per_weight'] = 'float';
        $fields_to_add['shipping_price_per_item'] = 'float';
        $fields_to_add['shipping_price_custom'] = 'float';

         mw()->database->build_table($table_name, $fields_to_add);

        return true;
    }


    function make_comments()
    {
        $table_name = 'comments';
        $fields_to_add = array();
        $fields_to_add[] = array('rel', 'longText');
        $fields_to_add[] = array('rel_id', 'longText');
        $fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('comment_name', 'longText');
        $fields_to_add[] = array('comment_body', 'longText');
        $fields_to_add[] = array('comment_email', 'longText');
        $fields_to_add[] = array('comment_website', 'longText');
        $fields_to_add[] = array('is_moderated', "integer");
        $fields_to_add[] = array('from_url', 'longText');
        $fields_to_add[] = array('comment_subject', 'longText');


        $fields_to_add[] = array('is_new', "integer");

        $fields_to_add[] = array('for_newsletter', "integer");
        $fields_to_add[] = array('session_id', 'string');
         mw()->database->build_table($table_name, $fields_to_add);
    }

}