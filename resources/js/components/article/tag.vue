<template>
  <div>
    <Header/>

    <main>
			<div class="content">
        <Notice v-if="show === true"/>
				<Side/>
        <div class="main">
            <SubHeader/>
        
            <div class="container">
              <div class="">
                <h2 class="ttl">タグ管理</h2>
                <div class="tag__list">
                  <Spinner v-if="loading == true"/>
                  <ul v-if = "tags != null">
                    <li v-for="tag in this.tags"><button @click="del_Tag(tag.id)" class="del" v-bind:value="tag.id"><i class="fa fa-times" aria-hidden="true"></i></button> <span>{{ tag.name }}</span></li>
                  </ul>
                </div>

                <div class="form">
                  <table>
                    <tr>
                      <th>タグ名</th>
                      <td>
                        <input type="text" placeholder="タグ名を入力してください" v-model="forms.name" class="form-control">
                        <p v-if="this.errors.name == true" class="error-txt">{{this.messages.name}}</p>
                      </td>
                    </tr>
                  </table>

                  <p v-if="this.error_flg == true" class="error-txt">入力に誤りがありました。</p>
                  <p v-if="this.comp_flg == true" class="comp_message">登録完了しました。</p>

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
import Notice from './part/notice'
import Header from '../Header'
import Side from '../Side'
import SubHeader from '../SubHeader'
import Footer from '../Footer'
import Spinner from 'vue-simple-spinner'

export default {
  name: 'Tag',
  components: {
    Header,
    Spinner,
    Notice,
    Side,
    SubHeader,
    Footer
  },
  data() {
      return {
        loading: true,
        show: false,
        tags: {},
        name:"",
        comp_flg:false,
        error_flg:false,
        errors: {
          name: false,
        },
        messages: {
          name: false,
        },
        forms: {
          name: "",
        },
      }
    },
    computed: {
      id () {
          return this.$store.state.user.id;
      },
    },
    created: function() {
        this.getTags();
    },
    methods: {
        del_Tag(tag_id) {
          if(confirm('削除してよろしいですか?'))
          axios
          .delete("/api/tag/tagname/"+ tag_id)
          .then(response => {
              this.show = true;
              
              setTimeout(() => {
                this.show = false;
              }
                ,3000
              )
              
              this.getTags();
              
          })
          .catch(err => {
              this.message = err;
              console.log(err)
          });
        },
        getTags() {
            axios
            .get("/api/tag")
            .then(response => {
                this.loading = false;
                this.tags = response.data;
            })
            .catch(err => {
                this.message = err;
            });
        },
        send: function() {
        // 全てのエラーをリセット
        // Obj型式をループする際はこれを使用する
        Object.keys(this.errors).forEach((key) => {
          this.errors[key] = false;
          this.messages[key] = null;
        })

        let formData = new FormData();
        formData.append('name',this.forms.name);

        axios.post('/api/tag/store', formData)
        .then((res) => {
          let response = res.data;
          console.log(response)
          //console.log(response);
          if (response.status == 400) {
            // バリデーションエラー
            this.error_flg = true;
            Object.keys(response.errors).forEach((key) => {
              this.errors[key] = true;
              this.messages[key] = response.errors[key];
              console.log(response.errors[key]);
              // Object.keys(key).forEach(function (mes) {
              //   console.log(mes);
              // });
            })
          } else {
            this.error_flg = false;
            this.comp_flg = true;
            this.getTags();
            clearError(name);
            
            console.log(this.messages)

          }
        })
        .catch((error) => {
          console.log(error.response)
        })
      },
      clearError(item) {
        this.errors[item] = false;
        this.messages[item] = null;
      //  this.forms[item] = null;
      },
    }
}
</script>