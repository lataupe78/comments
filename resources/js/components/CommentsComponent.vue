<template>

	<div class="card my-5">

		<div class="card-header d-flex">
			<h2>Ici les commentaires</h2>
				<small class="text-muted ml-auto">ip: {{ ip }}</small>
		</div>

		<div class="card-body">

			<div class="alert alert-info" v-if="comments.length === 0">
				<p>No Comments Yet</p>
			</div>


			<comment v-else
				v-for="(comment, index) in comments"
					:comment="comment"
					:ip="ip"
					:key="index">
			</comment>

		</div>
		<div class="card-footer">
			<comment-form :id="id" :model="model"></comment-form>
		</div>
	</div>
</template>
<script>

	import CommentComponent from './CommentComponent.vue';
	import CommentFormComponent from './CommentFormComponent.vue';

	export default {

		components: {
			comment: CommentComponent,
			commentForm: CommentFormComponent,
		},

		props: {
			id: Number,
			model: String,
			comments: Array,
			ip: String
		}

	}
</script>
