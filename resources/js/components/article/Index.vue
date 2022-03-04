<template>
  <div>
    <Header/>

    <main>
			<div class="content">
				<Side/>
      
        <div class="main">
            <SubHeader/>
        
            <div class="container">
              <div class="search">
                <h2 class="ttl">検索</h2>
                <ul>
                  <li><input v-model="search.content" type="text" class="form-control"></li>
                  <li>
                    <label v-for="category in this.categories">
                      <input type="radio" v-model="search.category" v-bind:value="category.id" class="radio"><span>{{ category.name }}</span>
                    </label>
                  </li>
                </ul>
                <button @click="SearchArticles()" class="btn">検索</button>
              </div>

              <ul class="add_new_item">
                <li><h2 class="ttl">すべての投稿</h2></li>
                <li><router-link to="/article/input" class="add_btn">新規投稿</router-link></li>
              </ul>

              <ul class="list">
                <li class="list_item" v-for="item in getItems">
                  <figure v-if = "item.image !== null" class="responsive-image">
                      <router-link :to="{ name: 'Detail', params: {id: item.id } }"><img :src="'/storage/upload/' + item.image.path"></router-link>
                  </figure>
                  <figure class="responsive-image" v-else><router-link :to="{ name: 'Detail', params: {id: item.id } }"><img :src="'/storage/upload/noimg.jpg'"></router-link></figure>

                  <p class="sub_ttl">
                    <router-link :to="{ name: 'Detail', params: {id: item.id } }">{{ item.title }}</router-link>
                    <span v-if="item.comment.length > 0">({{item.comment.length}})</span>
                    <span v-else>(0)</span>
                  </p>

                  <dl>
                    <dt>{{ item.category.name }}</dt>
                    <dd>{{ item.created_at }} </dd>
                  </dl>

                  <ul class="button_area" v-if = "item.user.id == id">
                    <li><router-link :to="{ name: 'Edit', params: {id: item.id } }"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 編集</router-link></li>
                    <li><a href="" @click="delete_article(item.id)"><i class="fa fa-trash-o" aria-hidden="true"></i> 削除</a></li>
                  </ul>
                </li>
              </ul>

              <vuejs-paginate
                    :page-count="getPaginateCount"
                    :prev-text="'<'"
                    :next-text="'>'"
                    :click-handler="paginateClickCallback"
                    :container-class="'pagination justify-content-center'"
                    :page-class="'page-item'"
                    :page-link-class="'page-link'"
                    :prev-class="'page-item'"
                    :prev-link-class="'page-link'"
                    :next-class="'page-item'"
                    :next-link-class="'page-link'"
                    :first-last-button="true"
                    :first-button-text="'<<'"
                    :last-button-text="'>>'"
                  >
              </vuejs-paginate>
              <Footer/>
            </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import VueJsPaginate from "vuejs-paginate";
import Header from '../Header'
import Side from '../Side'
import SubHeader from '../SubHeader'
import Footer from '../Footer'

export default {
  name: 'ArticleIndex',
  components: {
    "vuejs-paginate": VueJsPaginate,
    Header,
    Side,
    SubHeader,
    Footer
  },
  data() {
     return {
       search: {
          content: "",
          category: "",
        },
        articles:[],
        categories: {},
        currentPage: 1,
        perPage: 10,
      }
  },
  mounted() {

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
      name () {
          return this.$store.state.user.name;
      },
      getItems: function () {
        let start = (this.currentPage - 1) * this.perPage;
        let end = this.currentPage * this.perPage;
        return this.articles.slice(start, end);
      },
      getPaginateCount: function () {
        return Math.ceil(this.articles.length / this.perPage);
      },
  },
  created() {
    this.getCategory();
    this.get_articles();
  },
  methods: {
      paginateClickCallback: function (pageNum) {
        this.currentPage = Number(pageNum);
      },
      SearchArticles() {
        let formData = new FormData();
        
        if (this.search.content != "") {
          formData.append('content',this.search.content);
        }
        if (this.search.category != "") {
          formData.append('category',this.search.category);
        }
        
        axios
        .post("/api/article/search",formData)
        .then(response => {
          this.articles = response.data.post;
          console.log(this.articles);
        })
        .catch(err => {
            this.message = err;
        });
        
        
      },
      getCategory() {
          axios
          .get("/api/category/")
          .then(response => {
              this.categories = response.data;
          })
          .catch(err => {
              this.message = err;
          });
      },
      get_articles: async function(){
        const res = await axios.get('/api/article')
        .then(function(response){
            this.articles = response.data;
            console.log(this.articles);
        }.bind(this))  //Promise処理を行う場合は.bind(this)が必要
        .catch(function(error){  //バックエンドからエラーが返却された場合に行う処理について
            console.log("error");
        }.bind(this))
        .finally(function(){
            }.bind(this))
      },
      delete_article (id) {
          if(confirm('削除してよろしいですか?(記事に対するコメントも削除されます。)'))
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
}

</script>