<template>
    <div>
        <main>
            <div class="login">
                <div class="container">
                    <h1 class="sub_ttl">Hamano Admin<br>パスワードリセット</h1>
                    <div v-if="this.comp_flg == false">
                      <div class="form-body">
                          <div class="form-group">
                              <label for="username">メールアドレス</label>
                              <input type="email" placeholder="メールアドレスを入力してください" v-model="forms.email" class="form-control">
                              <!-- <p v-if="this.errors.email == true">{{this.messages.email}}</p> -->
                          </div>
                          <div class="form-group">
                              <label for="username">パスワード</label>
                              <input type="email" placeholder="パスワードを入力してください" v-model="forms.password" class="form-control">
                              <!-- <p v-if="this.errors.email == true">{{this.messages.email}}</p> -->
                          </div>
                          <div class="form-group">
                              <label for="username">確認用パスワード</label>
                              <input type="email" placeholder="パスワードを入力してください" v-model="forms.confirmed" class="form-control">
                              <!-- <p v-if="this.errors.email == true">{{this.messages.email}}</p> -->
                          </div>
                          <ul v-show="this.error_flg" class="error-list">
                              <template v-for="item in this.messages.email">
                              <li v-if="item">
                                  {{item}}
                              </li>
                              </template>
                              <template v-for="item in this.messages.password">
                              <li v-if="item">
                                  {{item}}
                              </li>
                              </template>
                              <template v-for="item in this.messages.confirmed">
                              <li v-if="item">
                                  {{item}}
                              </li>
                              </template>
                          </ul>
                          <!-- <div v-show="this.error_flg">
                              <p>パスワードリセットに失敗しました。期限切れの可能性があります。再度設定してください。</p>
                              <router-link to="/password-reset">パスワードリセット再設定</router-link>
                          </div> -->
                          <button @click="send()" class="common_btn">送信する</button>
                      </div>
                    </div>
                    <div v-else>
                        <p class="comp_message">パスワードのリセットが完了しました。再度ログインしてください。</p>
                        <p class="sign-up"><router-link to="/login">ログイン</router-link></p>
                      </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
  name: 'PasswordReset',
  data() {
     return {
        comp_flg:false,
        error_flg:false,
        errors: {
          email: false,
          password: false,
          confirmed: false,
        },
        messages: {
          email: false,
          password: false,
          confirmed: false,
        },
        forms: {
          email: "",
          password: "",
          confirmed: "",
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
        formData.append('password',this.forms.password);
        formData.append('confirmed',this.forms.confirmed);
        formData.append('token',this.token);

        axios.post('/api/password/reset', formData)
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
            clearError(password);
            clearError(confirm);
            
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