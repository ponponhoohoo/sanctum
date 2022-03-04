<template>
    <div>
        <main>
            <div class="login">
                <div class="container">
                    <h1 class="sub_ttl">Hamano Admin</h1>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="username">メールアドレス</label>
                            <input type="email" placeholder="メールアドレスを入力してください" v-model="forms.email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="username">パスワード</label>
                            <input type="password" placeholder="パスワードを入力してください" v-model="forms.password" class="form-control">
                        </div>
                        <p class="forget"><router-link to="/password-reset">パスワードを忘れた方はこちら</router-link></p>
                        <button @click="login()" class="common_btn">送信する</button>
                        <p class="sign-up"><router-link to="/signup">新規登録はこちら</router-link></p>
                        <ul v-show="this.error_flg">
                                <li>{{this.error_m}}</li>
                                <!-- <template v-for="item in this.error_m">
                                <li v-if="item">
                                    {{item}}
                                </li>
                                </template> -->
                            </ul>
                        
                            <div v-if="this.comp_flg == true">
                                {{ $store.state.user.id }} 
                                ログイン完了しました。
                                <router-link to="/login">ログイン</router-link>
                            </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>

export default {
  name: 'Index',
    data() {
        return {
        error_m:[],
        comp_flg:false,
        error_flg:false,
        errors: {
          email: false,
          password: false,
        },
        messages: {
        name: "",
          email: "",
          password: "",
        },
        forms: {
            email: "",
            password: "",
        },
        }
    },
    computed: {
        token () {
            return this.$store.state.user.token;
        }
    },
    methods: {
        login() {
            Object.keys(this.errors).forEach((key) => {
                this.errors[key] = false;
                this.messages[key] = null;
            })

            let formData = new FormData();
            formData.append('email',this.forms.email);
            formData.append('password',this.forms.password);

            axios.get('/sanctum/csrf-cookie')
            .then((response) => {
                axios.post('/api/login', formData)
                .then((response) => {
                    console.log(response)
                    this.comp_flg = true;

                    //ログイン情報を保持
                    this.$store.dispatch('setUserToken' ,response.data.token)
                    this.$store.dispatch('setUserAuth' ,true)
                    this.$store.dispatch('setUserId' ,response.data.user.id)
                    this.$store.dispatch('setUserName' ,response.data.user.name)

                    //Indexに遷移
                    this.$router.push('/article/')
                })
                .catch((error) => {
                    //エラーメッセージが入っているobject
                    console.log(error.response);
                  //  this.error_m = error.response.data;
                    this.error_m = 'メールアドレスかパスワードが違います。';
                    // Object.keys(error.response.data).forEach((key) => {
                    // this.errors[key] = true;
                    // this.messages[key] = error.response.data[key];
                    // console.log(error.response.data[key]);

                    // // Object.keys(key).forEach(function (mes) {
                    // //   console.log(mes);
                    // // });
                    // })
                    this.error_flg = true;
                    console.log(this.error_m);
                })
            })
            .catch((error) => {
            //
            })
        },
        send_data: async function(){
        Object.keys(this.errors).forEach((key) => {
            this.errors[key] = false;
            this.messages[key] = null;
        })

        let formData = new FormData();
        formData.append('email',this.forms.email);
        formData.append('password',this.forms.password);

        const res = await axios.post('/api/login', formData)
        .then(function(response){
            console.log(response)
            this.comp_flg = true;

            //ログイン情報を保持
            this.$store.dispatch('setUserToken' ,response.data.token)
            this.$store.dispatch('setUserAuth' ,true)

            //Indexに遷移
            this.$router.push('/post/')
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