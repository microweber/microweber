<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-102 / AI-107 / TICKET-BD + TICKET-BH — DB indexes regression
 * coverage.
 *
 * Pins:
 *   - The Cart compound index migration `add_ai_107_compound_indexes_to_cart`
 *     declares the `(session_id, rel_id)` compound index named
 *     `cart_session_rel_id_index` (schema-mapped from the brief's
 *     `cart_items(cart_id, product_id)`).
 *   - The Newsletter index migration `add_ai_107_index_to_newsletter_subscribers`
 *     declares the `(email, is_subscribed)` compound index named
 *     `newsletter_subscribers_email_is_subscribed_index` (schema-
 *     mapped from the brief's `(email, confirmed_at)`).
 *   - Both migrations carry the schema-mapping rationale doc-comment
 *     so future maintainers know why the column names differ from
 *     the brief.
 *
 * Style after the cycle-52..101 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Ai107DbIndexesContractTest extends TestCase
{
    private const CART_MIGRATION = 'Modules/Cart/database/migrations/2026_05_09_000001_add_ai_107_compound_indexes_to_cart.php';
    private const NL_MIGRATION   = 'Modules/Newsletter/database/migrations/2026_05_09_000001_add_ai_107_index_to_newsletter_subscribers.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function cart_migration_declares_session_rel_id_compound_index(): void
    {
        $src = $this->read(self::CART_MIGRATION);

        // The compound index must be declared via Blueprint::index()
        // with an explicit name (not auto-generated, so we can pin
        // it).
        $this->assertMatchesRegularExpression(
            "/\\\$table->index\\(\\s*\\['session_id',\\s*'rel_id'\\],\\s*'cart_session_rel_id_index'\\s*\\)/",
            $src,
            self::CART_MIGRATION . ': must declare `index([\'session_id\', \'rel_id\'], \'cart_session_rel_id_index\')`'
        );

        // Schema::hasIndex guard so re-running the migration doesn't
        // crash with "duplicate key" errors.
        $this->assertStringContainsString(
            "Schema::hasIndex('cart', 'cart_session_rel_id_index')",
            $src,
            self::CART_MIGRATION . ': must guard the index() call with Schema::hasIndex(...)'
        );

        // down() must drop the index symmetrically.
        $this->assertStringContainsString(
            "\$table->dropIndex('cart_session_rel_id_index')",
            $src,
            self::CART_MIGRATION . ': down() must drop the index symmetrically'
        );

        // Schema-mapping rationale must be documented (the brief used
        // different column names; a doc-comment captures the mapping).
        $this->assertStringContainsString(
            'cart_items(cart_id, product_id)',
            $src,
            self::CART_MIGRATION . ': must document the schema mapping from the brief'
        );
    }

    #[Test]
    public function newsletter_migration_declares_email_subscribed_compound_index(): void
    {
        $src = $this->read(self::NL_MIGRATION);

        $this->assertMatchesRegularExpression(
            "/\\\$table->index\\(\\s*\\['email',\\s*'is_subscribed'\\],\\s*'newsletter_subscribers_email_is_subscribed_index'\\s*\\)/",
            $src,
            self::NL_MIGRATION . ': must declare `index([\'email\', \'is_subscribed\'], \'newsletter_subscribers_email_is_subscribed_index\')`'
        );

        $this->assertStringContainsString(
            "Schema::hasIndex('newsletter_subscribers', 'newsletter_subscribers_email_is_subscribed_index')",
            $src,
            self::NL_MIGRATION . ': must guard the index() call with Schema::hasIndex(...)'
        );

        $this->assertStringContainsString(
            "\$table->dropIndex('newsletter_subscribers_email_is_subscribed_index')",
            $src,
            self::NL_MIGRATION . ': down() must drop the index symmetrically'
        );

        // Schema-mapping rationale (brief said `confirmed_at`, schema
        // has `is_subscribed`).
        $this->assertStringContainsString(
            'confirmed_at',
            $src,
            self::NL_MIGRATION . ': must document the schema mapping from the brief'
        );
    }

    #[Test]
    public function both_migrations_carry_audit_trail(): void
    {
        foreach ([self::CART_MIGRATION, self::NL_MIGRATION] as $rel) {
            $src = $this->read($rel);
            $this->assertStringContainsString(
                'AI-107',
                $src,
                "{$rel}: must carry the AI-107 audit-trail comment"
            );
            $this->assertStringContainsString(
                'cycle-102',
                $src,
                "{$rel}: must carry the cycle-102 audit-trail comment"
            );
        }
    }
}
