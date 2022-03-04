<template>
  <div>
    <nav class="sp_nav">
      <div class="navtext-container">
        <div class="navtext">Hamano Admin</div>
      </div>
      <input type="checkbox" class="menu-btn" id="menu-btn">
      <label for="menu-btn" class="menu-icon"><span class="navicon"></span></label>
      <ul class="menu">
        <li><router-link to="/article/">記事一覧</router-link></li>
        <li><router-link to="/article/input">新規記事投稿</router-link></li>
        <li><a href="" v-if="this.$store.state.user.auth" v-on:click.prevent="logout()">ログアウト</a></li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'Header',
  data () {
    return {

    }
  },
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