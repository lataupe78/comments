<template>

	<div class="card my-5">

		<div class="card-header">
			<h2>Ici les commentaires</h2>
			<small class="text-muted ml-auto">ip: {{ ip }}</small>
		</div>

		<div class="card-body">

			<div class="alert alert-info" v-if="isLoading">
				<div class="d-flex align-items-center">
					<div class="spinner-border mx-4" role="status">
						<span class="sr-only">Loading...</span>
					</div>
					<span>Loading...</span>
				</div>
			</div>

			<div class="alert alert-info" v-if="!isLoading && comments.length === 0">
				<p>No Comments Yet</p>
			</div>

			<div class="list-group list-group-flush" v-else>
			<comment
				v-for="(comment, index) in comments"
				:comment="comment"
				:ip="ip"
				:key="index">
			</comment>
			</div>

	</div>
	<div class="card-footer" v-if="!isLoading">
		<comment-form :id="id" :model="model"></comment-form>

		<pre>
			{{ comments }}
		</pre>


	</div>
</div>
</template>
<script>

	import CommentComponent from './CommentComponent.vue';
	import CommentFormComponent from './CommentFormComponent.vue';

	export default {

		vuex: {

			getters: {
				comments: (state) => state.comments
			},
			/*actions: {

				addComments: function(context, comments){
					console.log('store action addComments', context, comments)
					context.commit('ADD_COMMENTS', comments)
				}

			}*/

		},

		components: {
			comment: CommentComponent,
			commentForm: CommentFormComponent,
		},

		props: {
			id: Number,
			model: String,
			//comments: Array,
			ip: String
		},


		data(){
			return {
				isLoading: false,
				comments: this.$store.getters.comments
			}
		},

		mounted(){
			console.log('Comments mounted');

			console.log(this.$store)

			this.isLoading = true
			axios.get('/comments', {
				params: {
					id: this.id,
					type: this.model
				}
			}).then((response) => {
				//this.comments = response.data
				//this.addComments(response.data)
				//this.$store.dispatch('ADD_COMMENTS', response.data)
				this.$store.dispatch('addComments', response.data)
				this.isLoading = false
			})
		}

	}
</script>
