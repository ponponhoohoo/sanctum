<template>
  <p class="like">
    <button type="submit" @click="UpdateLike(id , article_id)" class="like_btn" v-bind:class="[ActiveFlg == true ? 'active' : '']"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{LikeCnt}}</button>
  </p>
</template>

<script>
export default {
  name: "GetLikeCnt",
  props: ["article_id","id"],
  setup(props) {
    console.log(props.article_id);
    console.log(props.id);
  },
  data() {
     return {
        LikeCnt:"",
        ActiveFlg:false,
      }
  },
  computed: {
      Cnt: function () {
        return Cnt;
      },
  },
  mounted() {
    this.getLike(this.article_id);
    this.CheckActiveLike(this.id , this.article_id);
  },
  created: function() {
    
  },
  methods: {
    getLike: async function(article_id){
        const res = await axios.get('/api/like/' + article_id)
        .then(function(response){
            this.LikeCnt = response.data;
            console.log(this.LikeCnt);
        }.bind(this))  //Promise処理を行う場合は.bind(this)が必要
        .catch(function(error){  //バックエンドからエラーが返却された場合に行う処理について
            console.log("error");
        }.bind(this))
        .finally(function(){
            }.bind(this))
    },
    CheckActiveLike: async function(id,article_id){
        const queries = { id: id ,  article_id:article_id};
        const res = await axios.get('/api/like/user/' + id + '/' + article_id )
        .then(function(response){
            this.ActiveFlg = response.data;
            console.log(this.ActiveFlg);
        }.bind(this))  //Promise処理を行う場合は.bind(this)が必要
        .catch(function(error){  //バックエンドからエラーが返却された場合に行う処理について
            console.log("error");
        }.bind(this))
        .finally(function(){
            }.bind(this))
    },
    UpdateLike: async function(id,article_id){
        let config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };
        config.headers['X-HTTP-Method-Override'] = 'PUT';
        const res = await axios.put('/api/like/update/' + id + '/' + article_id)
        .then(function(response){
            this.ActiveFlg = response.data.ActiveFlg;
            if (this.ActiveFlg == true) {
              this.LikeCnt = this.LikeCnt + 1
            } else {
              this.LikeCnt = this.LikeCnt - 1
            }
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

<style>
</style>