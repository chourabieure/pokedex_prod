<template>
	<div class="container pokedex_comp team">
		<menu-comp :position="'myTeam'"></menu-comp>

		<h2 class="container back-arrow">
			<router-link :to="{name:from}">
				<i class="fas fa-arrow-left"></i>
			</router-link>
		</h2>
		<h2 class="title">My Team</h2>

		<div class="modal fade" id="modalSend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Release Confirmation</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Do you really want to release {{pokemonToRelease.nom_pok}} ?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" @click="release" data-dismiss="modal" data-toggle="modal" data-target="#modalResponse">Yes</button>
					</div>
				</div>
			</div>
		</div>
		<div class="team-container">

			<div class="pokemon-container" v-for='(pokemon,index) in myPokemons' :key='index' :style="'background-color:'+getColor(pokemon.type)">

				<router-link v-if="pokemon.id!=0" class="here" :to="{name:'pokemon_detail', params:{id:pokemon.id,from: 'myTeam'}}">

					<h2 class="pokemon-id">#{{(pokemon.id<10)?'00':''}}{{(pokemon.id<100&&pokemon.id>=10)?'0':''}}{{pokemon.id}}</h2>
					<img :src="'/assets/images/sprite/normal/'+pokemon.id+'.png'" alt="image_pokemon">
					<h2 class="pokemon-name">{{pokemon.nom_pok}}</h2>
					<div class="bg-team-pokeball" @click.prevent="update(pokemon)" data-toggle="modal" data-target="#modalSend"><img src="/assets/images/capture.png" alt="image logo"></div>
				</router-link>

				<div v-else class="empty">
					<h2 class="pokemon-name">-- Empty --</h2>
					<div class="bg-team-pokeball"></div>
				</div>

			</div>
		</div>

	</div>

	</div>
</template>


<script>
export default {
	props: {
		from: {
			type: String,
			default: "home",
		},
	},
	data() {
		return {
			myPokemons: {},
			pokemonToRelease: {},
		};
	},
	mounted() {
		axios
			.get("/users/me/team")
			.then((response) => {
				this.myPokemons = response.data;
			})
			.catch((error) => console.log(error));
	},
	methods: {
		getColor(type) {
			switch (type.type1) {
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
		update(pokemon) {
			console.log("tset");
			this.pokemonToRelease = pokemon;
		},
		release() {
			axios
				.post("/release", {
					release_id: this.pokemonToRelease.id,
				})
				.then((response) => {
					axios
						.get("/users/me/team")
						.then((response) => {
							this.myPokemons = response.data;
						})
						.catch((error) => console.log(error));
				})
				.catch((error) => console.log(error));
		},
	},
};
</script>