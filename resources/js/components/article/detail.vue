<template>
<div>
    <Header/>

    <main>
			<div class="content">
				<Side/>
      
        <div class="main">
            <SubHeader/>
            <div class="container">
				      <h2 class="ttl">{{articles.title}}</h2>
              <div class="detail">
                <figure v-if = "articles.image" class="image">
                  <img :src="'/storage/upload/' + articles.image.path">
                </figure>
                <figure class="image" v-else><img :src="'/storage/upload/noimg.jpg'"></figure>
                  <figcaption>
                    <p v-if="articles.content" class="">{{articles.content}}</p>
                  </figcaption>
                </figure>

                <section class="comment" v-if="comments">
                  <div v-if="comments.length > 0">
                    <h2 class="ttl">コメント {{comments.length}}件</h2>
                    <div v-for="comment in comments" class="box">
                      <div class="ttl_box">
                        <p class="sub_ttl">{{comment.title}}</p>
                        <span v-if = "comment.user_id">
                            <button @click="delete_comment(article_id,comment.id)" v-if = "comment.user_id == id" class="del_btn"><i class="fa fa-trash-o" aria-hidden="true"></i> 削除</button>
                        </span>
                      </div>
                      <dl>
                        <dt v-if="comment.user">{{ comment.user.name }}</dt>
                        <dd v-if="comment.created_at">{{ comment.created_at }}</dd>
                      </dl>
                      <p>
                        {{comment.content}}
                      </p>
                    </div>
                  </div>
                </section>

                <CommentForm :article_id="article_id" :id="id" @child_articles='comments = $event' />
                
              </div>
            </div>
        </div>
      </div>
    </main>
</div>
</template>

<script>
import Comment from './comment.vue'
import CommentForm from './comment_form.vue'
import Header from '../Header'
import Side from '../Side'
import SubHeader from '../SubHeader'
import Footer from '../Footer'

export default {
  name: 'ArticleDetail',
  components: {
    Comment,
    CommentForm,
    Header,
    Side,
    SubHeader,
    Footer
  },
  data() {
     return {
        articles:[],
        users:[],
        categories:[],
        image:[],
        comments:[],
      }
  },
  computed: {
      token () {
          return this.$store.state.user.token;
      },
      auth () {
          return this.$store.state.user.auth;
      },
      id () {
          return this.$store.state.user.id;
      },
      article_id () {
          return this.$route.params['id']
      },
  },
  created: function() {
    this.getArticle(this.article_id);
    this.getComment(this.article_id);
  },
  methods: {
      getComment(article_id) {
        axios
          .get("/api/comment/" + article_id)
          .then(response => {
              this.comments = response.data;
              console.log("コメント:" . this.comment);
          })
          .catch(err => {
              this.message = err;
              console.log(err);
          });
      },
      getArticle(article_id) {
        axios
          .get("/api/article/" + article_id)
          .then(response => {
              this.articles = response.data;
            //  console.log(this.articles);
          })
          .catch(err => {
              this.message = err;
              console.log(err);
          });
      },
      delete_comment (article_id,id) {
        if(confirm('削除してよろしいですか?'))
        
        axios
        .delete("/api/comment/del/"+ id)
        .then(response => {
            this.getArticle(article_id);
            this.getComment(article_id);
        })
        .catch(err => {
            this.message = err;
            console.log(err)
        });
    },
  } 
}

</script>