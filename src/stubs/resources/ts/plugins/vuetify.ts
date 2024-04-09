import { createVuetify } from 'vuetify';
import '@mdi/font/css/materialdesignicons.css';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
//DragScroll
import { VueDraggableNext } from 'vue-draggable-next'
import { BLUE_THEME, AQUA_THEME, PURPLE_THEME, GREEN_THEME, CYAN_THEME, ORANGE_THEME } from '@/ts/theme/LightTheme';
import {
    DARK_BLUE_THEME,
    DARK_AQUA_THEME,
    DARK_ORANGE_THEME,
    DARK_PURPLE_THEME,
    DARK_GREEN_THEME,
    DARK_CYAN_THEME
} from '@/ts/theme/DarkTheme';

export default createVuetify({
    components: {
        draggable: VueDraggableNext,
      },
    directives,

    theme: {
        defaultTheme: 'BLUE_THEME',
        themes: {
            BLUE_THEME,
            AQUA_THEME,
            PURPLE_THEME,
            GREEN_THEME,
            CYAN_THEME,
            ORANGE_THEME,
            DARK_BLUE_THEME,
            DARK_AQUA_THEME,
            DARK_ORANGE_THEME,
            DARK_PURPLE_THEME,
            DARK_GREEN_THEME,
            DARK_CYAN_THEME
        }
    },
    defaults: {
        VCard: {
            rounded: 'md'
        },
        VTextField: {
            variant: 'outlined',
            density: 'comfortable',
            color: 'primary'
        },
        VTextarea: {
            variant: 'outlined',
            density: 'comfortable',
            color: 'primary'
        },
        VSelect: {
            variant: 'outlined',
            density: 'comfortable',
            color: 'primary'
        },
        VListItem: {
            minHeight: '45px'
        },
        VTooltip: {
            location: 'top'
        }
    }
});
