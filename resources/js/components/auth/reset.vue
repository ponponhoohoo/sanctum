<template>
    <div>
        <main>
            <div class="login">
                <div class="container">
                    <h1 class="sub_ttl">Hamano Admin<br>パスワードリセット</h1>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="username">メールアドレス</label>
                            <input type="email" placeholder="メールアドレスを入力してください" v-model="forms.email" class="form-control">
                            <!-- <p v-if="this.errors.email == true">{{this.messages.email}}</p> -->
                        </div>
                       <ul v-show="this.error_flg" class="error-list">
                          <template v-for="item in this.messages.email">
                          <li v-if="item">
                              {{item}}
                          </li>
                          </template>
                        </ul>
                      
                      <button @click="send()" class="common_btn">送信する</button>
                      <div v-if="this.comp_flg == true">
                          <p class="comp_message">パスワードリセットのメールを送信しました。</p>
                      </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
  name: 'PasswordResetForm',
  data() {
     return {
        comp_flg:false,
        error_flg:false,
        errors: {
          email: false,
        },
        messages: {
          email: false,
        },
        forms: {
          email: "",
        },
    }
  },
  computed: {
    token() {
        return this.$route.query.token;
    }
  },
  methods: {
    send: function() {
        Object.keys(this.errors).forEach((key) => {
          this.errors[key] = false;
          this.messages[key] = null;
        })

        let formData = new FormData();
        formData.append('email',this.forms.email);

        axios.post('/api/password/request', formData)
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
            })
          } else {
            this.error_flg = false;
            this.comp_flg = true;

            clearError(email);
            
            console.log(this.messages)
            // 成功したらUserItemコンポーネントを表示
          //  this.$router.push('/user/item');
          }
        })
        .catch((error) => {
          console.log(error.response)
        })
    }

  },// 各エラーのリセット
    clearError(item) {
      this.errors[item] = false;
      this.messages[item] = null;
    //  this.forms[item] = null;
    },
}

</script>