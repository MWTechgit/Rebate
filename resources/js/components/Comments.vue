<template>
    <div>

    	<div class="w-full flex items-center mb-6">

    		<heading class="mb-3 text-90 font-normal text-2xl">Comments</heading>

    		<div class="flex-no-shrink ml-auto">
    			<button @click="create" class="btn btn-default btn-primary">Add Comment</button>
    		</div>
    	</div>

		<loading-card class="mb-6 py-3 px-6" :loading="busy">

            <div v-if="apiError" class="py-4 border-b border-40">
                <p>The following error was received: <span style="color:red">{{ apiError }}</span></p>
                <button class="mt-3 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest" type="button" @click="fetch">
                    Try again?
                </button>
            </div>

            <div v-if="creating" class="w-full py-4">
                <textarea v-model="modContent" rows=5 class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea>
                <button @click="store" class="btn btn-default btn-primary">Save</button>
            </div>
			
			<loader v-if="busy"></loader>
			<p v-else-if="!comments.length">
				There are no comments
    		</p>
    		<ul v-else class="comments">
    			<li v-for="comment in comments" :key="comment.id" class="py-4 border-b border-40">
    				<div class="comment flex">

						<div class="w-1/6 py-4 font-normal text-80">
							<span v-if="comment.admin">{{ comment.admin.full_name }}</span>
							<span v-else>Unknown</span>
						</div>

	    				<p class="w-5/6 py-4 text-90" v-if="!editing || editing.id != comment.id">{{ comment.content }}</p>

                        <div v-else class="w-3/4 py-4">
                            <textarea v-model="modContent" rows=3 class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea>
                            <button @click="update" class="btn btn-default btn-primary">Save</button>
                        </div>

	    			</div>

					<div class="comment-meta">
						<a class="cursor-pointer text-70 hover:text-primary mr-3" @click="edit(comment)" href="javascript:void(0);" title="Edit">
							<icon type="edit"></icon>
						</a>
						<a class="cursor-pointer text-70 hover:text-primary mr-3" @click="ddelete(comment)" href="javascript:void(0);" title="Delete">
							<icon type="delete"></icon>
						</a>
    					
    					<small class="text-70">{{ comment.created_at }} 
                            <em class="text-muted" v-if="comment.updated_at && comment.updated_at !== comment.created_at"> - Edited on {{ comment.updated_at }}</em></small>
					</div>


    			</li>
    		</ul>

    	</loading-card>

    	<delete-resource-modal v-if="deleting" @close="deleting=false" @confirm="destroy"></delete-resource-modal>
    </div>
</template>

<style>

	ul.comments {
		list-style-type: none;
        padding-left: 0;
	}

    .comment {
        align-items: center;
    }

	.comment .author {
		/*width: 120; padding-right: 20px;*/
	}

	.comment p {
		padding: 0.75rem;
		color: var(--90);
	}

    .comment-meta small {
        float: right;
    }

</style>

<script>

export default {
    props: ['endpoint'],

    data() {
    	return {
    		comments: [],
    		busy: false,
            creating: false,
            editing: false,
    		deleting: false,
    		showForm: false,
            modContent: '',
            apiError: false,
    	}
    },

    mounted() {
        this.fetch();
    	
    },

    methods: {
        fetch() {
            this.apiError = false;
            this.busy = true;
            axios.get(this.endpoint)
                .then(response => this.comments = response.data.data )
                .catch(err => this.fail(err) )
                .finally(() => this.busy = false );
        },

    	create() {
    		this.editing = false;
            this.creating = true;
            this.modContent = '';
    	},

        store() {
            this.creating = false;
            this.busy = true;
            axios.post( this.endpoint, {content: this.modContent})
                .then(response => this.comments.push(response.data))
                .catch(err => this.fail(err))
                .finally(() => this.busy = false);
        },

    	edit(comment) {
            this.creating = false;
    		this.editing = comment;
            this.modContent = comment.content;
    	},

        update() {
            var id = this.editing.id;
            this.editing = false;
            this.busy = true;
            axios.put( `${this.endpoint}/${id}`, {content: this.modContent})
                .then(response => this.comments = this.comments.map( c => c.id === response.data.id ? response.data : c ) )
                .catch(err => this.fail(err))
                .finally(() => this.busy = false);
        },

    	ddelete(comment) {
    		this.deleting = comment;
    	},

    	destroy() {
			var id = this.deleting.id;
			this.deleting = false;
    		this.busy = true;
    		axios.delete(`${this.endpoint}/${id}` )
    			.then(response => this.comments = this.comments.filter( c => c.id != id ) )
    			.catch(err => this.fail(err))
    			.finally(() => this.busy = false );
    	},

        fail(err) {
            console.error(err);
            if (typeof err === 'string') {
                var msg = err;
            } else if ((err||{}).response) {
                var msg = _.get(err.response, 'data.message') || err.response.statusText;
            } else {
                var msg = err.message || err.name || 'Something went wrong. Contact a system admin.'
            }
            this.apiError = msg;
            this.$toasted.show(msg, {type: 'error'})
        }
    }
}
</script>
