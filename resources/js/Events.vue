<template>
  <div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <p class="text-2xl">Events</p>
          </div>

          <div class="content-body p-5">

            <div class="flex justify-content-center align-items-center">

              <div id="reader" class="w-[400px]" width="400px"></div>


              <ol class="max-w-md text-xl space-y-1 text-gray-500 list-decimal list-inside dark:text-gray-400">
                <li v-for="event in events">
                  <a class="font-semibold text-blue-500" @click="goEventPage(event)">{{ event.title }}</a>
                </li>

              </ol>

            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
</template>


<script setup>

import {onBeforeMount, onMounted, ref} from "vue";
import  pages from './Supports/pages'

const props = defineProps({
  setPage: Object,
  events: Array,
  setEvent: Object
})

const goEventPage = (event)=>{
  props.setPage(pages.attendee)
  props.setEvent(event)
}

onMounted(() => {

})

</script>
