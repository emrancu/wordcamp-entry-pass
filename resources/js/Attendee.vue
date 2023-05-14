<template>
  <div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-sm sm:rounded-lg">
          <p @click="setPage(pages.events)"
             class="p-3 cursor-pointer hover:text-blue-500">Back</p>
          <div class="p-6 text-gray-900 flex justify-around">
            <div class="w-2/4">
              <p class="text-2xl">Event: {{ event.title }}</p>
              <div>
                <label class="py-2 block font-medium text-sm text-gray-700">
                  Search With ID
                </label>
                <input
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    type="text"
                    v-model="attendeeID"
                    @keyup.enter="getAttendee()"
                />
                <p>Press Enter for Result</p>
              </div>
            </div>

            <div class="w-[350px]  bg-gray-200" id="cameraRender"></div>
          </div>
          <div class="content-body p-5">
            <div class="flex">
              <div class="w-3/5">
                <table class="table w-full">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>T-shirt Size</th>
                    <th>Last Update</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="attendee.id">
                    <td>{{ attendee.last_name }} {{ attendee.first_name }}</td>
                    <td>{{ attendee.attendee_type }}</td>
                    <td><b>{{ attendee.tshirt_size }}</b></td>
                    <td>{{ attendee.last_modified_at }}</td>
                  </tr>
                  </tbody>
                </table>

                <div v-if="showCheckInBtn" class="mt-4">
                  <button type="button" class="btn primary" @click="confirmCheckIn">Check In</button>
                </div>
              </div>
              <div class="pl-2">
                <p><b>Completed Events: </b></p>
                <div class="flex gap-3">
                  <div class="flex" v-for="event in attendee.events">
                    <div class="flex items-center h-5">
                      <input :id="'event' + event.id" disabled checked type="checkbox" value=""
                             class="w-4 h-4 text-blue-600 checkbox-active border-gray-300 rounded focus:ring-blue-500 focus:ring-2 ">
                    </div>
                    <div class="ml-2 text-sm">
                      <label :for="'event' + event.id" class="font-medium text-gray-900 dark:text-gray-300">{{ event.title }}</label>
                      <p id="helper-checkbox-text" class="text-xs font-normal text-gray-500 dark:text-gray-300"> 👉 {{ event.created_at }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="selectCameraModal" class="fixed top-0 left-0 right-0 bottom-0 bg-[rgba(0,0,0,.4)] flex justify-center items-center">

              <div class="bg-white p-6 w-[300px]">
                <h1 v-if="deviceLoading">Loading Camera...</h1>
                <div v-else>
                  <label class="block font-medium pb-1 text-sm text-gray-700">
                    Select Camera
                  </label>
                  <select v-model="selectedCamera" class="w-full">
                    <option value="no">No Camera</option>
                    <option v-for="device in cameras" :value="device.id">{{ device.label }}</option>
                  </select>

                  <button
                      v-if="selectedCamera"
                      @click="selectCameraModal = false"
                      type="button"
                      class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                  >
                    OK
                  </button>

                </div>
              </div>

            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
</template>


<script setup>

import {Html5Qrcode} from "html5-qrcode";
import {computed, onBeforeMount, onMounted, ref, watch} from "vue";
import pages from './Supports/pages'

const props = defineProps({
  event: Object,
  setPage: Object,
})

const attendeeID = ref(null)
const cameras = ref([])
const selectedCamera = ref('no')
const selectCameraModal = ref(true)
const deviceLoading = ref(true)
const events = ref([])
const attendee = ref({events: []})

const showCheckInBtn = computed(() => {
  if (!attendee.value.id) {
    return false;
  }

  if (!attendee.value.events.length) {
    return true;
  }

  const event = attendee.value.events.find(item => item.id === props.event.id);

  return !event?.id;
})

function onScanSuccess(decodedText, decodedResult) {
  // handle the scanned code as you like, for example:
  console.log(`Code matched = ${decodedText}`, decodedResult);
}

function onScanFailure(error) {
  // handle scan failure, usually better to ignore and keep scanning.
  // for example:
  console.warn(`Code scan error = ${error}`);
}

const getEvents = async () => {
  let request = await axios.get(wordcamp_entry_pass.api_endpoint + '/events')
  events.value = request.data
}

onBeforeMount(() => {
  getEvents()
})

onMounted(() => {

  Html5Qrcode.getCameras().then(devices => {
    if (devices && devices.length) {
      cameras.value = devices
    }
    deviceLoading.value = false
  }).catch(err => {
    deviceLoading.value = false
  });
})

watch(selectedCamera, async (newValue, oldValue) => {
  if (newValue && newValue !== 'no') {
    startScanner();
  }
})

const startScanner = () => {

  const html5QrCode = new Html5Qrcode("cameraRender");
  html5QrCode.start(
      selectedCamera.value,
      {
        fps: 1,
        qrbox: {width: 350, height: 350}
      },
      (decodedText, decodedResult) => {
        attendeeID.value = decodedText
        getAttendee();
      },
      (errorMessage) => {
        console.log('no..')
      })
      .catch((err) => {
        // Start failed, handle it.
      });

}

const getAttendee = async () => {
  let request = await axios.get(wordcamp_entry_pass.api_endpoint + '/attendee/' + attendeeID.value)
  if(request.status === 200){
    attendee.value = request.data
  }
}

const confirmCheckIn = async () => {
  const check = confirm("Are You sure?")
  let request = await axios.post(wordcamp_entry_pass.api_endpoint + '/attendee-event',
      {attendee_id: attendee.value.id, event_id: props.event.id}
  )

  if(request.status === 200) {
    attendee.value = request.data
  }
}


</script>
