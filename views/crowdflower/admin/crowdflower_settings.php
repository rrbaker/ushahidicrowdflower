<div class="row">
	<?php print form::input('crowdflower_apikey', $form['crowdflower_apikey'], ' class="text"'); ?>
	<label>
		<a href="#" class="tooltip" title="This is provided in your CrowdFlower account settings">
			<?php echo Kohana::lang('crowdflower.cf_api_label');?>
		</a>
	</label>
</div>

<div class="row">
	<?php print form::input('crowdflower_jobid', $form['crowdflower_jobid'], ' class="text"'); ?>
	<label>
		<a href="#" class="tooltip" title="This is the Job # in the title of your account">
			<?php echo Kohana::lang('crowdflower.cf_jobid_label');?>
		</a>
	</label>
</div>