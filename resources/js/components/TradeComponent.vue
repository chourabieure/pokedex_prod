<template>
	<div class="container pokedex_comp">
		<menu-comp :position="'trades'"></menu-comp>

		<h2 class="container back-arrow">
			<router-link :to="{name:from}">
				<i class="fas fa-arrow-left"></i>
			</router-link>
		</h2>
		<h2 class="title">Trades</h2>

		<!-- MODEL FOR DROP DOWN RESULT <div class="d-flex justify-content-center search-bar">
			<i class="fas fa-search"></i>
			<input type="text" v-model="search" placeholder="Search for a user to trade with" @keyup="getSearch">
			<div class="panel-footer" v-if="results.length">
				<ul class="list-group">
					<li type="button" class="list-group-item" v-for="result in results" :key="result.id" @click="selectUser(result.id)">
						{{ result.username }}
					</li>
				</ul>
			</div>
		</div> -->
		<div class="d-flex justify-content-center search-bar">
			<i class="fas fa-search"></i>
			<input type="text" v-model="search" placeholder="Search for a user to trade with" @keyup="getSearch">
		</div>

		<div class="trade-container">

			<div v-if="Object.keys(users).length === 0 && search != ''">
				<h2 class="no-user">No users found</h2>
			</div>


			<div :id="'user'+user.id" class="user-container" v-for="user in users" :key="user.id">
				<router-link :to="{name: 'trading', params: { id:user.id,username:user.username,from:'trades' } }" class="user-info" >
					<h2 class="user-id">#{{(user.id<10)?'00':''}}{{(user.id<100&&user.id>=10)?'0':''}}{{user.id}}</h2>
					<h2 class="user-name">{{user.username}}</h2>

				</router-link>
				<div class="pokemon-info">
					<div v-for="(pokemon,index) in user.team" :key="index" class="pokemon" :style="[ pokemon ? {backgroundColor:getColor(pokemon.type)}: {backgroundColor: 'rgb(199,199,199)'}]">

						<router-link :to="{name:'pokemon_detail', params:{id:pokemon.id,from: 'trades' }}" v-if="pokemon">
							<img :src="'/assets/images/sprite/normal/'+pokemon.id+'.png'" alt="image_pokemon">
						</router-link>
						<a v-else>
							<div class="empty"><img src="/assets/images/empty.png" alt=""></div>
						</a>

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
			search: "",
			all_users: {},
			users: {},
		};
	},
	mounted() {
		axios
			.get("/users")
			.then((response) => {
				this.all_users = response.data;
				this.users = response.data;
			})
			.catch((error) => console.log(error));
	},
	methods: {
		getSearch() {
			if (this.search.length >= 1) {
				axios
					.get("/users/search/" + this.search)
					.then((response) => (this.users = response.data))
					.catch((error) => console.log(error));
			} else {
				this.users = this.all_users;
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