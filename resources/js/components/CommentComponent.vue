<template>

	<transition type="transition" name="fade-from-right">

	<div class="list-group-item" :class="{ 'pl-5 pr-0': isAReply }">
		<div class="w-100 d-flex align-items-start">

			<img :src="'http://www.gravatar.com/avatar/' + comment.email_md5" :alt="comment.username" class="rounded-circle mr-2 avatar">

			<div class="comment w-100">

				<div class="d-flex align-items-center w-100 py-2 border-bottom">
						<div class="comment-meta mr-auto">
							<small class="text-muted mr-1">{{ comment.created_at }},</small>

							<strong>{{ comment.username }}</strong> a dit:
							<div class="spinner-border mx-4" role="status" v-if="isLoading">
								<span class="sr-only">Loading...</span>
							</div>
						</div>

						<div class="actions ml-auto">
							<button class="btn btn-sm btn-outline-danger"
							v-if="canEdit"
							@click="deleteComment">Supprimer</button>
							<button class="btn btn-sm btn-outline-primary"
							v-if="canEdit">Editer</button>
							<button class="btn btn-sm btn-outline-success"
							v-if="!isAReply"
							@click="toggleShowForm"
							>Répondre</button>
						</div>
				</div>

				<div class="comment-content py-2">
					{{ comment.content }}
				</div>
			</div>

		</div>


		<div class="list-group list-group-flush" v-if="comment.replies">
			<comment
			v-for="(comment, index) in comment.replies"
			:ip="ip"
			:comment="comment"
			:key="index"
			:isReply="true"></comment>
		</div>

		<comment-form v-if="!isAReply"
		v-show="showForm === true"
		:id="comment.commentable_id"
		:model="comment.commentable_type"
		:reply="comment.id"
		:anchor="'form_' + comment.id"
		@cancelEdit="showForm = false"
		/>


	</div>

	</transition>
</template>

<script>
	import CommentFormComponent from './CommentFormComponent.vue';

	export default {

		components: {
			commentForm: CommentFormComponent,
		},

		name: 'comment',

		props: {
			comment: Object,
			ip: String,
			isReply: {
				type: Boolean,
				default :false
			}

		},

		data(){

			return {
				showForm: false,
				isLoading: false
			}
		},

		methods: {
			toggleShowForm(){
				this.showForm = !this.showForm
				if(this.showForm === true){
					console.log('looking for #form_' + + this.comment.id)
					this.$nextTick(function () {

						this.$scrollTo("#form_" + this.comment.id)

						// document.location.hash = "#form_" + this.comment.id
					});
				}
			},

			deleteComment(){
				if(confirm("Confirmez la suppression du commentaire:\r\n\r\n" + '#' + this.comment.id + "\r\n" + this.comment.content)){
					this.isLoading = true;
					axios.delete('/comments/' + this.comment.id)
					.then((response) => {
						this.$store.dispatch('deleteComment', response.data)
						this.isLoading = false
					}).catch((error) => {
						this.isLoading = false;
						let errorObject = JSON.parse(JSON.stringify(error));
						console.error(errorObject)
					})
				}
			}
		},

		computed: {
			isAReply(){
				return this.comment.reply_to != null
			},

			canEdit(){
				return this.comment.ip_md5 === this.ip
			},

		}

	}

</script>

<style>
.actions {
	text-align: right
}

.actions .btn {
	font-size: .64rem !important;
	margin-bottom: .5rem;
	margin-left: .25rem;

}
.avatar {
	max-height: 48px;
	width: auto;
}
.comment-content {
	/*white-space: pre;*/
}

	.fade-from-right-enter-active, .fade-from-right-leave-active {
		opacity: 1;
		transition: all 0.3s !important;
		transform: translateX(0) !important;
	}

	.fade-from-right-enter, .fade-from-right-leave-to {
		opacity: 0;
		transform: translateX(64px) !important;
	}
</style>

