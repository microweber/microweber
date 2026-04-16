<?php
namespace Modules\Offer\Repositories;

use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use Modules\Offer\Models\Offer;

class OfferRepository extends CachingModelRepository
{
    protected string $modelClass = Offer::class;

    public static function add($offerData)
    {
        return Offer::add($offerData);
    }

    public function getAll()
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () {
            return Offer::getAll();
        });
    }

    public function getProductIdsThatHaveOfferPrice()
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () {
            return Offer::getProductIdsThatHaveOfferPrice();
        });
    }

    public function getPrice($productId, $priceId)
    {
        $existingIds = $this->getProductIdsThatHaveOfferPrice();
        if (!in_array($productId, $existingIds)) {
            return [];
        }

        return $this->cached(__FUNCTION__, func_get_args(), function () use ($productId, $priceId) {
            return Offer::getPrice($productId, $priceId);
        });
    }

    public function getByProductId($productId)
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($productId) {
            return Offer::getByProductId($productId);
        });
    }

    public function getById($offerId)
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($offerId) {
            return Offer::getById($offerId);
        });
    }

    public static function deleteById($offerId)
    {
        return Offer::deleteById($offerId);
    }
}
