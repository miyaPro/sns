<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<?php echo __('reports_list') ?>
				<span style="text-transform:none;"></span>
			</header>
			<div class="panel-body service-first">
				<?php echo $this->Form->create('Report', array('type' => 'get', 'url' => array('controller' => 'reports', 'action' => 'index'), 'id' => 'dateForm')); ?>
				<div class="form-group form-report-list">
					<div class="search-box col-lg-10 col-md-12">
						<div class="col-md-2 col-sm-4 col-xs-4 xs-period">
							<div class="control-label label-period"><?php echo __d('field', 'period'); ?></div>
						</div>
						<div class="input-group input-large input-space col-md-3 col-sm-8 col-xs-8" data-date="" data-date-format="yyyy/mm/dd">
							<input type="text" name="from" id="inputDateFrom" class="form-control input-dp form-control default-date-picker dpd1" value="<?php echo date('Y/m/d',$startDate); ?>">
							<span class="dateLabel input-group-addon"><?php echo __('To'); ?></span>
							<input type="text"  name="to" id="inputDateTo" class="form-control input-dp form-control default-date-picker dpd2" value="<?php echo date('Y/m/d',$endDate); ?>">
						</div>
						<div class="col-md-2 col-sm-4 col-xs-4 xs-period col-keyword">
							<div class="control-label keyword"><?php echo __d('field', 'keyword'); ?></div>
						</div>
						<div class="input-group input-large input-space col-md-1 col-sm-8 col-xs-8">
							<input type="text"name="keyword" id="keyword" class="form-control" value="<?php echo @$keyword; ?>">
						</div>
						<div class="col-md-1 col-xs-1 button-box">
							<button class="btn btn-primary" type="button" id="btnGo"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="panel-body table-responsive">
				<table class="table table-striped table-hover table-bordered">
					<thead>
					<tr role="row">
						<th class="clientNo"><?php echo __d('field','username');?></th>
						<th class="clientNo"><?php echo __d('field','ga_account_name');?></th>
						<th><?php echo __d('field', 'client');?></th>
						<th><?php echo __d('field', 'service');?></th>
						<th><?php echo __d('field', 'url');?></th>
						<th class="clientAction"><?php echo __d('field','action'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php if(isset($sites) && count($sites) > 0): ?>
						<?php foreach($sites as $site): ?>
							<?php $arrReport=null;?>
							<?php if(!empty($site['Report']['filenamefull'])):?>
								<?php $typeReport = @explode(',',$site['Report']['filenamefull']);?>
							<?php else:?>
								<?php $typeReport='';?>
							<?php endif?>
							<?php if(!empty($typeReport)) :?>
								<?php foreach($typeReport as $key=>$row):?>
									<?php $file = @explode(':',$row);?>
									<?php if(@!empty($file[1])):?>
										<?php if($file[1]==$makeReports['lite']):?>
											<?php $arrReport['lite'] = array(
												'status'=>@$file[0],
												'type_make_report'=>@$file[1],
												'filename'=>@$file[2]
											);?>
										<?php else:?>
											<?php $arrReport['full'] = array(
												'status'=>@$file[0],
												'type_make_report'=>@$file[1],
												'filename'=>@$file[2]
											);?>
										<?php endif?>

									<?php endif;?>
								<?php endforeach; ?>
							<?php endif;?>
							<?php $complete = array(); ?>
							<?php if(isset($arrReport['lite']['status'])): ?>
								<?php if($arrReport['lite']['status'] === $statusReport['inprogress'] || $arrReport['lite']['status'] === $statusReport['waiting']): ?>
									<?php $complete['lite'] = false;?>
								<?php elseif($arrReport['lite']['status'] === $statusReport['done']): ?>
									<?php $complete['lite'] = true;?>
								<?php elseif($arrReport['lite']['status'] === $statusReport['error']): ?>
									<?php $complete['lite'] = null;?>
								<?php endif; ?>
							<?php else: ?>
								<?php $complete['lite'] = null; ?>
							<?php endif ?>
							<?php if(isset($arrReport['full']['status'])): ?>
								<?php if($arrReport['full']['status'] === $statusReport['inprogress'] || $arrReport['full']['status'] === $statusReport['waiting']): ?>
									<?php $complete['full'] = false;?>
								<?php elseif($arrReport['full']['status'] === $statusReport['done']): ?>
									<?php $complete['full'] = true;?>
								<?php elseif($arrReport['full']['status'] === $statusReport['error']): ?>
									<?php $complete['full'] = null;?>
								<?php endif; ?>
							<?php else: ?>
								<?php $complete['full'] = null; ?>
							<?php endif ?>
							<tr>
								<td><?php echo @$site['User']['username']; ?></td>
								<td><?php echo @$site['Account']['ga_account_name']; ?></td>
								<td><?php echo $site['User']['companyname']; ?></td>
								<td><?php echo $site['User']['service']; ?></td>
								<td class="url-width"><div class="ctWordWrapUrl text-left"><?php echo $site['Site']['url']; ?></div></td>
								<td id="tdMake<?php echo $site['Site']['id'];?>" class="action-make">
									<?php if(isset($complete['full']) && isset($complete['lite'])): ?>
										<?php if($complete['full'] && $complete['lite']): ?>
											<div class="complete">
												<a href="/download-report/<?php echo $arrReport['lite']['filename']?>" class="btn btn-sm btn-success btnDownloadReportLite" >
													<?php echo __d('button', 'download_report_lite'); ?>
												</a>
												<a href="/download-report/<?php echo $arrReport['full']['filename']?>" class="btn btn-sm btn-success btnDownloadReport" >
													<?php echo __d('button', 'download'); ?>
												</a>
											</div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnReMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','remake_report_lite'); ?>
												</button>
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnReMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','remake_report'); ?>
												</button>
											</div>
										<?php elseif($complete['full']): ?>
											<div class="complete">
												<a href="/download-report/<?php echo $arrReport['full']['filename']?>" class="btn btn-sm btn-success btnDownloadReport" >
													<?php echo __d('button', 'download'); ?>
												</a>
											</div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnReMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','remake_report'); ?>
												</button>
												<a class="progress-lite"><span class="label label-primary label-progress"><?php echo __d('button','in_progress_report_lite'); ?></span></a>
											</div>
										<?php elseif($complete['lite']): ?>
											<div class="complete">
												<a href="/download-report/<?php echo $arrReport['lite']['filename']?>" class="btn btn-sm btn-success btnDownloadReportLite" >
													<?php echo __d('button', 'download_report_lite'); ?>
												</a>
											</div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnReMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','remake_report_lite'); ?>
												</button>
												<a class="progress-full"><span class="label label-primary label-progress"><?php echo __d('button','in_progress'); ?></span></a>
											</div>
										<?php else: ?>
											<div class="incomplete">
												<a class="progress-lite"><span class="label label-primary label-progress"><?php echo __d('button','in_progress_report_lite'); ?></span></a>
												<a class="progress-full"><span class="label label-primary label-progress"><?php echo __d('button','in_progress'); ?></span></a>
											</div>
										<?php endif; ?>
									<?php elseif(isset($complete['full'])): ?>
										<?php if($complete['full']):?>
											<div class="complete">
												<a href="/download-report/<?php echo $arrReport['full']['filename']?>" class="btn btn-sm btn-success btnDownloadReport" >
													<?php echo __d('button', 'download'); ?>
												</a>
											</div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','make_report_lite'); ?>
												</button>
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnReMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','remake_report'); ?>
												</button>
											</div>
										<?php else: ?>
											<div class="complete"></div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','make_report_lite'); ?>
												</button>
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','make_report'); ?>
												</button>
											</div>
										<?php endif; ?>
									<?php elseif(isset($complete['lite'])): ?>
										<?php if($complete['lite']): ?>
											<div class="complete">
												<a href="/download-report/<?php echo $arrReport['lite']['filename']?>" class="btn btn-sm btn-success btnDownloadReportLite" >
													<?php echo __d('button', 'download_report_lite'); ?>
												</a>
											</div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnReMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','remake_report_lite'); ?>
												</button>
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','make_report'); ?>
												</button>
											</div>
										<?php else: ?>
											<div class="complete"></div>
											<div class="incomplete">
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','make_report_lite'); ?>
												</button>
												<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','make_report'); ?>
												</button>
											</div>
										<?php endif; ?>
									<?php else: ?>
										<div class="incomplete">
											<?php if(isset($group) && $group == 1) :?>
												<button id="butMake_test<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnTestMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
													<?php echo __d('button','test_make_report'); ?>
												</button>
											<?php endif;?>
											<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReportLite" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
												<?php echo __d('button','make_report_lite'); ?>
											</button>
											<button id="butMake<?php echo $site['Site']['id'];?>" class="btn btn-primary btn-sm  btnMakeReport" data-siteid = "<?php echo $site['Site']['id']; ?>" data-status="0">
												<?php echo __d('button','make_report'); ?>
											</button>
										</div>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>

					</tbody>
				</table>
				<?php if(isset($sites) && count($sites) > 0): ?>
				<?php endif ?>
				<br/>
				<?php echo $this->element("paginate");?>
			</div>
		</section>
	</div>
</div>
<div id="modalReportAllError" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class='alert alert-danger' role='alert'>
					<span><?php echo __d('message', 'report_not_select'); ?><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>×</span></button></span>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modalReportError" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class='alert alert-danger' role='alert'><span><?php echo __d('message', 'common_error'); ?><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>×</span></button></span></div>
			</div>
		</div>
	</div>
</div>
<div id="modalDatesError" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class='alert alert-danger' role='alert'><span><?php echo __d('message', 'date_compare_error'); ?><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>×</span></button></span></div>
			</div>
		</div>
	</div>
</div>
<div id="modalMakeReportError" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class='alert alert-danger' role='alert'><span><?php echo __d('message', 'make_report_error'); ?><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>×</span></button></span></div>
			</div>
		</div>
	</div>
</div>
<!-- Ajax -->
<script type="text/javascript">
	$(function () {
		var baseUrl = '<?php echo Router::fullbaseUrl();?>';
		$('.action-make').on('click', '.btnMakeReport', function(event) {
			console.log('btnMakeReport');
			event.preventDefault();
			var parent = $(this).parents('.action-make');
			parent.find('.btnTestMakeReportLite').hide();
			$(this).replaceWith("<a class='progress-full'><span class='label label-primary label-progress'><?php echo __d('button','in_progress')?></span></a>");
			var startDate = moment($('#inputDateFrom').val()).format("YYYYMMDD");
			var endDate = moment($('#inputDateTo').val()).format("YYYYMMDD");
			var resultCheckDate = compareDate(startDate, endDate);
			if (resultCheckDate == true ) {
				var data = {
					siteId: $(this).data('siteid'),
					startDate: startDate,
					endDate: endDate,
					singleMake: 1, //1 (single make) or 0 (much make)
					typeMakeReport:<?php echo $makeReports['full']?> //1 Make lite or 2 make full version
				};
				$(this).makeReport(data, baseUrl, '.btnMakeReport');
			} else {
				$('#modalDatesError').modal('show');
			}
		});
		$('.action-make').on('click', '.btnReMakeReport', function(event) {
			event.preventDefault();
			var parent = $(this).parents('.action-make');
//			$(this).hide();
			parent.find(".btnDownloadReport").hide();
			$(this).replaceWith("<a class='progress-full'><span class='label label-primary label-progress'><?php echo __d('button','in_progress')?></span></a>");
			var startDate = moment($('#inputDateFrom').val()).format("YYYYMMDD");
			var endDate = moment($('#inputDateTo').val()).format("YYYYMMDD");
			var resultCheckDate = compareDate(startDate, endDate);
			if (resultCheckDate == true ) {
				var data = {
					siteId: $(this).data('siteid'),
					startDate: startDate,
					endDate: endDate,
					singleMake: 1,
					typeMakeReport:<?php echo $makeReports['full']?>,
					remake:1
				};
				$(this).makeReport(data, baseUrl, '.btnReMakeReport');
			} else {
				$('#modalDatesError').modal('show');
			}
		});

		$('.action-make').on('click', '.btnMakeReportLite', function(event) {
			console.log('btnMakeReportLite');
			event.preventDefault();
			var parent = $(this).parents('.action-make');
//			$(this).hide();
			parent.find('.btnTestMakeReportLite').hide();
//			$(this).removeClass('btn-primary').addClass('btn-info').text("<?php //echo __d('button','in_progress_report_lite')?>//");
			$(this).replaceWith("<a class='progress-lite'><span class='label label-primary label-progress'><?php echo __d('button','in_progress_report_lite')?></span></a>");
			var startDate = moment($('#inputDateFrom').val()).format("YYYYMMDD");
			var endDate = moment($('#inputDateTo').val()).format("YYYYMMDD");
			var resultCheckDate = compareDate(startDate, endDate);
			if (resultCheckDate == true ) {
				var data = {
					siteId: $(this).data('siteid'),
					startDate: startDate,
					endDate: endDate,
					singleMake: 1, //1 (single make) or 0 (much make),
					remake:1,
					typeMakeReport:<?php echo $makeReports['lite']?>
				};
//	            console.log(data);
				$(this).makeReport(data, baseUrl, '.btnMakeReportLite');
			} else {
				$('#modalDatesError').modal('show');
			}
		});
		$('.action-make').on('click', '.btnReMakeReportLite', function(event) {
			console.log("btnReMakeReportLite");
			event.preventDefault();
			var parent = $(this).parents('.action-make');
//			$(this).hide();
//			$(this).removeClass('btn-primary').addClass('btn-info').text("<?php //echo __d('button','in_progress_report_lite')?>//");
			$(this).replaceWith("<a class='progress-lite'><span class='label label-primary label-progress'><?php echo __d('button','in_progress_report_lite')?></span></div>");
			var startDate = moment($('#inputDateFrom').val()).format("YYYYMMDD");
			var endDate = moment($('#inputDateTo').val()).format("YYYYMMDD");
			var resultCheckDate = compareDate(startDate, endDate);
//			$(".btnReMakeReportLite").hide();
			parent.find('.btnDownloadReportLite').hide();
			if (resultCheckDate == true ) {
				var data = {
					siteId: $(this).data('siteid'),
					startDate: startDate,
					endDate: endDate,
					singleMake: 1,
					remake:1,
					typeMakeReport:<?php echo $makeReports['lite']?>
				};
				$(this).makeReport(data, baseUrl,'.btnReMakeReportLite');
			} else {
				$('#modalDatesError').modal('show');
			}
		});

		// make report
		var ajxMake;
		$.fn.makeReport = function(data, baseUrl, classname) {
			var parent = $(this).parent();
			// console.log(data);
			if(data.typeMakeReport==<?php echo $makeReports['full']?>) {
				progress = "<?php echo __d('button','in_progress'); ?>";
				download = "<?php echo __d('button', 'download'); ?>";
			}else{
				progress="<?php echo __d('button','in_progress_report_lite'); ?>";
				download = "<?php echo __d('button', 'download_report_lite'); ?>";
			}
//			parent.html(parent.html() + '<a class="improgres_'+data.typeMakeReport+'"><span class="label label-primary">'+progress+'</span></a>');
			// Post ajax
			ajxMake = $.ajax({
				type:"POST",
				url: baseUrl+'/make',
				data: data,
				cache: false,
				context:this,
				success:function(result)
				{
					result = JSON.parse(result);
					if (result.status == "done") {

					} else if(result.status == "error"){
						parent.find( classname ).show();
						parent.find( ".improgres_"+data.typeMakeReport ).hide();
						$('#modalMakeReportError').modal('show');
					}
				}
			});
		};
	});

</script>
<script type="text/javascript">
	$("#btnGo").click(function() {
		var form = moment($('#inputDateFrom').val()).format("YYYYMMDD");
		var to   = moment($('#inputDateTo').val()).format("YYYYMMDD");
		if (compareDate(form, to)) {
			$('#inputDateFrom').val(form);
			$('#inputDateTo').val(to);
			$( "#dateForm" ).submit();
		} else {
			$('#modalDatesError').modal('show');
		}
	});
</script>