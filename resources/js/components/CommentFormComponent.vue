<template>
	<div>
		<div class="form-group row">
			<label for="username" class="col-form-label col-md-3 text-md-right">Pseudo</label>
			<div class="col-md-9">
				<input type="text" id="username" class="form-control" v-model="form.username">
			</div>
		</div>
		<div class="form-group row">
			<label for="email" class="col-form-label col-md-3 text-md-right">Email</label>
			<div class="col-md-9">
				<input type="email" id="email" class="form-control" v-model="form.email">
			</div>
		</div>
		<div class="form-group row">
			<label for="comment" class="col-form-label col-md-3 text-md-right">Commentaire</label>
			<div class="col-md-9">
				<textarea id="comment" class="form-control" v-model="form.comment"></textarea>
			</div>
		</div>

		<div class="actions d-flex py-2">
			<button class="btn btn-primary ml-auto" @click="sendForm">Envoyer</button>
		</div>

	</div>
</template>
<script>
	export default {

		props : {
			id: Number,
			model: String,
			reply: {
				type: Number,
				default: null
			}
		},

		data(){

			return {
				form: {
					username: '',
					email: '',
					comment : ''
				}

			}
		},

		methods: {


			sendForm(){

				console.log(this.form)

			}
		}

	}
</script>
