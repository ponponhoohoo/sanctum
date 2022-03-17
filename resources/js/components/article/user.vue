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
                <h2 class="ttl">ユーザー情報</h2>
                <div class="form">
                   <table>
                    <tr>
                      <th>ユーザー名</th>
                      <td>
                          {{user.name}}
                      </td>
                    </tr>
                    <tr>
                      <th>メールアドレス</th>
                      <td>
                         {{user.email}}
                      </td>
                    </tr>
                  </table>
                  <button @click="del_user(id)" class="common_btn">退会する</button>
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

export default {
  name: 'User',
  components: {
    Header,
    Side,
    SubHeader,
    Footer
  },
  data() {
      return {
        user: {
          name: "",
          email: "",
        },
      }
    },
    computed: {
      token () {
          return this.$store.state.user.token;
      },
      id () {
          return this.$store.state.user.id;
      },
  },
    created() {
    this.get_user();
  },
  methods: {
      get_user: async function(){
        try {
          const res = await axios.get('/api/user', {
            // Tokenを付与してUser情報を取得する
            headers: {
              Authorization: `Bearer ${this.token}`,
            }
          })
          this.user = res.data;
          console.log(this.user);
        } catch (error) {
           console.log("error");
        }
      },
      del_user: async function(id){
        if (confirm('削除してよろしいですか?')) {
          try {
            const res = await axios.delete("/api/image/del/" + id)
            console.log(id);
            try {
              const res = await axios.delete('/api/user', {
              //const res = await axios.get('/api/user', {
                // Tokenを付与してUser情報を取得する
                headers: {
                  Authorization: `Bearer ${this.token}`,
                }
              })
            } catch (error) {
              console.log(error);
            }
            this.$router.push('/thanks/')
          } catch (error) {
            console.log("error");
          }
        }
      }
  } 
}
</script>