<template>
  <div id="app" class="font-sans antialiased text-gray-900 bg-gray-100 min-h-screen">
    <header class="bg-white shadow-md p-6 mb-8 flex justify-between items-center">
      <h1 class="text-4xl font-extrabold text-indigo-600">{{ $t('welcome') }}</h1>
      <div class="language-switcher">
        <label for="lang-select" class="mr-2">{{ $t('language') }}:</label>
        <select id="lang-select" v-model="locale" class="p-2 border rounded-md">
          <option value="en">English</option>
          <option value="zh-TW">繁體中文</option>
        </select>
      </div>
    </header>

    <main class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <ProductList />
      </div>
      <div class="lg:col-span-1">
        <Checkout />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import ProductList from './components/ProductList.vue';
import Checkout from './components/Checkout.vue';
const { locale } = useI18n();
watch(locale, (newLocale) => {
  console.log(`Language switched to: ${newLocale}`);
});
</script>
