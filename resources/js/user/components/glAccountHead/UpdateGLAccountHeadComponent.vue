<template>
	<div class="card">
	    <div class="card-header d-flex justify-content-between align-items-center"> 
	        <h5 class="m-0">Add Head of GL Account</h5>
	    </div>
		
	    <div class="card-body"> 
	        <form @submit.prevent="saveGlHead" class="form-row">
				<div class="form-group col-md-12 required">
					<label for="name">GL Head Name</label>
					<input type="text" class="form-control" id="name" v-model="glRecord.name" placeholder="Enter gl head name" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="type">Account Type</label>
					<select v-model="glRecord.type" class="form-control" id="type" required>
						<option :value="null" disabled>Select one</option>
						<option v-for="(statement, slug) in glStatements" :value="slug" :key="slug">{{ statement }}</option>
					</select>
				</div>

				<div class="form-group col-md-6 required">
					<label for="gl_account_id">GL Account Name</label>
					<select v-model="glRecord.gl_account_id" class="form-control" id="gl_account_id" required>
						<option :value="null" disabled>Select one</option>
						<option v-for="(glAccount, index) in glAccounts" :value="glAccount.id" :key="index">{{ glAccount.name }}</option>
					</select>
				</div>

				<div class="form-group col-md-12">
					<div class="row">
						<label class="col-sm-1">Status</label>
						<div class="col-sm-auto">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="status" v-model="glRecord.status" id="status-active" value="1">
								<label class="form-check-label" for="status-active">Active</label>
							</div>

							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="status" v-model="glRecord.status" id="status-inactive" value="0">
								<label class="form-check-label" for="status-inactive">Inactive</label>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group col-md-12">
					<label for="description">Description</label>
					<textarea v-model="glRecord.description" id="description" class="form-control" placeholder="write something about this gl head"></textarea>
				</div>

	            <div class="col-md-12 text-right">
	                <button type="reset" class="btn btn-danger">Reset</button>
	                <button type="submit" class="btn btn-primary">Save changes</button>
	            </div>
	        </form>
	    </div>
	</div>
</template>
<script>
	export default {
		name: "UpdateGLAccountHeadComponent", // in app-admin.js make a component named UpdateGLAccountHeadComponent 
		props: ['glAccounts', 'glStatements', 'glRecord'], // get value from admin.accounting.glAccountHead.edit blade
		data() {
			return {}
		},
		methods: {
			saveGlHead() {
				this.$awn.asyncBlock(
					// in web route admin/glAccountHead update route 
					axios.patch(baseURL + 'user/glAccountHead/' + this.glRecord.id, this.glRecord),
					response => {
						console.log(response.data)

						this.$awn.success(response.data);
						// location.reload(true);
					},
					error => {
						if (error.response.status === 422) { //validation error
							this.errors = error.response.data.errors
							this.$awn.alert('Opps! Enter the valid information of product')
						} else {
							this.$awn.alert('Opps! something went wrong. Try again later')
						}
					}
				)
			},
		}
	}
</script>
