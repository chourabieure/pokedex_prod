<template>
	<div class="menu">
		<div class="menu-trigger" @click='toggle'>
			<i class="fas fa-bars"></i>
		</div>
		<div class="menu-elements">
			<router-link :to="{name:'pokedex', params:{from: position}}" class="elem" v-on:click.native="remove">
				<img class="arrow" src="/assets/images/arrow.png" alt="image logo">
				<span>Pokedex</span>
				<img src="/assets/images/pokedex.png" alt="image logo">

			</router-link>
			<router-link :to="{name:'myTeam', params:{from: position}}" class="elem" v-on:click.native="remove">
				<img class="arrow" src="/assets/images/arrow.png" alt="image logo">
				<span>My Team</span>
				<img src="/assets/images/team.png" alt="image logo">


			</router-link>
			<router-link :to="{name:'home', params:{from: position}}" class="elem" v-on:click.native="remove">
				<img class="arrow" src="/assets/images/arrow.png" alt="image logo">
				<span>Capture</span>
				<img src="/assets/images/capture.png" alt="image logo">

			</router-link>
			<router-link :to="{name:'trades', params:{from: position}}" class="elem" v-on:click.native="remove">
				<img class="arrow" src="/assets/images/arrow.png" alt="image logo">
				<span>Trades</span>
				<img src="/assets/images/exchange.png" alt="image logo">

			</router-link>
			<router-link :to="{name:'home', params:{from: position}}" class="elem" v-on:click.native="remove">
				<img class="arrow" src="/assets/images/arrow.png" alt="image logo">
				<span>Settings</span>
				<img src="/assets/images/settings.png" alt="image logo">

			</router-link>
			<a href="/logout" class="elem" @click="logout">
				<img class="arrow" src="/assets/images/arrow.png" alt="image logo">
				<span>Logout</span>
				<img src="/assets/images/logout.png" alt="image logo">
			</a>
		</div>
	</div>
</template>

<script>
export default {
	props:['position'],
	data() {
		return {};
	},
	methods: {
		toggle() {
			$(".menu-elements").toggleClass("active");
			$(".bg_dropdown").toggleClass("bg_dropdown_toggle");
			$(".bg-background").toggleClass("triggered");
			$(".menu-trigger").toggleClass("menu-trigger-active");
			$("body").toggleClass("overflow-hidden");
		},
		remove(){
			$(".menu-elements").removeClass("active");
			$(".bg_dropdown").removeClass("bg_dropdown_toggle");
			$(".bg-background").removeClass("triggered");
			$(".menu-trigger").removeClass("menu-trigger-active");
			$("body").removeClass("overflow-hidden");
		},
		logout(){
			this.remove()
		}
	},
};
</script>
