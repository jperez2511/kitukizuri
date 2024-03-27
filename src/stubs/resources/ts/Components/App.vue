<script setup lang="ts">
import VerticalSidebarVue from './vertical-sidebar/VerticalSidebar.vue';
import VerticalHeaderVue from './vertical-header/VerticalHeader.vue';
</script>

<template>
    <v-locale-provider>
        <v-app 
            :theme="'BLUE_THEME'"
            :class="[
                'BLUE_THEME',
                '',
                'verticalLayout',
                ''
            ]">
            
            <VerticalSidebarVue/>   
            <VerticalHeaderVue/>   

            <v-main>
                <v-container fluid class="page-wrapper pb-sm-15 pb-10">
                    <div>
                        Hola mundo
                    </div>
                </v-container>
            </v-main>
        </v-app>
    </v-locale-provider>
</template>
