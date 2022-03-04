<template>
  <div>

    <!-- 入力ページへのリンク -->
    {{ user.name }} さん ようこそ
  </div>
</template>

<script>
export default {
  name: 'PostIndex',
  data() {
     return {
        user:[],
      }
  },
  mounted() {
    console.log(this.$store.getters.isAuthenticated)
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
      }
  },
  created() {
    this.get_user();
  },
  methods: {
      get_user: async function(){
        const res = await axios.get('/api/user', {
          // Tokenを付与してUser情報を取得する
          headers: {
            Authorization: `Bearer ${this.token}`,
          }
        })
        .then(function(response){
            this.user = response.data;
            console.log(this.user);
        }.bind(this))  //Promise処理を行う場合は.bind(this)が必要
        .catch(function(error){  //バックエンドからエラーが返却された場合に行う処理について
            console.log("error");
        }.bind(this))
        .finally(function(){
            }.bind(this))
        },
  } 
}

</script>