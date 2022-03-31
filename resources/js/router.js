import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import Signup from './components/Signup.vue'
import Login from './components/login.vue'
import PostIndex from './components/post/Index.vue'
import TagIndex from './components/article/tag_index.vue'
import ArticleIndex from './components/article/Index.vue'
import ArticleInput from './components/article/Input.vue'
import User from './components/article/user.vue'
import ArticleDetail from './components/article/detail.vue'
import ArticleEdit from './components/article/edit.vue'
import Tag from './components/article/tag.vue'
import PasswordResetForm from './components/auth/reset.vue'
import PasswordReset from './components/auth/Index.vue'
import Thanks from './components/auth/thanks.vue'
import store from './store.js'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/signup',
    component: Signup,
    meta: {requiresAuth: false},
  },
  {
    path: '/password-reset/',
    component: PasswordResetForm,
    meta: {requiresAuth: false},
  },
  {
    path: '/reset/:token',
    component: PasswordReset,
    meta: {requiresAuth: false},
  },
  {
    path: '/login',
    component: Login,
    meta: {requiresAuth: false},
  },
  {
    path: '/thanks',
    component: Thanks,
    meta: {requiresAuth: false},
  },
  {
    path: '/user/',
    component: User,
    meta: {requiresAuth: true},
  },
  {
    path: '/tag/',
    component: Tag,
    meta: {requiresAuth: true},
  },
  {
    path: '/post/',
    component: PostIndex,
    meta: {requiresAuth: true},
  },
  {
    path: '/article/',
    component: ArticleIndex,
    meta: {requiresAuth: true},
  },
  {
    path: '/article/tag/:id',
    name: 'TagIndex',
    component: TagIndex,
    meta: {requiresAuth: true},
  },
  {
    path: '/article/input',
    component: ArticleInput,
    meta: {requiresAuth: true},
  },
  {
    path: '/article/detail/:id',
    name: 'Detail',
    component: ArticleDetail,
    meta: {requiresAuth: true},
  },
  {
    path: '/article/edit/:id',
    name: 'Edit',
    component: ArticleEdit,
    meta: {requiresAuth: true},
  },
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    routes
})

router.beforeEach((to,from,next)=>{
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // requiresAuthがtrueなら評価
    if(!store.getters["isAuthenticated"]){  //ログイン済み:true,未ログイン:false
      // 未ログインならログインページへ
     next('/login');
    } else {
      
      next()  // スルー
    }
  } else {
    next()  // スルー
  }
})

export default router