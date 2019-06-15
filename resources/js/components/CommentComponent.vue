<template>
	<div class="list-group-item" :class="{ 'pl-5 pr-0': isAReply }">
		<div class="w-100 d-flex align-items-center py-2 border-bottom">
			<div>
				<small class="text-muted mr-1">{{ comment.created_at }},</small>
				<strong>{{ comment.username }}</strong> a dit:
			</div>

			<div class="actions ml-auto">
				<button class="btn btn-sm btn-danger"
				v-if="canEdit">Supprimer</button>
				<button class="btn btn-sm btn-primary"
				v-if="canEdit">Editer</button>
				<button class="btn btn-sm btn-success"
				v-if="!isAReply"
				@click="toggleShowForm"
				>Répondre</button>
			</div>
		</div>

		<div class="py-2">
			{{ comment.content }}
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

</template>

<script>
	import CommentFormComponent from './CommentFormComponent.vue';

	var options = {
		container: '#container',
		easing: 'ease-in',
		offset: -60,
		force: true,
		cancelable: true,
		onStart: function(element) {
	      // scrolling started
	  },
	  onDone: function(element) {
	      // scrolling is done
	  },
	  onCancel: function() {
	      // scrolling has been interrupted
	  },
	  x: false,
	  y: true
	}

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
				showForm: false
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
</style>
