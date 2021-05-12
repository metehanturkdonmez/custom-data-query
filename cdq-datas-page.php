<?php include 'header.php'; ?>

<div class="row">

	<div class="col-12">

		<?php 

		wp_enqueue_media();

		global $cdqplug;

		$cdqplug = new cdqPlug();

		$cdqplug->add_data();

		$cdq_data_info = $cdqplug->get_list_item($_GET['cdq_list_id']);

		$name = $cdq_data_info[0]->name;

		$xid = $cdq_data_info[0]->id;

		if (count($cdq_data_info)>0) :

			$titles = json_decode($cdq_data_info[0]->titles);

		endif;

		?>

		<?php global $cdq_notices; ?>

		<?php if (!empty($cdq_notices)): ?>

			<div class="cdq-notices">

				<?php echo $cdq_notices; ?>

			</div>

		<?php endif ?>

		<div class="datas-table-wrap">

			<div class="cdq-table-title">

				<div class="row">

					<div class="col-auto">

						<h2 class="data-list-name"><?php echo $name; ?></h2>

					</div>

					<div class="col-auto ms-auto">

						<a href="admin.php?page=edit-data-list&cdq_list_id=<?php echo $cdq_data_info[0]->id; ?>" class="btn btn-warning text-decoration-none btn-sm btn-square mt-1">Option</a>

					</div>

				</div>

			</div>

			<table class="table cdq-table">

				<thead>

					<tr>

						<th scope="col">

							<input class="form-check-input cdq-list-checkbox" type="checkbox" id="all-cdq-list" >

						</th>

						<th scope="col" class="d-table-cell d-md-none">

							Data Summary

						</th>

						<?php foreach ($titles as $key => $title): ?>

							<th scope="col" class="d-none d-md-table-cell"><?php echo $title->name; ?></th>

						<?php endforeach ?>

						<th scope="col" class="d-none d-md-table-cell">Options</th>

					</tr>

				</thead>

				<tbody>

					<?php if (!empty($cdqplug->get_data($_GET['cdq_list_id']))): ?>	

						<?php $get_datas = $cdqplug->get_data($_GET['cdq_list_id']); ?>

								<?php foreach ($get_datas as $key => $data): ?>

									<tr>

										<?php $datajson[$data->id] = $data->datas; ?>

										<?php $datas_array = json_decode($data->datas); ?>

										<td scope="col">

											<input class="form-check-input cdq-list-checkbox" type="checkbox" id="check-cdq-data" name="check-cdq-data" value="<?php echo $data->id; ?>" >

										</td>

										<td scope="col" class="data-summary d-table-cell d-md-none">

											<table class="table table-striped">
												<?php 

												foreach ($titles as $key => $title): 

													if ($title->type == 'text') :

														if (isset($datas_array->{str_replace('-','_',sanitize_title($title->name))})) :

															echo "<tr><td><b>".$title->name."</b></td><td>".$datas_array->{str_replace('-','_',sanitize_title($title->name))}."</td></tr>";

														endif;

													else:

														if (isset($datas_array->{str_replace('-','_',sanitize_title($title->name))})):
															$attachment_url = wp_get_attachment_url($datas_array->{str_replace('-','_',sanitize_title($title->name))});

															if (!empty($attachment_url)) :
																echo "<tr><td><b>".$title->name."</b></td><td> <a target='blank' class='btn btn-danger text-decoration-none btn-sm preview-".$data->id."-".str_replace('-','_',sanitize_title($title->name))."' href='".$attachment_url."'>Preview</a>"."</td></tr>";	

															endif;

														endif;

													endif;

												endforeach;

												?>

											</table>

											<button type="button" class='btn btn-mobile btn-link text-decoration-none btn-sm btn-edit-<?php echo $data->id; ?>' href='javascript:;'>Edit</button>

											<a class='btn btn-mobile btn-link text-decoration-none btn-sm' href='admin.php?page=cdq-datas-page&cdq_list_id=<?php echo $xid; ?>&delete_data=<?php echo $data->id; ?>' onclick="return confirm('Delete the data?');">Delete</a>

										</td>

										<?php foreach ($titles as $key => $title): ?>
											<td scope="col" class="d-none d-md-table-cell">

												<?php 

												if ($title->type == 'text') :

													if (isset($datas_array->{str_replace('-','_',sanitize_title($title->name))})) :
														echo $datas_array->{str_replace('-','_',sanitize_title($title->name))};
													endif;

												else:

													if (isset($datas_array->{str_replace('-','_',sanitize_title($title->name))})) :
														$attachment_url = wp_get_attachment_url($datas_array->{str_replace('-','_',sanitize_title($title->name))});

														if (!empty($attachment_url)) :
															echo "<a target='blank' class='btn btn-danger text-decoration-none btn-sm preview-".$data->id."-".str_replace('-','_',sanitize_title($title->name))."' href='".$attachment_url."'>Preview</a>";	
														endif;

													endif;

												endif;

												?>

											</td>

										<?php endforeach ?>
										<td scope="col" class="option-td d-none d-md-table-cell">
											<button type="button" class='btn btn-success text-decoration-none btn-sm btn-edit-<?php echo $data->id; ?>' href='javascript:;'>Edit</button>

											<a class='btn btn-outline-danger text-decoration-none btn-sm' href='admin.php?page=cdq-datas-page&cdq_list_id=<?php echo $xid; ?>&delete_data=<?php echo $data->id; ?>' onclick="return confirm('Delete the data?');">Delete</a>

										</td>

										<script type="text/javascript">

											jQuery('.btn-edit-<?php echo $data->id; ?>').click(function(){

												resetForm();

												jQuery('.btn-add-data-text').html('Save Data');

												var myModal = new bootstrap.Modal(document.getElementById('add-new-modal'), {})

												jQuery('#datas_id').val(<?php echo $data->id; ?>);

												var dataJson = JSON.parse('<?php echo $datajson[$data->id]; ?>');

												jQuery.each(dataJson, function(index, element) {

													jQuery(".field-"+index).val(element);

													if (jQuery('a').hasClass('preview-'+<?php echo $data->id; ?>+'-'+index)==true) {
														jQuery('.preview-btn-'+index).html('<a target="blank" class="btn btn-success btn-sm" href="'+jQuery('.preview-'+<?php echo $data->id; ?>+'-'+index).attr('href')+'">Preview</a>');
													}

												});

												myModal.toggle();

											});

										</script>

									</tr>

								<?php endforeach ?>

							<?php else: ?>

								<tr>

								<td colspan="100" class="text-center">

									<button type="button" class="btn btn-warning btn-sm border p-2 my-3 border-dark" data-bs-toggle="modal" data-bs-target="#add-new-modal" id="add-new-modal-click" onclick="resetForm();">Create a data now!</button>

								</td>

							</tr>

						<?php endif ?>

					</tbody>

				</table>

				<div class="table-footer">

					<div class="row m-0 p-0">

						<div class=" p-0 col-auto me-auto">

							<button type="button" class="btn btn-danger btn-sm btn-square delete_selected">Delete Selected</button>

						</div>

						<div class=" p-0 col-auto ms-auto">

							<a href="javascript:;" class="btn btn-primary btn-sm btn-square" data-bs-toggle="modal" data-bs-target="#add-new-modal" id="add-new-modal-click" onclick="resetForm();">Add New Data</a>

						</div>

					</div>

				</div>

			</div>

			<script type="text/javascript">

				function resetForm(){

					jQuery('#datas_id').val('0');
					jQuery('.preview_image_link').html('');

					jQuery('.btn-add-data-text').html('Add Data');

					<?php foreach ($titles as $key => $title): ?>

						jQuery('.field-<?php echo str_replace('-','_',sanitize_title($title->name)); ?>').val('');

					<?php endforeach; ?>

				}

			</script>

		</div>

	</div>

	<div id="add-new-modal" class="modal mt-0" tabindex="-1">

		<div class="modal-dialog">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title"><?php echo $name; ?></h5>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

				</div>

				<form method="post" action="admin.php?page=cdq-datas-page&cdq_list_id=<?php echo $_GET['cdq_list_id']; ?>">

					<input type="hidden" name="datas_id" id="datas_id" value="0">

					<div class="modal-body">

						<table class="table table-light border-gray ">

							<tbody>

								<?php foreach ($titles as $key => $title): ?>

									<tr class="data-<?php echo $key; ?>">

										<td>

											<span><?php echo $title->name; ?></span>

										</td>

										<td>

											<?php if ($title->type == 'text') : ?>

												<input type='text' name="cdq_insert_field[<?php echo str_replace('-','_',sanitize_title($title->name)); ?>]" class='form-input w-100 data-text-input field-<?php echo str_replace('-','_',sanitize_title($title->name)); ?>' value=''>

												<?php elseif($title->type == 'image' or $title->type == 'file'): ?>

													<div class="row">

														<div class="col">

															<div class="preview_image_link preview-btn-<?php echo str_replace('-','_',sanitize_title($title->name)); ?>"></div>

														</div>

														<div class="col">

															<input type="button" name="upload-btn" id="upload-btn-<?php echo $key; ?>" class="w-100 button-secondary" value="Upload <?php echo $title->type; ?>">

															<input type='hidden' id="image_url_<?php echo $key; ?>" name="cdq_insert_field[<?php echo str_replace('-','_',sanitize_title($title->name)); ?>]" class='image_attachment_url field-<?php echo str_replace('-','_',sanitize_title($title->name)); ?>' value=''>

														</div>

														<script type="text/javascript">
															jQuery(document).ready(function($){

																$('#upload-btn-<?php echo $key; ?>').click(function(e) {
																	e.preventDefault();

																	var wrap_upl_button = jQuery(this).parent().parent();

																	var image = wp.media({ 
																		title: 'Upload  <?php echo $title->type; ?>',
																		multiple: false,

																		<?php if ($title->type == 'image'): ?>
																			library: {
																				type: [ 'image' ]
																			},

																			<?php elseif ($title->type == 'file'): ?>

																				library: {
																					type: [ 'application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-excel' ,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.oasis.opendocument.text']
																				},

																			<?php endif; ?>

																		}).open()
																	.on('select', function(e){

																		var uploaded_image = image.state().get('selection').first();


																		console.log(uploaded_image);
																		var image_url = uploaded_image.toJSON().url;

																		jQuery(wrap_upl_button).find('.preview_image_link').html('<a target="blank" href="'+image_url+'" class="btn btn-success btn-sm">Preview</a>');
																		jQuery(wrap_upl_button).find( '#image_url_<?php echo $key; ?>' ).val( uploaded_image.toJSON().id  );

																	});
																});
															});
														</script>

													</div>

												<?php endif; ?>

											</td>

										</tr>

									</tbody>

								<?php endforeach ?>

							</table>

						</div>

						<div class="modal-footer">

							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

							<button type="submit" class="btn btn-primary"><span class="btn-add-data-text">Add Data</span></button>

						</div>

					</form>

				</div>

			</div>

		</div>

		<script type="text/javascript">

			jQuery('.delete_selected').click(function(){

				var selected_items = [];

				var count = 0;

				jQuery("tbody .cdq-list-checkbox").each(function(i) {

					if (jQuery(this).prop('checked')==true) {

						selected_items[count] = jQuery(this).val();

						count++;

					}

				});

				var arrStr = encodeURIComponent(JSON.stringify(selected_items));

				let searchParams = new URLSearchParams(window.location.search)

				let param = searchParams.get('cdq_list_id');

				location.href = 'admin.php?page=cdq-datas-page&cdq_list_id='+param+'&delete_datas='+arrStr;

			});

		</script>

		<?php include 'footer.php'; ?>
