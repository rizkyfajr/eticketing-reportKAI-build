<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Card from '@/Components/Card.vue';
import Icon from '@/Components/Icon.vue';
import Builder from '@/Components/DataTable/Builder.vue';
import Th from '@/Components/DataTable/Th.vue';
</script>

<template>
  <DashboardLayout
    :title="__('login activities')"
  >
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">login activities</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Builtin</span> 
            <slot />
        </main>
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem]  shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
      <template #header>

      </template>

      <template #body>
        <Builder
          :url="route('superuser.activity.login')"
        >
          <template #thead="table">
            <tr class="bg-gray-200 border-gray-300">
              <Th
                :table="table"
                :sort="false"
                class="border p-2 text-center capitalize font-bold"
              >
                {{ __('no') }}
              </Th>
              
              <Th
                :table="table"
                :sort="true"
                name="users.name"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
              >
                {{ __('name') }}
              </Th>

              <Th
                :table="table"
                :sort="true"
                name="users.username"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
              >
                {{ __('username') }}
              </Th>

              <Th
                :table="table"
                :sort="true"
                name="login_activities.ip_address"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
              >
                {{ __('ip address') }}
              </Th>

              <Th
                :table="table"
                :sort="true"
                name="login_activities.browser"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
              >
                {{ __('browser') }}
              </Th>

              <Th
                :table="table"
                :sort="true"
                name="login_activities.platform"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
              >
                {{ __('platform') }}
              </Th>

              <Th
                :table="table"
                :sort="true"
                name="login_activities.created_at"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
              >
                {{ __('at') }}
              </Th>
            </tr>
          </template>

          <template #tfoot="table">
            <tr class="bg-gray-200 border-gray-300">
              <Th
                :table="table"
                :sort="false"
                class="border p-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]"
              >
                {{ __('no') }}
              </Th>

              <Th
                :table="table"
                :sort="false"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
              >
                {{ __('name') }}
              </Th>

              <Th
                :table="table"
                :sort="false"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
              >
                {{ __('username') }}
              </Th>

              <Th
                :table="table"
                :sort="false"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
              >
                {{ __('ip address') }}
              </Th>

              <Th
                :table="table"
                :sort="false"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
              >
                {{ __('browser') }}
              </Th>

              <Th
                :table="table"
                :sort="false"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
              >
                {{ __('platform') }}
              </Th>

              <Th
                :table="table"
                :sort="false"
                class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
              >
                {{ __('at') }}
              </Th>

            </tr>
          </template>

          <template #tbody="{ data ,processing }">
            <TransitionGroup
              enterActiveClass="transition-all duration-100"
              leaveActiveClass="transition-all duration-50"
              enterFromClass="opacity-0 -scale-y-100"
              leaveToClass="opacity-0 -scale-y-100"
            >
              <tr
                v-for="(activity, i) in data"
                :key="i"
                :class="processing && 'bg-gray-100'"
                class="transition-all duration-300 h-[4.375rem]"
              >
                <td class="px-2 py-1 border-b border-inherit text-center">
                  {{ i + 1 }}
                </td>

                <td class="px-2 py-1 border-b border-inherit uppercase">
                  {{ activity.name }}
                </td>

                <td class="px-2 py-1 border-b border-inherit uppercase">
                  {{ activity.username }}
                </td>

                <td class="px-2 py-1 border-b border-inherit uppercase">
                  {{ activity.ip_address }}
                </td>

                <td class="px-2 py-1 border-b border-inherit uppercase">
                  {{ __(activity.browser) }}
                </td>

                <td class="px-2 py-1 border-b border-inherit uppercase">
                  {{ __(activity.platform) }}
                </td>

                <td class="px-2 py-1 border-b border-inherit uppercase">
                  {{ new Date(activity.created_at).toLocaleString('id') }}
                </td>
              </tr>
            </TransitionGroup>
          </template>
        </Builder>
      </template>
    </Card>
  </DashboardLayout>
</template>