<script setup>
import {reactive} from "vue";
import {getCookie} from "@/cookie.js";

const state = reactive({
    activities: [],
    nextPage: 1,
    accessToken: getCookie("token"),
    pagination: {}
})


function fetch(url) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + state.accessToken;
    axios.get(url)
        .then((data) => {
            state.activities = data.data.data
            delete data.data.data
            state.pagination = data.data
        }).catch(({error}) => {
            console.log(error)
        })
}

fetch('api/v1/event-list');
</script>
<template>
  <div class="overflow-x-auto p-4">
    <table class="min-w-full border border-gray-300" id="events-list-table">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-2 px-4 border">Message</th>
          <th class="py-2 px-4 border">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(activity, index) in state.activities" :key="index" :class="{ 'bg-gray-50': index % 2 === 0 }">
          <td class="py-2 px-4 border">{{ activity.message }}</td>
          <td class="py-2 px-4 border">
            <div class="p-4">
                <label class="flex items-center space-x-2">
                  <input
                    type="checkbox"
                    v-model="activity.read_status"
                    class="form-checkbox h-5 w-5 text-indigo-600"
                  />
                </label>
              </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="state.activities.length === 0" class="mt-2">
      No activities to display.
    </div>
  </div>
</template>
