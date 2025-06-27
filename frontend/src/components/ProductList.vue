<template>
  <div class="product-list p-6 bg-gray-50 rounded-lg shadow-md">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $t('products') }}</h2>
    <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="product in products" :key="product.id" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-900">{{ product.name }}</h3>
        <p class="text-gray-600 mt-2">{{ product.description }}</p>
        <div class="mt-4 flex items-center justify-between">
          <span class="text-2xl font-bold text-indigo-600">{{ product.price_formatted }}</span>
          <button class="bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
            Add to Cart
          </button>
        </div>
      </div>
    </div>
    <div v-else class="text-center text-gray-500 py-10">
      <p>No products found for this tenant.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getProducts } from '@/api/products.ts';
const products = ref([]);
onMounted(async () => {
  try {
    const response = await getProducts();
    products.value = response.data;
  } catch (error) {
    console.error('Failed to fetch products:', error);
  }
});
</script>

<style scoped>
/* You can add custom styles here if needed */
</style>
