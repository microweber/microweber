<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\StoredXssStripper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StoredXssStripperTest extends TestCase
{
    #[Test]
    public function it_removes_event_handlers(): void
    {
        $cases = [
            ['<img src=x onerror=alert(1)>', '<img src=x>'],
            ['<button onclick="x()">btn</button>', '<button>btn</button>'],
            ['<a href="x" onmouseover=alert(1)>x</a>', '<a href="x">x</a>'],
        ];
        foreach ($cases as [$in, $expect]) {
            $this->assertSame($expect, StoredXssStripper::strip($in));
        }
    }

    #[Test]
    public function it_removes_script_blocks(): void
    {
        $this->assertSame('', StoredXssStripper::strip('<script>evil()</script>'));
        $this->assertSame(
            'before  after',
            StoredXssStripper::strip('before <script>x</script> after')
        );
    }

    #[Test]
    public function it_removes_svg_with_handlers(): void
    {
        $this->assertSame('', StoredXssStripper::strip('<svg onload=alert(1)></svg>'));
        $this->assertSame('', StoredXssStripper::strip('<svg><script>x</script></svg>'));
    }

    #[Test]
    public function it_preserves_plain_svg(): void
    {
        $this->assertSame(
            '<svg><circle r=10/></svg>',
            StoredXssStripper::strip('<svg><circle r=10/></svg>')
        );
    }

    #[Test]
    public function it_neutralizes_dangerous_url_schemes(): void
    {
        $this->assertSame(
            '<a href="#">x</a>',
            StoredXssStripper::strip('<a href="javascript:alert(1)">x</a>')
        );
        $this->assertSame(
            '<a href="#">x</a>',
            StoredXssStripper::strip('<a href="data:text/html,<script>x</script>">x</a>')
        );
        $this->assertSame(
            '<a href="#">x</a>',
            StoredXssStripper::strip('<a href="vbscript:msgbox">x</a>')
        );
    }

    #[Test]
    public function it_preserves_safe_urls(): void
    {
        $this->assertSame(
            '<a href="https://example.com">x</a>',
            StoredXssStripper::strip('<a href="https://example.com">x</a>')
        );
    }

    #[Test]
    public function it_handles_empty_string(): void
    {
        $this->assertSame('', StoredXssStripper::strip(''));
    }
}