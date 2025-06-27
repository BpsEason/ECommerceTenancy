<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <div class="text-center p-8 bg-gray-800 rounded-lg shadow-2xl w-full max-w-4xl mx-auto">
      <h1 class="text-6xl font-extrabold mb-8 animate-pulse">{{ $t('queue.title') }}</h1>

      <div class="mb-12">
        <p class="text-4xl font-semibold mb-4 text-gray-400">{{ $t('queue.currently_serving') }}</p>
        <div class="bg-indigo-600 p-8 rounded-full shadow-inner border-4 border-indigo-400 transform transition-transform duration-500 hover:scale-105">
          <p v-if="currentlyServing" class="text-8xl font-black text-white tracking-widest">{{ currentlyServing }}</p>
          <p v-else class="text-6xl font-black text-gray-300">{{ $t('queue.no_number') }}</p>
        </div>
      </div>

      <div>
        <p class="text-3xl font-semibold mb-6 text-gray-400">{{ $t('queue.waiting_list') }}</p>
        <div v-if="waitingList.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
          <div
            v-for="item in waitingList"
            :key="item.id"
            class="bg-gray-700 p-6 rounded-xl shadow-lg border-2 border-gray-600 transition-all duration-300 hover:shadow-xl hover:bg-gray-600"
          >
            <p class="text-5xl font-bold text-teal-400">{{ item.queue_number }}</p>
          </div>
        </div>
        <p v-else class="text-4xl text-gray-500 italic mt-8">{{ $t('queue.no_waiting') }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
// Assuming you have a WebSocket setup
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

const { t } = useI18n();
const currentlyServing = ref(null);
const waitingList = ref([]);

const fetchQueueStatus = async () => {
  try {
    const response = await fetch('/api/queue/public');
    const data = await response.json();
    currentlyServing.value = data.currently_serving;
    waitingList.value = data.waiting_list;
  } catch (error) {
    console.error("Failed to fetch queue status:", error);
  }
};

onMounted(() => {
  fetchQueueStatus();

  // // Listen for real-time updates via WebSocket
  // window.Echo = new Echo({
  //   broadcaster: 'pusher',
  //   key: import.meta.env.VITE_WEBSOCKET_APP_KEY,
  //   cluster: import.meta.env.VITE_WEBSOCKET_APP_CLUSTER ?? 'mt1',
  //   wsHost: import.meta.env.VITE_WEBSOCKET_HOST ? import.meta.env.VITE_WEBSOCKET_HOST : `ws-${window.location.hostname}`,
  //   wsPort: import.meta.env.VITE_WEBSOCKET_PORT ?? 6001,
  //   wssPort: import.meta.env.VITE_WEBSOCKET_PORT ?? 6001,
  //   forceTLS: import.meta.env.VITE_WEBSOCKET_FORCE_TLS ?? false,
  //   enabledTransports: ['ws', 'wss'],
  // });
  //
  // window.Echo.channel('public-queue')
  //   .listen('.queue.updated', (e) => {
  //     console.log('Real-time queue update received:', e);
  //     currentlyServing.value = e.queue.currently_serving;
  //     waitingList.value = e.queue.waiting_list;
  //   });
});

onUnmounted(() => {
  // window.Echo.leave('public-queue');
});
</script>

<style scoped>
/* You can add custom styles here if needed */
</style>
