

<div class="mw-settings-list<? if(isset($params['option_group'])): ?> mw-settings-list-<? print strtolower(trim($params['option_group'])) ?><? endif; ?>">
  <? if(isset($params['option_group'])): ?>
      <module="settings/group/<? print $params['option_group'] ?>" />
       <?  else: ?>
  <? // _e("No options found"); ?>
  <? endif; ?>
</div>
