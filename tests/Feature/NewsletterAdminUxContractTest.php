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
        // Acceptance #5 — ->poll('10s', condition: fn () => ...) NOT bare ->poll('10s').
        $this->assertStringContainsString(
            "->poll('10s', condition:",
            $this->campaignSrc,
            'CampaignResource: ->poll() must carry a condition: gate to skip when no campaigns are in transition'
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
        // Acceptance #5 negative — bare unconditional poll is gone.
        // Strip PHP `//` and `#` comment lines before regex match so the
        // doc-comment text describing the prior shape doesn't trigger.
        $strippedSrc = preg_replace('/^\\s*\\/\\/.*$/m', '', $this->campaignSrc);
        $this->assertDoesNotMatchRegularExpression(
            "/->poll\\('10s'\\)/",
            $strippedSrc,
            'CampaignResource: bare unconditional ->poll(\'10s\') must not appear in live code'
        );
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
