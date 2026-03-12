# Filament Admin Resource API Reference

> Auto-generated from source code by `dev/generate-filament-docs.php`
> Last updated: 2026-03-12 20:00

This document describes every Filament Resource registered in the Microweber admin panel.
Each resource maps to an Eloquent model and exposes form fields (create/edit), table columns (list view),
actions, filters, relation managers, and global search configuration.

## Table of Contents

| Module | Resource | Model | Navigation Group |
|--------|----------|-------|------------------|
| Ai | [AgentChatResource](#agentchatresource) | `AgentChat` | System Settings |
| AiWizard | [AiWizardResource](#aiwizardresource) | `Content` | Other |
| Backup | [BackupResource](#backupresource) | `Backup` | System Settings |
| Billing | [BillingUserResource](#billinguserresource) | `BillingUser` | — |
| Billing | [SubscriptionPlanGroupsResource](#subscriptionplangroupsresource) | `SubscriptionPlanGroup` | Billing |
| Billing | [SubscriptionPlanResource](#subscriptionplanresource) | `SubscriptionPlan` | Billing |
| Billing | [SubscriptionResource](#subscriptionresource) | `Subscription` | Billing |
| Category | [CategoryResource](#categoryresource) | `Category` | Website |
| Category | [ShopCategoryResource](#shopcategoryresource) | `N/A` | Shop |
| Checkout | [CheckoutResource](#checkoutresource) | `N/A` | — |
| Comments | [CommentResource](#commentresource) | `Comment` | Other |
| Content | [ContentResource](#contentresource) | `Content` | Website |
| Coupons | [CouponResource](#couponresource) | `Coupon` | Shop Settings |
| Customer | [CustomerResource](#customerresource) | `Customer` | Shop Settings |
| Faq | [FaqModuleResource](#faqmoduleresource) | `Faq` | Other |
| Filament | [ModuleResource](#moduleresource) | `SystemModulesSushi` | Customization Settings |
| Invoice | [InvoiceResource](#invoiceresource) | `Invoice` | Shop Settings |
| MailTemplate | [MailTemplateResource](#mailtemplateresource) | `MailTemplate` | Email Settings |
| Marketplace | [MarketplaceResource](#marketplaceresource) | `MarketplaceItem` | Customization Settings |
| Newsletter | [CampaignResource](#campaignresource) | `NewsletterCampaign` | Campaigns |
| Newsletter | [ListResource](#listresource) | `NewsletterList` | Campaigns |
| Newsletter | [SenderAccountsResource](#senderaccountsresource) | `NewsletterSenderAccount` | Settings |
| Newsletter | [SubscribersResource](#subscribersresource) | `NewsletterSubscriber` | Mail |
| Newsletter | [TemplatesResource](#templatesresource) | `NewsletterTemplate` | Mail |
| Offer | [OfferResource](#offerresource) | `Offer` | Shop Settings |
| Order | [OrderResource](#orderresource) | `Order` | Shop |
| Page | [PageResource](#pageresource) | `Page` | Website |
| Payment | [PaymentProviderResource](#paymentproviderresource) | `PaymentProvider` | Shop Settings |
| Payment | [PaymentResource](#paymentresource) | `Payment` | Shop Settings |
| Post | [PostResource](#postresource) | `Post` | Website |
| Product | [ProductResource](#productresource) | `Product` | Shop |
| Rating | [RatingModuleResource](#ratingmoduleresource) | `Rating` | Other |
| Settings | [TranslationResource](#translationresource) | `TranslationKey` | Language Settings |
| Shipping | [ShippingProviderResource](#shippingproviderresource) | `ShippingProvider` | Shop Settings |
| Tag | [TagGroupResource](#taggroupresource) | `TagGroup` | Content |
| Tag | [TagResource](#tagresource) | `Tag` | Content |
| Tag | [TaggedResource](#taggedresource) | `Tagged` | Content |
| Tax | [TaxResource](#taxresource) | `TaxType` | Shop Settings |
| User (Core) | [UsersResource](#usersresource) | `User` | Users |

## Summary

| Metric | Count |
|--------|-------|
| Total Resources | 39 |
| Total Form Fields | 298 |
| Total Table Columns | 198 |
| Resources with Global Search | 7 |
| Resources with Navigation Badge | 4 |
| Resources with Relations | 4 |
| Resources with Widgets | 1 |

---

## Module: Ai

### AgentChatResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Ai/Filament/Resources/AgentChatResource.php` |
| **Namespace** | `Modules\Ai\Filament\Resources` |
| **Model** | `AgentChat` |
| **Navigation Group** | System Settings |
| **Navigation Sort** | 1100 |
| **Navigation Icon** | heroicon-o-chat-bubble-left-right |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | Yes |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `title` | TextInput | — | Yes | max:255 | — |
| `description` | Textarea | — | Yes | max:1000 | — |
| `agent_type` | Select | — | Yes | — | — |
| `user_id` | Select | — | — | — | relation: `user`; searchable; Assign this chat to a specific user (optional) |
| `is_active` | Toggle | — | — | — | Active chats can receive new messages |
| `metadata` | KeyValue | — | — | — | Additional settings for this chat |
| `search` | TextInput | Search | — | — | — |
| `created_from` | DatePicker | Created From | — | — | — |
| `created_until` | DatePicker | Created Until | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `title` | TextColumn | Agent Type | Yes | Yes | — | — |
| `agent_type` | BadgeColumn | Agent Type | — | — | — | — |
| `user.name` | TextColumn | Assigned User | — | Yes | Yes | — |
| `messages_count` | TextColumn | Messages | — | Yes | — | — |
| `last_message_at` | TextColumn | Last Activity | — | — | — | — |
| `is_active` | ToggleColumn | Active | — | Yes | Yes | — |
| `created_at` | TextColumn | Created | — | Yes | Yes | — |

#### Filters

- `agent_type`
- `is_active`
- `user_id`
- `search`
- `created_at`
- `agent_type`
- `user_id`

#### Table Actions

`Edit`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListAgentChats` | `/` |
| `create` | `CreateAgentChat` | `/create` |
| `edit` | `EditAgentChat` | `/{record}/edit` |
| `view` | `ViewAgentChat` | `/{record}` |

---

## Module: AiWizard

### AiWizardResource

| Property | Value |
|----------|-------|
| **File** | `Modules/AiWizard/Filament/Admin/AiWizardResource.php` |
| **Namespace** | `Modules\AiWizard\Filament\Admin` |
| **Model** | `Content` |
| **Navigation Group** | Other |
| **Navigation Sort** | — |
| **Navigation Icon** | heroicon-o-sparkles |
| **Navigation Label** | AI Page Wizard |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `title` | TextInput | — | Yes | max:255 | Describe what kind of page you want to create |
| `description` | Textarea | — | Yes | max:1000 | Describe what kind of page you want to create |
| `content_type` | Hidden | — | — | — | — |
| `is_active` | Hidden | — | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `title` | TextColumn | Published | Yes | Yes | — | — |
| `description` | TextColumn | Published | Yes | Yes | — | — |
| `is_active` | IconColumn | Published | — | Yes | — | — |
| `created_at` | TextColumn | — | — | Yes | — | — |

#### Table Actions

`Edit`, `Delete`, `view`

#### Bulk Actions

`Delete`

---

## Module: Backup

### BackupResource

> Manage your backups, restore content, and download backup files

| Property | Value |
|----------|-------|
| **File** | `Modules/Backup/Filament/Resources/BackupResource.php` |
| **Namespace** | `Modules\Backup\Filament\Resources` |
| **Model** | `Backup` |
| **Navigation Group** | System Settings |
| **Navigation Sort** | 9999 |
| **Navigation Icon** | heroicon-o-arrow-uturn-left |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `restoreType` | RadioDeck | Restore Type | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `filename` | TextColumn | Filename | Yes | Yes | — | — |
| `date` | TextColumn | Date | — | Yes | — | — |
| `time` | TextColumn | Time | — | Yes | — | — |
| `size` | TextColumn | Size | — | Yes | — | — |

#### Table Actions

`Delete`, `restore`, `download`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListBackups` | `/` |

---

## Module: Billing

### BillingUserResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Billing/Filament/Admin/Resources/BillingUserResource.php` |
| **Namespace** | `Modules\Billing\Filament\Admin\Resources` |
| **Model** | `BillingUser` |
| **Navigation Group** | — |
| **Navigation Sort** | 3000 |
| **Navigation Icon** | heroicon-o-user-group |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `subscription_plan_id` | Select | Subscription Plan | Yes | — | — |
| `auto_activate_free_trial_after_date` | Toggle | Automatically activate free trial after date | — | — | — |
| `activate_free_trial_after_date` | DatePicker | Activate free trial after date | — | — | — |
| `trial_status` | Select | — | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `id` | TextColumn | User ID | Yes | Yes | — | — |
| `email` | TextColumn | Email | Yes | Yes | — | — |
| `subscription` | TextColumn | Subscription | Yes | Yes | — | — |

#### Filters

- `subscription_status`
- `trial_status`
- `subscription_status`

#### Table Actions

`Edit`, `sync_customers`, `impersonate`

#### Bulk Actions

`Delete`

#### Relation Managers

- `SubscriptionsRelationManager`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListUsers` | `/` |
| `create` | `CreateUser` | `/create` |
| `edit` | `EditUser` | `/{record}/edit` |

### SubscriptionPlanGroupsResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Billing/Filament/Admin/Resources/SubscriptionPlanGroupsResource.php` |
| **Namespace** | `Modules\Billing\Filament\Admin\Resources` |
| **Model** | `SubscriptionPlanGroup` |
| **Navigation Group** | Billing |
| **Navigation Sort** | 4 |
| **Navigation Icon** | heroicon-o-rectangle-stack |
| **Navigation Label** | Plan Groups |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | Name | Yes | max:255 | Display name for this plan group |
| `sku` | TextInput | SKU | Yes | max:255 | Unique identifier for this group |
| `type` | TextInput | Type | — | max:255 | Category or type of plans in this group |
| `description` | Textarea | Description | — | max:65535 | Detailed description of this plan group |
| `position` | TextInput | Position | — | max:255, numeric | Display order (lower numbers first) |
| `icon` | TextInput | Icon | — | max:255 | Icon identifier for visual representation |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | Name | Yes | Yes | — | Yes |
| `sku` | TextColumn | SKU | Yes | Yes | — | Yes |
| `type` | TextColumn | Type | Yes | Yes | — | Yes |
| `position` | TextColumn | Position | — | Yes | — | Yes |
| `icon` | TextColumn | Icon | — | — | — | Yes |

#### Filters

- `type`
- `type`

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Relation Managers

- `PlansRelationManager`
- `FeaturesRelationManager`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListSubscriptionPlanGroups` | `/` |
| `create` | `CreateSubscriptionPlanGroups` | `/create` |
| `edit` | `EditSubscriptionPlanGroups` | `/{record}/edit` |
| `view` | `ViewSubscriptionPlanGroups` | `/{record}` |

### SubscriptionPlanResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Billing/Filament/Admin/Resources/SubscriptionPlanResource.php` |
| **Namespace** | `Modules\Billing\Filament\Admin\Resources` |
| **Model** | `SubscriptionPlan` |
| **Navigation Group** | Billing |
| **Navigation Sort** | 2 |
| **Navigation Icon** | heroicon-o-currency-dollar |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | — | Yes | — | A descriptive name for your subscription plan |
| `sku` | TextInput | — | Yes | — | relation: `group`; searchable; A unique identifier for this plan |
| `subscription_plan_group_id` | Select | — | Yes | — | relation: `group`; searchable; Name of the plan group (e.g., Business Plans) |
| `description` | Textarea | — | — | — | Detailed description of what this plan includes |
| `price` | TextInput | — | Yes | numeric | The regular price shown to customers |
| `currency` | Select | — | — | — | — |
| `discount_price` | TextInput | — | — | numeric | Optional discounted price |
| `save_price` | TextInput | — | — | numeric | Amount customers save (calculated automatically) |
| `save_price_badge` | TextInput | — | — | — | — |
| `billing_interval` | Select | — | Yes | numeric | How often customers will be billed |
| `trial_days` | TextInput | — | — | numeric | Number of days for free trial (0 for no trial) |
| `alternative_annual_plan_id` | TextInput | — | — | numeric | ID of the annual version of this plan (if applicable) |
| `remote_provider` | Select | — | — | — | The payment provider this plan is integrated with |
| `remote_provider_id` | Select | — | — | — | — |
| `remote_provider_price_id` | TextInput | — | — | — | The price ID from your payment provider |
| `features` | Repeater | Name | Yes | max:255 | relation: `features`; Feature identifier key |
| `key` | TextInput | Name | Yes | max:255 | Feature identifier key |
| `value` | TextInput | Value | Yes | max:255 | Feature value or limit |
| `limit` | TextInput | Limit | — | max:255 | Numeric limit for this feature (if applicable) |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | — | Yes | Yes | — | — |
| `sku` | TextColumn | — | Yes | Yes | — | Yes |
| `group.name` | TextColumn | — | Yes | Yes | — | Yes |
| `price` | TextColumn | — | — | Yes | — | Yes |
| `billing_interval` | TextColumn | — | — | — | — | Yes |
| `remote_provider` | TextColumn | — | Yes | Yes | — | Yes |

#### Filters

- `group`
- `billing_interval`
- `remote_provider`
- `group`
- `billing_interval`
- `remote_provider`

#### Table Actions

`Edit`, `View`, `Delete`, `Sync from Stripe`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListSubscriptionPlans` | `/` |
| `create` | `CreateSubscriptionPlan` | `/create` |
| `edit` | `EditSubscriptionPlan` | `/{record}/edit` |

### SubscriptionResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Billing/Filament/Admin/Resources/SubscriptionResource.php` |
| **Namespace** | `Modules\Billing\Filament\Admin\Resources` |
| **Model** | `Subscription` |
| **Navigation Group** | Billing |
| **Navigation Sort** | 310 |
| **Navigation Icon** | heroicon-o-currency-dollar |
| **Navigation Label** | Subscriptions |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | Yes |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `subscription_plan_id` | Select | Plan | Yes | — | relation: `plan`; searchable |
| `stripe_status` | Select | Status | — | — | — |
| `stripe_id` | TextInput | Stripe Subscription ID | — | numeric | — |
| `stripe_price` | TextInput | Stripe Price ID | — | numeric | — |
| `quantity` | TextInput | Quantity | — | numeric | — |
| `is_trial` | Toggle | In Trial Period | — | — | — |
| `trial_ends_at` | DateTimePicker | Trial Ends At | — | — | — |
| `starts_at` | DateTimePicker | Subscription Starts | — | — | — |
| `ends_at` | DateTimePicker | Subscription Ends | — | — | searchable |
| `trial_status` | Select | — | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `id` | TextColumn | Name | Yes | Yes | — | — |
| `name` | TextColumn | Name | Yes | Yes | — | — |
| `user_id` | TextColumn | User ID | Yes | Yes | — | — |
| `customer_id` | TextColumn | Customer ID | Yes | Yes | — | — |
| `plan.name` | TextColumn | Plan | Yes | Yes | Yes | — |
| `stripe_id` | TextColumn | Stripe ID | — | Yes | Yes | Yes |
| `stripe_status` | TextColumn | Status | — | — | — | Yes |
| `trial_status` | TextColumn | Trial | — | — | — | Yes |
| `starts_at` | TextColumn | Starts | — | Yes | Yes | — |
| `ends_at` | TextColumn | Ends | — | Yes | Yes | — |
| `trial_ends_at` | TextColumn | Trial Ends | — | Yes | Yes | — |
| `created_at` | TextColumn | Created | — | Yes | Yes | — |

#### Filters

- `stripe_status`
- `trial_status`
- `active_subscriptions`
- `canceled_subscriptions`
- `expired_subscriptions`
- `stripe_status`

#### Table Actions

`Edit`, `View`, `Delete`, `syncStripe`, `refund`, `cancelSubscriptions`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListSubscriptions` | `/` |
| `view` | `ViewSubscription` | `/{record}` |
| `edit` | `EditSubscription` | `/{record}/edit` |

---

## Module: Category

### CategoryResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Category/Filament/Admin/Resources/CategoryResource.php` |
| **Namespace** | `Modules\Category\Filament\Admin\Resources` |
| **Model** | `Category` |
| **Navigation Group** | Website |
| **Navigation Sort** | 3 |
| **Navigation Icon** | heroicon-o-rectangle-stack |
| **Navigation Label** | — |
| **Record Title** | title |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `id` | Hidden | Title | Yes | — | — |
| `parent_id` | Hidden | Title | Yes | — | — |
| `rel_type` | Hidden | Title | Yes | — | — |
| `rel_id` | Hidden | Title | Yes | — | — |
| `title` | TextInput | Title | Yes | — | — |
| `description` | Textarea | Description | — | — | — |
| `url` | TextInput | Url | — | — | — |
| `mediaIds` | MwMediaBrowser | Category Images | — | — | — |
| `category_meta_title` | TextInput | Meta Title | — | — | — |
| `category_meta_description` | Textarea | Meta Description | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `title` | TextColumn | — | Yes | Yes | — | — |
| `url` | TextColumn | — | Yes | Yes | — | — |
| `description` | TextColumn | — | Yes | — | — | — |
| `category_meta_title` | TextColumn | — | Yes | — | — | — |
| `category_meta_description` | TextColumn | — | Yes | — | — | — |

#### Table Actions

`Edit`, `edit`, `view`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListCategories` | `/` |
| `create` | `CreateCategory` | `/create` |
| `edit` | `EditCategory` | `/{record}/edit` |

#### Global Search

Searchable attributes: `title`, `description`, `url`, `category_meta_title`, `category_meta_description`

### ShopCategoryResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Category/Filament/Admin/Resources/ShopCategoryResource.php` |
| **Namespace** | `Modules\Category\Filament\Admin\Resources` |
| **Model** | `N/A` |
| **Navigation Group** | Shop |
| **Navigation Sort** | — |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

_No form fields defined (resource may use a custom form or wizard)._

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListShopCategories` | `/` |
| `create` | `CreateShopCategory` | `/create` |
| `edit` | `EditShopCategory` | `/{record}/edit` |

---

## Module: Checkout

### CheckoutResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Checkout/Filament/Resources/CheckoutResource.php` |
| **Namespace** | `Modules\Checkout\Filament\Resources` |
| **Model** | `N/A` |
| **Navigation Group** | — |
| **Navigation Sort** | — |
| **Navigation Icon** | heroicon-o-shopping-cart |
| **Navigation Label** | Checkout |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `first_name` | TextInput | — | Yes | max:255 | — |
| `last_name` | TextInput | — | Yes | max:255 | — |
| `email` | TextInput | — | Yes | max:255, email | — |
| `phone` | TextInput | — | Yes | max:255 | — |
| `country` | Select | — | Yes | — | — |
| `city` | TextInput | — | Yes | max:255 | — |
| `state` | TextInput | — | Yes | max:255 | — |
| `postal_code` | TextInput | — | Yes | max:20 | — |
| `address` | Textarea | — | Yes | max:255 | — |
| `shipping_provider_id` | Radio | Shipping Method | — | — | — |
| `coupon_code` | Placeholder | Coupon Code | — | — | — |
| `coupon_code` | TextInput | Coupon Code | Yes | max:255 | — |
| `payment_method_id` | Radio | Payment Method | — | — | — |
| `terms` | Checkbox | I agree to the terms and conditions | Yes | — | — |

#### Table Actions

`apply_coupon`, `remove_coupon`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `CheckoutPage` | `/` |
| `success` | `CheckoutSuccessPage` | `/success` |
| `failed` | `CheckoutFailedPage` | `/failed` |
| `cancelled` | `CheckoutCancelledPage` | `/cancelled` |

---

## Module: Comments

### CommentResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Comments/Filament/Resources/CommentResource.php` |
| **Namespace** | `Modules\Comments\Filament\Resources` |
| **Model** | `Comment` |
| **Navigation Group** | Other |
| **Navigation Sort** | — |
| **Navigation Icon** | heroicon-o-chat-bubble-left-right |
| **Navigation Label** | — |
| **Record Title** | comment_subject |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | Yes |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `comment_name` | TextInput | Name | Yes | email | — |
| `comment_email` | TextInput | Email | Yes | email | — |
| `comment_website` | TextInput | Website | Yes | — | — |
| `comment_subject` | TextInput | Subject | Yes | — | — |
| `comment_body` | Textarea | Comment | Yes | — | — |
| `is_moderated` | Toggle | Approved | — | — | — |
| `is_spam` | Toggle | Mark as Spam | — | — | searchable |
| `rel_type` | Select | Related To | — | — | searchable |
| `rel_id` | Select | Related ID | — | — | relation: `content`; searchable |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `comment_name` | TextColumn | Name | Yes | — | — | — |
| `comment_email` | TextColumn | Email | Yes | — | — | — |
| `comment_body` | TextColumn | Comment | Yes | — | — | — |
| `content.title` | TextColumn | Content | Yes | — | — | — |
| `is_moderated` | IconColumn | Approved | — | Yes | — | — |
| `is_spam` | IconColumn | Spam | — | Yes | — | — |
| `created_at` | TextColumn | Date | — | Yes | — | — |

#### Filters

- `is_moderated`
- `is_spam`
- `rel_type`
- `rel_type`

#### Table Actions

`Edit`, `Delete`, `approve`, `spam`, `mark_as_spam`, `edit`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListComments` | `/` |
| `create` | `CreateComment` | `/create` |
| `edit` | `EditComment` | `/{record}/edit` |

#### Global Search

Searchable attributes: `comment_name`, `comment_email`, `comment_subject`, `comment_body`, `content.title`

---

## Module: Content

### ContentResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Content/Filament/Admin/ContentResource.php` |
| **Namespace** | `Modules\Content\Filament\Admin` |
| **Model** | `Content` |
| **Navigation Group** | Website |
| **Navigation Sort** | — |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | title |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `id` | Hidden | — | — | — | — |
| `session_id` | Hidden | — | — | — | — |
| `content_type` | Hidden | — | — | — | — |
| `subtype` | Hidden | — | — | — | — |
| `multilanguage` | Hidden | — | — | — | — |
| `active_site_template` | Hidden | — | — | — | — |
| `layout_file` | Hidden | — | — | — | — |
| `tags` | Hidden | — | — | — | — |
| `categoryIds` | Hidden | — | — | — | — |
| `menuIds` | Hidden | — | — | — | — |
| `parent` | Hidden | — | — | — | — |
| `is_shop` | Hidden | — | — | — | — |
| `is_home` | Hidden | — | — | — | — |
| `title` | TextInput | — | Yes | max:255 | — |
| `url` | TextInput | — | — | max:255 | — |
| `content_body` | RichEditor | — | — | — | — |
| `mediaIds` | MwMediaBrowser | Add images | — | — | — |
| `price` | TextInput | — | Yes | numeric | — |
| `special_price` | TextInput | — | — | — | — |
| `is_active` | Toggle | Published | — | — | — |
| `tags` | TagsInput | — | — | — | Separate using commas or Enter key. |
| `content_meta_title` | TextInput | Meta Title | — | — | Describe for what is this page about in short title |
| `description` | Textarea | Meta Description | — | — | Please provide a brief summary of this web page |
| `content_meta_keywords` | TextInput | Meta Keywords | — | — | Separate keywords with a comma and space. Type keywords that describe your content - Example: Blog, Online News, Phones for sale |
| `original_link` | TextInput | Redirect URL | — | — | Redirect to another URL when this content is accessed |
| `require_login` | Toggle | Require login | — | — | Require user to be logged in to view this content |
| `created_by` | Select | Author | — | — | searchable |
| `content_type` | Select | Content Type | — | — | — |
| `subtype` | Select | Content Subtype | — | — | — |
| `is_shop` | Toggle | Is Shop | — | — | This page will accept products to be added to it. |
| `is_home` | Toggle | Is Homepage | — | — | This will be the first page of your website. |
| `created_at` | DateTimePicker | Created At | — | — | — |
| `updated_at` | DateTimePicker | Updated At | — | — | — |
| `id` | Placeholder | ID | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `media_url` | ImageUrlColumn | Image | Yes | — | — | — |
| `title` | TextColumn | — | Yes | — | — | — |
| `price_display` | TextColumn | — | Yes | — | — | — |
| `content` | ViewColumn | — | Yes | — | — | — |
| `media_url` | ImageUrlColumn | — | Yes | — | — | — |
| `id` | TextColumn | — | Yes | — | — | — |
| `title` | TextColumn | — | Yes | — | — | — |
| `title` | TextColumn | — | Yes | — | — | — |
| `created_at` | TextColumn | — | Yes | — | — | — |
| `price_display` | TextColumn | — | Yes | — | — | — |
| `created_at` | TextColumn | — | Yes | — | — | — |

#### Filters

- `content_type`
- `content_subtype`
- `is_active`
- `category_id`
- `content_type`
- `content_subtype`
- `is_active`
- `category_id`

#### Table Actions

`Edit`, `Delete`, `title`, `url`, `content_body`, `generateSeoContent`, `content_meta_title`, `description`, `content_meta_keywords`, `live_edit`, `edit`, `delete`, `view`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListContents` | `/` |
| `create` | `CreateContent` | `/create` |
| `view` | `ViewContent` | `/{record}` |
| `edit` | `EditContent` | `/{record}/edit` |

#### Global Search

Searchable attributes: `title`, `description`, `content_body`, `url`

---

## Module: Coupons

### CouponResource

> Configure your shop coupons settings

| Property | Value |
|----------|-------|
| **File** | `Modules/Coupons/Filament/Resources/CouponResource.php` |
| **Namespace** | `Modules\Coupons\Filament\Resources` |
| **Model** | `Coupon` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 12 |
| **Navigation Icon** | heroicon-o-ticket |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `coupon_name` | TextInput | Coupon Name | Yes | max:255 | — |
| `coupon_code` | TextInput | Coupon Code | Yes | max:255 | — |
| `discount_type` | Select | Discount Type | Yes | numeric | — |
| `discount_value` | TextInput | Discount Value | Yes | numeric | — |
| `total_amount` | TextInput | Minimum Order Amount | — | numeric | — |
| `uses_per_coupon` | TextInput | Uses Per Coupon | — | numeric | — |
| `uses_per_customer` | TextInput | Uses Per Customer | — | numeric | — |
| `is_active` | Toggle | Active | — | — | searchable |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `coupon_name` | TextColumn | Name | Yes | Yes | — | — |
| `coupon_code` | TextColumn | Code | Yes | Yes | — | — |
| `discount_type` | TextColumn | Type | — | — | — | — |
| `discount_value` | TextColumn | Value | — | — | — | — |
| `total_amount` | TextColumn | Min. Amount | — | — | — | — |
| `is_active` | IconColumn | Active | — | Yes | — | — |
| `created_at` | TextColumn | Created | — | Yes | — | — |

#### Filters

- `discount_type`
- `is_active`
- `discount_type`

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Relation Managers

- `LogsRelationManager`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListCoupons` | `/` |
| `create` | `CreateCoupon` | `/create` |
| `edit` | `EditCoupon` | `/{record}/edit` |

---

## Module: Customer

### CustomerResource

> Manage customers for your shop

| Property | Value |
|----------|-------|
| **File** | `Modules/Customer/Filament/CustomerResource.php` |
| **Namespace** | `Modules\Customer\Filament` |
| **Model** | `Customer` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 3 |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | name |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | — | Yes | max:255, email | — |
| `first_name` | TextInput | — | Yes | max:255, email | — |
| `last_name` | TextInput | — | Yes | max:255, email | relation: `user` |
| `phone` | TextInput | — | Yes | max:255, email | relation: `user`; searchable |
| `email` | TextInput | Currency | Yes | max:255, email | relation: `user`; searchable |
| `active` | Toggle | Currency | Yes | — | relation: `user`; searchable |
| `user_id` | Select | Currency | Yes | — | relation: `user`; searchable |
| `currency_id` | Select | Currency | Yes | max:255 | relation: `company`; searchable |
| `company_id` | Select | Company | Yes | max:255 | relation: `company`; searchable |
| `company_number` | TextInput | Email address | — | max:255, email | — |
| `vat_number` | TextInput | Email address | — | max:255, email | — |
| `address` | Textarea | — | — | max:255 | — |
| `city` | TextInput | — | — | max:255 | — |
| `zip` | TextInput | — | — | max:255 | — |
| `country` | TextInput | — | — | max:255 | — |
| `website` | TextInput | — | — | max:255 | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `id` | TextColumn | — | Yes | Yes | — | — |
| `name` | TextColumn | — | Yes | Yes | — | — |
| `first_name` | TextColumn | — | Yes | Yes | — | — |
| `last_name` | TextColumn | — | Yes | Yes | — | — |
| `phone` | TextColumn | — | Yes | Yes | — | — |
| `email` | TextColumn | — | Yes | Yes | — | — |
| `active` | BooleanColumn | — | — | Yes | — | — |
| `user.username` | TextColumn | — | — | Yes | — | — |
| `currency.name` | TextColumn | — | — | Yes | — | — |
| `company.name` | TextColumn | — | — | Yes | — | — |

#### Filters

- `active`

#### Table Actions

`Edit`, `Delete`, `edit`, `view`

#### Bulk Actions

`Delete`

#### Global Search

Searchable attributes: `name`, `first_name`, `last_name`, `email`, `phone`, `company.name`

---

## Module: Faq

### FaqModuleResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Faq/Filament/Resources/FaqModuleResource.php` |
| **Namespace** | `Modules\Faq\Filament\Resources` |
| **Model** | `Faq` |
| **Navigation Group** | Other |
| **Navigation Sort** | 100 |
| **Navigation Icon** | heroicon-o-question-mark-circle |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `question` | TextInput | — | Yes | max:255, numeric | — |
| `answer` | Textarea | — | Yes | max:255, numeric | — |
| `position` | TextInput | — | — | max:255, numeric | — |
| `is_active` | Toggle | — | — | max:255, numeric | searchable |
| `rel_type` | TextInput | — | — | max:255, numeric | searchable |
| `rel_id` | TextInput | — | — | numeric | searchable |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `question` | TextColumn | — | Yes | Yes | — | — |
| `answer` | TextColumn | — | Yes | Yes | — | — |
| `position` | TextColumn | — | Yes | Yes | — | — |
| `is_active` | ToggleColumn | — | Yes | Yes | — | — |
| `rel_type` | TextColumn | — | Yes | Yes | — | — |
| `rel_id` | TextColumn | — | — | Yes | — | — |
| `created_at` | TextColumn | — | — | Yes | — | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListFaqs` | `/` |
| `create` | `CreateFaq` | `/create` |
| `edit` | `EditFaq` | `/{record}/edit` |

---

## Module: Filament

### ModuleResource

> Manage system modules

| Property | Value |
|----------|-------|
| **File** | `src/MicroweberPackages/LaravelModules/Filament/Resources/ModuleResource/ModuleResource.php` |
| **Namespace** | `MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource` |
| **Model** | `SystemModulesSushi` |
| **Navigation Group** | Customization Settings |
| **Navigation Sort** | 120 |
| **Navigation Icon** | heroicon-o-puzzle-piece |
| **Navigation Label** | Modules |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `type` | Select | — | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | — | Yes | — | — | — |

#### Filters

- `installed`
- `type`
- `installed`

#### Table Actions

`view`

#### Global Search

Searchable attributes: `name`, `description`

---

## Module: Invoice

### InvoiceResource

> Manage invoices

| Property | Value |
|----------|-------|
| **File** | `Modules/Invoice/Filament/Resources/InvoiceResource.php` |
| **Namespace** | `Modules\Invoice\Filament\Resources` |
| **Model** | `Invoice` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 120 |
| **Navigation Icon** | — |
| **Navigation Label** | Invoices |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `invoice_date` | DatePicker | — | Yes | — | — |
| `due_date` | DatePicker | — | Yes | — | — |
| `invoice_number` | TextInput | — | Yes | — | relation: `customer` |
| `reference_number` | TextInput | — | Yes | — | relation: `customer`; searchable |
| `customer_id` | Select | — | Yes | — | relation: `customer`; searchable |
| `user_id` | Select | — | Yes | email | relation: `user`; searchable |
| `name` | TextInput | — | Yes | email | — |
| `email` | TextInput | — | Yes | email | — |
| `phone` | TextInput | — | Yes | — | searchable |
| `address` | Textarea | — | Yes | — | searchable |
| `status` | Select | — | — | — | — |
| `paid_status` | Select | — | Yes | — | — |
| `items` | Repeater | — | Yes | — | — |
| `description` | Textarea | — | Yes | numeric | — |
| `price` | TextInput | — | Yes | numeric | — |
| `quantity` | TextInput | — | Yes | numeric | — |
| `subtotal` | Placeholder | — | — | — | — |
| `total` | Placeholder | — | — | — | — |
| `export_multiple` | Checkbox | Export to multiple files (ZIP) | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `invoice_number` | TextColumn | — | Yes | Yes | — | — |
| `customer.name` | TextColumn | — | Yes | Yes | — | — |
| `invoice_date` | TextColumn | — | — | Yes | — | — |
| `due_date` | TextColumn | — | — | Yes | — | — |
| `total` | TextColumn | — | — | Yes | — | — |
| `status` | BadgeColumn | — | — | — | — | — |
| `paid_status` | BadgeColumn | — | — | — | — | — |

#### Filters

- `status`
- `paid_status`
- `status`
- `paid_status`

#### Table Actions

`Edit`, `Create`, `Export`, `pdf`

#### Bulk Actions

`Delete`, `Export`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListInvoices` | `/` |
| `create` | `CreateInvoice` | `/create` |
| `edit` | `EditInvoice` | `/{record}/edit` |

---

## Module: MailTemplate

### MailTemplateResource

| Property | Value |
|----------|-------|
| **File** | `Modules/MailTemplate/Filament/Resources/MailTemplateResource.php` |
| **Namespace** | `Modules\MailTemplate\Filament\Resources` |
| **Model** | `MailTemplate` |
| **Navigation Group** | Email Settings |
| **Navigation Sort** | — |
| **Navigation Icon** | heroicon-o-document-text |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | — | Yes | max:255 | — |
| `type` | Select | — | Yes | max:255 | — |
| `from_name` | TextInput | — | Yes | max:255, email | — |
| `from_email` | TextInput | — | Yes | max:255, email | — |
| `copy_to` | TextInput | — | Yes | max:255, email | — |
| `subject` | TextInput | — | Yes | max:255 | — |
| `message` | RichEditor | — | Yes | — | — |
| `is_active` | Toggle | Active | — | — | — |
| `variables` | Placeholder | — | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | — | Yes | Yes | — | — |
| `type` | TextColumn | — | Yes | Yes | — | — |
| `subject` | TextColumn | Active | Yes | Yes | — | — |
| `from_email` | TextColumn | Active | Yes | Yes | — | — |
| `is_active` | ToggleColumn | Active | — | Yes | — | — |
| `updated_at` | TextColumn | — | — | Yes | — | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

---

## Module: Marketplace

### MarketplaceResource

> Extend your website with modules and themes

| Property | Value |
|----------|-------|
| **File** | `Modules/Marketplace/Filament/Admin/MarketplaceResource.php` |
| **Namespace** | `Modules\Marketplace\Filament\Admin` |
| **Model** | `MarketplaceItem` |
| **Navigation Group** | Customization Settings |
| **Navigation Sort** | — |
| **Navigation Icon** | heroicon-o-shopping-bag |
| **Navigation Label** | Marketplace |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `license_key` | TextInput | License Key | — | — | — |
| `version` | Select | Version | — | — | — |
| `screenshot` | Placeholder | — | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `screenshot_link` | ImageUrlColumn | — | Yes | — | — | — |
| `name` | TextColumn | — | Yes | — | — | — |

#### Table Actions

`Edit`, `View`, `view-details`, `installPackageVersion`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListMarketplaces` | `/` |

---

## Module: Newsletter

### CampaignResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Newsletter/Filament/Admin/Resources/CampaignResource.php` |
| **Namespace** | `Modules\Newsletter\Filament\Admin\Resources` |
| **Model** | `NewsletterCampaign` |
| **Navigation Group** | Campaigns |
| **Navigation Sort** | 2 |
| **Navigation Icon** | heroicon-o-megaphone |
| **Navigation Label** | Campaigns |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | List | Yes | max:255 | relation: `list`; searchable |
| `list_id` | Select | List | Yes | — | relation: `list`; searchable |
| `status` | Select | — | — | — | — |
| `email_content_html` | Textarea | Email Content HTML | Yes | — | — |
| `email_content_type` | Select | Email Content Type | Yes | — | searchable |
| `export_multiple` | Checkbox | Export to multiple files (ZIP) | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | — | Yes | — | — | — |
| `list.name` | TextColumn | — | — | — | — | — |
| `subscribers` | TextColumn | — | — | — | — | — |
| `scheduled` | TextColumn | — | — | — | — | — |
| `scheduled_at` | TextColumn | — | — | — | — | — |
| `opened` | TextColumn | — | — | — | — | — |
| `clicked` | TextColumn | — | — | — | — | — |
| `status` | ViewColumn | — | — | — | — | — |
| `status_log` | TextColumn | — | — | — | — | — |

#### Table Actions

`Delete`, `Create`, `Export`, `edit`, `cancel`, `expand-opened`, `expand-clicked`

#### Bulk Actions

`Delete`, `Export`

### ListResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Newsletter/Filament/Admin/Resources/ListResource.php` |
| **Namespace** | `Modules\Newsletter\Filament\Admin\Resources` |
| **Model** | `NewsletterList` |
| **Navigation Group** | Campaigns |
| **Navigation Sort** | 2 |
| **Navigation Icon** | heroicon-o-list-bullet |
| **Navigation Label** | Lists |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | Name | — | — | searchable |
| `export_multiple` | Checkbox | Export to multiple files (ZIP) | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | — | Yes | — | — | — |
| `subscribersCount` | TextColumn | — | — | — | — | — |

#### Table Actions

`Edit`, `Delete`, `Create`, `Export`

#### Bulk Actions

`Delete`, `Export`

### SenderAccountsResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Newsletter/Filament/Admin/Resources/SenderAccountsResource.php` |
| **Namespace** | `Modules\Newsletter\Filament\Admin\Resources` |
| **Model** | `NewsletterSenderAccount` |
| **Navigation Group** | Settings |
| **Navigation Sort** | 4 |
| **Navigation Icon** | heroicon-o-arrow-trending-up |
| **Navigation Label** | Senders |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `account_type` | RadioDeck | Send email function | — | — | — |
| `smtp_username` | TextInput | SMTP Username | Yes | — | Enter the SMTP username |
| `smtp_password` | TextInput | SMTP Password | Yes | — | Enter the SMTP password |
| `smtp_host` | TextInput | SMTP Host | Yes | — | Enter the SMTP host |
| `smtp_port` | TextInput | SMTP Port | Yes | — | Enter the SMTP port |
| `mailchimp_secret` | TextInput | Mailchimp Secret | Yes | — | Enter the Mailchimp secret key |
| `mailgun_domain` | TextInput | Mailgun Domain | Yes | — | Enter the Mailgun domain |
| `mailgun_secret` | TextInput | Mailgun Secret | Yes | — | Enter the Mailgun secret |
| `mandrill_secret` | TextInput | Mandrill Secret | Yes | — | Enter the Mandrill secret |
| `sparkpost_secret` | TextInput | Sparkpost Secret | Yes | — | Enter the Sparkpost secret |
| `amazon_ses_key` | TextInput | Amazon SES Key | Yes | — | Enter the Amazon SES key |
| `amazon_ses_secret` | TextInput | Amazon SES Secret | Yes | — | Enter the Amazon SES secret |
| `amazon_ses_region` | TextInput | Amazon SES Region | Yes | — | Enter the Amazon SES region |
| `name` | TextInput | Name | Yes | — | Enter the name of the sender account |
| `from_name` | TextInput | From Name | Yes | — | This name will be visible as Sender name in the received e-mail |
| `from_email` | TextInput | From Email | Yes | — | This e-mail will be visible as Sender e-mail address in the received e-mail |
| `reply_email` | TextInput | Reply To Email | Yes | — | This e-mail will used for reply in the received e-mail |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `account_type` | IconColumn | Type | — | — | — | — |
| `provider` | TextColumn | — | — | — | — | — |
| `from_name` | TextColumn | — | — | — | — | — |
| `from_email` | TextColumn | — | — | — | — | — |
| `reply_email` | TextColumn | — | — | — | — | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

### SubscribersResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Newsletter/Filament/Admin/Resources/SubscribersResource.php` |
| **Namespace** | `Modules\Newsletter\Filament\Admin\Resources` |
| **Model** | `NewsletterSubscriber` |
| **Navigation Group** | Mail |
| **Navigation Sort** | 4 |
| **Navigation Icon** | heroicon-o-users |
| **Navigation Label** | Subscribers |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `email` | TextInput | Email | Yes | email | relation: `lists` |
| `name` | TextInput | Name | — | — | relation: `lists` |
| `export_multiple` | Checkbox | Export to multiple files (ZIP) | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | Email address | Yes | Yes | — | — |
| `email` | TextColumn | Email address | Yes | Yes | — | — |
| `lists.name` | TextColumn | Lists | — | — | — | — |

#### Table Actions

`Edit`, `Delete`, `Create`, `Export`, `importProducts`

#### Bulk Actions

`Delete`, `Export`

### TemplatesResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Newsletter/Filament/Admin/Resources/TemplatesResource.php` |
| **Namespace** | `Modules\Newsletter\Filament\Admin\Resources` |
| **Model** | `NewsletterTemplate` |
| **Navigation Group** | Mail |
| **Navigation Sort** | 3 |
| **Navigation Icon** | heroicon-o-paint-brush |
| **Navigation Label** | Designs |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

_No form fields defined (resource may use a custom form or wizard)._

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `title` | TextColumn | — | — | — | — | — |
| `created_at` | TextColumn | — | — | — | — | — |

#### Table Actions

`Delete`, `Edit`

#### Bulk Actions

`Delete`

---

## Module: Offer

### OfferResource

> Configure your shop offers settings

| Property | Value |
|----------|-------|
| **File** | `Modules/Offer/Filament/Admin/Resources/OfferResource.php` |
| **Namespace** | `Modules\Offer\Filament\Admin\Resources` |
| **Model** | `Offer` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 8 |
| **Navigation Icon** | heroicon-o-tag |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `product_id` | Select | — | Yes | — | relation: `product`; searchable |
| `price_id` | Select | Price | — | — | — |
| `offer_price` | TextInput | Offer Price | Yes | numeric | — |
| `expires_at` | DateTimePicker | Expires At | — | — | — |
| `is_active` | Toggle | Is Active | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `product_id` | ViewColumn | Product | Yes | Yes | — | — |
| `offer_price` | TextColumn | Offer Price | Yes | Yes | — | — |
| `expires_at` | TextColumn | Expires At | — | Yes | — | — |
| `is_active` | IconColumn | Active | — | Yes | Yes | — |
| `created_at` | TextColumn | Created At | — | Yes | Yes | — |
| `updated_at` | TextColumn | Updated At | — | Yes | Yes | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListOffers` | `/` |
| `create` | `CreateOffer` | `/create` |
| `edit` | `EditOffer` | `/{record}/edit` |

---

## Module: Order

### OrderResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Order/Filament/Admin/Resources/OrderResource.php` |
| **Namespace** | `Modules\Order\Filament\Admin\Resources` |
| **Model** | `Order` |
| **Navigation Group** | Shop |
| **Navigation Sort** | 12 |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | order_reference_id |
| **Hidden from Nav** | No |
| **Navigation Badge** | Yes |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `country` | Select | — | — | — | searchable |
| `city` | TextInput | State / Province | — | — | — |
| `state` | TextInput | State / Province | — | — | — |
| `zip` | TextInput | Zip / Postal code | — | — | — |
| `address` | Textarea | — | — | — | — |
| `address2` | Textarea | — | — | — | — |
| `phone` | TextInput | — | — | — | — |
| `order_status` | Select | Order completed | Yes | — | — |
| `order_completed` | Toggle | Order completed | Yes | — | — |
| `is_paid` | Toggle | Is paid | Yes | — | — |
| `created_at` | Placeholder | Created at | — | — | — |
| `updated_at` | Placeholder | Last modified at | — | — | — |
| `order_reference_id` | TextInput | — | Yes | max:32 | relation: `customer`; searchable |
| `customer_id` | Select | Customer | Yes | — | relation: `customer`; searchable |
| `first_name` | TextInput | Email address | Yes | max:255, email | — |
| `last_name` | TextInput | Email address | Yes | max:255, email | — |
| `email` | TextInput | Email address | Yes | max:255, email | — |
| `currency` | Select | — | Yes | — | searchable |
| `other_info` | MarkdownEditor | Items | — | — | relation: `cart` |
| `cart` | Repeater | Items | — | — | relation: `cart` |
| `rel_type` | Hidden | Product | — | — | — |
| `order_completed` | Hidden | Product | — | — | — |
| `rel_id` | Select | Product | — | — | — |
| `qty` | TextInput | Quantity | Yes | numeric | — |
| `price` | TextInput | Price | Yes | numeric | — |
| `custom_fields_data` | Repeater | Custom fields | — | — | — |
| `field_name` | TextInput | — | — | — | — |
| `field_name_key` | TextInput | Field | — | — | — |
| `field_type` | TextInput | Field | — | — | — |
| `field_value` | TextInput | Field | — | — | — |
| `field_id` | Select | Field | — | — | — |
| `field_value_id` | Select | Field Value | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `id` | TextColumn | ID | Yes | Yes | — | — |
| `created_at` | TextColumn | Product | — | — | — | — |
| `firstProductThumbnail` | ImageUrlColumn | Product | Yes | — | — | — |
| `order_reference_id` | TextColumn | Number | Yes | Yes | — | Yes |
| `order_status` | TextColumn | Status | Yes | Yes | Yes | Yes |
| `customer.email` | TextColumn | Email | Yes | Yes | Yes | — |
| `amount` | TextColumn | Completed | — | Yes | Yes | — |
| `order_completed` | BooleanColumn | Completed | — | Yes | Yes | — |
| `is_paid` | BooleanColumn | Paid | — | Yes | Yes | — |

#### Table Actions

`Edit`, `Create`, `edit`, `view`

#### Bulk Actions

`Delete`

#### Relation Managers

- `PaymentsRelationManager`

#### Widgets

- `OrderStats`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListOrders` | `/` |
| `create` | `CreateOrder` | `/create` |
| `edit` | `EditOrder` | `/{record}/edit` |

#### Global Search

Searchable attributes: `order_reference_id`, `id`, `amount`, `customer.email`, `customer.name`

---

## Module: Page

### PageResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Page/Filament/Resources/PageResource.php` |
| **Namespace** | `Modules\Page\Filament\Resources` |
| **Model** | `Page` |
| **Navigation Group** | Website |
| **Navigation Sort** | 1 |
| **Navigation Icon** | heroicon-o-rectangle-stack |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

_No form fields defined (resource may use a custom form or wizard)._

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListPages` | `/` |
| `create` | `CreatePage` | `/create` |
| `edit` | `EditPage` | `/{record}/edit` |

---

## Module: Payment

### PaymentProviderResource

> Configure your shop payments settings

| Property | Value |
|----------|-------|
| **File** | `Modules/Payment/Filament/Admin/Resources/PaymentProviderResource.php` |
| **Namespace** | `Modules\Payment\Filament\Admin\Resources` |
| **Model** | `PaymentProvider` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 14 |
| **Navigation Icon** | heroicon-o-credit-card |
| **Navigation Label** | Configure your shop payments settings |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `provider` | RadioDeck | — | Yes | — | — |
| `name` | TextInput | Name | Yes | — | — |
| `is_active` | Toggle | Is Active | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `logo` | ImageUrlColumn | Name | — | — | — | — |
| `name` | TextColumn | Name | Yes | Yes | — | — |
| `provider` | TextColumn | Provider | Yes | Yes | — | — |
| `is_active` | BooleanColumn | Is Active | — | Yes | — | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

### PaymentResource

> Manage payments and transactions

| Property | Value |
|----------|-------|
| **File** | `Modules/Payment/Filament/Admin/Resources/PaymentResource.php` |
| **Namespace** | `Modules\Payment\Filament\Admin\Resources` |
| **Model** | `Payment` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 4 |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `rel_id` | TextInput | Related ID | Yes | numeric | — |
| `rel_type` | TextInput | Related Type | Yes | numeric | — |
| `amount` | TextInput | — | Yes | numeric | — |
| `currency` | TextInput | — | Yes | — | searchable |
| `status` | Select | Payment Data | Yes | — | searchable |
| `payment_provider` | Select | Payment Data | Yes | — | searchable |
| `transaction_id` | TextInput | Payment Data | — | — | — |
| `payment_data` | KeyValue | Payment Data | — | — | searchable |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `id` | TextColumn | Type | Yes | Yes | — | — |
| `rel_type` | TextColumn | Type | Yes | Yes | — | — |
| `rel_id` | TextColumn | Related ID | Yes | Yes | — | — |
| `amount` | TextColumn | — | Yes | Yes | — | Yes |
| `currency` | TextColumn | — | Yes | — | — | Yes |
| `status` | TextColumn | — | — | — | — | Yes |
| `payment_provider` | TextColumn | Provider | Yes | Yes | Yes | — |
| `transaction_id` | TextColumn | — | Yes | Yes | Yes | — |
| `created_at` | TextColumn | — | — | Yes | Yes | — |

#### Filters

- `status`
- `payment_provider`
- `status`
- `payment_provider`

#### Table Actions

`Edit`, `View`

#### Bulk Actions

`Delete`

---

## Module: Post

### PostResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Post/Filament/Admin/Resources/PostResource.php` |
| **Namespace** | `Modules\Post\Filament\Admin\Resources` |
| **Model** | `Post` |
| **Navigation Group** | Website |
| **Navigation Sort** | 2 |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

_No form fields defined (resource may use a custom form or wizard)._

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListPosts` | `/` |
| `create` | `CreatePost` | `/create` |
| `edit` | `EditPost` | `/{record}/edit` |

---

## Module: Product

### ProductResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Product/Filament/Admin/Resources/ProductResource.php` |
| **Namespace** | `Modules\Product\Filament\Admin\Resources` |
| **Model** | `Product` |
| **Navigation Group** | Shop |
| **Navigation Sort** | 1 |
| **Navigation Icon** | — |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

_No form fields defined (resource may use a custom form or wizard)._

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListProducts` | `/` |
| `create` | `CreateProduct` | `/create` |
| `edit` | `EditProduct` | `/{record}/edit` |

---

## Module: Rating

### RatingModuleResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Rating/Filament/Resources/RatingModuleResource.php` |
| **Namespace** | `Modules\Rating\Filament\Resources` |
| **Model** | `Rating` |
| **Navigation Group** | Other |
| **Navigation Sort** | 100 |
| **Navigation Icon** | heroicon-o-star |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `rel_type` | TextInput | — | Yes | max:255, numeric | — |
| `rel_id` | TextInput | — | Yes | max:255, numeric | — |
| `rating` | TextInput | — | Yes | max:255, numeric | — |
| `comment` | Textarea | — | — | max:255, numeric | searchable |
| `session_id` | TextInput | — | — | max:255, numeric | searchable |
| `created_by` | TextInput | — | — | numeric | searchable |
| `edited_by` | TextInput | — | — | numeric | searchable |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `rel_type` | TextColumn | — | Yes | Yes | — | — |
| `rel_id` | TextColumn | — | Yes | Yes | — | — |
| `rating` | TextColumn | — | Yes | Yes | — | — |
| `comment` | TextColumn | — | Yes | Yes | — | — |
| `created_by` | TextColumn | — | — | Yes | — | — |
| `created_at` | TextColumn | — | — | Yes | — | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListRatings` | `/` |
| `create` | `CreateRating` | `/create` |
| `edit` | `EditRating` | `/{record}/edit` |

---

## Module: Settings

### TranslationResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Settings/Filament/Resources/TranslationResource.php` |
| **Namespace** | `Modules\Settings\Filament\Resources` |
| **Model** | `TranslationKey` |
| **Navigation Group** | Language Settings |
| **Navigation Sort** | 10 |
| **Navigation Icon** | heroicon-o-language |
| **Navigation Label** | Translations |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `translation_key` | TextInput | Translation Key | Yes | max:255 | The unique identifier for this translation |
| `translation_namespace` | TextInput | Namespace | — | max:255 | Optional namespace to group related translations |
| `translation_group` | TextInput | Translation Group | — | max:255 | Group for organizing translations |
| `translation_value_default` | Textarea | Default Value | — | — | The default text in the base language |
| `initial_locale` | Select | Initial Language | — | — | — |
| `initial_translation` | Textarea | Initial Translation | — | — | The translated text for the selected language |
| `locale` | Select | Language | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `translation_key` | TextColumn | Key | Yes | Yes | Yes | — |
| `translation_namespace` | TextColumn | Namespace | Yes | Yes | Yes | — |
| `translation_group` | TextColumn | Group | Yes | Yes | Yes | — |
| `translation_value_default` | TextColumn | Default Value | Yes | — | Yes | — |
| `translations_count` | TextColumn | Languages | — | — | — | Yes |
| `updated_at` | TextColumn | Last Updated | — | Yes | Yes | — |

#### Filters

- `translation_namespace`
- `translation_group`
- `translation_namespace`
- `translation_group`

#### Table Actions

`Edit`, `Delete`, `add_translation`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListTranslations` | `/` |
| `create` | `CreateTranslation` | `/create` |
| `edit` | `EditTranslation` | `/{record}/edit` |

---

## Module: Shipping

### ShippingProviderResource

> Configure your shop Shipping Providers

| Property | Value |
|----------|-------|
| **File** | `Modules/Shipping/Filament/Admin/Resources/ShippingProviderResource.php` |
| **Namespace** | `Modules\Shipping\Filament\Admin\Resources` |
| **Model** | `ShippingProvider` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 15 |
| **Navigation Icon** | heroicon-o-truck |
| **Navigation Label** | Shipping Providers |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `provider` | RadioDeck | — | Yes | — | — |
| `name` | TextInput | Name | Yes | — | — |
| `is_active` | Toggle | Is Active | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | Name | Yes | Yes | — | — |
| `provider` | TextColumn | Provider | Yes | Yes | — | — |
| `is_active` | BooleanColumn | Is Active | — | Yes | — | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListShippingProviders` | `/` |
| `create` | `CreateShippingProvider` | `/create` |
| `edit` | `EditShippingProvider` | `/{record}/edit` |

---

## Module: Tag

### TagGroupResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Tag/Filament/Resources/TagGroupResource.php` |
| **Namespace** | `Modules\Tag\Filament\Resources` |
| **Model** | `TagGroup` |
| **Navigation Group** | Content |
| **Navigation Sort** | 91 |
| **Navigation Icon** | heroicon-o-tag |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | — | Yes | max:255 | searchable; The URL-friendly version of the name. Leave blank to auto-generate. |
| `slug` | TextInput | — | — | max:255 | searchable; The URL-friendly version of the name. Leave blank to auto-generate. |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | Tags Count | Yes | Yes | — | — |
| `slug` | TextColumn | Tags Count | Yes | Yes | Yes | — |
| `tags_count` | TextColumn | Tags Count | — | Yes | Yes | — |
| `created_at` | TextColumn | — | — | Yes | Yes | — |
| `updated_at` | TextColumn | — | — | Yes | Yes | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListTagGroups` | `/` |
| `create` | `CreateTagGroup` | `/create` |
| `edit` | `EditTagGroup` | `/{record}/edit` |

### TagResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Tag/Filament/Resources/TagResource.php` |
| **Namespace** | `Modules\Tag\Filament\Resources` |
| **Model** | `Tag` |
| **Navigation Group** | Content |
| **Navigation Sort** | 90 |
| **Navigation Icon** | heroicon-o-tag |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | — | Yes | max:255 | The URL-friendly version of the name. Leave blank to auto-generate. |
| `slug` | TextInput | Suggest | — | max:255 | The URL-friendly version of the name. Leave blank to auto-generate. |
| `description` | Textarea | Suggest | — | numeric | The description is not prominent by default; however, some templates may show it. |
| `suggest` | Toggle | Suggest | — | numeric | Include this tag in suggestions |
| `tag_group_id` | TextInput | Tag Group | — | numeric | searchable; Optional group ID for this tag |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | Usage Count | Yes | Yes | — | — |
| `slug` | TextColumn | Usage Count | Yes | Yes | — | — |
| `description` | TextColumn | Usage Count | — | Yes | — | — |
| `count` | TextColumn | Usage Count | — | Yes | Yes | — |
| `suggest` | ToggleColumn | Suggested | — | Yes | Yes | — |
| `created_at` | TextColumn | — | — | Yes | Yes | — |
| `updated_at` | TextColumn | — | — | Yes | Yes | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListTags` | `/` |
| `create` | `CreateTag` | `/create` |
| `edit` | `EditTag` | `/{record}/edit` |

### TaggedResource

| Property | Value |
|----------|-------|
| **File** | `Modules/Tag/Filament/Resources/TaggedResource.php` |
| **Namespace** | `Modules\Tag\Filament\Resources` |
| **Model** | `Tagged` |
| **Navigation Group** | Content |
| **Navigation Sort** | 92 |
| **Navigation Icon** | heroicon-o-document-text |
| **Navigation Label** | Tagged Content |
| **Record Title** | — |
| **Hidden from Nav** | Yes (hidden) |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `taggable_id` | TextInput | Content ID | Yes | numeric | — |
| `taggable_type` | TextInput | Content Type | Yes | — | — |
| `tag_name` | Select | Tag | Yes | — | searchable |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `taggable_id` | TextColumn | Content ID | Yes | Yes | — | — |
| `taggable_type` | TextColumn | Content Type | Yes | Yes | — | — |
| `tag_name` | TextColumn | Tag | Yes | Yes | — | — |
| `tag_slug` | TextColumn | Tag Slug | Yes | Yes | Yes | — |
| `created_at` | TextColumn | — | — | Yes | Yes | — |
| `updated_at` | TextColumn | — | — | Yes | Yes | — |

#### Table Actions

`Edit`, `Delete`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListTagged` | `/` |
| `create` | `CreateTagged` | `/create` |
| `edit` | `EditTagged` | `/{record}/edit` |

---

## Module: Tax

### TaxResource

> Configure your shop taxes settings

| Property | Value |
|----------|-------|
| **File** | `Modules/Tax/Filament/Admin/Resources/TaxResource.php` |
| **Namespace** | `Modules\Tax\Filament\Admin\Resources` |
| **Model** | `TaxType` |
| **Navigation Group** | Shop Settings |
| **Navigation Sort** | 7 |
| **Navigation Icon** | heroicon-o-calculator |
| **Navigation Label** | — |
| **Record Title** | — |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `name` | TextInput | Name | Yes | — | — |
| `type` | Select | Type | Yes | — | — |
| `rate` | TextInput | Rate | Yes | numeric | — |
| `description` | TextInput | Description | — | — | — |
| `example_display` | Placeholder | Tax Display | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `name` | TextColumn | Name | Yes | Yes | — | — |
| `type` | TextColumn | Type | Yes | Yes | — | — |
| `rate` | TextColumn | Rate | Yes | Yes | — | — |
| `description` | TextColumn | Description | Yes | Yes | — | — |
| `global_status` | TextColumn | Global Status | — | — | — | Yes |

#### Table Actions

`Edit`, `Delete`, `toggle_taxes`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListTaxes` | `/` |
| `create` | `CreateTax` | `/create` |
| `edit` | `EditTax` | `/{record}/edit` |

---

## Module: User (Core)

### UsersResource

| Property | Value |
|----------|-------|
| **File** | `src/MicroweberPackages/User/Filament/Resources/UsersResource.php` |
| **Namespace** | `MicroweberPackages\User\Filament\Resources` |
| **Model** | `User` |
| **Navigation Group** | Users |
| **Navigation Sort** | 98 |
| **Navigation Icon** | heroicon-o-users |
| **Navigation Label** | — |
| **Record Title** | username |
| **Hidden from Nav** | No |
| **Navigation Badge** | No |

#### Form Fields

| Field | Component | Label | Required | Validation | Notes |
|-------|-----------|-------|----------|------------|-------|
| `first_name` | TextInput | — | Yes | email | — |
| `last_name` | TextInput | — | Yes | email | — |
| `username` | TextInput | — | Yes | email | — |
| `email` | TextInput | — | Yes | email | — |
| `password` | TextInput | Is Admin | — | — | — |
| `password_confirmation` | TextInput | Is Admin | — | — | — |
| `is_admin` | Select | Is Admin | — | — | — |
| `is_active` | Select | Is Active | — | — | — |

#### Table Columns

| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |
|--------|------|-------|------------|----------|------------|-------|
| `id` | TextColumn | — | — | — | — | — |
| `username` | TextColumn | — | — | — | — | — |
| `phone` | TextColumn | — | — | — | — | — |
| `email` | TextColumn | — | — | — | — | — |
| `first_name` | TextColumn | — | — | — | — | — |
| `last_name` | TextColumn | — | — | — | — | — |
| `created_at` | TextColumn | — | — | — | — | — |

#### Table Actions

`Edit`, `edit`

#### Bulk Actions

`Delete`

#### Pages

| Key | Page Class | Route |
|-----|------------|-------|
| `index` | `ListUsers` | `/` |
| `create` | `CreateUsers` | `/create` |
| `edit` | `EditUsers` | `/{record}/edit` |

#### Global Search

Searchable attributes: `username`, `email`, `first_name`, `last_name`, `phone`

---

## Navigation Map

Resources organized by their admin panel navigation group:

### Billing

- **SubscriptionPlanResource** — `SubscriptionPlan` (sort: 2)
- **SubscriptionPlanGroupsResource** — `SubscriptionPlanGroup` (sort: 4)
- **SubscriptionResource** — `Subscription` (sort: 310) [badge]

### Campaigns

- **CampaignResource** — `NewsletterCampaign` (sort: 2) _(hidden)_
- **ListResource** — `NewsletterList` (sort: 2) _(hidden)_

### Content

- **TagResource** — `Tag` (sort: 90) _(hidden)_
- **TagGroupResource** — `TagGroup` (sort: 91) _(hidden)_
- **TaggedResource** — `Tagged` (sort: 92) _(hidden)_

### Customization Settings

- **ModuleResource** — `SystemModulesSushi` (sort: 120)
- **MarketplaceResource** — `MarketplaceItem` (sort: —)

### Email Settings

- **MailTemplateResource** — `MailTemplate` (sort: —) _(hidden)_

### Language Settings

- **TranslationResource** — `TranslationKey` (sort: 10) _(hidden)_

### Mail

- **TemplatesResource** — `NewsletterTemplate` (sort: 3) _(hidden)_
- **SubscribersResource** — `NewsletterSubscriber` (sort: 4)

### Other

- **FaqModuleResource** — `Faq` (sort: 100) _(hidden)_
- **RatingModuleResource** — `Rating` (sort: 100) _(hidden)_
- **AiWizardResource** — `Content` (sort: —) _(hidden)_
- **CommentResource** — `Comment` (sort: —) _(hidden)_ [badge]

### Settings

- **SenderAccountsResource** — `NewsletterSenderAccount` (sort: 4) _(hidden)_

### Shop

- **ProductResource** — `Product` (sort: 1)
- **OrderResource** — `Order` (sort: 12) [badge]
- **ShopCategoryResource** — `N/A` (sort: —)

### Shop Settings

- **CustomerResource** — `Customer` (sort: 3)
- **PaymentResource** — `Payment` (sort: 4)
- **TaxResource** — `TaxType` (sort: 7)
- **OfferResource** — `Offer` (sort: 8)
- **CouponResource** — `Coupon` (sort: 12)
- **PaymentProviderResource** — `PaymentProvider` (sort: 14) _(hidden)_
- **ShippingProviderResource** — `ShippingProvider` (sort: 15)
- **InvoiceResource** — `Invoice` (sort: 120)

### System Settings

- **AgentChatResource** — `AgentChat` (sort: 1100) _(hidden)_ [badge]
- **BackupResource** — `Backup` (sort: 9999) _(hidden)_

### Ungrouped

- **CheckoutResource** — `N/A` (sort: —)
- **BillingUserResource** — `BillingUser` (sort: 3000)

### Users

- **UsersResource** — `User` (sort: 98)

### Website

- **PageResource** — `Page` (sort: 1)
- **PostResource** — `Post` (sort: 2)
- **CategoryResource** — `Category` (sort: 3)
- **ContentResource** — `Content` (sort: —) _(hidden)_

---

## Regenerating This Document

Run the generator script from the project root:

```bash
php dev/generate-filament-docs.php
```

To output to a custom path:

```bash
php dev/generate-filament-docs.php --output=docs/api/my-resources.md
```
