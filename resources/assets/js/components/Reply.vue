<script>
  export default {
    props: ['attributes'],

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
