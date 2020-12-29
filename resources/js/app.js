require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import Home from './components/HomeComponent.vue'
import Pokedex from './components/PokedexComponent.vue'
import Team from './components/TeamComponent.vue'
import PokemonCard from './components/PokemonCardComponent.vue'
import Trades from './components/TradeComponent.vue'
import Counter from './components/CounterComponent.vue'
import NewPokemon from './components/NewPokemonComponent.vue'
import Trading from './components/TradingComponent.vue'
import Avatar from './components/AvatarComponent.vue'
import PokemonDetail from './components/PokemonDetailComponent.vue'
import Menu from './components/MenuComponent.vue'

// Init
Vue.use(VueRouter);
Vue.component('pokemon-comp',PokemonCard);
Vue.component('team-comp',Team);
Vue.component('counter-comp',Counter);
Vue.component('new-pokemon-comp',NewPokemon);
Vue.component('avatar-comp',Avatar);
Vue.component('menu-comp',Menu);

const routes = [
    {
        path:'/',
        component:Home,
        name: 'home',
        props: true
    },
    {
        path:'/pokedex',
        component:Pokedex,
        name: 'pokedex',
        props: true
    }, 
    {
        path:'/pokedex/:id',
        component:PokemonDetail,
        name: 'pokemon_detail',
        props: true
    }, 
    {
        path:'/myTeam',
        component:Team,
        name: 'myTeam',
        props: true
    },
    {
        path:'/trades',
        component:Trades,
        name: 'trades',
        props: true
    },
    {
        path:'/trading/:id',
        name:'trading',
        component:Trading,
        props:true
    },
]
const router = new VueRouter({
    routes,
    mode: 'history'
});

const app = new Vue({
    el: '#app',
    router: router
});
