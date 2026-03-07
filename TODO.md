# TODO.md – PHPUnit Unit Test Plan for ALL Modules
Last updated: March 2026  
Focus: **classic PHPUnit style only** (no Pest, no Livewire testing, no Filament resource/page testing, no integration/feature tests)  
Scope: pure **unit tests** → services • tools • managers • formatters • DTOs • model logic (accessors, scopes, mutators) • calculation classes • adapters/drivers • value objects  
Location convention: `Modules/ModuleName/Tests/Unit/*Test.php`  
Base class recommendation: `UnitTestCase` (extends PHPUnit\Framework\TestCase)

## General PHPUnit Setup Tasks (do these first)

- [ ] Create `tests/Unit/UnitTestCase.php` abstract base class
- [ ] Add common mocking helpers / traits (FakeAiDriver, FakeHttpClient, FakeMoney, etc.)
- [ ] Create naming & structure guideline document / README in `tests/Unit/`
- [ ] Add CI job: "phpunit-unit-modules" – runs only `**/Tests/Unit/**/*Test.php`
- [ ] Decide minimum coverage goal per module (example: 70% on service/tool classes)

## Per-module PHPUnit unit test plan

### Accordion
- [ ] AccordionModuleTest – settings parsing & defaults merging
- [ ] AccordionModuleSettingsTest – DTO validation & normalization

### Address
- [ ] AddressTest – formatting methods, country code normalization
- [ ] AddressValidatorTest – rule composition (if separated)

### Ai
- [ ] OpenAiDriverTest / GeminiAiDriverTest / OllamaAiDriverTest / ... (one per driver)
- [ ] CreateContentToolTest • CreateProductToolTest • RagSearchToolTest • GoogleTrendsToolTest • AmazonScraperToolTest
- [ ] AgentChatHistoryTest – truncation, role enforcement, token estimation
- [ ] AgentFactoryTest – mapping string → concrete agent class
- [ ] AiServiceTest – fallback chain & driver selection logic
- [ ] ContentAgentTest / ShopAgentTest / CustomerAgentTest – routing decisions (mock tools)

### AiWizard
- [ ] SectionProcessorTest – section type detection, prompt fragment building
- [ ] AiWizardSettingsTest – DTO / config merging logic

### Attributes
- [ ] AttributesManagerTest – casting, comparison operators, bulk operations
- [ ] HasAttributesTest – accessor/mutator isolation (mock model)

### Audio
- [ ] AudioModuleTest – embed code / settings → parameters mapping

### Backup
- [ ] JsonBackupTest • XlsxBackupTest • CsvBackupTest • ZipBatchBackupTest – round-trip serialize/deserialize
- [ ] BackupFileNameGetSetTest – pattern generation & uniqueness
- [ ] SessionStepperTest – step machine transitions & validation
- [ ] EncodingFixTest – charset detection & repair logic

### BeforeAfter
- [ ] BeforeAfterModuleTest – image path / label normalization

### Billing
- [ ] SubscriptionTest – status transitions, trial/proration calculations
- [ ] SubscriptionPlanTest – feature checks, price formatting
- [ ] SubscriptionManagerTest – upgrade/downgrade rules, eligibility
- [ ] StripeServiceTest – payload builders (mock Stripe client)

### Blog
- [ ] BlogFilterTest – scope composition & query fragment logic

### Btn
- [ ] BtnModuleTest – class/attribute generation from settings

### Captcha
- [ ] CaptchaManagerTest – driver resolution & fallback
- [ ] GoogleRecaptchaV3Test • GoogleRecaptchaV2Test • MicroweberCaptchaTest – response parsing & scoring
- [ ] CaptchaValidatorTest – rule behavior (mock manager)

### Cart
- [ ] CartTest – subtotal/total calc, item normalization, discount application
- [ ] CartItemTest – price modifiers, tax line calculation (if any)

### Comments (if exists as separate module)
- [ ] CommentFormatterTest – sanitization & markdown processing
- [ ] CommentModerationRulesTest – spam/score logic

### ContactForm
- [ ] ContactFormSubmissionTest – field normalization & validation rules object
- [ ] ContactFormSettingsTest – DTO → mail config mapping

### Content
- [ ] ContentTypeResolverTest – slug → class mapping
- [ ] ContentRepositoryTest – query scope composition (mock query builder)

### Coupons / Discounts
- [ ] CouponTest – eligibility, usage limits, expiration
- [ ] DiscountCalculatorTest – fixed / percent / tiered logic

### CustomFields
- [ ] CustomFieldValueTest – type casting & formatting
- [ ] CustomFieldRuleBuilderTest – validation rule generation

### Eshop / Shop / Product / Variant
- [ ] ProductPriceCalculatorTest – base + modifiers + tax + discounts
- [ ] VariantCombinationTest – option matching logic
- [ ] StockManagerTest – reservation / release calculation

### Export / Import
- [ ] ExporterTest / ImporterTest – row mapping & transformation logic
- [ ] FailedImportRowHandlerTest – retry / skip rules

### Newsletter
- [ ] SubscriberTest – status transitions, token generation
- [ ] CampaignParserTest – placeholder replacement

### Order
- [ ] OrderNumberGeneratorTest – format & uniqueness
- [ ] OrderStatusMachineTest – allowed transitions
- [ ] OrderTotalCalculatorTest – subtotal + shipping + tax + discounts

### Payment
- [ ] PaymentProviderResolverTest – gateway selection
- [ ] PaymentStatusMapperTest – provider → internal status

### Post
- [ ] PostSlugGeneratorTest – uniqueness & sanitization
- [ ] PostExcerptGeneratorTest – truncation logic

### QRCode (if exists)
- [ ] QRCodeGeneratorTest – parameter → data URL / file content

### SEO
- [ ] MetaTagGeneratorTest – title / description / og tags building
- [ ] SitemapGeneratorTest – url priority & change freq logic

### ShopAgent / CustomerAgent / … (Ai sub-agents)
- [ ] see Ai section above

### Template / Skin logic (if separate)
- [ ] SkinResolverTest – fallback chain
- [ ] TemplateVariableParserTest – placeholder replacement

### User / Admin
- [ ] UserRoleCheckerTest – permission bitmask / array logic
- [ ] PasswordResetTokenGeneratorTest – expiry & uniqueness

### All remaining small / UI-only modules
(for modules without significant logic – minimal or empty unit test file is acceptable)

- Accordion, Audio, Background, Breadcrumb, Btn, Gallery, GoogleAnalytics, GoogleMaps, HtmlBlock, Icon, Image, Label, Menu, Newsletter, Paragraph, Picture, Progress, QRCode, SearchBar, Separator, Share, Slider, SocialLinks, Spacer, Tabs, Testimonial, Text, Title, Video, etc.
- [ ] Create placeholder `ModuleNameLogicTest.php` with comment:
  ```php
  // This module is mostly presentational → unit test coverage not required
  // Consider integration / visual regression tests instead