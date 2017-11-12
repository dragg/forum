<template>
  <div :id="`reply-${id}`" class="panel panel-default">
    <div class="panel-heading">
      <div class="level">
        <h5 class="flex">
          <a :href="`/profiles/${data.owner.name}`">
            {{ data.owner.name }}
          </a> said {{ data.created_at }}
        </h5>

        <div v-if="signedIn">
          <favorite :reply="data"></favorite>
        </div>
      </div>
    </div>

    <div class="reply-body panel-body">
      <div v-if="editing">
        <div class="form-group">
          <textarea class="form-control" v-model="tmpBody"></textarea>
        </div>

        <button class="btn btn-xs btn-primary" @click="update">Update</button>
        <button class="btn btn-xs btn-link" @click="cancelEdit">Cancel</button>
      </div>

      <div v-else v-text="body"></div>
    </div>

    <div class="panel-footer level" v-if="canUpdate">
      <button class="btn btn-xs mr-1" @click="edit">Edit</button>
      <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
    </div>
  </div>
</template>

<script>
  import Favorite from './Favorite.vue';

  export default {
    props: ['data'],

    components: {Favorite},

    data() {
      return {
        editing: false,
        id: this.data.id,
        body: this.data.body,
      };
    },

    computed: {
      signedIn() {
        return window.App.signedIn;
      },

      canUpdate() {
        return this.authorize(user => user.id == this.data.user_id);
      },
    },

    methods: {
      update() {
        axios.patch('/replies/' + this.data.id, {
          body: this.body,
        })
          .then(() => {
            this.body = this.tmpBody;

            this.editing = false;

            flash('Updated!');
          });
      },

      destroy() {
        axios.delete('/replies/' + this.data.id, {})
          .then(() => {
            this.$emit('deleted', this.data.id);
          });
      },

      edit() {
        this.tmpBody = this.body;
        this.editing = true;
      },

      cancelEdit() {
        this.editing = false;
      },
    }
  }
</script>
