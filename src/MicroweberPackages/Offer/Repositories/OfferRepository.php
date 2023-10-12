<?php
namespace MicroweberPackages\Offer\Repositories;

use Illuminate\Support\Carbon;
use MicroweberPackages\Offer\Models\Offer;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class OfferRepository extends AbstractRepository
{
    public $model = Offer::class;

    public static function add($offerData)
    {
        if (isset($offerData['product_id_with_price_id'])) {
            $id_parts = explode('|', $offerData['product_id_with_price_id']);
            $offerData['product_id'] = $id_parts[0];
            $offerData['price_id'] = $id_parts[1];
        } else if (isset($offerData['product_id'])) {
            if (strstr($offerData['product_id'], '|')) {
                $id_parts = explode('|', $offerData['product_id']);
                $offerData['product_id'] = $id_parts[0];
            }
        }
        if (isset($offerData['offer_price'])) {
            $offerData['offer_price'] = mw()->format->amount_to_float($offerData['offer_price']);
        }

        if (isset($offerData['expires_at']) and $offerData['expires_at'] != '') {
            // >>> Format date
            try {
                $carbonExpiresAt = Carbon::parse($offerData['expires_at']);
                $offerData['expires_at'] = $carbonExpiresAt->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                //
            }
            // <<< Format date
        } else {
            $offerData['expires_at'] = null;
        }

        if (empty($offerData['is_active'])) {
            $offerData['is_active'] = 0;
        } elseif ($offerData['is_active'] == 'on') {
            $offerData['is_active'] = 1;
        }
        if(isset($offerData['id'])){
            $offer = Offer::updateOrCreate(
                ['id' =>  $offerData['id']],
                $offerData
            );
        } else {
            $offer = Offer::create(
                $offerData
            );
        }
        cache_delete('offers');

        return $offer;
    }

    public function getAll()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            $offers = Offer::select(
                'offers.id',
                'offers.product_id',
                'offers.offer_price',
                'offers.created_at',
                'offers.updated_at',
                'offers.expires_at',
                'offers.is_active',
                'content.title as product_title',
                'content.is_deleted',
                'custom_fields.name as price_name',
                'custom_fields_values.value as price'
            )
                ->where('content.content_type', '=', 'product')
                ->where('custom_fields.type', '=', 'price')
                ->leftJoin('custom_fields', 'offers.price_id', '=', 'custom_fields.id')
                ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
                ->leftJoin('content', 'offers.product_id', '=', 'content.id')
                ->orderBy('offers.id', 'desc')
                ->get()
                ->toArray();

            return $offers;
        });
    }
    public function getProductIdsThatHaveOfferPrice()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $productIds = [];

            $getOffers = \DB::table('offers')
                ->select('product_id')
                ->groupBy('product_id')
                ->get();

            if ($getOffers->count() > 0) {
                foreach ($getOffers as $offer) {
                    if ($offer->product_id > 0) {
                        $productIds[] = $offer->product_id;
                    }
                }
            }

            return $productIds;
       });
    }

    public function getPrice($productId, $priceId)
    {

        $existingIds = $this->getProductIdsThatHaveOfferPrice();
        if (!in_array($productId,$existingIds)) {
            return [];
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($productId, $priceId) {

            $showOfferPrice = false;
            $contentData = content_data($productId);
            if (isset($contentData['has_special_price']) && $contentData['has_special_price'] == 1) {
                $showOfferPrice = true;
            }

            if (!$showOfferPrice) {
                return [];
            }

            $query = Offer::where('price_id', $priceId);
            if ($productId) {
                $query->where('product_id', '=', $productId);
            }
            $res = $query->first();
            if (!empty($res)) {
                return $res->toArray();
            } else {
                return [];
            }
        });
    }

    public function getByProductId($productId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($productId) {
            $offers = Offer::select(
                'custom_fields.id as id',
                'offers.id as offer_id',
                'offers.offer_price',
                'offers.expires_at',
                'custom_fields.name as price_name',
                'custom_fields_values.value as price'
            )
                ->where('content.id', '=', $productId)
                ->where('content.is_deleted', '=', 0)
                ->where('offers.is_active', '=', 1)
                ->where('custom_fields.type', '=', 'price')
                ->leftJoin('content', 'offers.product_id', '=', 'content.id')
                ->leftJoin('custom_fields', 'offers.price_id', '=', 'custom_fields.id')
                ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
                ->get()
                ->toArray();

            $specialOffers = array();

            foreach ($offers as $offer) {

                if (!($offer['expires_at']) || $offer['expires_at'] == '0000-00-00 00:00:00' || (strtotime($offer['expires_at']) > strtotime("now"))) {
                    // converting price_name to lowercase to match key from in FieldsManager function get line 556

                    if (isset($offer['offer_price']) and $offer['offer_price'] and isset($offer['price'])) {

                        $price_change_direction = 'decrease';
                        $offer['offer_price'] = floatval($offer['offer_price']);
                        $offer['price'] = floatval($offer['price']);

                        $answer = abs($offer['price'] - $offer['offer_price']);
                        $offer['price_change_direction_sign'] = '-';
                        $offer['offer_value_difference'] = $answer;

                        if ($offer['offer_price'] > $offer['price']) {
                            $price_change_direction = 'increase';
                            $answer = abs($offer['price'] - $offer['offer_price']);
                            $offer['price_change_direction_sign'] = '+';
                            $offer['offer_value_difference'] = $answer;
                        }

                        $percent = mw()->format->percent($offer['offer_value_difference'], $offer['price']);
                        $offer['offer_value_difference_percent'] = $percent;
                        $offer['price_change_direction'] = $price_change_direction;
                    }

                    $specialOffers[strtolower($offer['price_name'])] = $offer;

                }
            }

            return $specialOffers;
        });
    }

    public function getById($offerId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($offerId) {
            $offer = Offer::find($offerId);
            $res = [];
            $additionalFields = [];

            if (isset($offer->id) and isset($offer->product_id)) {
                $prodOffers = self::getByProductId($offer->product_id);
                if ($prodOffers) {
                    foreach ($prodOffers as $key => $prodOffer) {
                        if ($prodOffer['id'] == $offer['id']) {
                            $additionalFields = $prodOffer;
                        }
                    }
                }
            }

            if (!empty($additionalFields)) {
                $res = array_merge($offer->toArray(), $additionalFields);
            } elseif (!empty($offer)) {
                $res = $offer->toArray();
            }

            return $res;
        });
    }

    public static function deleteById($offerId)
    {
        if (!isset($offerId)) {
            return false;
        }

        $offer = Offer::find($offerId);

        if(!empty($offer)) {
            $offer->delete();
            $res = true;
        } else {
            $res = false;
        }

        return $res;
    }

}
