<?php include 'header.php'; ?>

<div class="row">

	<div class="col-12">

		<div class="cdq-new-list-wrap">

			<div class="row title-bar">
				<div class="col-12">
					<h2>Create Data List</h2>
				</div>
			</div>


			<form method="post" action="admin.php?page=new-data-list">
				
				<div class="row">
					
					<div class="col-12">

						<div class="mb-3">

							<label for="data_list_title" class="form-label">Data List Title</label>

							<input type="text" class="form-control" id="data_list_title" name="data_list_title" placeholder="Data List Title" autocomplete="off" required>

						</div>
						
					</div>

					<div class="col-md-6">
						
						<div class="mb-3">

							<label for="data_list_title" class="form-label">Front Search Button Text</label>

							<input type="text" class="form-control" id="front_search_button_text" name="front_search_button_text" placeholder="Search Button Text" autocomplete="off" required>

						</div>

					</div>

					<div class="col-md-6">
						<div class="mb-3">

							<label for="data_list_title" class="form-label">Front Search Placeholder Text</label>

							<input type="text" class="form-control" id="front_search_placeholder_text" name="front_search_placeholder_text" placeholder="Search Placeholder Text" autocomplete="off" required>

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


					<div class="row ndf">
						
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

							<input class="form-check-input cdq-list-checkbox data-search-field" type="checkbox" name="data-search[]">

						</div>

						<div class="col-md-2">
							<button type="button" class="btn btn-success add-data-field-button" onclick="addDataField();">+</button>

						</div>

					</div>



					
				</div>
				<div class="row">
					<div class="col-auto ms-auto mt-5">
						<button type="submit" class="btn btn-primary btn-create-data-list mt-3">Create Data List</button>
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

						<input class="form-check-input cdq-list-checkbox data-search-field" type="checkbox" name="data-search[]">

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
