<script>
  import Favorite from './Favorite.vue';

  export default {
    props: ['attributes'],

    components: {Favorite},

    data() {
      return {
        editing: false,
        body: this.attributes.body,
      };
    },

    methods: {
      update() {
        axios.patch('/replies/' + this.attributes.id, {
          body: this.body,
        })
          .then(() => {
            this.body = this.tmpBody;

            this.editing = false;

            flash('Updated!');
          });
      },

      destroy() {
        axios.delete('/replies/' + this.attributes.id, {})
          .then(() => {

            $(this.$el).fadeOut(300, () => {
              flash('Your reply was been deleted!');
            });
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
