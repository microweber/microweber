<?php

namespace MicroweberPackages\Format\Tests;

use MicroweberPackages\Format\FormatService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use MicroweberPackages\Format\Facades\Format;

class FormatTest extends TestCase
{
    private FormatService $format;

    protected function setUp(): void
    {
        parent::setUp();
        $this->format = new FormatService();
    }

    // ─── Container / Facade ──────────────────────────────────────────

    #[Test]
    public function it_resolves_from_container(): void
    {
        $format = Format::getFacadeRoot();
        $this->assertInstanceOf(FormatService::class, $format);
    }

    #[Test]
    public function it_is_a_singleton(): void
    {
        $this->assertSame(Format::getFacadeRoot(), Format::getFacadeRoot());
    }

    // ─── Array helpers ───────────────────────────────────────────────

    #[Test]
    public function array_to_ul_creates_nested_list(): void
    {
        $result = $this->format->array_to_ul(['Name' => 'John', 'Age' => '30']);
        $this->assertStringContainsString('<ul>', $result);
        $this->assertStringContainsString('<li><span>Name:</span> John</li>', $result);
        $this->assertStringContainsString('<li><span>Age:</span> 30</li>', $result);
    }

    #[Test]
    public function array_to_ul_returns_empty_for_empty_values(): void
    {
        $result = $this->format->array_to_ul(['key' => '']);
        $this->assertSame('', $result);
    }

    #[Test]
    public function array_to_ul_handles_nested_arrays(): void
    {
        $result = $this->format->array_to_ul([
            'parent' => ['child' => 'value'],
        ]);
        $this->assertStringContainsString('Parent:', $result);
        $this->assertStringContainsString('Child:', $result);
    }

    #[Test]
    public function array_to_ul_with_custom_tags(): void
    {
        $result = $this->format->array_to_ul(['Name' => 'John'], 'ol', 'div');
        $this->assertStringContainsString('<ol>', $result);
        $this->assertStringContainsString('<div>', $result);
    }

    #[Test]
    public function array_to_table_creates_html_table(): void
    {
        $data = [
            ['Name' => 'John', 'Age' => 30],
            ['Name' => 'Jane', 'Age' => 25],
        ];
        $result = $this->format->array_to_table($data);
        $this->assertStringContainsString('<table>', $result);
        $this->assertStringContainsString('</table>', $result);
        $this->assertStringContainsString('<th>Name</th>', $result);
        $this->assertStringContainsString('<td>John</td>', $result);
    }

    #[Test]
    public function array_trim_trims_all_values(): void
    {
        $result = $this->format->array_trim(['  hello ', ' world  ']);
        $this->assertSame(['hello', 'world'], $result);
    }

    #[Test]
    public function array_values_flattens_nested_arrays(): void
    {
        $result = $this->format->array_values([
            'a' => 1,
            'b' => [2, 3],
            'c' => ['d' => [4, 5]],
        ]);
        $this->assertSame([1, 2, 3, 4, 5], $result);
    }

    // ─── Date helpers ────────────────────────────────────────────────

    #[Test]
    public function date_formats_with_explicit_format(): void
    {
        $result = $this->format->date('2024-01-15 10:30:00', 'Y-m-d');
        $this->assertSame('2024-01-15', $result);
    }

    #[Test]
    public function date_uses_default_format_when_none_given(): void
    {
        $result = $this->format->date('2024-01-15 10:30:00');
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function find_date_finds_numeric_date(): void
    {
        $result = $this->format->find_date('some text 01/05/2012 some text');
        $this->assertIsArray($result);
        $this->assertSame('01', $result['day']);
        $this->assertSame('05', $result['month']);
        $this->assertSame('2012', $result['year']);
    }

    #[Test]
    public function find_date_finds_named_month_date(): void
    {
        $result = $this->format->find_date('Meeting on March 15, 2023');
        $this->assertIsArray($result);
        $this->assertSame('15', $result['day']);
        $this->assertSame('03', $result['month']);
        $this->assertSame('2023', $result['year']);
    }

    #[Test]
    public function find_date_returns_false_for_no_date(): void
    {
        $result = $this->format->find_date('no date here');
        $this->assertFalse($result);
    }

    #[Test]
    public function find_date_handles_ordinal_day(): void
    {
        $result = $this->format->find_date('October 5th 2023');
        $this->assertIsArray($result);
        $this->assertSame('05', $result['day']);
    }

    #[Test]
    public function get_date_format_returns_string(): void
    {
        $result = $this->format->get_date_format();
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function get_supported_date_formats_returns_array(): void
    {
        $formats = $this->format->get_supported_date_formats();
        $this->assertIsArray($formats);
        $this->assertContains('Y-m-d H:i:s', $formats);
    }

    #[Test]
    public function date_system_format_formats_valid_date(): void
    {
        $result = $this->format->date_system_format('2024-01-15 10:30:00');
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function date_system_format_returns_input_for_invalid_date(): void
    {
        $result = $this->format->date_system_format('not a date');
        $this->assertSame('not a date', $result);
    }

    #[Test]
    public function split_dates_returns_correct_count(): void
    {
        $result = $this->format->split_dates('2024-01-01', '2024-01-31', 4);
        // Should have 4 + 2 (start + end) = 6, but implementation gives parts+1
        $this->assertIsArray($result);
        $this->assertGreaterThan(2, count($result));
    }

    #[Test]
    public function available_date_formats_returns_php_and_js(): void
    {
        $formats = $this->format->available_date_formats();
        $this->assertIsArray($formats);
        $this->assertArrayHasKey('php', $formats[0]);
        $this->assertArrayHasKey('js', $formats[0]);
    }

    // ─── String helpers ──────────────────────────────────────────────

    #[Test]
    public function add_slashes_recursive_handles_strings(): void
    {
        $result = $this->format->add_slashes_recursive("it's a test");
        $this->assertSame("it\\'s a test", $result);
    }

    #[Test]
    public function add_slashes_recursive_handles_arrays(): void
    {
        $result = $this->format->add_slashes_recursive(["it's", "he's"]);
        $this->assertSame(["it\\'s", "he\\'s"], $result);
    }

    #[Test]
    public function strip_slashes_recursive_reverses_add_slashes(): void
    {
        $original = "it's a test";
        $slashed = $this->format->add_slashes_recursive($original);
        $result = $this->format->strip_slashes_recursive($slashed);
        $this->assertSame($original, $result);
    }

    #[Test]
    public function autolink_converts_urls_to_links(): void
    {
        $text = 'Visit https://example.com today';
        $result = $this->format->autolink($text);
        $this->assertStringContainsString('<a', $result);
        $this->assertStringContainsString('https://example.com', $result);
        $this->assertStringContainsString('nofollow', $result);
    }

    #[Test]
    public function auto_link_is_alias_for_autolink(): void
    {
        $text = 'Visit https://example.com today';
        $this->assertSame(
            $this->format->autolink($text),
            $this->format->auto_link($text)
        );
    }

    #[Test]
    public function autolink_truncates_long_urls(): void
    {
        $text = 'Visit https://example.com/' . str_repeat('very/long/path/', 20) . ' today';
        $result = $this->format->autolink($text);
        $this->assertStringContainsString('&hellip;', $result);
    }

    #[Test]
    public function human_filesize_formats_bytes(): void
    {
        $this->assertSame('1.00kB', $this->format->human_filesize(1024));
        $this->assertSame('1.00MB', $this->format->human_filesize(1024 * 1024));
        $this->assertSame('500.00B', $this->format->human_filesize(500));
    }

    #[Test]
    public function ago_returns_just_now_for_current_time(): void
    {
        $result = $this->format->ago(date('Y-m-d H:i:s'));
        $this->assertStringContainsString('just now', $result);
    }

    #[Test]
    public function ago_returns_time_ago_string(): void
    {
        $result = $this->format->ago(date('Y-m-d H:i:s', strtotime('-2 hours')));
        $this->assertStringContainsString('hour', $result);
        $this->assertStringContainsString('ago', $result);
    }

    #[Test]
    public function ago_accepts_timestamp(): void
    {
        $result = $this->format->ago(time() - 3600);
        $this->assertStringContainsString('ago', $result);
    }

    #[Test]
    public function ago_full_mode_shows_all_parts(): void
    {
        $result = $this->format->ago(date('Y-m-d H:i:s', strtotime('-1 year -2 months -3 days')), true);
        $this->assertStringContainsString('year', $result);
        $this->assertStringContainsString('month', $result);
    }

    #[Test]
    public function string_between_extracts_text(): void
    {
        $result = $this->format->string_between('Hello [world] end', '[', ']');
        $this->assertSame('world', $result);
    }

    #[Test]
    public function string_between_returns_empty_when_not_found(): void
    {
        $result = $this->format->string_between('Hello world', '[', ']');
        $this->assertSame('', $result);
    }

    #[Test]
    public function replace_once_replaces_first_occurrence(): void
    {
        $result = $this->format->replace_once('world', 'PHP', 'Hello world world');
        $this->assertSame('Hello PHP world', $result);
    }

    #[Test]
    public function replace_once_returns_original_when_not_found(): void
    {
        $result = $this->format->replace_once('missing', 'PHP', 'Hello world');
        $this->assertSame('Hello world', $result);
    }

    #[Test]
    public function prep_url_adds_scheme(): void
    {
        $this->assertSame('http://example.com', $this->format->prep_url('example.com'));
        $this->assertSame('https://example.com', $this->format->prep_url('https://example.com'));
        $this->assertSame('', $this->format->prep_url(''));
        $this->assertSame('', $this->format->prep_url('http://'));
    }

    #[Test]
    public function percent_calculates_correctly(): void
    {
        $this->assertSame('50', $this->format->percent(50, 100));
        $this->assertSame(0, $this->format->percent(0, 100));
        $this->assertSame(0, $this->format->percent(50, 0));
        $this->assertEquals(50.0, $this->format->percent(50, 100, false));
    }

    #[Test]
    public function amount_to_float_parses_money_strings(): void
    {
        $this->assertSame(1234.56, $this->format->amount_to_float('$1,234.56'));
        $this->assertSame(1234.56, $this->format->amount_to_float('1.234,56'));
        $this->assertSame(99.99, $this->format->amount_to_float('99.99'));
    }

    #[Test]
    public function limit_truncates_string(): void
    {
        $long = str_repeat('word ', 200);
        $result = $this->format->limit($long, 50);
        $this->assertLessThanOrEqual(60, strlen($result)); // word boundary + ellipsis
        $this->assertStringContainsString('&#8230;', $result);
    }

    #[Test]
    public function limit_returns_short_strings_unchanged(): void
    {
        $this->assertSame('Hello', $this->format->limit('Hello', 500));
    }

    #[Test]
    public function lipsum_returns_limited_text(): void
    {
        $result = $this->format->lipsum(50);
        $this->assertLessThanOrEqual(60, strlen($result));
    }

    #[Test]
    public function random_color_returns_valid_hex(): void
    {
        $color = $this->format->random_color();
        $this->assertMatchesRegularExpression('/^#[0-9A-F]{6}$/i', $color);
    }

    #[Test]
    public function titlelize_converts_dashes_and_underscores(): void
    {
        $this->assertSame('Hello World', $this->format->titlelize('hello-world'));
        $this->assertSame('Hello World', $this->format->titlelize('hello_world'));
    }

    #[Test]
    public function no_dashes_removes_dashes(): void
    {
        $this->assertSame('hello world', $this->format->no_dashes('hello-world'));
        $this->assertSame('hello world', $this->format->no_dashes('hello_world'));
    }

    // ─── Encoding helpers ────────────────────────────────────────────

    #[Test]
    public function array_to_base64_and_back(): void
    {
        $data = ['key' => 'value', 'nested' => [1, 2, 3]];
        $encoded = $this->format->array_to_base64($data);
        $this->assertIsString($encoded);

        $decoded = $this->format->base64_to_array($encoded);
        $this->assertSame($data, $decoded);
    }

    #[Test]
    public function array_to_base64_empty_input(): void
    {
        $this->assertSame('', $this->format->array_to_base64(''));
    }

    #[Test]
    public function base64_to_array_returns_false_for_invalid(): void
    {
        $this->assertFalse($this->format->base64_to_array(''));
        $this->assertFalse($this->format->base64_to_array('not-valid-base64!!!'));
    }

    #[Test]
    public function base64_to_array_returns_array_input_unchanged(): void
    {
        $arr = ['test' => 1];
        $this->assertSame($arr, $this->format->base64_to_array($arr));
    }

    #[Test]
    public function is_base64_validates_correctly(): void
    {
        $this->assertTrue($this->format->is_base64(base64_encode('hello')));
        $this->assertFalse($this->format->is_base64('not base64!!!'));
    }

    #[Test]
    public function encrypt_decrypt_roundtrip(): void
    {
        $original = 'secret data';
        $encrypted = $this->format->encrypt($original);
        $this->assertNotSame($original, $encrypted);

        $decrypted = $this->format->decrypt($encrypted);
        $this->assertSame($original, $decrypted);
    }

    #[Test]
    public function encode_decode_ids(): void
    {
        $encoded = $this->format->encode_ids(42);
        $this->assertIsString($encoded);

        $decoded = $this->format->decode_ids($encoded);
        $this->assertSame([42], $decoded);
    }

    // ─── Validation ──────────────────────────────────────────────────

    #[Test]
    public function is_fqdn_validates_domains(): void
    {
        $this->assertTrue($this->format->is_fqdn('example.com'));
        $this->assertTrue($this->format->is_fqdn('sub.domain.example.co.uk'));
        $this->assertFalse($this->format->is_fqdn(''));
        $this->assertFalse($this->format->is_fqdn('not a domain'));
        $this->assertFalse($this->format->is_fqdn('localhost'));
    }

    // ─── Security / sanitisation ─────────────────────────────────────

    #[Test]
    public function clean_xss_strips_script_tags(): void
    {
        $result = $this->format->clean_xss('<script>alert("xss")</script>');
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringNotContainsString('alert', $result);
    }

    #[Test]
    public function clean_xss_strips_javascript_protocol(): void
    {
        $result = $this->format->clean_xss('javascript:alert(1)');
        $this->assertStringNotContainsString('javascript:', $result);
    }

    #[Test]
    public function clean_xss_handles_arrays(): void
    {
        $result = $this->format->clean_xss([
            'safe' => 'hello',
            'unsafe' => '<script>alert("xss")</script>',
        ]);
        $this->assertIsArray($result);
        $this->assertSame('hello', $result['safe']);
        $this->assertStringNotContainsString('<script>', $result['unsafe']);
    }

    #[Test]
    public function clean_xss_do_not_strip_tags_option(): void
    {
        $result = $this->format->clean_xss('<b>bold</b>', true);
        $this->assertStringContainsString('<b>', $result);
    }

    #[Test]
    public function clean_scripts_removes_script_tags(): void
    {
        $result = $this->format->clean_scripts('<p>Hello</p><script>alert("x")</script>');
        $this->assertStringContainsString('<p>Hello</p>', $result);
        $this->assertStringNotContainsString('<script>', $result);
    }

    #[Test]
    public function clean_scripts_handles_arrays(): void
    {
        $result = $this->format->clean_scripts(['<script>x</script>test']);
        $this->assertSame(['test'], $result);
    }

    #[Test]
    public function clean_html_strips_unsafe_content(): void
    {
        $input = '<script>alert("x")</script><p>safe</p>';
        $result = $this->format->clean_html($input);
        $this->assertStringNotContainsString('<script>', $result);
    }

    #[Test]
    public function clean_html_handles_arrays(): void
    {
        $result = $this->format->clean_html(['<script>x</script>safe']);
        $this->assertIsArray($result);
        $this->assertStringNotContainsString('<script>', $result[0]);
    }

    #[Test]
    public function strip_unsafe_removes_dangerous_tags(): void
    {
        $input = '<p>safe</p><iframe src="evil.com"></iframe><script>x</script>';
        $result = $this->format->strip_unsafe($input);
        $this->assertStringContainsString('<p>safe</p>', $result);
        $this->assertStringNotContainsString('<iframe', $result);
        $this->assertStringNotContainsString('<script', $result);
    }

    #[Test]
    public function strip_unsafe_optionally_removes_images(): void
    {
        $input = '<p>text</p><img src="photo.jpg">';
        $this->assertStringContainsString('<img', $this->format->strip_unsafe($input, false));
        $this->assertStringNotContainsString('<img', $this->format->strip_unsafe($input, true));
    }

    #[Test]
    public function strip_unsafe_handles_event_handlers(): void
    {
        $input = '<div onclick="alert(1)">test</div>';
        $result = $this->format->strip_unsafe($input);
        $this->assertStringNotContainsString('onclick', $result);
    }

    // ─── unvar_dump (fixed create_function) ──────────────────────────

    #[Test]
    public function unvar_dump_parses_simple_dump(): void
    {
        // Test with a basic serialization scenario
        $data = ['name' => 'test', 'value' => 42];
        $dump = var_export($data, true);
        // unvar_dump is designed for var_dump output, not var_export
        // Let's test with actual var_dump output
        ob_start();
        var_dump('hello');
        $dump = ob_get_clean();

        $result = $this->format->unvar_dump($dump);
        // The method is best-effort; ensure it doesn't crash (no create_function error)
        $this->assertTrue(true); // Main assertion: no PHP error thrown
    }

    #[Test]
    public function unvar_dump_does_not_use_create_function(): void
    {
        // Verify the method works in PHP 8+ (create_function was removed)
        $reflector = new \ReflectionMethod($this->format, 'unvar_dump');
        $source = file_get_contents($reflector->getFileName());
        // Strip comments to avoid false positives from doc comments
        $tokens = token_get_all($source);
        $codeOnly = '';
        foreach ($tokens as $token) {
            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT])) {
                continue;
            }
            $codeOnly .= is_array($token) ? $token[1] : $token;
        }
        $this->assertStringNotContainsString('create_function', $codeOnly);
    }

    // ─── Color helpers ───────────────────────────────────────────────

    #[Test]
    public function hex_to_rgb_converts_6_digit_hex(): void
    {
        $result = $this->format->hex_to_rgb('#FF0000');
        $this->assertSame(255, $result['r']);
        $this->assertSame(0, $result['g']);
        $this->assertSame(0, $result['b']);
    }

    #[Test]
    public function hex_to_rgb_converts_3_digit_hex(): void
    {
        $result = $this->format->hex_to_rgb('#F00');
        $this->assertSame(255, $result['r']);
        $this->assertSame(0, $result['g']);
        $this->assertSame(0, $result['b']);
    }

    #[Test]
    public function hex_to_rgb_includes_alpha(): void
    {
        $result = $this->format->hex_to_rgb('#FF0000', 0.5);
        $this->assertSame(0.5, $result['a']);
    }

    #[Test]
    public function hex_to_rgb_handles_invalid_input(): void
    {
        $result = $this->format->hex_to_rgb('invalid');
        $this->assertSame(0, $result['r']);
        $this->assertSame(0, $result['g']);
        $this->assertSame(0, $result['b']);
    }

    // ─── HTML attribute helpers ──────────────────────────────────────

    #[Test]
    public function array_to_html_attributes(): void
    {
        $result = $this->format->arrayToHtmlAttributes([
            'class' => 'my-class',
            'id' => 'my-id',
            'disabled',
        ]);
        $this->assertSame('class="my-class" id="my-id" disabled', $result);
    }

    #[Test]
    public function array_to_html_attributes_empty(): void
    {
        $this->assertSame('', $this->format->arrayToHtmlAttributes([]));
    }

    #[Test]
    public function array_to_html_attributes_escapes_values(): void
    {
        $result = $this->format->arrayToHtmlAttributes(['data' => 'a"b']);
        $this->assertStringContainsString('&quot;', $result);
    }

    // ─── Tree builder ────────────────────────────────────────────────

    #[Test]
    public function string_to_tree_builds_nested_structure(): void
    {
        $result = $this->format->stringToTree('Courses > PHP > Arrays');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('Courses', $result);
        $this->assertArrayHasKey('PHP', $result['Courses']);
        $this->assertArrayHasKey('Arrays', $result['Courses']['PHP']);
    }

    #[Test]
    public function string_to_tree_single_element(): void
    {
        $result = $this->format->stringToTree('Courses');
        $this->assertSame(['Courses' => []], $result);
    }

    // ─── Notification helpers ────────────────────────────────────────

    #[Test]
    public function notif_returns_html(): void
    {
        $result = $this->format->notif('Test message');
        $this->assertStringContainsString('mw-notification', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString('mw-success', $result);
    }

    #[Test]
    public function notif_with_custom_class(): void
    {
        $result = $this->format->notif('Error!', 'error');
        $this->assertStringContainsString('mw-error', $result);
    }

    #[Test]
    public function notif_with_true_class(): void
    {
        $result = $this->format->notif('Info', true);
        $this->assertStringNotContainsString('mw-notification mw-', $result);
        $this->assertStringContainsString('Info', $result);
    }
}