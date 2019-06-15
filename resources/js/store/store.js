import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({

	//strict: true,

	state: {
		comments: []
	},

	getters: {
		comments: function(state){
			return state.comments
		}
	},

	mutations: {

		ADD_COMMENTS(state, comments){
			state.comments.push(...comments)
		},

		ADD_COMMENT(state, comment){
			if(comment.reply_to){
				let c = state.comments.find((c) => c.id === comment.reply_to)
				if(c.replies === undefined){
					c.replies = []
				}
				c.replies.push(comment)
			} else {
				state.comments.push(comment)
			}
		},

		DELETE_COMMENT(state, comment){
			if(comment.reply_to){
				let parent = state.comments.find((c) => c.id === comment.reply_to)
				let index = parent.replies.findIndex((c) => c.id === comment.id)
				parent.replies.splice(index, 1)

			} else {
				let index = state.comments.findIndex((c) => c.id === comment.id)
				state.comments.splice(index, 1)

			}
		}
	},

	actions: {

		addComments: function(context, comments){
			//console.log('store action addComments', comments)
			context.commit('ADD_COMMENTS', comments)
		},


		addComment: function(context, comment) {
			//console.log('store action addComment', comment)
			context.commit('ADD_COMMENT', comment)
		},

		deleteComment: function(context, comment){
			//console.log('store action addComments', comments)
			context.commit('DELETE_COMMENT', comment)
		},
	}

});

export default store;
