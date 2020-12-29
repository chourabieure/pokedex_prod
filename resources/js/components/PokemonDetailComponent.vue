<template>
	<div class="pokedex_comp pokemon_detail " v-if="pokemon" :style="'background-color:'+getColor(pokemon.type.type1)">

		<h2 class="container back-arrow">
			<router-link :to="{name:from,params: { id:id_route,myChosen_route:myChosen_route,hisChosen_route:hisChosen_route }}" v-if="id_route">
				<i class="fas fa-arrow-left"></i>
			</router-link>
			<router-link :to="{name:from}" v-else>
				<i class="fas fa-arrow-left"></i>
			</router-link>

		</h2>

		<div class="container detail-container">

			<div class="detail-title">
				<h2 class="pokemon-name">{{pokemon.nom_pok}}</h2>
				<h2 class="pokemon-id">#{{(pokemon.id<10)?'00':''}}{{(pokemon.id<100&&pokemon.id>=10)?'0':''}}{{pokemon.id}}</h2>

			</div>
			<div class="type-container">
				<h4 class="type" v-for="(type,index) in pokemon.type" :key="index" v-if="type"><span :style="'background-color:'+getColor(type)">{{type.toUpperCase()}}</span></h4>
			</div>

			<div class="next">
				<router-link v-if="id>1" :to="{name:'pokemon_detail', params:{id:pokemon.id-1,from: from}}"><i class="fas fa-chevron-left"></i></router-link>
				<div v-if="id==1" style="height:10px;width:10px"></div>
				<router-link v-if="id<151" :to="{name:'pokemon_detail', params:{id:pokemon.id+1,from: from}}"><i class="fas fa-chevron-right"></i></router-link>
			</div>

			<img :src="'/assets/images/sprite/normal/'+pokemon.id+'.png'" alt="image_pokemon">
			<div class="bg-pokeball"></div>

			<div class="detail-infos">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="true">About</a>
						<a class="nav-item nav-link" id="nav-stats-tab" data-toggle="tab" href="#nav-stats" role="tab" aria-controls="nav-stats" aria-selected="false">Stats</a>
						<a class="nav-item nav-link" id="nav-evolutions-tab" data-toggle="tab" href="#nav-evolutions" role="tab" aria-controls="nav-evolutions" aria-selected="false">Evolutions</a>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">

						<div class="detail-content">
							<p class="description">"{{pokemon_detail.description}}"</p>

							<div>
								<h3>Height</h3>
								<h3>{{pokemon_detail.height*10}} cm</h3>
							</div>

							<div>
								<h3>Weight</h3>
								<h3>{{pokemon_detail.height/10}} kg</h3>
							</div>

							<div>
								<h3>Abilities</h3>
								<h3 class="abilities">
									<span v-for="(ability,index) in pokemon_detail.abilities" :key="index">
										{{ability.ability.name}}<span v-if="index+1<Object.keys(pokemon_detail.abilities).length">,</span>
									</span>
								</h3>
							</div>
						</div>

					</div>
					<div class="tab-pane fade" id="nav-stats" role="tabpanel" aria-labelledby="nav-stats-tab">
						<div class="detail-content">
							<div>
								<h3>Hp</h3>
								<h3>{{pokemon_detail.hp}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+((pokemon_detail.hp*100)/255)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="255"></div>
								</div>
							</div>
							<div>
								<h3>Attack</h3>
								<h3>{{pokemon_detail.attack}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+((pokemon_detail.attack*100)/255)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="255"></div>
								</div>
							</div>
							<div>
								<h3>Defense</h3>
								<h3>{{pokemon_detail.defense}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+((pokemon_detail.defense*100)/255)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="255"></div>
								</div>
							</div>
							<div>
								<h3>Sp. Atk</h3>
								<h3>{{pokemon_detail.speAtk}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+((pokemon_detail.speAtk*100)/255)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="255"></div>
								</div>
							</div>
							<div>
								<h3>Sp. Def</h3>
								<h3>{{pokemon_detail.speDef}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+((pokemon_detail.speDef*100)/255)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="255"></div>
								</div>
							</div>
							<div>
								<h3>Speed</h3>
								<h3>{{pokemon_detail.speed}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+((pokemon_detail.speed*100)/255)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="255"></div>
								</div>
							</div>
							<div>
								<h3 class="title-cap">Total</h3>
								<h3>{{pokemon_detail.hp+pokemon_detail.attack+pokemon_detail.defense+pokemon_detail.speAtk+pokemon_detail.speDef+pokemon_detail.speed}}</h3>
								<div class="progress w-100">
									<div class="progress-bar bg-success" role="progressbar" :style="'width:'+(((pokemon_detail.hp+pokemon_detail.attack+pokemon_detail.defense+pokemon_detail.speAtk+pokemon_detail.speDef+pokemon_detail.speed)*100)/1530)+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="1530"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="nav-evolutions" role="tabpanel" aria-labelledby="nav-evolutions-tab">...</div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
export default {
	props: {
		id: undefined,
		pokemon_info: undefined,
		id_route: undefined,
		myChosen_route: undefined,
		hisChosen_route: undefined,
		from: {
			type: String,
			default: "home",
		},
	},
	data() {
		return {
			pokemon: "",
			pokemon_detail: {},
			message: "",
		};
	},
	mounted() {
		if (!this.pokemon_info) {
			axios
				.get("/pokemons/" + this.id)
				.then((response) => {
					this.pokemon = response.data;
					this.getPokemonDetail(this.id);
				})
				.catch((error) => console.log(error));
		} else {
			this.pokemon = this.pokemon_info;
			this.getPokemonDetail(this.pokemon_info.id);
		}
	},
	watch: {
		id: {
			handler() {
				axios
					.get("/pokemons/" + this.id)
					.then((response) => {
						this.pokemon = response.data;
						this.getPokemonDetail(this.id);
					})
					.catch((error) => console.log(error));
			},
		},
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
		getPokemonDetail(id) {
			var description = "";
			axios
				.get("https://pokeapi.co/api/v2/pokemon-species/" + this.id)
				.then((response) => {
					description = response.data.flavor_text_entries[0].flavor_text;
					description = description.replaceAll('\n',' ')
					description = description.replaceAll('\f',' ')
					axios
						.get("https://pokeapi.co/api/v2/pokemon/" + this.id)
						.then((response) => {
							this.pokemon_detail = {
								height: response.data.height,
								weight: response.data.weight,
								abilities: response.data.abilities,
								description: description,
								hp: response.data.stats[0].base_stat,
								attack: response.data.stats[1].base_stat,
								defense: response.data.stats[2].base_stat,
								speAtk: response.data.stats[3].base_stat,
								speDef: response.data.stats[4].base_stat,
								speed: response.data.stats[5].base_stat,
							};
						})
						.catch((error) => console.log(error));
				});
		},
	},
};
</script>
