<template>
  <div>
    <div v-if="signedIn">
      <div class="form-group">
      <textarea v-model="body"
                name="body"
                id="body"
                class="form-control"
                placeholder="Have something to say?"
                rows="5"></textarea>
      </div>

      <button type="button" class="btn btn-default" @click="addReply">Post</button>
    </div>

    <p v-else class="text-center">Please <a href="/login">sign in</a> to participate in this discussion.
    </p>
  </div>
</template>

<script>
  export default {
    props: ['threadId'],

    data() {
      return {
        body: '',
        endpoint: `/threads/${this.threadId}/replies`,
      };
    },

    computed: {
      signedIn() {
        return window.App.signedIn;
      }
    },

    methods: {
      addReply() {
        axios.post(this.endpoint, {body: this.body})
          .then(({data}) => {
            this.body = '';

            this.$emit('created', data);

            flash('Your reply has been added.');
          })
      },
    }
  }
</script>