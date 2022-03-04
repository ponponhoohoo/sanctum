<template>
  <div class="comment" v-if="comments">
    <div v-if="comments.length > 0">
      <div v-for="comment in comments">
        <h3>{{comment.title}} <span v-if="comment.user">>投稿者:{{ comment.user.name }}</span></h3>
        <p>投稿日:{{ comment.created_at }} </p>
        <div v-if = "comment.user_id">
          <p v-if = "comment.user_id == id">
            <button @click="delete_comment(comment.id)">削除</button>
          </p>
        </div>
        <p>{{comment.content}}</p>
      </div>
    </div>
    <p v-else>コメントはありません。</p>
  </div>
</template>

<script>
export default {
  name: "Comment",
  props: ["comments","id"],
  setup(props) {
    console.log(props.comments) // コンソールに配列の内容が表示される
  },
  data() {
     return {
        comments:[],
      }
  },
  methods: {
    getArticle(id) {
        axios
          .get("/api/comment/" + article_id)
          .then(response => {
              this.comments = response.data;
              console.log(this.comment);
          })
          .catch(err => {
              this.message = err;
              console.log(err);
          });
      },
    delete_comment (id) {
        if(confirm('削除してよろしいですか?'))
        axios
        .delete("/api/article/del/"+ id)
        .then(response => {
            this.get_articles();
        })
        .catch(err => {
            this.message = err;
            console.log(err)
        });
    },
  }
};
</script>

<style>
</style>