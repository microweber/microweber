<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tests;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Workflow\WorkflowState;
use PHPUnit\Framework\Attributes\Test;

class BaseToolTest extends TestCase
{
    private function makeTool(): BaseTool
    {
        return new class extends BaseTool {
            public function __construct()
            {
                parent::__construct('test_tool', 'A test tool', []);
                $this->domain = 'testing';
                $this->requiredPermissions = ['view testing'];
            }

            protected function properties(): array
            {
                return [];
            }

            public function __invoke(mixed ...$args): string
            {
                $params = is_array($args[0] ?? null) ? $args[0] : $args;
                if (!empty($params['fail'])) {
                    return $this->handleError('failed');
                }

                return $this->handleSuccess((string) ($params['msg'] ?? 'ok'));
            }

            public function exposeTable(array $data, array $headers = []): string
            {
                return $this->formatAsHtmlTable($data, $headers);
            }

            public function exposeMoney(float $amount): string
            {
                return $this->formatMoney($amount, 'USD');
            }
        };
    }

    #[Test]
    public function metadata_accessors(): void
    {
        $tool = $this->makeTool();

        $this->assertSame('test_tool', $tool->getName());
        $this->assertSame('A test tool', $tool->getDescription());
        $this->assertSame('testing', $tool->getDomain());
        $this->assertSame(['view testing'], $tool->getRequiredPermissions());
        $this->assertTrue($tool->isAuthorized());
        $this->assertSame(500, $tool->getMaxTries());
        $this->assertSame([], $tool->getProperties());
    }

    #[Test]
    public function success_and_error_html_and_marker(): void
    {
        $tool = $this->makeTool();

        $ok = $tool(['msg' => 'done']);
        $this->assertStringContainsString('alert-success', $ok);
        $this->assertStringContainsString('done', $ok);

        $err = $tool(['fail' => true]);
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $err);
        $this->assertStringContainsString('alert-danger', $err);
        $this->assertStringContainsString('failed', $err);
    }

    #[Test]
    public function workflow_state_is_set_on_finish(): void
    {
        $tool = $this->makeTool();
        $state = new WorkflowState();
        $tool->setState($state);

        $tool(['msg' => 'x']);

        $this->assertTrue((bool) $state->get($tool::class . '_finished'));
    }

    #[Test]
    public function html_table_and_money_helpers(): void
    {
        $tool = $this->makeTool();

        $table = $tool->exposeTable(
            [['a' => 1, 'b' => 2]],
            ['A', 'B']
        );
        $this->assertStringContainsString('<table', $table);
        $this->assertStringContainsString('A', $table);

        $empty = $tool->exposeTable([]);
        $this->assertStringContainsString('No data found', $empty);

        $this->assertSame('12.50 USD', $tool->exposeMoney(12.5));
    }
}
