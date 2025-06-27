<template>
  <div class="checkout p-6 bg-white rounded-lg shadow-md mt-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $t('checkout') }}</h2>
    <form @submit.prevent="processPayment">
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold">{{ $t('order_id') }}</label>
        <input v-model="orderId" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold">{{ $t('amount') }}</label>
        <input v-model="amount" type="number" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required />
      </div>
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold">{{ $t('payment_method') }}</label>
        <select v-model="paymentMethod" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
          <option value="stripe">Stripe</option>
          <option value="ecpay">ECPay</option>
          <option value="line_pay">Line Pay</option>
        </select>
      </div>
      <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-200">
        {{ $t('pay_now') }}
      </button>
    </form>
    <div v-if="paymentStatus" :class="['mt-4 p-4 rounded-md font-semibold', paymentStatus === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
      {{ paymentMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { processPayment } from '@/api/payments.ts';
const orderId = ref('ORDER-12345');
const amount = ref(250.00);
const paymentMethod = ref('stripe');
const paymentStatus = ref(null);
const paymentMessage = ref('');
async function processPayment() {
  try {
    const response = await processPayment({ order_id: orderId.value, amount: amount.value, payment_method: paymentMethod.value });
    paymentStatus.value = response.data.status;
    paymentMessage.value = response.data.message;
  } catch (error) {
    paymentStatus.value = 'failed';
    paymentMessage.value = 'Payment failed. Please try again.';
    console.error('Payment failed:', error);
  }
}
</script>
