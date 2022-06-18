<template>
	<div class="card">
	    <div class="card-header d-flex justify-content-between align-items-center"> 
	        <h5 class="m-0">Add Head of GL Account</h5>
	    </div>
		
	    <div class="card-body"> 
	        <form @submit.prevent="saveGlHead" class="form-row">
				<div class="form-group col-md-12 required">
					<label for="name">GL Head Name</label>
					<input type="text" class="form-control" id="name" v-model="name" placeholder="Enter gl head name" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="type">Account Type</label>
					<select v-model="type" class="form-control" id="type" required>
						<option :value="null" disabled>Select one</option>
						<option v-for="(statement, key, index) in statements" :value="key" :key="index">{{ statement }}</option>
					</select>
				</div>

				<div class="form-group col-md-6 required">
					<label for="gl_account_id">GL Account Name</label>
					<select v-model="gl_account_id" class="form-control" id="gl_account_id" required>
						<option :value="null" disabled>Select one</option>
						<option v-for="(gl_account, index) in glAccounts" :value="gl_account.id" :key="index">{{ gl_account.name }}</option>
					</select>
				</div>

				<div class="form-group col-md-12">
					<label for="description">Description</label>
					<textarea v-model="description" id="description" class="form-control" placeholder="write something about this gl head"></textarea>
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
		// in app-admin.js make a component named CreateGLAccountHeadComponent 
		name: "CreateGLAccountHeadComponent",
		props: ['statements', 'glAccount'],

		data(){
			return{
				name:  null,
				type: null,
				getGlName: [],
				glAccounts: [],
				gl_account_id: null,
				description: null
			}
		},
		methods: {
			initValues(){
				console.log(this.glAccount);

				// set value from v-model
				this.glAccounts = this.glAccount;

				console.log(this.glAccounts)
			},
			saveGlHead(){
				this.$awn.asyncBlock(
					// in web route admin/genaralLedgerHead store route 
					axios.post(baseURL + 'admin/glAccountHead', { // post means store method 
						name: this.name,
						type: this.type,
						gl_account_id: this.gl_account_id,
						description: this.description
					}),
					response => {
						console.log(response.data);
						
						this.$awn.success("GL account head created.");
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
		},
		mounted(){
			this.initValues();
			console.log(this.statements);
		}
	}
</script>
