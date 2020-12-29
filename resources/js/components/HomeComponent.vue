<template>
	<div class="container home-container">

		<menu-comp :position="'home'"></menu-comp>

		<!-- <div style="color: rgba(0,0,0,0.6); margin-top: 16px;">{{ lastClicked }}</div> -->
		<router-link :to="{name:'pokedex', params:{from: 'home'}}" style="background-color:#E63F36">
			Pokedex
			<div class="bg-pokeball"></div>
			<div class="bg-ball"></div>

		</router-link>
		<router-link :to="{name:'myTeam', params:{from: 'home'}}" style="background-color:#00ac0e">
			My team
			<div class="bg-pokeball"></div>
			<div class="bg-ball"></div>

		</router-link>
		<router-link to="/" style="background-color:#b44c1a">
			Capture
			<div class="bg-pokeball"></div>
			<div class="bg-ball"></div>

		</router-link>
		<router-link :to="{name:'trades', params:{from: 'home'}}" style="background-color:#1800aa">
			Trades
			<div class="bg-pokeball"></div>
			<div class="bg-ball"></div>
		</router-link>
	</div>
</template>

<script>
export default {
	data() {
		return {
			user: {},
		};
	},
	mounted() {
		axios
			.get("/users/me")
			.then((response) => (this.user = response.data))
			.catch((error) => console.log(error));
	},
};
</script>
