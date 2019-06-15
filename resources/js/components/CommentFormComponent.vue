<template>
	<div class="my-2">

		<a :id="anchor"></a>

		<div class="form-group row">
			<label for="username" class="col-form-label col-md-3 text-md-right">Pseudo</label>
			<div class="col-md-9">
				<input type="text" id="username" class="form-control" :class="{'is-invalid' : errors.username }" v-model="username">
				<div class="invalid-feedback" v-if="errors.username">{{ errors.username.join(', ') }}</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="email" class="col-form-label col-md-3 text-md-right">Email</label>
			<div class="col-md-9">
				<input type="email" id="email" class="form-control" :class="{'is-invalid' : errors.email }" v-model="email">
				<div class="invalid-feedback" v-if="errors.email">{{ errors.email.join(', ') }}</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="content" class="col-form-label col-md-3 text-md-right">commentaire</label>
			<div class="col-md-9">
				<textarea id="content" class="form-control" :class="{'is-invalid' : errors.content }" v-model="content"></textarea>
				<div class="invalid-feedback" v-if="errors.content">{{ errors.content.join(', ') }}</div>
			</div>
		</div>

		<div class="actions d-flex py-2">
			<button class="btn btn-default" v-if="reply !=null" @click.prevent="$emit('cancelEdit')">Annuler</button>

			<button class="btn btn-primary ml-auto" @click.prevent="sendForm">
				<div class="spinner-border mx-4" role="status" v-if="isLoading">
					<span class="sr-only">Loading...</span>
				</div>
				{{ (reply === null) ? 'Commenter' : 'Répondre' }}
			</button>
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
			},
			anchor: {
				type: String,
				default: 'form_main'
			}
		},

		data(){

			return {
				username: '',
				email: '',
				content: '',
				errors : {},
				isLoading: false
			}
		},

		methods: {

			clearForm(){
				this.username = ''
				this.email = ''
				this.content = ''
				this.errors = {}
			},

			sendForm(){

				//var vm = this
				this.isLoading = true;
				axios.post('/comments', {
					content: this.content,
					reply_to: this.reply ? this.reply : null,
					commentable_id: this.id,
					commentable_type: this.model,
					username: this.username,
					email: this.email,

				})
				.then((response) => {
					this.isLoading = false;
					this.$store.dispatch('addComment', response.data)
					this.clearForm()
				})
				.catch((error) => {
					this.isLoading = false;
					let errorObject=JSON.parse(JSON.stringify(error));
					console.error(errorObject)
					//console.error(error)
					this.errors = errorObject.response.data.errors
				})

			}
		}

	}
</script>
<style>
.form-group label {
	font-weight: bold !important;
}
</style>
