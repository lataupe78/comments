<template>
	<div class="list-group-item" :class="{ 'ml-5 pl-5': isAReply }">
		<div class="w-100 d-flex align-items-center py-2 border-bottom">
			<div>
				<small class="text-muted mr-1">{{ comment.created_at }},</small>
				<strong>{{ comment.username }}</strong> a dit:
			</div>
			<div class="actions ml-auto" v-if="canEdit">
				<button class="btn btn-sm btn-danger mr-0 mb-1">Supprimer</button>
				<button class="btn btn-sm btn-primary mr-0 ml-1 mb-1">Editer</button>
			</div>
			<div class="actions ml-auto" v-if="!isAReply">
				<button class="btn btn-sm btn-success mr-0 mb-1" @click="showForm =!showForm">Répondre</button>
			</div>
		</div>
		<div class="py-2">
			{{ comment.content }}
		</div>

		<comment-form v-if="!isAReply"
			v-show="showForm"
			:id="comment.commentable_id"
			:model="comment.commentable_type"
			:reply="comment.id"
			/>

		<comment
			v-if="!isAReply"
			v-for="(comment, index) in comment.replies"
			:ip="ip"
			:comment="comment"
			:key="index"
			:isReply="true"></comment>
	</div>

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
				showForm: false
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
