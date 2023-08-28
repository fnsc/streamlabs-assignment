<script setup>
import {reactive} from "vue";
import {getCookie} from "@/cookie.js";

const state = reactive({
  followersCount: 0
})

const accessToken = getCookie("token");
axios.defaults.headers.common['Authorization'] = 'Bearer ' + accessToken;
axios.get('api/v1/followers-count')
    .then(({data}) => {
      state.followersCount = data.followers_count
    }).catch(({error}) => {
        console.log(error)
    })
</script>

<template>
  <div class="bg-white shadow-md p-4 rounded-md w-full mr-4">
    <h2 class="text-lg font-semibold mb-2 text-center">Followers in the Last 30 Days</h2>
    <div class="flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
      </svg>
      <p class="text-xl font-semibold" v-text="state.followersCount"></p>
    </div>
  </div>
</template>
