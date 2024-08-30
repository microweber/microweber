<?php
/*
 * EMAIL CAMPAIGN FUNCTIONS
 */

api_expose_admin('newsletter_get_campaign');
function newsletter_get_campaign($campaign_id) {
    $data = ['id' => $campaign_id, 'single' => true];
    $table = "newsletter_campaigns";

    return db_get($table, $data);
}

api_expose('newsletter_get_campaigns');
function newsletter_get_campaigns() {

    $campaigns = DB::table("newsletter_campaigns")
    ->select('newsletter_campaigns.*', 'newsletter_lists.name as list_name')
    ->leftJoin('newsletter_lists', 'newsletter_lists.id', '=', 'newsletter_campaigns.list_id')
    ->get();

    $readyCampaigns = array();
    foreach ($campaigns as $campaign) {

        $list_name = 'Default';
        if ($campaign->list_name) {
            $list_name = $campaign->list_name;
        }

        $readyCampaigns[] = array(
            'name'=>$campaign->name,
            'subject'=>$campaign->subject,
            'from_name'=>$campaign->from_name,
            //'from_email'=>$campaign->from_email,
            'created_at'=>$campaign->created_at,
            'list_name'=>$list_name,
            'list_id'=>$campaign->list_id,
            'is_done'=>$campaign->is_done,
            'sending_limit_per_day'=>$campaign->sending_limit_per_day,
            'is_scheduled'=>$campaign->is_scheduled,
            'scheduled_at'=>$campaign->scheduled_at,
            'id'=>$campaign->id
        );
    }

    return $readyCampaigns;
}

api_expose('newsletter_save_campaign');
function newsletter_save_campaign($data) {
    $table = "newsletter_campaigns";
    return db_save($table, $data);
}

api_expose('newsletter_delete_campaign');
function newsletter_delete_campaign($params) {
    if (isset($params ['id'])) {
        $table = "newsletter_campaigns";
        $id = $params ['id'];
        return db_delete($table, $id);
    }
}

api_expose('newsletter_send_campaign');
function newsletter_send_campaign($params) {


}

api_expose('newsletter_finish_campaign');
function newsletter_finish_campaign($campaign_id) {

	$save = array();
	$save['id'] = $campaign_id;
	$save['is_done'] = 1;

	$table = 'newsletter_campaigns';

	return db_save($table, $save);
}

function newsletter_campaigns_send_log($campaign_id, $subscriber_id) {

    $save = array();
    $save['campaign_id'] = $campaign_id;
    $save['subscriber_id'] = $subscriber_id;
    $save['is_sent'] = 1;
    $save['created_at'] = date('Y-m-d H:i:s');

    $table = 'newsletter_campaigns_send_log';

    return db_save($table, $save);
}
