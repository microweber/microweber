<?php

namespace Modules\Cart\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Cart\Events\AddToCartEvent;
use Modules\Cart\Events\RemoveFromCartEvent;
use Modules\Cart\Exceptions\CartException;
use Modules\Cart\Exceptions\CartNotFoundException;
use Modules\Cart\Exceptions\InvalidCartItemException;
use Modules\Cart\Models\Cart;
use Modules\Cart\Repositories\CartRepository;

class CartService
{
    /**
     * @var \MicroweberPackages\App\LaravelApplication
     */
    protected $app;

    /**
     * @var CartRepository
     */
    protected $cartRepository;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
        $this->cartRepository = $this->app->cart_repository;
    }

    /**
     * Get cart items with optional filtering.
     *
     * @param array|string $params
     * @return array
     */
    public function getCart($params = []): array
    {
        $params2 = [];

        if (is_string($params)) {
            parse_str($params, $params2);
            $params = $params2;
        }

        $params['table'] = 'cart';
        $params['session_id'] = $this->app->user_manager->session_id();
        $params['limit'] = 10000;

        if (!isset($params['order_completed'])) {
            if (!isset($params['order_id'])) {
                $params['order_completed'] = 0;
            }
        } elseif (isset($params['order_completed']) && $params['order_completed'] === 'any') {
            unset($params['order_completed']);
        }

        $this->normalizeRelParams($params);

        try {
            $get = $this->app->database_manager->get($params);
        } catch (QueryException $e) {
            Log::error('Failed to retrieve cart items from database', [
                'exception' => $e->getMessage(),
                'params' => $params,
            ]);
            throw CartException::databaseOperationFailed('get', 'cart', $e);
        }

        if (isset($params['count']) && $params['count']) {
            return is_array($get) ? $get : [$get];
        }

        return $this->processCartItems($get, $params);
    }

    /**
     * Get cart items by order ID.
     *
     * @param int $orderId
     * @return array
     */
    public function getByOrderId(int $orderId): array
    {
        if ($orderId === 0) {
            return [];
        }

        $params = [
            'table' => 'cart',
            'order_id' => $orderId,
        ];

        try {
            $get = $this->app->database_manager->get($params);
        } catch (QueryException $e) {
            Log::error('Failed to retrieve cart items by order ID', [
                'exception' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            throw CartException::databaseOperationFailed('get', 'cart', $e);
        }

        if (!empty($get)) {
            foreach ($get as $k => $item) {
                if (is_array($item) && isset($item['custom_fields_data']) && $item['custom_fields_data'] != '') {
                    $item = $this->app->format->render_item_custom_fields_data($item);
                }

                if (!isset($item['item_image']) && is_array($item) && isset($item['rel_id']) && isset($item['rel_type']) && $item['rel_type'] == morph_name(\Modules\Content\Models\Content::class)) {
                    $item['item_image'] = get_picture($item['rel_id']);
                }

                if (!isset($item['item_image'])) {
                    $item['item_image'] = false;
                }

                $get[$k] = $item;
            }
        }

        return $get;
    }

    /**
     * Remove an item from the cart.
     *
     * @param array|int $data
     * @return array
     */
    public function removeItem($data): array
    {
        if (!is_array($data)) {
            $id = intval($data);
            $data = ['id' => $id];
        }

        if (!isset($data['id']) || $data['id'] == 0) {
            return ['error' => _e('Invalid cart item', true)];
        }

        $cart = [
            'id' => intval($data['id']),
            'session_id' => $this->app->user_manager->session_id(),
            'order_completed' => 0,
            'one' => 1,
            'limit' => 1,
        ];

        $checkCart = $this->getCart($cart);

        // Check if cart item exists (getCart returns single item when 'one' => 1)
        if (empty($checkCart) || !is_array($checkCart)) {
            return ['error' => _e('Item not removed from cart', true)];
        }

        // Check if item has an ID (means it was found)
        if (!isset($checkCart['id']) || $checkCart['id'] != $cart['id']) {
            return ['error' => _e('Item not removed from cart', true)];
        }

        $cartData = [];
        $findCart = Cart::where('id', $cart['id'])->first();
        if ($findCart) {
            $cartData = $findCart->toArray();
            $findCart->delete();
        }

        $this->clearCartCache();

        $cartSum = $this->cartRepository->getCartAmount();
        $cartQty = $this->cartRepository->getCartItemsCount();

        event(new RemoveFromCartEvent($cartData));

        return [
            'success' => _e('Item was removed from cart', true),
            'product' => $checkCart,
            'cart_sum' => $cartSum,
            'cart_items_quantity' => $cartQty,
        ];
    }

    /**
     * Update cart item quantity.
     *
     * @param array $data
     * @return array
     */
    public function updateItemQty(array $data): array
    {
        if (!isset($data['id'])) {
            return ['error' => _e('Invalid data', true)];
        }

        if (!isset($data['qty'])) {
            return ['error' => _e('Invalid data', true)];
        }

        $data['qty'] = intval($data['qty']);
        if ($data['qty'] < 1) {
            return ['error' => _e('Invalid product quantity', true)];
        }

        $cart = [
            'id' => intval($data['id']),
            'session_id' => $this->app->user_manager->session_id(),
            'order_completed' => 0,
            'one' => 1,
            'limit' => 1,
        ];

        $checkCart = $this->getCart($cart);

        if (empty($checkCart) || !is_array($checkCart)) {
            return ['error' => _e('Item not found', true)];
        }

        // Check if item was found (has ID matching)
        if (!isset($checkCart['id']) || $checkCart['id'] != $cart['id']) {
            return ['error' => _e('Item not found', true)];
        }

        $dataFields = $this->getContentDataForCartItem($checkCart);
        $quantityAdjustment = $this->applyQuantityConstraints($data['qty'], $dataFields);
        $cart['qty'] = $quantityAdjustment['qty'];
        $cartReturn = $checkCart;
        $cartReturn['qty'] = $cart['qty'];

    $cartDataToSave = [
            'qty' => $cart['qty'],
            'id' => $cart['id'],
        ];

        try {
            $this->app->database_manager->save('cart', $cartDataToSave);
        } catch (QueryException $e) {
            Log::error('Failed to update cart item quantity', [
                'exception' => $e->getMessage(),
                'cart_data' => $cartDataToSave,
            ]);
            throw CartException::databaseOperationFailed('save', 'cart', $e);
        }

        $cartSum = $this->cartRepository->getCartAmount();
            $cartQty = $this->cartRepository->getCartItemsCount();

            $this->clearCartCache();

            $result = [
                 'success' => _e('Item quantity changed', true),
                 'product' => $cartReturn,
                 'cart_sum' => $cartSum,
                 'cart_items_quantity' => $cartQty,
             ];

            if (!empty($quantityAdjustment['warnings'])) {
                $result['warnings'] = $quantityAdjustment['warnings'];
            }

            return $result;
    }

    /**
     * Empty the cart.
     *
     * @return array
     */
    public function emptyCart(): array
    {
        $sid = $this->app->user_manager->session_id();

        Cart::where('order_completed', 0)->where('session_id', $sid)->delete();

        $this->no_cache = true;
        $this->clearCartCache();

        $cartSum = $this->cartRepository->getCartAmount();
        $cartQty = $this->cartRepository->getCartItemsCount();

        return [
            'success' => 'Cart is emptied',
            'cart_sum' => $cartSum,
            'cart_items_quantity' => $cartQty,
        ];
    }

    /**
     * Delete cart by params.
     *
     * @param array|string $params
     * @return void
     */
    public function deleteCart($params): void
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }

        if (isset($params['session_id'])) {
            $id = $params['session_id'];
            Cart::where('session_id', $id)->whereNull('order_completed')->delete();
            Cart::where('session_id', $id)->where('order_completed', 0)->delete();
        }

        if (isset($params['order_id'])) {
            $id = $params['order_id'];
            Cart::where('order_id', $id)->delete();
        }

        $this->clearCartCache();
    }

    /**
     * Add or update item in cart.
     *
     * @param array $data
     * @return array
     */
    public function updateCart(array $data): array
    {
        $data = $this->normalizeCartData($data);

        $target = $this->resolveCartTarget($data);
        if (isset($target['error'])) {
            return $target;
        }

        $data['for'] = $target['for'];
        $data['for_id'] = $target['for_id'];
        $for = $data['for'];
        $forId = $data['for_id'];

        if ($forId === 0) {
            return ['error' => 'Invalid data for_id'];
        }

        $updateQty = $this->extractQty($data);
        $contentData = [];

        // Validate product and get content data
        $productValidation = $this->validateProduct($for, $forId, $data);
        if (isset($productValidation['error'])) {
            return $productValidation;
        }
        $contentData = $productValidation['content_data'] ?? [];
        $data['title'] = $productValidation['title'] ?? $data['title'];
        $canonicalPrice = $productValidation['canonical_price'] ?? null;

        // Process custom fields and price
        $customFieldsData = $this->processCustomFields($data, $for, $forId, $canonicalPrice);
        $foundPrice = $customFieldsData['price'];
        $priceModifierFields = $customFieldsData['price_modifiers'];
        $add = $customFieldsData['add'];

        // Apply price modifiers
        if ($foundPrice && $priceModifierFields) {
            foreach ($priceModifierFields as $modifier) {
                if (isset($modifier['selected_price_modifier_from_user'])) {
                    $foundPrice += floatval($modifier['selected_price_modifier_from_user']);
                }
            }
        }

        // Validate custom fields
        $validationResult = $this->validateCustomFields($data, $for, $forId, $add);
        if (!empty($validationResult)) {
            return $validationResult;
        }

        // Build cart data
        $cart = $this->buildCartData($data, $for, $forId, $foundPrice, $add);

        // Find or create cart item
        $findCart = $this->findExistingCartItem($cart);

        // Determine quantity
        $requestedQty = $this->calculateQuantity($findCart, $updateQty['new'], $updateQty['update']);
        $quantityAdjustment = $this->applyQuantityConstraints($requestedQty, $contentData);
        $cart['qty'] = $quantityAdjustment['qty'];

        // Save cart item
        $cartId = $this->saveCartItem($findCart, $cart);

        // Prepare return data
        $cartReturn = $this->prepareReturnData($cart, $data, $cartId);

        $this->clearCartCache();

        event(new AddToCartEvent([
            'cart' => get_cart(),
            'total' => cart_total(),
            'discount' => cart_get_discount(),
            'currency' => get_currency_code(),
        ]));

        $result = [
            'success' => 'Item added to cart',
            'product' => $cartReturn,
            'cart_sum' => $this->cartRepository->getCartAmount(),
            'cart_items_quantity' => $this->cartRepository->getCartItemsCount(),
        ];

        if (!empty($quantityAdjustment['warnings'])) {
            $result['warnings'] = $quantityAdjustment['warnings'];
        }

        return $result;
    }

    /**
     * Recover cart from order.
     *
     * @param int|false $orderId
     * @return void
     */
    public function recoverCart($orderId = false): void
    {
        $currentSid = $this->app->user_manager->session_id();
        if ($currentSid === false) {
            return;
        }

        $params = [
            'table' => 'cart',
        ];

        if ($orderId !== false) {
            unset($params['order_completed']);
            $params['order_id'] = intval($orderId);
        }

        $willAdd = true;

        try {
            $res = $this->app->database_manager->get($params);
        } catch (QueryException $e) {
            Log::error('Failed to retrieve cart for recovery', [
                'exception' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            throw CartException::databaseOperationFailed('get', 'cart', $e);
        }

        if (!empty($res)) {
            foreach ($res as $item) {
                if (isset($item['id'])) {
                    $data = $item;
                    unset($data['id']);
                    if (isset($item['order_id'])) {
                        unset($data['order_id']);
                    }
                    if (isset($item['created_by'])) {
                        unset($data['created_by']);
                    }
                    if (isset($item['updated_at'])) {
                        unset($data['updated_at']);
                    }

                    if (isset($item['rel_type']) && isset($item['rel_id'])) {
                        $isExParams = [
                            'order_completed' => 0,
                            'session_id' => $currentSid,
                            'table' => 'cart',
                            'rel_type' => $item['rel_type'],
                            'rel_id' => $item['rel_id'],
                            'count' => 1,
                        ];

                        try {
                            $isEx = $this->app->database_manager->get($isExParams);
                        } catch (QueryException $e) {
                            Log::error('Failed to check existing cart item during recovery', [
                                'exception' => $e->getMessage(),
                                'params' => $isExParams,
                            ]);
                            throw CartException::databaseOperationFailed('get', 'cart', $e);
                        }
                        if ($isEx !== false) {
                            $willAdd = false;
                        }
                    }

                    $data['order_completed'] = 0;
                    $data['session_id'] = $currentSid;

                    if (isset($item['order_completed']) && intval($item['order_completed']) == 1) {
                        $data['id'] = $item['id'];
                        try {
                            $this->app->database_manager->save('cart', $data);
                        } catch (QueryException $e) {
                            Log::error('Failed to save cart item during recovery', [
                                'exception' => $e->getMessage(),
                                'cart_data' => $data,
                            ]);
                            throw CartException::databaseOperationFailed('save', 'cart', $e);
                        }
                    }
                }
            }
        }

        if ($willAdd) {
            $this->clearCartCache();
        }
    }

    /**
     * Check if product is in stock.
     *
     * @param int $contentId
     * @return bool
     */
    public function isProductInStock(int $contentId): bool
    {
        $item = content_data($contentId);
        $isInStock = true;

        if ($item) {
            if (isset($item['qty']) && $item['qty'] != 'nolimit') {
                $quantity = intval($item['qty']);
                if ($quantity < 1) {
                    $isInStock = false;
                }
            }
        }

        return $isInStock;
    }

    /**
     * Get cart item image.
     *
     * @param int $cartItemId
     * @return string|false
     */
    public function getCartItemImage(int $cartItemId)
    {
        $cartItem = Cart::where('id', $cartItemId)->first();

        if ($cartItem) {
            if ($cartItem->rel_type == morph_name(\Modules\Content\Models\Content::class)
                || $cartItem->rel_type == 'content') {
                return $this->app->media_manager->get_picture($cartItem->rel_id);
            }
        }

        return false;
    }

    /**
     * Normalize cart data parameters.
     *
     * @param array $data
     * @return array
     */
    protected function normalizeCartData(array $data): array
    {
        if (!isset($data['for']) && isset($data['rel_type'])) {
            $data['for'] = $data['rel_type'];
        }
        if (!isset($data['for_id']) && isset($data['rel_id'])) {
            $data['for_id'] = $data['rel_id'];
        }
        if (!isset($data['for']) && !isset($data['rel_type'])) {
            $data['for'] = morph_name(\Modules\Content\Models\Content::class);
        }

        if (isset($data['content_id'])) {
            $data['for'] = morph_name(\Modules\Content\Models\Content::class);
            $data['for_id'] = $data['content_id'];
        }

        if (isset($data['for']) && $data['for'] == 'content') {
            $data['for'] = morph_name(\Modules\Content\Models\Content::class);
        }

        // Trigger event for cart update
        $override = $this->app->event_manager->trigger('mw.shop.update_cart', $data);
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (is_array($resp) && !empty($resp)) {
                    $data = array_merge($data, $resp);
                }
            }
        }

        return $data;
    }

    /**
     * Resolve and validate the supported cart target type.
     *
     * @param array $data
     * @return array
     */
    protected function resolveCartTarget(array $data): array
    {
        if (!isset($data['for']) || !isset($data['for_id'])) {
            return ['error' => 'Invalid for and for_id params'];
        }

        $canonicalType = $this->normalizeSupportedCartRelType((string) $data['for']);
        if ($canonicalType === null) {
            return ['error' => 'Invalid cart item type'];
        }

        return [
            'for' => $canonicalType,
            'for_id' => intval($data['for_id']),
        ];
    }

    /**
     * Normalize the supported cart relation type.
     *
     * @param string $type
     * @return string|null
     */
    protected function normalizeSupportedCartRelType(string $type): ?string
    {
        $type = trim($type);
        $contentType = morph_name(\Modules\Content\Models\Content::class);

        if ($type === '' || $type === 'content' || $type === $contentType) {
            return $contentType;
        }

        if (class_exists(\Modules\Billing\Models\SubscriptionPlan::class)) {
            $subscriptionPlanType = morph_name(\Modules\Billing\Models\SubscriptionPlan::class);
            if ($type === $subscriptionPlanType) {
                return $subscriptionPlanType;
            }
        }

        return null;
    }

    /**
     * Extract quantity from data.
     *
     * @param array $data
     * @return array
     */
    protected function extractQty(array &$data): array
    {
        $updateQty = 0;
        $updateQtyNew = 0;

        if (isset($data['qty'])) {
            $updateQtyNew = $updateQty = intval($data['qty']);
            unset($data['qty']);
        }

        return ['update' => $updateQty, 'new' => $updateQtyNew];
    }

    /**
     * Validate product and get data.
     *
     * @param string $for
     * @param int $forId
     * @param array $data
     * @return array
     */
    protected function validateProduct(string $for, int $forId, array $data): array
    {
        $contentData = [];
        $title = $data['title'] ?? null;
        $canonicalPrice = null;
        $contentType = morph_name(\Modules\Content\Models\Content::class);

        if ($for == $contentType) {
            $cont = $this->app->content_manager->get_by_id($forId);

            if (isset($cont['is_active']) && $cont['is_active'] != 1) {
                $cont = false;
            }
            if (isset($cont['is_deleted']) && $cont['is_deleted'] == 1) {
                $cont = false;
            }
            if (isset($cont['content_type']) && $cont['content_type'] != 'product') {
                $cont = false;
            }

            $contentData = $this->app->content_manager->data($forId);

            if ($cont === false) {
                return ['error' => 'Invalid product?'];
            } elseif (is_array($cont) && isset($cont['title']) && $cont['title']) {
                $title = $cont['title'];
            }

            $canonicalPrice = $this->resolveContentCanonicalPrice($forId, $cont);
        } elseif (class_exists(\Modules\Billing\Models\SubscriptionPlan::class)
            && $for === morph_name(\Modules\Billing\Models\SubscriptionPlan::class)) {
            $plan = \Modules\Billing\Models\SubscriptionPlan::query()->find($forId);

            if (!$plan) {
                return ['error' => 'Invalid product?'];
            }

            $title = $plan->name ?: $title;
            $canonicalPrice = is_numeric($plan->price) ? (float) $plan->price : 0.0;
        }

        return [
            'content_data' => $contentData,
            'title' => $title,
            'canonical_price' => $canonicalPrice,
        ];
    }

    /**
     * Resolve the canonical server-side price for a content product.
     *
     * @param int $forId
     * @param array $content
     * @return float|null
     */
    protected function resolveContentCanonicalPrice(int $forId, array $content): ?float
    {
        $productPrices = $this->getProductPrices($forId);
        if (!empty($productPrices)) {
            $firstPrice = reset($productPrices);
            if (is_numeric($firstPrice)) {
                return (float) $firstPrice;
            }
        }

        if (isset($content['price']) && is_numeric($content['price'])) {
            return (float) $content['price'];
        }

        return null;
    }

    /**
     * Process custom fields and calculate price.
     *
     * @param array $data
     * @param string $for
     * @param int $forId
     * @return array
     */
    protected function processCustomFields(array $data, string $for, int $forId, ?float $canonicalPrice = null): array
    {
        $add = [];
        $prices = [];
        $contentCustomFields = $this->app->fields_manager->get([
            'rel_type' => $for,
            'rel_id' => $forId,
            'return_full' => true,
        ]);

        $productPrices = $this->getProductPrices($forId);
        $requestedPrice = $this->normalizeComparablePrice($data['price'] ?? null);

        if ($contentCustomFields === false) {
            $contentCustomFields = [];
        } elseif (is_array($contentCustomFields)) {
            foreach ($contentCustomFields as $cf) {
                if (isset($cf['type']) && $cf['type'] == 'price') {
                    if (isset($productPrices[$cf['name']])) {
                        $prices[$cf['name']] = $productPrices[$cf['name']];
                    } else {
                        $prices[$cf['name']] = $cf['value'];
                    }
                }
            }
        }

        $priceModifierFields = [];
        $foundPrice = false;
        $skipKeys = [];

        foreach ($data as $k => $item) {
            if ($k != 'for' && $k != 'for_id' && $k != 'title') {
                $found = $this->processFieldValue($k, $item, $contentCustomFields, $prices, $skipKeys, $priceModifierFields);

                if ($found === false) {
                    $skipKeys[] = $k;
                }

                if (is_array($prices)) {
                    foreach ($prices as $priceKey => $price) {
                        $normalizedPrice = $this->normalizeComparablePrice($price);
                        $normalizedItemPrice = $this->normalizeComparablePrice($item);

                        if ($requestedPrice !== null && $normalizedPrice !== null && $normalizedPrice === $requestedPrice) {
                            $found = true;
                            $foundPrice = (float) $price;
                        } elseif ($normalizedItemPrice !== null && $normalizedPrice !== null && $normalizedPrice === $normalizedItemPrice) {
                            $found = true;
                            if ($foundPrice === false) {
                                $foundPrice = (float) $price;
                            }
                        }
                    }

                    if ($foundPrice !== false && count($prices) > 1) {
                        foreach ($prices as $pk => $pv) {
                            $normalizedPrice = $this->normalizeComparablePrice($pv);
                            $normalizedFoundPrice = $this->normalizeComparablePrice($foundPrice);
                            if ($normalizedPrice !== null && $normalizedFoundPrice !== null && $normalizedPrice === $normalizedFoundPrice) {
                                $add[$pk] = $this->app->shop_manager->currency_format($pv);
                            }
                        }
                    }
                }

                if (isset($item) && $found && $k != 'price' && !in_array($k, $skipKeys)) {
                    $add[$k] = $this->app->format->clean_html($item);
                }
            }
        }

        if ($foundPrice === false && is_array($prices)) {
            $lastPrice = end($prices);
            if ($lastPrice !== false && is_numeric($lastPrice)) {
                $foundPrice = (float) $lastPrice;
            }
        }
        if ($foundPrice === false) {
            $foundPrice = $canonicalPrice ?? 0;
        }

        return ['price' => $foundPrice, 'add' => $add, 'price_modifiers' => $priceModifierFields];
    }

    /**
     * Normalize a comparable price string for strict matching.
     *
     * @param mixed $price
     * @return string|null
     */
    protected function normalizeComparablePrice($price): ?string
    {
        if (is_string($price)) {
            $price = trim($price);
        }

        if ($price === '' || $price === null || !is_numeric($price)) {
            return null;
        }

        return sprintf('%.6F', (float) $price);
    }

    /**
     * Process field value and check for price modifiers.
     *
     * @param string $k
     * @param mixed $item
     * @param array $contentCustomFields
     * @param array $prices
     * @param array $skipKeys
     * @param array $priceModifierFields
     * @return bool
     */
    protected function processFieldValue(string $k, $item, array $contentCustomFields, array &$prices, array &$skipKeys, array &$priceModifierFields): bool
    {
        $found = false;

        foreach ($contentCustomFields as $cf) {
            if (isset($cf['type']) && isset($cf['name']) && $cf['type'] != 'price') {
                if (isset($data[$cf['name_key']])) {
                    $cf['name'] = $data[$cf['name_key']];
                }
            } elseif (isset($cf['type']) && $cf['type'] == 'price' && isset($cf['name']) && isset($cf['value'])) {
                if ($cf['value'] != '') {
                    if (isset($productPrices[$cf['name']])) {
                        $prices[$cf['name']] = $productPrices[$cf['name']];
                    } else {
                        $prices[$cf['name']] = $cf['value'];
                    }
                }
            }
        }

        if ($contentCustomFields) {
            foreach ($contentCustomFields as $cf) {
                if (isset($cf['type']) && isset($cf['name']) && $cf['type'] != 'price') {
                    if ($k == $cf['name'] || $k == $cf['name_key']) {
                        $found = true;
                    }

                    if ($found && isset($cf['values_price_modifiers']) && !empty($cf['values_price_modifiers'])) {
                        $modifierData = [
                            'custom_field_id' => $cf['id'],
                            'name' => $cf['name'],
                            'name_key' => $cf['name'],
                            'value' => $item,
                        ];

                        $getModifier = DB::table('custom_fields_values')
                            ->where('custom_field_id', $cf['id'])
                            ->where('value', $item)
                            ->orderBy('position', 'asc')
                            ->first();

                        if ($getModifier && $getModifier->price_modifier) {
                            $modifierData['selected_price_modifier_from_user'] = $getModifier->price_modifier;
                        }

                        $priceModifierFields[$cf['id']] = $modifierData;
                    }
                }
            }
        }

        return $found;
    }

    /**
     * Get product prices.
     *
     * @param int $productId
     * @return array
     */
    protected function getProductPrices(int $productId): array
    {
        $productPrices = [];

        if (function_exists('app') && app()->shop_manager) {
            $pricesData = app()->shop_manager->get_product_prices($productId, true);

            if ($pricesData) {
                foreach ($pricesData as $priceData) {
                    if (isset($priceData['name'])) {
                        $productPrices[$priceData['name']] = $priceData['value'];
                    }
                }
            }
        }

        return $productPrices;
    }

    /**
     * Validate custom fields.
     *
     * @param array $data
     * @param string $for
     * @param int $forId
     * @param array $add
     * @return array
     */
    protected function validateCustomFields(array $data, string $for, int $forId, array $add): array
    {
        $contentCustomFields = $this->app->fields_manager->get([
            'rel_type' => $for,
            'rel_id' => $forId,
            'return_full' => true,
        ]);

        $fieldRules = [];
        $filesUtils = mw_filesystem();
        $inputFields = [];
        $fieldsValidationRules = [];

        foreach ($contentCustomFields as $cf) {
            if (!$add) {
                continue;
            }
            if (isset($add[$cf['name_key']])) {
                $inputFields[] = $cf['name_key'];
            }

            $customFieldRules = ['max:500'];
            if ((isset($cf['required']) && $cf['required']) || (isset($cf['options']['required']) && $cf['options']['required'] == 1)) {
                $customFieldRules[] = 'required';
            }

            if (isset($cf['type']) && $cf['type'] == 'upload') {
                $mimeTypes = $this->getMimeTypes($cf, $filesUtils);
                if (!empty($mimeTypes)) {
                    if (is_array($mimeTypes)) {
                        $mimeTypes = implode(',', $mimeTypes);
                    }
                    $customFieldRules[] = 'mimes:' . $mimeTypes;
                }
            }

            $fieldsValidationRules[$cf['name_key']] = implode('|', $customFieldRules);
        }

        if (!empty($fieldsValidationRules)) {
            $validator = Validator::make(request()->only($inputFields), $fieldsValidationRules);
            if ($validator->fails()) {
                $validatorMessages = false;
                foreach ($validator->messages()->toArray() as $inputFieldErrors) {
                    $validatorMessages = implode("\n", $inputFieldErrors);
                }
                return [
                    'form_errors' => $validator->messages()->toArray(),
                    'error' => $validatorMessages,
                ];
            }
        }

        return [];
    }

    /**
     * Get MIME types for file upload validation.
     *
     * @param array $cf
     * @param \MicroweberPackages\Filesystem\FilesystemService $filesUtils
     * @return array|string
     */
    protected function getMimeTypes(array $cf, \MicroweberPackages\Filesystem\FilesystemService $filesUtils)
    {
        $mimeTypes = [];

        if (isset($cf['options']['file_types']) && !empty($cf['options']['file_types'])) {
            foreach ($cf['options']['file_types'] as $optionFileTypes) {
                if (!empty($optionFileTypes)) {
                    $mimeTypesString = $filesUtils->getAllowedExtensionsForUpload($optionFileTypes);
                    $mimeTypesArray = explode(',', $mimeTypesString);
                    $mimeTypes = array_merge($mimeTypes, $mimeTypesArray);
                }
            }
        }

        if (empty($mimeTypes)) {
            $mimeTypes = $filesUtils->getAllowedExtensionsForUpload('images');
        }

        return $mimeTypes;
    }

    /**
     * Build cart data array.
     *
     * @param array $data
     * @param string $for
     * @param int $forId
     * @param float|null $foundPrice
     * @param array $add
     * @return array
     */
    protected function buildCartData(array $data, string $for, int $forId, ?float $foundPrice, array $add): array
    {
        ksort($add);
        asort($add);
        $add = app()->format->clean_xss($add);

        $cart = [
            'rel_type' => trim($for),
            'rel_id' => $forId,
            'session_id' => $this->app->user_manager->session_id(),
            'no_cache' => 1,
            'disable_triggers' => 1,
            'order_completed' => 0,
            'custom_fields_data' => $add,
            'custom_fields_json' => json_encode($add),
            'allow_html' => 1,
            'price' => doubleval($foundPrice),
            'limit' => 1,
        ];

        if (isset($data['qty'])) {
            $cart['qty'] = $data['qty'];
        }

        if (!isset($data['title']) || !$data['title']) {
            $data['title'] = 'Product ' . $cart['rel_id'];
        }

        $cart['title'] = app()->format->clean_html($data['title']);

        // Add optional fields
        foreach (['other_info', 'description', 'image', 'item_image', 'link', 'currency'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $cart[$field] = $this->app->format->clean_html($data[$field]);
            }
        }

        return $cart;
    }

    /**
     * Find existing cart item.
     *
     * @param array $cart
     * @return Cart|null
     */
    protected function findExistingCartItem(array $cart): ?Cart
    {
        // audit-test 2026-05-07 Cart deep-pass finding #7 (BUG MEDIUM) +
        // post-merge follow-up #1 (NULL/empty mismatch):
        // - Cycle-36 added custom_fields_data to disambiguate variants
        //   (size=M vs size=L no longer collapse into one row).
        // - Follow-up #1: Format::array_to_base64([]) returns '' (empty
        //   string) so new no-customisation rows carry '' while legacy
        //   rows persisted via paths that left the column NULL still
        //   exist. SQL `WHERE custom_fields_data = ''` doesn't match
        //   NULL, so legacy NULL rows wouldn't qty-merge with new ''
        //   rows — customer ends up with two cart rows for the same
        //   product. Treat NULL and '' as equivalent at query time.
        $query = Cart::where('session_id', $cart['session_id'])
            ->where('order_completed', $cart['order_completed'])
            ->where('rel_id', $cart['rel_id'])
            ->where('rel_type', $cart['rel_type']);

        // custom_fields_data is stored as JSON (Cart model 'array' cast), so a
        // variant is identified by its JSON form. Empty (no customisation)
        // matches the legacy NULL/'' rows as well as the JSON empties ('[]'/'""').
        $cf = $cart['custom_fields_data'] ?? null;
        if (!empty($cf)) {
            $query->where('custom_fields_data', json_encode($cf));
        } else {
            $query->where(function ($q) {
                $q->whereNull('custom_fields_data')
                  ->orWhere('custom_fields_data', '')
                  ->orWhere('custom_fields_data', '""')
                  ->orWhere('custom_fields_data', '[]');
            });
        }

        return $query->first();
    }

    /**
     * Calculate quantity based on existing cart and updates.
     *
     * @param Cart|null $findCart
     * @param int $updateQtyNew
     * @param int $updateQty
     * @return int
     */
    protected function calculateQuantity(?Cart $findCart, int $updateQtyNew, int $updateQty): int
    {
        $checkCart = $findCart ? $findCart->toArray() : false;

        if ($checkCart !== false && isset($checkCart['id'])) {
            if ($updateQty > 0) {
                return $checkCart['qty'] + $updateQty;
            } elseif ($updateQtyNew > 0) {
                return $updateQtyNew;
            } else {
                return $checkCart['qty'] + 1;
            }
        } else {
            if ($updateQty > 0) {
                return $updateQty;
            }
            return 1;
        }
    }

    /**
     * Apply stock limits to quantity.
     *
     * @param int $qty
     * @param array $contentData
     * @return int
     */
    protected function applyStockLimits(int $qty, array $contentData): int
    {
        if (isset($contentData['qty']) && trim($contentData['qty']) != 'nolimit' && intval($contentData['qty']) > 0) {
            if (intval($contentData['qty']) < $qty) {
                return intval($contentData['qty']);
            }
        }
        return $qty;
    }

    /**
     * Apply max quantity per order limit.
     *
     * @param int $qty
     * @param array $contentData
     * @return int
     */
    protected function applyMaxQtyLimit(int $qty, array $contentData): int
    {
        if (isset($contentData['max_qty_per_order']) && intval($contentData['max_qty_per_order']) != 0) {
            if ($qty > intval($contentData['max_qty_per_order'])) {
                return intval($contentData['max_qty_per_order']);
            }
        }
        return $qty;
    }

    /**
     * Validate quantity against content data.
     *
     * @param int $qty
     * @param array $contentData
     * @return int
     */
    protected function validateQty(int $qty, array $contentData): int
    {
        if ($qty < 0) {
            $qty = 0;
        }

        if (isset($contentData['max_qty_per_order']) && intval($contentData['max_qty_per_order']) != 0) {
            if ($qty > intval($contentData['max_qty_per_order'])) {
                $qty = intval($contentData['max_qty_per_order']);
            }
        }

        return $qty;
    }

    /**
     * Apply quantity constraints and return any surfaced warnings.
     *
     * @param int $qty
     * @param array $contentData
     * @return array
     */
    protected function applyQuantityConstraints(int $qty, array $contentData): array
    {
        $warnings = [];
        $originalQty = $qty;

        $stockLimitedQty = $this->applyStockLimits($qty, $contentData);
        if ($stockLimitedQty !== $qty) {
            $warnings[] = $this->makeQuantityAdjustmentWarning(
                'stock_limit',
                $originalQty,
                $stockLimitedQty,
                'Requested quantity was adjusted to the available stock.',
                ['available_quantity' => $stockLimitedQty]
            );
            $qty = $stockLimitedQty;
        }

        $maxQtyLimitedQty = $this->applyMaxQtyLimit($qty, $contentData);
        if ($maxQtyLimitedQty !== $qty) {
            $warnings[] = $this->makeQuantityAdjustmentWarning(
                'max_qty_per_order',
                $originalQty,
                $maxQtyLimitedQty,
                'Requested quantity was adjusted to the maximum allowed per order.',
                ['max_quantity_per_order' => $maxQtyLimitedQty]
            );
            $qty = $maxQtyLimitedQty;
        }

        return [
            'qty' => $qty,
            'warnings' => $warnings,
        ];
    }

    /**
     * Create a surfaced quantity-adjustment warning payload.
     *
     * @param string $code
     * @param int $requestedQty
     * @param int $adjustedQty
     * @param string $message
     * @param array $context
     * @return array
     */
    protected function makeQuantityAdjustmentWarning(
        string $code,
        int $requestedQty,
        int $adjustedQty,
        string $message,
        array $context = []
    ): array {
        return array_merge([
            'code' => $code,
            'message' => $message,
            'requested_quantity' => $requestedQty,
            'adjusted_quantity' => $adjustedQty,
        ], $context);
    }

    /**
     * Get content data for cart item validation.
     *
     * @param array $checkCart
     * @return array
     */
    protected function getContentDataForCartItem(array $checkCart): array
    {
        $dataFields = [];

        if (isset($checkCart['rel_type']) && isset($checkCart['rel_id']) && $checkCart['rel_type'] == morph_name(\Modules\Content\Models\Content::class)) {
            $data = $this->app->content_manager->data($checkCart['rel_id']);
            if (is_array($data)) {
                $dataFields = $data;
            }
        }

        return $dataFields;
    }

    /**
     * Save cart item to database.
     *
     * @param Cart|null $findCart
     * @param array $cart
     * @return int
     */
    protected function saveCartItem(?Cart $findCart, array $cart): int
    {
        if ($findCart == null) {
            $findCart = new Cart();
            $findCart->rel_id = $cart['rel_id'];
            $findCart->rel_type = $cart['rel_type'];
            $findCart->custom_fields_data = $cart['custom_fields_data'];
            $findCart->custom_fields_json = $cart['custom_fields_json'];
        }

        if (!isset($cart['qty'])) {
            $cart['qty'] = 1;
        }

        $findCart->qty = $cart['qty'];
        $findCart->title = $cart['title'];
        $findCart->price = $cart['price'];
        $findCart->session_id = $cart['session_id'];
        $findCart->order_completed = $cart['order_completed'];

        try {
            $findCart->save();
        } catch (QueryException $e) {
            Log::error('Failed to save cart item', [
                'exception' => $e->getMessage(),
                'cart_data' => $cart,
            ]);
            throw CartException::databaseOperationFailed('save', 'cart', $e);
        }

        return $findCart->id;
    }

    /**
     * Prepare return data for cart update.
     *
     * @param array $cart
     * @param array $data
     * @param int $cartId
     * @return array
     */
    protected function prepareReturnData(array $cart, array $data, int $cartId): array
    {
        $cartReturn = [
            'custom_fields_data' => json_decode($cart['custom_fields_json'], true) ?? [],
            'price' => $cart['price'],
        ];

        if (isset($cart['rel_type']) && isset($cart['rel_id']) && $cart['rel_type'] == morph_name(\Modules\Content\Models\Content::class)) {
            $cartReturn['image'] = $this->app->media_manager->get_picture($cart['rel_id']);
            $cartReturn['product_link'] = $this->app->content_manager->link($cart['rel_id']);
            $cartReturn['content_id'] = $cart['rel_id'];
        }

        foreach (['description', 'item_image', 'link', 'currency'] as $field) {
            if (isset($cart[$field])) {
                $cartReturn[$field] = $cart[$field];
            }
        }

        $cartReturn['cart_item_id'] = $cartId;

        return $cartReturn;
    }

    /**
     * Normalize rel parameters.
     *
     * @param array $params
     * @return void
     */
    protected function normalizeRelParams(array &$params): void
    {
        if (!isset($params['rel']) && isset($params['for'])) {
            $params['rel_type'] = $params['for'];
        } elseif (isset($params['rel']) && !isset($params['rel_type'])) {
            $params['rel_type'] = $params['rel'];
        }

        if (!isset($params['rel_id']) && isset($params['for_id'])) {
            $params['rel_id'] = $params['for_id'];
        }
    }

    /**
     * Process cart items for return.
     *
     * @param mixed $get
     * @param array $params
     * @return array
     */
    protected function processCartItems($get, array $params): array
    {
        $return = [];

        if (is_array($get)) {
            foreach ($get as $k => $item) {
                if (is_array($item)) {
                    if (isset($item['rel_id']) && isset($item['rel_type'])
                        && $item['rel_type'] == morph_name(\Modules\Content\Models\Content::class)) {
                        $item['content_data'] = $this->app->content_manager->data($item['rel_id']);
                        $item['url'] = $this->app->content_manager->link($item['rel_id']);
                        $item['picture'] = $this->app->media_manager->get_picture($item['rel_id']);

                        if (isset($params['for_checkout']) && $params['for_checkout']) {
                            $checkContent = get_content_by_id($item['rel_id']);
                            $removeFromCart = false;

                            if ($checkContent === false) {
                                $removeFromCart = true;
                            } elseif (isset($checkContent['is_deleted']) && $checkContent['is_deleted'] == 1) {
                                $removeFromCart = true;
                            } elseif (isset($checkContent['is_active']) && $checkContent['is_active'] == 0) {
                                $removeFromCart = true;
                            }

                            if ($removeFromCart) {
                                $this->removeItem($item['id']);
                                unset($get[$k]);
                                continue;
                            }
                        }
                    }

                    if (isset($item['custom_fields_data']) && $item['custom_fields_data'] != '') {
                        $item = $this->app->format->render_item_custom_fields_data($item);
                    }

                    if (isset($item['title'])) {
                        $item['title'] = $this->cleanTitle($item['title']);
                    }

                    if (!isset($item['url'])) {
                        $item['url'] = '';
                    }
                    if (!isset($item['picture'])) {
                        $item['picture'] = '';
                    }
                }

                $return[$k] = $item;
            }
        } else {
            $return = $get;
        }

        return $return ?: [];
    }

    /**
     * Clean title text.
     *
     * @param string $title
     * @return string
     */
    protected function cleanTitle(string $title): string
    {
        $title = html_entity_decode($title);
        $title = strip_tags($title);
        $title = $this->app->format->clean_html($title);
        $title = htmlspecialchars_decode($title);
        return $title;
    }

    /**
     * Clear cart cache.
     *
     * @return void
     */
    protected function clearCartCache(): void
    {
        $this->app->cache_manager->delete('cart');
        $this->app->cache_manager->delete('cart_orders');
    }
}
