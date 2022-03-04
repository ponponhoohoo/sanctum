<template>
    <div>
        <main>
            <div class="login">
                <div class="container">
                    <h1 class="sub_ttl">Hamano Admin 新規登録</h1>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="username">ユーザー名</label>
                            <input type="email" placeholder="ユーザー名を入力してください" v-model="forms.name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="username">メールアドレス</label>
                            <input type="email" placeholder="メールアドレスを入力してください" v-model="forms.email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="username">パスワード</label>
                            <input type="password" placeholder="パスワードを入力してください" v-model="forms.password" class="form-control">
                        </div>

                        <ul v-show="this.error_flg" class="error-list">
                            <template v-for="(item, index) in this.error_m">
                            <li v-if="item">
                                {{item }}
                            </li>
                            </template>
                        </ul>

                        <button @click="send_data()" class="common_btn">送信する</button>
                        
                        <div v-if="this.comp_flg == true">
                            <p class="comp_message">登録完了しました。</p>
                            <p class="sign-up"><router-link to="/login">ログイン</router-link></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>

export default {
  name: 'Signup',
    data() {
        return {
        comp_flg:false,
        error_flg:false,
        errors: {
        name: false,
          email: false,
          password: false,
        },
        error_m:[],
        messages: {
        name: "",
          email: "",
          password: "",
        },
        forms: {
            name: "",
            email: "",
            password: "",
        },
        }
    },
    methods: {
        send_data: async function(){
        Object.keys(this.errors).forEach((key) => {
            this.errors[key] = false;
            this.messages[key] = null;
        })

        let formData = new FormData();
        formData.append('name',this.forms.name);
        formData.append('email',this.forms.email);
        formData.append('password',this.forms.password);

        const res = await axios.post('/api/register', formData)
        .then(function(response){
            console.log(response)
            this.comp_flg = true;
        }.bind(this))  //Promise処理を行う場合は.bind(this)が必要
          .catch(function(error){  //バックエンドからエラーが返却された場合に行う処理について
            //エラーメッセージが入っているobject
            console.log(error.response);
            this.error_m = error.response.data;
            Object.keys(error.response.data).forEach((key) => {
              this.errors[key] = true;
              this.messages[key] = error.response.data[key];
              console.log(error.response.data[key]);
              // Object.keys(key).forEach(function (mes) {
              //   console.log(mes);
              // });
            })
            this.error_flg = true;
            console.log("error");
        }.bind(this))
          .finally(function(){
            }.bind(this))
        },
    }
}
</script>