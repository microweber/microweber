<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * audit-test 2026-05-08 PM TASK-019 / TICKET-AN regression coverage.
 *
 * Pins acceptance #6 of the Newsletter admin UX cluster:
 *   - CampaignResource and SubscribersResource have non-empty filters.
 *   - Subscriber badge colour table includes pending_confirmation /
 *     complained / invalid_email.
 *   - CampaignResource ->poll() carries a `condition:` gate.
 *
 * Style after the cycle-52/53 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class NewsletterAdminUxContractTest extends TestCase
{
    private string $campaignSrc;
    private string $subscribersSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->campaignSrc = file_get_contents(base_path(
            'Modules/Newsletter/Filament/Admin/Resources/CampaignResource.php'
        ));
        $this->subscribersSrc = file_get_contents(base_path(
            'Modules/Newsletter/Filament/Admin/Resources/SubscribersResource.php'
        ));
    }

    #[Test]
    public function campaign_resource_has_status_list_and_sender_filters(): void
    {
        // Acceptance #2 — filters are non-empty.
        $this->assertStringContainsString(
            "SelectFilter::make('status')",
            $this->campaignSrc,
            'CampaignResource: status SelectFilter must be present'
        );
        $this->assertStringContainsString(
            "SelectFilter::make('list_id')",
            $this->campaignSrc,
            'CampaignResource: list_id SelectFilter must be present'
        );
        $this->assertStringContainsString(
            "SelectFilter::make('sender_account_id')",
            $this->campaignSrc,
            'CampaignResource: sender_account_id SelectFilter must be present'
        );
    }

    #[Test]
    public function subscribers_resource_has_status_and_lists_filters(): void
    {
        // Acceptance #2 — filters are non-empty.
        $this->assertStringContainsString(
            "SelectFilter::make('status')",
            $this->subscribersSrc,
            'SubscribersResource: status SelectFilter must be present'
        );
        $this->assertStringContainsString(
            "SelectFilter::make('lists')",
            $this->subscribersSrc,
            'SubscribersResource: lists SelectFilter must be present'
        );
    }

    #[Test]
    public function subscriber_badge_color_covers_all_canonical_states(): void
    {
        // Acceptance #3 — badge colours include pending_confirmation,
        // complained, invalid_email + the three pre-existing states.
        $required = [
            'active',
            'unsubscribed',
            'bounced',
            'pending_confirmation',
            'complained',
            'invalid_email',
        ];
        foreach ($required as $state) {
            $this->assertMatchesRegularExpression(
                '/[\'"]'.preg_quote($state, '/').'[\'"]\\s*=>\\s*[\'"](?:success|danger|warning|info|gray)[\'"]/',
                $this->subscribersSrc,
                "SubscribersResource: state '{$state}' must map to a non-default colour in the match() table"
            );
        }
    }

    #[Test]
    public function campaign_resource_poll_carries_a_condition_gate(): void
    {
        // Acceptance #5 — Filament's Table::poll() accepts only ONE parameter
        // (`Closure|string|null $interval`); cycle-54 first attempt used the
        // named-arg `condition:` form which doesn't exist, raising a fatal
        // error at runtime (HTTP 500 on /admin/newsletter/campaigns).
        // Cycle-56 regression fix wraps the gate in a Closure that returns
        // '10s' when active campaigns exist, or null to disable polling.
        // Strip PHP `//` comment lines before assertions so the doc-comment
        // text describing the prior bug doesn't trip the negative checks.
        $strippedSrc = preg_replace('/^\\s*\\/\\/.*$/m', '', $this->campaignSrc);

        // Positive: ->poll(fn () => ... ? '10s' : null) is the correct shape.
        $this->assertMatchesRegularExpression(
            "/->poll\\(\\s*fn\\s*\\(\\)\\s*=>/",
            $strippedSrc,
            'CampaignResource: ->poll() must carry a Closure $interval that returns \'10s\' when polling should run, null otherwise'
        );

        // Spot-check the gated states.
        $this->assertStringContainsString(
            'NewsletterCampaign::STATUS_QUEUED',
            $this->campaignSrc,
            'CampaignResource: poll gate should reference STATUS_QUEUED'
        );
        $this->assertStringContainsString(
            'NewsletterCampaign::STATUS_SENDING',
            $this->campaignSrc,
            'CampaignResource: poll gate should reference STATUS_SENDING'
        );

        // Negative #1: cycle-54's broken `->poll('10s', condition:` named-arg
        // form must NOT appear in live code (would raise a fatal error).
        $this->assertDoesNotMatchRegularExpression(
            "/->poll\\('10s'\\s*,\\s*condition:/",
            $strippedSrc,
            'CampaignResource: ->poll(\'10s\', condition: ...) named-arg form raises a fatal Error at runtime — Filament Table::poll() accepts only one parameter'
        );

        // Negative #2: bare unconditional ->poll('10s') is gone.
        $this->assertDoesNotMatchRegularExpression(
            "/->poll\\('10s'\\)/",
            $strippedSrc,
            'CampaignResource: bare unconditional ->poll(\'10s\') must not appear in live code'
        );
    }

    #[Test]
    public function filament_table_poll_signature_accepts_one_parameter(): void
    {
        // Cycle-56 regression-prevention: pin the Filament contract that
        // Table::poll() accepts a SINGLE $interval (Closure|string|null).
        // If a future Filament upgrade adds a `condition:` named arg this
        // test will fail loudly — at which point the gate-shape can be
        // upgraded back to the cleaner named-arg form. Until then the
        // Closure-returning-string-or-null pattern is the contract.
        $reflection = new \ReflectionMethod('Filament\\Tables\\Table', 'poll');
        $this->assertSame(
            1,
            $reflection->getNumberOfParameters(),
            'Filament Table::poll() must accept exactly 1 parameter; if this changed, the named-arg poll() shape may now be valid'
        );

        $param = $reflection->getParameters()[0];
        $this->assertSame('interval', $param->getName());
        // Type is `Closure|string|null` — verify Closure is accepted.
        $type = (string) $param->getType();
        $this->assertStringContainsString('Closure', $type);
    }

    #[Test]
    public function export_forms_use_section_grouping(): void
    {
        // Acceptance #4 — Section::make('Columns to export') + Section::make('Export options').
        $this->assertStringContainsString(
            "Section::make('Columns to export')",
            $this->campaignSrc,
            'CampaignResource: export form must use Section::make(\'Columns to export\')'
        );
        $this->assertStringContainsString(
            "Section::make('Export options')",
            $this->campaignSrc,
            'CampaignResource: export form must use Section::make(\'Export options\')'
        );
        $this->assertStringContainsString(
            "Section::make('Columns to export')",
            $this->subscribersSrc,
            'SubscribersResource: export form must use Section::make(\'Columns to export\')'
        );
        $this->assertStringContainsString(
            "Section::make('Export options')",
            $this->subscribersSrc,
            'SubscribersResource: export form must use Section::make(\'Export options\')'
        );
    }

    #[Test]
    public function campaign_actions_keep_contextual_first_delete_inside_action_group(): void
    {
        // Acceptance #1 — Edit/View/Cancel actions must come BEFORE the
        // ActionGroup() in the actions() array. DeleteAction must live
        // inside the ActionGroup. Use byte-offset comparison.
        $editPos = strpos($this->campaignSrc, "Tables\\Actions\\Action::make('edit')");
        $viewPos = strpos($this->campaignSrc, "Tables\\Actions\\Action::make('view')");
        $cancelPos = strpos($this->campaignSrc, "Tables\\Actions\\Action::make('cancel')");
        $actionGroupPos = strpos($this->campaignSrc, 'ActionGroup::make([');
        $deletePos = strpos($this->campaignSrc, 'Tables\\Actions\\DeleteAction::make()');

        $this->assertNotFalse($editPos, 'CampaignResource: edit action must exist');
        $this->assertNotFalse($viewPos, 'CampaignResource: view action must exist');
        $this->assertNotFalse($cancelPos, 'CampaignResource: cancel action must exist');
        $this->assertNotFalse($actionGroupPos, 'CampaignResource: ActionGroup must exist');
        $this->assertNotFalse($deletePos, 'CampaignResource: DeleteAction must exist');

        $this->assertLessThan($actionGroupPos, $editPos, 'edit must come before ActionGroup');
        $this->assertLessThan($actionGroupPos, $viewPos, 'view must come before ActionGroup');
        $this->assertLessThan($actionGroupPos, $cancelPos, 'cancel must come before ActionGroup');
        $this->assertGreaterThan($actionGroupPos, $deletePos, 'DeleteAction must live inside ActionGroup (i.e. after ActionGroup::make([ marker)');
    }
}
