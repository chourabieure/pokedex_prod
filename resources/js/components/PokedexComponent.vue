<template>
	<div class="container pokedex_comp pokedex_detail">
		<menu-comp :position="'pokedex'"></menu-comp>
		
		<h2 class="container back-arrow">
			<router-link :to="{name:from}">
				<i class="fas fa-arrow-left"></i>
			</router-link>
		</h2>
		<h2 class="title">Pokedex</h2>

		<div class="d-flex justify-content-center search-bar">
			<i class="fas fa-search"></i>
			<input type="text" v-model="search" placeholder="Search for a pokemon" @keyup="getSearch">
		</div>

		<!-- <transition-group name="" tag="div" class="card-container"> -->
		<div class="card-container">

			<div class="no-user" v-if="Object.keys(pokemons).length === 0 && search != ''">
				<h2 >No pokemon found</h2>
			</div>

			<div class="card-flex-container" v-for="pokemon in pokemons" :key="pokemon.id">
				<pokemon-comp :pokemon_info="pokemon"></pokemon-comp>
			</div>
		</div>
		<!-- </transition-group> -->

	</div>
</template>

<script>
export default {
	props:{
		from: {
			type: String,
			default: 'home'
		}
	},
	data() {
		return {
			all_pokemons: {},
			pokemons: {},
			search: "",
		};
	},
	mounted() {
		axios
			.get("/pokemons")
			.then((response) => {
				this.pokemons = response.data;
				this.all_pokemons = response.data;
			})
			.catch((error) => console.log(error));
	},
	methods: {
		getSearch() {
			if (this.search.length >= 1) {
				axios
					.get("/pokemons/search/" + this.search)
					.then((response) => (this.pokemons = response.data))
					.catch((error) => console.log(error));
			} else {
				this.pokemons = this.all_pokemons;
			}
		},
	},
};
</script>
