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
                      <th>カテゴリ</th>
                      <td>
                        <Spinner v-if="loading.category == true"/>
                        <label v-for="item in this.categories">
                          <input type="radio" v-model="forms.category" v-bind:value="item.id" class="radio"><span>{{ item.name }}</span>
                        </label>
                        <p v-if="this.errors.category == true" class="error-txt">{{this.messages.category}}</p>
                      </td>
                    </tr>
                     <tr>
                      <th>タグ (複数選択可)</th>
                      <td>
                        <Spinner v-if="loading.tag == true"/>
                        <label v-for="(tag, index)  in this.tag">
                          <input type="checkbox" v-model="forms.tags" v-bind:value="tag.id" class="radio"><span>{{ tag.name }}</span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <th>画像</th>
                      <td>
                        <input @change="fileSelect" type="file" v-if="view" class="file">

                        <figure v-if = "forms.image">
                          <img :src="'/storage/upload/' + forms.image.path" v-if="env === 'local'">
                          <img v-else :src="forms.image.path">
                          <input type="hidden" v-model="forms.path_original">
                        </figure>

                        <figure v-show="uploadedImage">
                          <img
                            v-show="uploadedImage"
                            class="preview-item-file"
                            :src="uploadedImage"
                            alt=""
                            />
                        </figure>

                        <div v-show="uploadedImage" class="preview-item-btn">
                          <p class="preview-item-name">{{ img_name }}</p>
                          <button @click="remove()" class="del_btn"><i class="fa fa-trash-o" aria-hidden="true"></i>  画像を削除する</button>
                        </div>

                        <ul v-if="this.errors.path == true" class="error-list">
                          <li v-for="item in this.messages.path">{{item}}</li>
                        </ul>
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
                  <p v-if="this.comp_flg == true" class="comp_message">記事の編集が完了しました。</p>

                  <button @click="send()" class="common_btn">送信する</button>
                </div>
                <Footer/>
              </div>
            </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Header from '../Header'
import Side from '../Side'
import SubHeader from '../SubHeader'
import Footer from '../Footer'
import Spinner from 'vue-simple-spinner'

export default {
  name: 'ArticleEdit',
  components: {
    Header,
    Spinner,
    Side,
    SubHeader,
    Footer
  },
  data() {
      return {
        env: this.$env, 
        view: true,
        tag: [], 
        categories: {},
        path:"",
        comp_flg:false,
        error_flg:false,
        uploadedImage: '',
        img_name: '',
        loading: {
          category: true,
          tag: true,
        },
        errors: {
          title: false,
          content: false,
          category: false,
          path: false,
        },
        messages: {
          title: false,
          content: false,
          category: false,
          path: false,
        },
        forms: {
          title: "",
          content: "",
          category: "",
          tags: [], 
          path:"",
          path_original:"",
        },
      }
    },
    computed: {
      id () {
          return this.$store.state.user.id;
      },
      article_id () {
          return this.$route.params['id']
      },
  },
    mounted: function() {
      this.getTags(this.article_id);
      this.getCategory();
      this.getArticle(this.article_id);
    },
    methods: {
        getArticle(article_id) {
        axios
          .get("/api/article/" + article_id)
          .then(response => {
              this.forms = response.data;
              this.forms.category = this.forms.category.id;
              this.forms.path_original = this.forms.image.path;
              Object.values(response.data.tags).forEach((value,index) => {
                //リアクティブ変数を変更
                this.$set(this.forms.tags, index, value['tag_id'])
              })
           //   console.log(this.forms);
          })
          .catch(err => {
              this.message = err;
              console.log(err);
          });
      },
        dataReset() {
          this.forms.title = null;
          this.forms.content = null;
          this.forms.path = null;
        },
        fileSelect: function(e) {
            //選択したファイルの情報を取得しプロパティにいれる
            this.path = e.target.files[0];
            this.createImage(this.path);
            this.img_name = this.path.name;
        },
        // アップロードした画像を表示
        createImage(file) {
          const reader = new FileReader();
          reader.onload = e => {
            this.uploadedImage = e.target.result;
          };
          reader.readAsDataURL(file);
        },
        remove() {
          this.forms.path = null;
          this.path = null;
          this.uploadedImage = false;
          this.view = false
          this.$nextTick(function () {
            this.view = true
          })
        },
        getTags: async function(){
          const res = await axios.get('/api/tag')
          .then(function(response){
              this.loading.tag = false;
              this.tag = response.data;
          }.bind(this))
          .catch(function(error){
              console.log("error");
          })
        },
        // getUserTags: async function(article_id){
        //   const res = await axios.get('/api/tag/' + article_id)
        //   .then(function(response){
        //  //     this.forms.tag = response.data;
        //       Object.values(response.data.tag).forEach((value,index) => {
        //         this.forms.tags.splice(index, 2, value['tag_id'])
        //         console.log(value['tag_id']);
        //       })
        //       console.log(this.forms.tag);
        //   }.bind(this))
        //   .catch(function(error){
        //       console.log("error");
        //   })
        // },
        getCategory: async function(article_id){
          const res = await axios.get('/api/category')
          .then(function(response){
              this.categories = response.data;
              this.loading.category = false;
          }.bind(this))
          .catch(function(error){
              console.log("error");
          })
        },
        send: function() {
        // 全てのエラーをリセット
        // Obj型式をループする際はこれを使用する
        Object.keys(this.errors).forEach((key) => {
          this.errors[key] = false;
          this.messages[key] = null;
        })

        let formData = new FormData();
        //appendでデータを追加(第一引数は任意のキー)
        //他に送信したいデータがある場合にはその分appendする
        formData.append('user_id',this.id);
        formData.append('title',this.forms.title);
        formData.append('content',this.forms.content);
        formData.append('category',this.forms.category);
        formData.append('path_original',this.forms.path_original);

        if (this.forms.tags != "") {
          this.forms.tags.map(function( value ) {
            if (value) {
              formData.append('tag' + '[]', value);
            }
          });
        }

      //  console.log(...formData.entries());
        //Fileが空の場合変数がundefinedになる
        if (typeof this.path != 'undefined' && this.path != "") {
          formData.append('path',this.path);
        }

        let config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };
        //リクエストの中身が空になるので、一旦 POST で送って、後から PUT で上書く。
        config.headers['X-HTTP-Method-Override'] = 'PUT';

        // 送信処理axios.post('/api/article/add/', formData,config)
        axios.post('/api/article/update/' +  this.article_id ,formData,config)
        .then((res) => {
          let response = res.data;
      //    console.log(response)
          //console.log(response);
          if (response.status == 400) {
            // バリデーションエラー
            this.error_flg = true;
            Object.keys(response.errors).forEach((key) => {
              this.errors[key] = true;
              this.messages[key] = response.errors[key];
   //           console.log(response.errors[key]);
              // Object.keys(key).forEach(function (mes) {
              //   console.log(mes);
              // });
            })
          } else {
            this.error_flg = false;
            this.comp_flg = true;

            clearError(title);
            clearError(content);
            clearError(category);
            clearError(path);
            
            console.log(this.messages)
            // 成功したらUserItemコンポーネントを表示
          //  this.$router.push('/user/item');
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
}
</script>