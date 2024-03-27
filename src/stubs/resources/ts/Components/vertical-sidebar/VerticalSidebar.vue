<script setup lang="ts">
import { ref, shallowRef } from 'vue';
import sidebarItems from './sidebarItem';
import Logo from '../logo/Logo.vue';

import NavGroup from './NavGroup/index.vue';
import NavItem from './NavItem/index.vue';
import NavCollapse from './NavCollapse/NavCollapse.vue';
import Profile from './profile/Profile.vue';

const sidebarMenu = shallowRef(sidebarItems);
</script>

<template>
    <v-navigation-drawer
        left
        elevation="0"
        rail-width="75"
        app
        class="leftSidebar"
        expand-on-hover width="270"
    >
        <!---Logo part -->
        <div class="pa-5">
            <Logo />
        </div>

        
        <!-- ---------------------------------------------- -->
        <!---Navigation -->
        <!-- ---------------------------------------------- -->
        <perfect-scrollbar class="scrollnavbar">
            <v-list class="pa-6">
                <!---Menu Loop -->
                <template v-for="(item) in sidebarMenu">
                    <!---Item Sub Header -->
                    <NavGroup :item="item" v-if="item.header" :key="item.title" />
                    <!---If Has Child -->
                    <NavCollapse class="leftPadding" :item="item" :level="0" v-else-if="item.children" />
                    <!---Single Item-->
                    <NavItem :item="item" v-else class="leftPadding" />
                    <!---End Single Item-->
                </template>
            </v-list>
            <div class="pa-6 userbottom">
                <Profile />
            </div>
        </perfect-scrollbar>
    </v-navigation-drawer>
</template>
