<script setup>
import Icon from '../Icon.vue';
</script>


  <!-- <div class="flex-none w-14 h-14 " style="margin-left: -97px;margin-bottom: -66px;">
    <button @click.prevent="$emit('toggle')" type="button" class="w-full h-full text-gray-700 transition-all p-1 bg-white">
      <Icon name="bars" />
    </button>
  </div> -->
  <template>
    <div class="flex-none w-14 h-14">
      <button @click.prevent="toggle" type="button" class="w-7 h-7  transition-all p-1 bg-white flex justify-center items-center rounded-md border">
        <!-- open -->
        <svg v-if="isOpen" class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="#0085D6" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 12H5m14 0-4 4m4-4-4-4"/>
        </svg>

        <!-- close -->
        <svg v-else class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="#0085D6" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
        </svg>

      </button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isOpen: false,
      };
    },
    methods: {
      toggle() {
        this.isOpen = !this.isOpen;
        this.$emit('toggle', this.isOpen);
      },
    },
  };
  </script>