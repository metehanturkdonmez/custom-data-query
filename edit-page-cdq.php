<?php include 'header.php'; ?>

<div class="row">

	<div class="col-12">

		<div class="cdq-new-list-wrap">

			<?php 

			global $cdqplug;

			$cdqplug = new cdqPlug();

			$cdq_data_lists = $cdqplug->get_list_item($_GET['cdq_list_id']);

			$name = $cdq_data_lists[0]->name;
			
			?>

			<div class="title-bar px-3">

				<div class="row">

					<div class="col">

						<h2 class="data-list-name"><?php echo $name; ?></h2>

					</div>

					<div class="col-auto ms-auto">

						<a href="admin.php?page=cdq-datas-page&cdq_list_id=<?php echo $cdq_data_lists[0]->id; ?>" class="btn btn-warning text-decoration-none btn-sm btn-square mt-1">Data List</a>

					</div>

				</div>

			</div>

			<form method="post" action="admin.php?page=edit-data-list">

				<input type="hidden" name="data_list_id" value="<?php echo $_GET['cdq_list_id']; ?>">

				<div class="row">
					
					<div class="col-12">

						<div class="mb-3">

							<label for="data_list_title" class="form-label">Data List Title</label>

							<input type="text" class="form-control" id="data_list_title" name="data_list_title_edit" placeholder="Data List Title" autocomplete="off" value="<?php echo $cdq_data_lists[0]->name; ?>" required>

						</div>
						
					</div>

					<div class="col-md-6">
						
						<div class="mb-3">

							<label for="data_list_title" class="form-label">Front Search Button Text</label>

							<input type="text" class="form-control" id="front_search_button_text" name="front_search_button_text" placeholder="Search Button Text" autocomplete="off" value="<?php echo $cdq_data_lists[0]->button_text; ?>" required>

						</div>

					</div>

					<div class="col-md-6">
						<div class="mb-3">

							<label for="data_list_title" class="form-label">Front Search Placeholder Text</label>

							<input type="text" class="form-control" id="front_search_button_text" name="front_search_placeholder_text" placeholder="Search Placeholder Text" autocomplete="off" value="<?php echo $cdq_data_lists[0]->placeholder_text; ?>" required>

						</div>
					</div>

				</div>

				<hr>

				<div class="cdq-card">

					<div class="row">

						<div class="col-md-4">

							<label class="form-label">Data name</label>

						</div>

						<div class="col-md-4">

							<label class="form-label">Data type</label>

						</div>

						<div class="col-md-2 text-center">

							<label class="form-label">Search field</label>

						</div>

					</div>

					<?php foreach (json_decode($cdq_data_lists[0]->titles) as $titles_key => $title) : ?>

						<div class="row ndf <?php if ($titles_key!=0){ echo "pt-3";} ?>">

							<div class="col-md-4">

								<input class="form-control" type="text" name="data-name[]" placeholder="Data name" value="<?php echo $title->name; ?>" required>

							</div>

							<div class="col-md-4">

								<select class="form-select data-type-field" name="data-type[]" required>

									<option value=""> - Select - </option>

									<option value="text" <?php if($title->type == 'text'){ echo "selected"; } ?> >Text</option>

									<option value="image" <?php if($title->type == 'image'){ echo "selected"; } ?> >Image</option>

									<option value="file" <?php if($title->type == 'file'){ echo "selected"; } ?> >File</option>

								</select>

							</div>

							<div class="col-md-2 text-center">

								<input class="form-check-input cdq-list-checkbox data-search-field" type="checkbox" name="data-search[<?php echo $titles_key; ?>]" <?php if($title->search != ''){ echo "checked"; } ?>>

							</div>

							<div class="col-md-1">

								<button type="button" class="btn btn-success add-data-field-button" onclick="addDataField();">+</button>

							</div>

							<div class="col-md-1">

								<?php if ($titles_key != 0): ?>

									<button type="button" class="btn btn-danger remove-data-field-button" onclick="removeDataField(this);">-</button>

								<?php endif; ?>

							</div>

						</div>

					<?php endforeach; ?>

				</div>

				<div class="row">

					<div class="col-auto ms-auto mt-5">

						<button type="submit" class="btn btn-primary btn-create-data-list mt-3">Save Data List</button>

					</div>

				</div>

			</form>

			<div class="copyfield d-none">

				<div class="row ndf pt-3">

					<div class="col-md-4">

						<input class="form-control" type="text" name="data-name[]" placeholder="Data name" required>

					</div>

					<div class="col-md-4">
						
						<select class="form-select data-type-field" name="data-type[]" required>

							<option value=""> - Select - </option>

							<option value="text">Text</option>

							<option value="image">Image</option>

							<option value="file">File</option>

						</select>

					</div>

					<div class="col-md-2 text-center">

						<input class="form-check-input cdq-list-checkbox data-search-field" type="checkbox" name="data-search[]" value="1">

					</div>

					<div class="col-md-1">

						<button type="button" class="btn btn-success add-data-field-button" onclick="addDataField();">+</button>

					</div>

					<div class="col-md-1">

						<button type="button" class="btn btn-danger remove-data-field-button" onclick="removeDataField(this);">-</button>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<?php include 'footer.php'; ?>
