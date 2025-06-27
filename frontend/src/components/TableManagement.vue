<template>
  <div class="p-4">
    <h2 class="text-2xl font-bold mb-4">{{ $t('table.management_title') }}</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
      <div
        v-for="table in tables"
        :key="table.id"
        :class="['p-4 rounded-lg shadow-md transition-transform transform hover:scale-105', getTableStatusClass(table.status)]"
      >
        <div class="font-semibold text-lg">{{ $t('table.number') }}: {{ table.number }}</div>
        <div class="text-sm">{{ $t('table.capacity') }}: {{ table.capacity }}</div>
        <div class="mt-2 font-bold">{{ $t('table.status') }}: {{ getStatusTranslated(table.status) }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const tables = ref([]);

const fetchTables = async () => {
  // Mock API call
  const response = await fetch('/api/tables'); // Replace with actual API call
  const data = await response.json();
  tables.value = data;
};

const getTableStatusClass = (status) => {
  switch (status) {
    case 'available':
      return 'bg-green-100 text-green-800 border-l-4 border-green-500';
    case 'occupied':
      return 'bg-red-100 text-red-800 border-l-4 border-red-500';
    case 'reserved':
      return 'bg-yellow-100 text-yellow-800 border-l-4 border-yellow-500';
    case 'cleaning':
      return 'bg-blue-100 text-blue-800 border-l-4 border-blue-500';
    default:
      return 'bg-gray-100 text-gray-800 border-l-4 border-gray-500';
  }
};

const getStatusTranslated = (status) => {
  return t(`table.status_options.${status}`);
};

onMounted(fetchTables);
</script>
