<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport\Tests;

use MicroweberPackages\FilamentModalTeleport\ModalTeleportPlugin;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Unit tests for the ModalTeleportPlugin class.
 */
class ModalTeleportPluginTest extends TestCase
{
    #[Test]
    public function plugin_can_be_instantiated_via_make(): void
    {
        $plugin = ModalTeleportPlugin::make();
        $this->assertInstanceOf(ModalTeleportPlugin::class, $plugin);
    }

    #[Test]
    public function plugin_has_correct_id(): void
    {
        $plugin = ModalTeleportPlugin::make();
        $this->assertSame('mw-modal-teleport', $plugin->getId());
    }

    #[Test]
    public function plugin_implements_filament_plugin_contract(): void
    {
        $plugin = ModalTeleportPlugin::make();
        $this->assertInstanceOf(\Filament\Contracts\Plugin::class, $plugin);
    }
}
