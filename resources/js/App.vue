<template>
  <div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Events v-if="page === pages.events" :events ="events" :setEvent="setEvent" :setPage="setPage"/>
        <Attendee  v-if="page === pages.attendee" :event="selectedEvent" :setPage="setPage"/>
      </div>
    </div>
  </div>
</template>


<script setup>

import {onBeforeMount, onMounted, ref} from "vue";
import Events from "./Events.vue";
import Attendee from "./Attendee.vue";
import  pages from './Supports/pages'

const page = ref('events');
const selectedEvent = ref({});

const events = ref([])

const setPage = (name)=>{
  page.value = name
}

const setEvent = (event)=>{
  selectedEvent.value = event
}

const getEvents = async ()=>{
  let request =  await axios.get(wordcamp_entry_pass.api_endpoint + '/events')
  if(request.status === 200) {
    events.value = request.data
  }
}

onBeforeMount(()=>{
  getEvents()
})


</script>
