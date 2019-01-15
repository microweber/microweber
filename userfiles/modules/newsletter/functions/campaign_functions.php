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
    foreach ($campaigns as $campaigns) {
        $readyCampaigns[] = array(
            'name'=>$campaigns->name,
            'subject'=>$campaigns->subject,
            'from_name'=>$campaigns->from_name,
            //'from_email'=>$campaigns->from_email,
            'created_at'=>$campaigns->created_at,
            'list_name'=>$campaigns->list_name,
            'is_done'=>$campaigns->is_done,
            'id'=>$campaigns->id
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
