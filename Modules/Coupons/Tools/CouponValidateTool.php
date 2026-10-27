<?php

declare(strict_types=1);

namespace Modules\Coupons\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Coupons\Services\CouponService;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP read tool: validate a coupon code against a cart total and report whether
 * it can be applied (and the message/discount). Read-only — it uses
 * CouponService::canApplyCoupon(), which checks the rules WITHOUT consuming the
 * coupon or mutating the cart (applyCoupon does that, and is intentionally not
 * exposed here).
 */
class CouponValidateTool extends BaseTool
{
    protected string $domain = 'shop';
    protected array $requiredPermissions = ['view orders'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'coupon_validate',
            'Check whether a coupon code is valid for a given cart total and what '
            . 'discount it would give. Does NOT apply or consume the coupon.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'code',
                type: PropertyType::STRING,
                description: 'The coupon code to validate.',
                required: true,
            ),
            new ToolProperty(
                name: 'cart_total',
                type: PropertyType::STRING,
                description: 'The cart total to check the coupon against (default 0).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to validate coupons.');
        }

        $code = trim((string) ($args['code'] ?? ''));
        if ($code === '') {
            return $this->handleError('A coupon code is required.');
        }

        $cartTotal = (float) ($args['cart_total'] ?? 0);

        try {
            /** @var CouponService $service */
            $service = app(CouponService::class);
            $result = $service->canApplyCoupon($code, $cartTotal);

            $canApply = (bool) ($result['can_apply'] ?? false);
            $message = (string) ($result['message'] ?? ($canApply ? 'Valid.' : 'Not valid.'));

            $lines = [
                '<strong>Coupon:</strong> ' . htmlspecialchars($code),
                '<strong>Valid for total ' . number_format($cartTotal, 2) . ':</strong> ' . ($canApply ? 'yes' : 'no'),
                '<strong>Message:</strong> ' . htmlspecialchars($message),
            ];
            foreach (['discount', 'discount_amount', 'discount_type'] as $k) {
                if (isset($result[$k]) && $result[$k] !== '') {
                    $lines[] = '<strong>' . htmlspecialchars($k) . ':</strong> ' . htmlspecialchars((string) $result[$k]);
                }
            }

            return '<div class="coupon-validate"><ul><li>' . implode('</li><li>', $lines) . '</li></ul></div>';
        } catch (\Throwable $e) {
            return $this->handleError('Error validating coupon: ' . $e->getMessage());
        }
    }
}
