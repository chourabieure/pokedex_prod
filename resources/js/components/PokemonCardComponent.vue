<template>
	<router-link :to="{name:'pokemon_detail', params:{id:pokemon_info.id,from: 'pokedex',pokemon_info:pokemon_info}}" class="my-card" :style="'background-color:'+this.color">
		<div class="my-card-title">
			<h3 class="pokemon-name">{{pokemon_info.nom_pok}}</h3>
			<h3 class="pokemon-id">#{{(pokemon_info.id<10)?'00':''}}{{(pokemon_info.id<100&&pokemon_info.id>=10)?'0':''}}{{pokemon_info.id}}</h3>
		</div>
		<div class="type-container">
			<h4 class="type" v-for="(type,index) in pokemon_info.type" :key="index">
				<span v-if="type" :style="'background-color:'+getColor(type)">{{type.toUpperCase()}}</span>
			</h4>

		</div>
		<img :src="'/assets/images/sprite/normal/'+pokemon_info.id+'.png'" alt="image_pokemon">
		<div class="bg-pokeball"></div>
	</router-link>
</template>

<script>
export default {
	props: ["pokemon_info"],
	data() {
		return {
			color: "",
		};
	},
	mounted() {
		this.color = this.getColor(this.pokemon_info.type.type1);
	},
	methods: {
		getColor(type) {
			switch (type) {
				case "bug":
					return "#A8B820";
					break;
				case "dark":
					return "#705848";
					break;
				case "dragon":
					return "#7038F8";
					break;
				case "electric":
					return "#F8D030";
					break;
				case "fairy":
					return "#EE99AC";
					break;
				case "fighting":
					return "#C03028";
					break;
				case "fire":
					return "#F08030";
					break;
				case "flying":
					return "#A890F0";
					break;
				case "ghost":
					return "#705898";
					break;
				case "grass":
					return "#78C850";
					break;
				case "ground":
					return "#E0C068";
					break;
				case "ice":
					return "#98D8D8";
					break;
				case "normal":
					return "#A8A878";
					break;
				case "poison":
					return "#A040A0";
					break;
				case "psychic":
					return "#F85888";
					break;
				case "rock":
					return "#B8A038";
					break;
				case "steel":
					return "#B8B8D0";
					break;
				case "water":
					return "#6890F0";
					break;

				default:
					return "#c7c7c7";
					break;
			}
		},
	},
};
</script>
