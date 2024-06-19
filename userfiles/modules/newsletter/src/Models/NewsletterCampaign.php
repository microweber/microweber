<?php
namespace MicroweberPackages\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    public $table = 'newsletter_campaigns';


   public function list()
   {
       return $this->hasOne(NewsletterList::class, 'id', 'list_id');
   }

   public function getSubscribersAttribute()
   {
       return 0;
   }

    public function getScheduledAttribute()
    {
         return 0;
    }

    public function getScheduledAtAttribute()
    {
        return 0;
    }

    public function getDoneAttribute()
    {
         return 0;
    }
}
