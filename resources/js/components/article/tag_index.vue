<template>
  <div>
    <Header/>

    <main>
			<div class="content">
				<Side/>
      
        <div class="main">
            <SubHeader/>
        
            <div class="container">
              <ul class="add_new_item">
                <li><h2 class="ttl">{{tag_name}}</h2></li>
                <li><router-link to="/article/input" class="add_btn">新規投稿</router-link></li>
              </ul>

              <Spinner v-if="loading == true"/>

              <ul class="list">
                <li class="list_item" v-for="item in getItems">
                  <figure v-if = "item.article.image !== null" class="responsive-image">
                      <router-link :to="{ name: 'Detail', params: {id: item.article.id } }">
                        <img :src="'/storage/upload/' + item.article.image.path" v-if="env === 'local'">
                        <img v-else :src="item.article.image.path">
                        </router-link>
                  </figure>
                  <figure class="responsive-image" v-else><router-link :to="{ name: 'Detail', params: {id: item.article.id } }"><img :src="'/storage/upload/noimg.jpg'"></router-link></figure>

                  <p class="sub_ttl"></p>
                    <router-link :to="{ name: 'Detail', params: {id: item.article.id } }">{{ item.article.title }}</router-link>
                    <span v-if="item.article.comment.length > 0">({{item.article.comment.length}})</span>
                    <span v-else>(0)</span>
                  </p>

                  <dl>
                    <dt>{{ item.article.category.name }}</dt>
                    <dd>{{ item.article.created_at }} </dd>
                  </dl>

                  <ul class="button_area" v-if = "item.article.user.id == id">
                    <li><router-link :to="{ name: 'Edit', params: {id: item.article.id } }"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 編集</router-link></li>
                    <li><a href="" @click="delete_article(item.article.id)"><i class="fa fa-trash-o" aria-hidden="true"></i> 削除</a></li>
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
import Spinner from 'vue-simple-spinner'

export default {
  name: 'TagIndex',
  components: {
    "vuejs-paginate": VueJsPaginate,
    Header,
    Side,
    SubHeader,
    Spinner,
    Footer
  },
  data() {
     return {
       env: this.$env,
        tag_name: "",
        loading: true,
        articles:[],
        categories: {},
        currentPage: 1,
        perPage: 9,
      }
  },
  mounted() {
    this.GetApiData(this.tag_id);
  },
  computed: {
      id () {
          return this.$store.state.user.id;
      },
      tag_id () {
          return this.$route.params['id']
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
    // this.getCategory();
    
  },
  methods: {
      paginateClickCallback: function (pageNum) {
        this.currentPage = Number(pageNum);
      },
      async GetApiData(tag_id) {
          await Promise.all([
              this.get_tag_name(tag_id),
              this.get_articles(tag_id),
          ]);
      },
      get_tag_name: async function(tag_id){
        const res = await axios.get('/api/tag/tagname/'  + tag_id)
        .then(function(response){
            this.tag_name = response.data.name;
          //  console.log(response.data);
        }.bind(this))  //Promise処理を行う場合は.bind(this)が必要
        .catch(function(error){  //バックエンドからエラーが返却された場合に行う処理について
            console.log("error");
        }.bind(this))
      },
      get_articles: async function(tag_id){
        const res = await axios.get('/api/article/tag/'  + tag_id)
        .then(function(response){
            this.loading = false;
            this.articles = response.data;
          //  console.log(this.articles);
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