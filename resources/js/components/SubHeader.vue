<template>
  <div class="header">
    <p class="name" v-if="name">ユーザー名:{{name}}</p>
  </div>
</template>

<script>
export default {
  name: 'SubHeader',
  computed: {
      name () {
          return this.$store.state.user.name;
      },
  },
}
</script>