import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, usePage } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Themes from './themes'
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import * as commons from './common.js'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const { $token } = JSON.parse(document.getElementById('app').dataset.page).props
axios.defaults.headers.common['Authorization'] = `Bearer ${$token}`

commons.getAllDefinedTranslation()

Object.keys(commons).forEach(key => Object.defineProperty(window, key, {
  value: commons[key],
}))

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mixin({
                methods: {
                  ...commons,
                  themes: () => Themes,
                },
            })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });

window.Swal = Swal
const Toast = window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    showCloseButton: true,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

Inertia.on('start', commons.authorization)
Inertia.on('finish', commons.authorization)
Inertia.on('finish', () => {
    const { $flash } = usePage().props.value
    const { success, error, info, warning } = $flash
    
    if (success) {
      Toast.fire({
        text: success,
        timer: 3000,
        icon: 'success',
      })
    }
    
    if (error) {
      Toast.fire({
        text: error,
        icon: 'error',
      })
    }
    
    if (info) {
      Toast.fire({
        text: info,
        timer: 3000,
        icon: 'info',
      })
    }
    
    if (warning) {
      Toast.fire({
        text: warning,
        timer: 3000,
        icon: 'warning',
      })
    }
})

Date.prototype.shortIndonesianMonths = {
  1: 'Jan',
  2: 'Feb',
  3: 'Mar',
  4: 'Apr',
  5: 'Mei',
  6: 'Jun',
  7: 'Jul',
  8: 'Agu',
  9: 'Sep',
  10: 'Okt',
  11: 'Nov',
  12: 'Des',
}

Date.prototype.fullIndonesianMonths = {
  1: 'Januari',
  2: 'Februari',
  3: 'Maret',
  4: 'April',
  5: 'Mei',
  6: 'Juni',
  7: 'Juli',
  8: 'Agustus',
  9: 'September',
  10: 'Oktober',
  11: 'November',
  12: 'Desember',
}

Date.prototype.toOnlyIndonesianDate = function(withSecond = false) {
  const year = this.getFullYear()
  const month = this.getMonth() + 1
  const day = this.getDate().toString().padStart(2, '0')

  if (year) {
    return `${day} ${this.fullIndonesianMonths[month]} ${year > 0 ? year : (~year) + 1}`;
  }

  return '-'
}

Date.prototype.toShortIndonesianDate = function(withSecond = false) {
  const year = this.getFullYear()
  const month = this.getMonth() + 1
  const day = this.getDate().toString().padStart(2, '0')
  const hour = this.getHours().toString().padStart(2, '0')
  const minute = this.getMinutes().toString().padStart(2, '0')
  const second = this.getSeconds().toString().padStart(2, '0')

  if (year) {
    return `${day}-${this.shortIndonesianMonths[month]}-${year > 0 ? year : (~year) + 1} ${hour}:${minute}${withSecond ? ':' + second : ''}`
  }

  return '-'
}

Date.prototype.toFullIndonesianDate = function(withSecond = false) {
  const year = this.getFullYear()
  const month = this.getMonth() + 1
  const day = this.getDate().toString().padStart(2, '0')
  const hour = this.getHours().toString().padStart(2, '0')
  const minute = this.getMinutes().toString().padStart(2, '0')
  const second = this.getSeconds().toString().padStart(2, '0')

  if (year) {
    return `${day} ${this.fullIndonesianMonths[month]} ${year > 0 ? year : (~year) + 1} ${hour}:${minute}${withSecond ? ':' + second : ''}`
  }

  return '-'
}