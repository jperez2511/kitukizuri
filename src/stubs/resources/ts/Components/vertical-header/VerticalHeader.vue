<script setup lang="ts">
import { ref } from 'vue';

import ProfileDD from './ProfileDD.vue';

const appsdrawer = ref(false);


</script>

<template>
    <v-app-bar elevation="0" :priority="0" height="70" class="">

        <v-spacer />
        

        <!-- right sidebar -->
        <v-btn
            variant="text"
            color="primary"
            class="hidden-lg-and-up"
            icon
            @click.stop="appsdrawer = !appsdrawer"
        >
            <GridDotsIcon size="17" stroke-width="1.5" />
        </v-btn>

        <!-- ---------------------------------------------- -->
        <!-- User Profile -->
        <!-- ---------------------------------------------- -->
        <div class="ml-2">
            <ProfileDD />
        </div>
    </v-app-bar>
    
</template>
