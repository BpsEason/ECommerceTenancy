import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TableManagement from '../components/TableManagement.vue'
import QueueDisplay from '../components/QueueDisplay.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/tables',
      name: 'tables',
      component: TableManagement // **NEW FEATURE: Added route for Table Management**
    },
    {
      path: '/queue/display',
      name: 'queue-display',
      component: QueueDisplay // **NEW FEATURE: Added route for Public Queue Display**
    }
  ]
})

export default router
