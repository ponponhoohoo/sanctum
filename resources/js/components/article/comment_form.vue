<template>
  <div class="CommentForm">
    <h2 class="ttl">コメントを投稿する</h2>
    <div class="box">
      <div class="form">
        <table>
          <tr>
            <th>タイトル</th>
            <td>
              <input type="text" placeholder="タイトルを入力してください" v-model="forms.title" class="form-control">
              <p v-if="this.errors.title == true" class="error-txt">{{this.messages.title}}</p>
            </td>
          </tr>
          <tr>
            <th>本文</th>
            <td>
              <textarea class="form-control row-5" v-model="forms.content">{{forms.content}}</textarea>
              <p v-if="this.errors.content == true" class="error-txt">{{this.messages.content}}</p>
            </td>
          </tr>
        </table>
        <p v-if="this.error_flg == true" class="error-txt">入力に誤りがありました。</p>
        <p v-if="this.comp_flg == true" class="comp_message">コメント投稿完了しました。</p>
        <button @click="send()" class="common_btn">送信する</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CommentForm",
  props: ["article_id","id"],
  setup(props) {
    console.log(props.article_id) // コンソールに配列の内容が表示される
  },
  data() {
      return {
        title:"",
        content:"",
        comp_flg:false,
        error_flg:false,
        comments:[],
        errors: {
          title: false,
          content: false,
        },
        messages: {
          title: false,
          content: false,
        },
        forms: {
          title: "",
          content: "",
        },
      }
    },
    methods: {
      send: function(article_id) {
        
        Object.keys(this.errors).forEach((key) => {
          this.errors[key] = false;
          this.messages[key] = null;
        })

        let formData = new FormData();
        formData.append('title',this.forms.title);
        formData.append('article_id',this.article_id);
        formData.append('user_id',this.id);
        formData.append('content',this.forms.content);

        // 送信処理axios.post('/api/article/add/', formData,config)
        axios.post('/api/comment/store', formData)
        .then((res) => {
          let response = res.data;
          console.log(response);
          if (response.status == 400) {
            // バリデーションエラー
            this.error_flg = true;
            Object.keys(response.errors).forEach((key) => {
              this.errors[key] = true;
              this.messages[key] = response.errors[key];
      //        console.log(response.errors[key]);
            })
          } else {
            this.error_flg = false;
            this.comp_flg = true;

            axios
            .get("/api/comment/" + this.article_id)
            .then(response => {
                this.comments = response.data;
                this.$emit("child_articles", this.comments);
            })
            .catch(err => {
                this.message = err;
                console.log(err);
            });

            this.clearError(title);
            this.clearError(content);
          }
        })
        .catch((error) => {
          console.log(error.response)
        })
      },
      // 各エラーのリセット
      clearError(item) {
        this.errors[item] = false;
        this.messages[item] = null;
      //  this.forms[item] = null;
      },
    }
};
</script>
