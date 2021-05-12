<?php include 'header.php'; ?>

<div class="row">

	<div class="col-12">

		<?php global $cdq_notices; ?>

		<?php if (!empty($cdq_notices)): ?>

			<div class="cdq-notices">
				
				<?php echo $cdq_notices; ?>

			</div>
			
		<?php endif ?>

		<div class="cdq-table-wrap">

			<table class="table cdq-table">

				<thead>

					<tr>

						<th scope="col">

							<input class="form-check-input cdq-list-checkbox" type="checkbox" id="all-cdq-list">

						</th>

						<th scope="col">Data List Title</th>

						<th scope="col">Datas</th>

						<th scope="col" class="text-center">Shortcode</th>

						<th scope="col">Options</th>

					</tr>

				</thead>

				<tbody>

					<?php 

					global $cdqplug;

					$cdqplug = new cdqPlug();

					$cdq_data_lists = $cdqplug->get_list();

					?>

					<?php if (count($cdq_data_lists)==0) : ?>

						<tr>

							<td colspan="5" class="text-center">

								<a href="admin.php?page=new-data-list" class="btn btn-warning btn-sm border p-2 my-3 border-dark">Create a data list now!</a>

							</td>

						</tr>

						<?php else: ?>

							<?php foreach($cdq_data_lists as $data): ?>

								<tr>

									<td>

										<input class="form-check-input cdq-list-checkbox"  type="checkbox" id="check-cdq-list" name="check-cdq-list" value="<?php echo $data->id; ?>">

									</td>

									<td>

										<span class="data_list_title">

											<?php echo $data->name; ?>

										</span>

									</td>

									<td>

										<a href="admin.php?page=cdq-datas-page&cdq_list_id=<?php echo $data->id; ?>" class="btn btn-success text-decoration-none btn-sm btn-add-edit">
											Add / Edit
										</a>

									</td>

									<td>

										<input type="text" class="form-control w-50 ms-auto me-auto text-center" value='[cdq-query-form id="<?php echo $data->id; ?>"]'>

									</td>

									<td>

										<a href="admin.php?page=edit-data-list&cdq_list_id=<?php echo $data->id; ?>" class="btn btn-primary text-decoration-none btn-sm btn-options">Options</a>

										<a href="admin.php?page=cdq-manage-page&cdq_delete_list_id=<?php echo $data->id; ?>" class="btn btn-danger text-decoration-none btn-sm btn-delete" onclick="return confirm('Delete the data list?');">Delete</a>

									</td>

								</tr>

							<?php endforeach; ?>

						<?php endif; ?>

					</tbody>

				</table>

				<?php if (count($cdq_data_lists)!=0) : ?>

					<div class="table-footer">

						<div class="row m-0 p-0">

							<div class=" p-0 col-auto me-auto">

								<button type="button" class="btn btn-danger btn-sm btn-square delete_selected">Delete Selected</button>

							</div>

							<div class=" p-0 col-auto ms-auto">

								<a href="admin.php?page=new-data-list" class="btn btn-warning btn-sm btn-square">New Data List</a>

							</div>

						</div>

					</div>

				<?php endif; ?>

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

			location.href = 'admin.php?page=cdq-manage-page&cdq_delete_list_ids='+arrStr;

		});

	</script>

	<?php include 'footer.php'; ?>
