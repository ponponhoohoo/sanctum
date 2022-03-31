<template>
  <div>
    <section v-if="tag">
      <ul class="tag">
        <li v-for="item in tag"><router-link :to="{ name: 'TagIndex', params: {id: item.tagname.id } }">{{ item.tagname.name }}</router-link></li>
      </ul>
    </section>
  </div>
</template>

<script>
export default {
  name: "TagList",
  props: ["article_id"],
  data() {
     return {
        tag:{},
      }
  },
  mounted() {
    this.getTags(this.article_id);
  },
  methods: {
    getTags: async function(article_id){
      const res = await axios.get('/api/tag/article/'  + article_id)
      .then(function(response){
          this.tag = response.data;
          console.log(this.tag);
      }.bind(this))
      .catch(function(error){
          console.log("error");
      })
    },
  }
};
</script>

<style>


</style>