<template>
  <div class="sidebar">
    <h1 class="sub_ttl">Hamano Admin</h1>
    <ul>
      <li><router-link to="/article/"><i class="fa fa-picture-o" aria-hidden="true"></i> 記事一覧</router-link></li>
      <li><router-link to="/article/input"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 新規記事投稿</router-link></li>
      <li><router-link to="/user"><i class="fa fa-user-o" aria-hidden="true"></i> ユーザー情報</router-link></li>
      <li><a href="" v-if="this.auth" v-on:click.prevent="logout()"><i class="fa fa-sign-out" aria-hidden="true"></i> ログアウト</a></li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Side',
  computed: {
      auth () {
          return this.$store.state.user.auth;
      },
  },
  methods: {
      logout:function(){
        axios.get('/api/logout')
        .then((res) => {
          //ログイン情報を削除
          this.$store.dispatch('setUserToken' ,null)
          this.$store.dispatch('setUserName' ,null)
          this.$store.dispatch('setUserId' ,null)
          this.$store.dispatch('setUserAuth' ,false)

          this.$router.push('/login')
        })
        .catch((err) => {
          console.log(err)
        })
      }
  } 
}
</script>