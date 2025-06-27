import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import { createI18n } from 'vue-i18n'
import './assets/main.css'

// i18n
const messages = {
  en: {
    welcome: 'Welcome!',
    queue: {
      title: 'Queue Display',
      currently_serving: 'Now Serving',
      waiting_list: 'Waiting List',
      no_number: 'N/A',
      no_waiting: 'No one is waiting. Enjoy!'
    },
    table: {
      management_title: 'Table Status Management',
      number: 'Table No.',
      capacity: 'Capacity',
      status: 'Status',
      status_options: {
        available: 'Available',
        occupied: 'Occupied',
        reserved: 'Reserved',
        cleaning: 'Cleaning'
      }
    }
  },
  'zh-TW': {
    welcome: '歡迎！',
    queue: {
      title: '叫號顯示',
      currently_serving: '目前號碼',
      waiting_list: '等候列表',
      no_number: '無',
      no_waiting: '目前沒有等候號碼。'
    },
    table: {
      management_title: '桌位狀態管理',
      number: '桌號',
      capacity: '容納人數',
      status: '狀態',
      status_options: {
        available: '空閒',
        occupied: '使用中',
        reserved: '已預訂',
        cleaning: '清潔中'
      }
    }
  }
}

const i18n = createI18n({
  legacy: false,
  locale: 'zh-TW',
  fallbackLocale: 'en',
  messages
})

const app = createApp(App)
const pinia = createPinia()

app.use(router)
app.use(pinia)
app.use(i18n)

app.mount('#app')
