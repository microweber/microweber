<div class="mw-module-admin-wrap">

	<module type="admin/modules/info" />

	<?php require 'admin_settings.php'; ?>

	<?php $translations = DB::table('translations')->get(); ?>

	<h3>
		Available Translations
		(<?php echo count($translations); ?>)
	</h3>

	<table width="100%">
	<thead>
		<tr>
			<th>Language</th>
			<th>Source ID</th>
			<th>Source Type</th>
			<th>Translated Data</th>
		</tr>
	<thead>
	<?php if(count($translations)): ?>
	<?php foreach($translations as $translation): ?>
		<tr>
			<td>
				<?php echo $translation->lang; ?>
				<div class="mw-language-tag"><?php echo $translation->lang; ?></div>
			</td>
			<td align="center"><?php echo $translation->translatable_id; ?></td>
			<td align="center"><?php echo $translation->translatable_type; ?></td>
			<td>
<pre>
<?php
$json = json_decode($translation->translation);
var_dump((array)$json);
?>
</pre>
			</td>
		</tr>
	<?php endforeach; ?>
	<?php endif; ?>
	</table>
</div>
