<?php

namespace Tasawk\Lib;

use Cknow\Money\Money;
use Darryldecode\Cart\Cart as CoreCart;
use Darryldecode\Cart\CartCondition;
use Darryldecode\Cart\Helpers\Helpers;
use Darryldecode\Cart\ItemCollection;
use DB;
use NumberFormatter;
use Tasawk\Models\Catalog\Product;
use Tasawk\Models\HotelService;
use Tasawk\Models\IndividualService;
use Tasawk\Settings\GeneralSettings;
use function Livewire\of;

class Cart extends CoreCart
{
    private $orderId;

    public function getSession()
    {
        return $this->sessionKey;
    }

    public function getQuantityByModelId($id): int
    {
        return $this->getContent()->where('associatedModel.id', $id)->first()->quantity ?? 0;
    }


    function removeCartCoupon()
    {
        $this->removeConditionsByType("coupon");
    }

    function applyItem(Product $product, $qty, $attributes, $conditions = [])
    {

        $this->add(
            \Str::uuid()->toString(),
            $product->title,
            $product->full_price->formatByDecimal(),
            $qty,
            [
                "original_price" => $product->price?->formatByDecimal(),
                'category_key' => $product?->category?->mapper_key,
                ...$attributes
            ],
            $conditions,
            (object)array_merge([...$product->toArray(), 'category' => $product?->category?->getOriginal('name')])
        );
    }

    function applyService(HotelService $service, $request, $qty = 1, $attributes = [], $conditions = [])
    {
        $price = $service->price_night;
        $qty = $request->to - $request->from;
        $this->add(
            \Str::uuid()->toString(),
            $service->service_name,
            $price,
            $qty,
            [
                "original_price" => $price,
                ...$attributes
            ],
            $conditions,
        );
        return $this;
    }

    function applyIndividualService(IndividualService $service, $request, $qty = 1, $attributes = [], $conditions = [])
    {
        $price = $service->price_night;
        $qty = $request->to - $request->from;
        $this->add(
            \Str::uuid()->toString(),
            $service->service_name,
            $price,
            $qty,
            [
                "original_price" => $price,
                ...$attributes
            ],
            $conditions,
        );
        return $this;
    }


    function applyCoupon($code): bool
    {

        !$this->getConditionsByType("coupon")->count() ?: $this->removeConditionsByType("coupon");
        $coupon = Coupon::codeOrName(trim($code))->first();
        if (!$coupon) {
            return false;
        }
        $coupon_value = $coupon->formatedValue();
        $conditionData = [
            'name' => $coupon->coupon_code,
            'type' => "coupon",
            'target' => "subtotal",
            'value' => "-" . $coupon_value,
            'order' => 1,
            'attributes' => [
                'original_value' => "-" . $coupon_value,
            ]
        ];
        $conditionData['attributes'] = $conditionData;
        $this->condition(new CartCondition($conditionData));
        return true;
    }

    function applyDeliveryService($distance): bool
    {

        $settings = new GeneralSettings();
        $overflowDistance = $distance - $settings->diameter;
        $cost = $settings->delivery_cost;
        if ($overflowDistance > 0) {
            $cost += $overflowDistance * $settings->delivery_cost_for_each_additional_kilometer;
        }
        $conditionData = [
            'name' => 'Delivery service',
            'type' => "delivery",
            'target' => "total",
            'value' => $cost,
            'order' => 2,
            'attributes' => [
                'original_value' => $settings->delivery_cost,
            ]
        ];
        $conditionData['attributes'] = $conditionData;
        $this->condition(new CartCondition($conditionData));
        return true;
    }

    function applyTakeawayDiscount(): bool
    {

        $settings = new GeneralSettings();
        if (!$settings->enable_orders_discount_upon_receipt_from_the_branch) {
            return false;
        }
        $conditionData = [
            'name' => 'discount upon receipt from the branch',
            'type' => "takeaway",
            'target' => "subtotal",
            'value' => -$settings->orders_discount_upon_receipt_from_the_branch,
            'order' => 2,
            'attributes' => [
                'original_value' => $settings->orders_discount_upon_receipt_from_the_branch,
            ]
        ];
        $conditionData['attributes'] = $conditionData;
        $this->condition(new CartCondition($conditionData));
        return true;
    }


    function applyTaxes()
    {
        $settings = new GeneralSettings();
        $value = $settings->taxes;
        $value = "{$value}%";
        !$this->getConditionsByType("taxes")->count() ?: $this->removeConditionsByType("taxes");
        $conditionData = [
            'name' => 'Taxes',
            'type' => "taxes",
            'target' => "total",
            'value' => $value,
            'order' => 1,
            'attributes' => [
                'original_value' => $value,
            ]
        ];
        $conditionData['attributes'] = $conditionData;
        $this->condition(new CartCondition($conditionData));
        return true;
    }

    function applyWalletDiscount($discount): bool
    {

        !$this->getConditionsByType("wallet")->count() ?: $this->removeConditionsByType("wallet");
        $conditionData = [
            'name' => 'wallet',
            'type' => "wallet",
            'target' => "total",
            'value' => -$discount,
            'order' => 1,
            'attributes' => [
                'original_value' => $discount,
            ]
        ];
        $conditionData['attributes'] = $conditionData;
        $this->condition(new CartCondition($conditionData));
        return true;
    }

    public function setOrderConditions(): static
    {
        foreach ($this->getConditions() as $condition) {
            DB::table('orders_conditions')->insert([
                'order_id' => $this->getOrderID(),
                'name' => $condition->getName(),
                'type' => $condition->getType(),
                'target' => $condition->getTarget(),
                'value' => $condition->getValue(),
                'order' => $condition->getOrder(),
                'attributes' => json_encode($condition->getAttributes()),
                'model' => null,
            ]);
        }


        return $this;
    }

    public function getOrderItemConditions($item): array
    {

        $conditions = [];
        foreach ($item->getConditions() as $condition) {

            $conditions[] = [

                'name' => $condition->getName(),
                'type' => $condition->getType(),
                'target' => $condition->getTarget(),
                'value' => $condition->getValue(),
                'order' => $condition->getOrder(),
                'attributes' => json_encode($condition->getAttributes()),
                'model' => null,
            ];
        }
        return $conditions;
    }

    public function nativeItems()
    {
        $ids = [];
        foreach ($this->getContent() as $item) {
            $ids[] = $item['associatedModel']->id;
        }
        return Items::whereIn('id', array_unique($ids))->get();
    }

    private function setOrderItemsLine(): static
    {
        /** @var ItemCollection $item */
        foreach ($this->getContent() as $item) {

            $model = collect($item->associatedModel)->only([
                "id",
                "title",
                "status",
                "price",
                "image",
                'category'
            ]);
            DB::table('orders_items_lines')->insert([
                'order_id' => $this->getOrderID(),
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'attributes' => json_encode($item->attributes),
                'conditions' => json_encode($this->getOrderItemConditions($item)),
                'group' => $item['attributes']['group'],
                'model' => json_encode($model),
            ]);
        }
        return $this;
    }

    public function saveItemsToOrderAndClearAll($orderID)
    {
        $this->saveItemsToOrder($orderID);
        parent::clearCartConditions();
        parent::clear();
    }

    public function saveItemsToOrder($orderID)
    {
        $this->setOrderID($orderID)
            ->setOrderItemsLine()
            ->setOrderConditions();
    }


    public function setOrderID($orderID): static
    {
        $this->orderId = $orderID;
        return $this;
    }

    public function getOrderID()
    {
        return $this->orderId;
    }

    public function foramtedTotal()
    {
        return $this->getTotal() . ' ' . Ecommerce::currentSymbol();
    }

    protected function updateQuantityRelative($item, $key, $value)
    {
        if (preg_match('/\-/', $value) == 1) {
            $value = (float)str_replace('-', '', $value);

            // we will not allowed to reduced quantity to 0, so if the given value
            // would result to item quantity of 0, we will not do it.
            if (($item[$key] - $value) > 0) {
                $item[$key] -= $value;
            }
        } elseif (preg_match('/\+/', $value) == 1) {
            $item[$key] += (float)str_replace('+', '', $value);
        } else {
            $item[$key] += (float)$value;
        }

        return $item;
    }

    protected function updateQuantityNotRelative($item, $key, $value)
    {
        $item[$key] = (float)$value;
        return $item;
    }


    public function itemsTotalWithoutVat()
    {
        return $this->getContent()->sum(fn ($i) => $i->getPriceSum());
    }

    public function itemsVatTotal()
    {
        $itemsVatTotal = $this->getContent()->sum(function (ItemCollection $item) {
            return collect($item->getConditions())->sum(function ($cond) use ($item) {
                return $cond->getCalculatedValue($item->getPriceSum());
            });
        });
        $config = $this->config;
        $config['format_numbers'] = true;
        return (float)Helpers::formatValue($itemsVatTotal, true, $config);
    }

    function format($value)
    {
        return Money::parse($value)->format();
    }

    public function hasDiscount(): bool
    {
        return $this->getConditionsByType('coupon')->count();
    }

    public function hasAdminDiscount(): bool
    {
        return $this->getConditionsByType('discount')->count();
    }

    public function hasWalletDiscount(): bool
    {
        return $this->getConditionsByType('wallet')->count();
    }

    public function discount()
    {
        return $this->getConditionsByType('coupon')?->first()?->getCalculatedValue($this->itemsTotalWithoutVat());
    }

    public function adminDiscount()
    {
        return $this->getConditionsByType('discount')?->first()?->getCalculatedValue($this->getSubTotal());
    }

    public function walletDiscount()
    {
        return $this->getConditionsByType('wallet')?->first()?->getValue();
    }

    public function formattedTotals(): array
    {
        return array_map([$this, 'format'], $this->totals());
    }


    public function totals(): array
    {
        $items_total_with_options = $this->getContent()->sum(fn (ItemCollection $item) => $item->getPriceSumWithConditions(true));
        return [
            //            'items_total_without_options' => $this->getSubTotalWithoutConditions(),
            // 'items_total_with_options' => $items_total_with_options,
            // 'takeaway_discount' => (float)$this->getConditionsByType("takeaway")?->first()?->getCalculatedValue($items_total_with_options),
            // "taxes" => $this->getConditionsByType("taxes")?->first()?->getCalculatedValue($items_total_with_options - (float)$this->getConditionsByType("takeaway")?->first()?->getCalculatedValue($items_total_with_options)),
            // "delivery" => floatval($this->getConditionsByType('delivery')->first()?->getValue()),
            "total" => $this->getTotal()
        ];
    }
}
