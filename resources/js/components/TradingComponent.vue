<template>
	<div class="container pokedex_comp trading">
		<menu-comp :position="'trades'"></menu-comp>

		<h2 class="container back-arrow">
			<router-link :to="{name:from}">
				<i class="fas fa-arrow-left"></i>
			</router-link>
		</h2>
		<h2 class="title">Trading</h2>

		<!-- Modal -->
		<div class="modal fade" id="modalSend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Trade Confirmation</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Do you really want to proceed to this exchange ?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" @click="trade" data-dismiss="modal" data-toggle="modal" data-target="#modalResponse">Yes</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modalResponse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="font-weight:bold;color:red; " v-if="error">
						{{error}}
					</div>
					<div class="modal-body" v-else>
						Trade Complete !
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="trading-container">
			<div class="upper">
				<div class="pokemons">
					<div @click="chooseMy(index)" v-for="(pokemon,index) in myTeam" :key="index" class="pokemon" :style="[ pokemon ? {backgroundColor:getColor(pokemon.type)}: {backgroundColor: 'rgb(199,199,199)'}]">
						<a v-if="pokemon.id!=0">
							<img :src="'/assets/images/sprite/normal/'+pokemon.id+'.png'" alt="image_pokemon">
						</a>
						<a v-else>
							<div class="empty"><img src="/assets/images/empty.png" alt=""></div>
						</a>

					</div>
				</div>
				<router-link class="chosen active" v-if="myChosen" :to="{name:'pokemon_detail', params:{id:myChosen.id,id_route:id,hisChosen_route:hisChosen,myChosen_route:myChosen,from: 'trading' }}" :style="'backgroundColor:'+getColor(myChosen.type)">
					<h2 class="pokemon-id">#{{(myChosen.id<10)?'00':''}}{{(myChosen.id<100&&myChosen.id>=10)?'0':''}}{{myChosen.id}}</h2>
					<img :src="'/assets/images/sprite/normal/'+myChosen.id+'.png'" alt="image_pokemon">
					<h2 class="pokemon-name">{{myChosen.nom_pok}}</h2>
					<div class="bg-team-pokeball"></div>
				</router-link>
				<div class="chosen" v-else>

					<h2>Please pick one of your pokemons </h2>

				</div>
			</div>

			<div :class="[(myChosen && hisChosen) ? 'center active' : 'center']" :data-toggle="[(myChosen && hisChosen) ? 'modal' : '']" data-target="#modalSend">
				<img :class="[(myChosen && hisChosen) ? 'arrow-left active' : 'arrow-left']" src="/assets/images/arrow.png" alt="arrow">
				<img :class="[(myChosen && hisChosen) ? 'logo active' : 'logo']" src="/assets/images/exchange.png" alt="image_exchange">
				<img :class="[(myChosen && hisChosen) ? 'arrow-right active' : 'arrow-right']" src="/assets/images/arrow.png" alt="arrow">
			</div>

			<div class="lower">
				<router-link class="chosen active" v-if="hisChosen" :to="{name:'pokemon_detail', params:{id:hisChosen.id,hisChosen_route:hisChosen,myChosen_route:myChosen,id_route:id,from: 'trading' }}" :style="'backgroundColor:'+getColor(hisChosen.type)">
					<h2 class="pokemon-id">#{{(hisChosen.id<10)?'00':''}}{{(hisChosen.id<100&&hisChosen.id>=10)?'0':''}}{{hisChosen.id}}</h2>
					<img :src="'/assets/images/sprite/normal/'+hisChosen.id+'.png'" alt="image_pokemon">
					<h2 class="pokemon-name">{{hisChosen.nom_pok}}</h2>
					<div class="bg-team-pokeball"></div>
				</router-link>
				<div class="chosen" v-else>

					<h2>Please pick one of his pokemons</h2>

				</div>
				<div class="pokemons">
					<div @click="chooseHis(index)" v-for="(pokemon,index) in hisTeam" :key="index" class="pokemon" :style="[ pokemon ? {backgroundColor:getColor(pokemon.type)}: {backgroundColor: 'rgb(199,199,199)'}]">

						<a v-if="pokemon.id!=0">
							<img :src="'/assets/images/sprite/normal/'+pokemon.id+'.png'" alt="image_pokemon">
						</a>
						<a v-else>
							<div class="empty"><img src="/assets/images/empty.png" alt=""></div>
						</a>

					</div>
				</div>

			</div>

		</div>

		<!-- <h3 type="button" class="mt-3 btn btn-success" v-if="mypok" data-toggle="modal" data-target="#modalSend">Send {{pokemon_info.nom_pok}} <i class="fas fa-arrow-right"></i></h3> -->

	</div>
</template>

<script>
export default {
	props: {
		id: undefined,
		username: undefined,
		id_route: undefined,
		myChosen_route: null,
		hisChosen_route: null,
		from: {
			type: String,
			default: "home",
		},
	},
	data() {
		return {
			myTeam: {},
			hisTeam: {},
			myChosen: null,
			hisChosen: null,
			error: "",
		};
	},
	mounted() {
		if (this.id === undefined) {
			this.$router.push("/trades");
		}
		axios
			.get("/users/" + this.id + "/team")
			.then((response) => {
				this.hisTeam = response.data;
			})
			.catch((error) => console.log(error));
		axios
			.get("/users/me/team")
			.then((response) => {
				this.myTeam = response.data;
			})
			.catch((error) => console.log(error));
	},
	watch: {
		myChosen_route: {
			immediate: true,
			handler() {
				this.myChosen = this.myChosen_route;
				this.hisChosen = this.hisChosen_route;
			},
		},
	},
	methods: {
		trade() {
			axios
				.post("/users/" + this.id + "/team", {
					myChosen_id: this.myChosen.id,
					hisChosen_id: this.hisChosen.id,
				})
				.then((response) => {
					if (response.data.error) {
						this.error = response.data.error;
					}
					this.refresh();
				})
				.catch((error) => console.log(error));
		},
		refresh() {
			axios
				.get("/users/" + this.id + "/team")
				.then((response) => {
					this.hisTeam = response.data;
				})
				.catch((error) => console.log(error));
			axios
				.get("/users/me/team")
				.then((response) => {
					this.myTeam = response.data;
				})
				.catch((error) => console.log(error));

			this.myChosen = null;
			this.hisChosen = null;
		},
		chooseMy(index) {
			if (this.myTeam[index].id == 0) {
				this.myChosen = null;
			} else {
				this.myChosen = this.myTeam[index];
			}
		},
		chooseHis(index) {
			if (this.hisTeam[index].id == 0) {
				this.hisChosen = null;
			} else {
				this.hisChosen = this.hisTeam[index];
			}
		},
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
	},
};
</script>
