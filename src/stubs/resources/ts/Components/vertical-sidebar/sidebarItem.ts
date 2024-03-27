import {
    CircleOffIcon,
    BoxMultipleIcon,
    AppsIcon,
    FileTextIcon,
    FileDotsIcon,
    FilesIcon,
    EditIcon,
    BorderAllIcon,
    BorderHorizontalIcon,
    BorderInnerIcon,
    BorderTopIcon,
    BorderVerticalIcon,
    BoxIcon,
    AlertCircleIcon,
    LoginIcon,
    UserPlusIcon,
    RotateIcon,
    CurrencyDollarIcon,
    ChartLineIcon,
    ChartAreaIcon,
    ChartDotsIcon,
    ChartArcsIcon,
    ChartCandleIcon,
    ChartDonut3Icon,
    ChartRadarIcon,
    ShoppingCartIcon,
    ApertureIcon,
    LayoutIcon,
    HelpIcon,
    UserCircleIcon,
    BoxAlignBottomIcon,
    BoxAlignLeftIcon,
    SettingsIcon,
    ZoomCodeIcon,
    StarIcon,
    AwardIcon,
    MoodSmileIcon,
    Message2Icon,
    PointIcon,
    AppWindowIcon,
    MailIcon,
    BasketIcon,
    CalendarIcon,
    BorderStyle2Icon,
    ColumnsIcon,
    RowInsertBottomIcon,
    EyeTableIcon,
    SortAscendingIcon,
    PageBreakIcon,
    FilterIcon,
    BoxModelIcon,
    ServerIcon,
    JumpRopeIcon,
    LayoutKanbanIcon,
} from 'vue-tabler-icons';

export interface menu {
    header?: string;
    title?: string;
    icon?: any;
    to?: string;
    chip?: string;
    chipBgColor?: string;
    chipColor?: string;
    chipVariant?: string;
    chipIcon?: string;
    children?: menu[];
    disabled?: boolean;
    type?: string;
    subCaption?: string;
}

const sidebarItem: menu[] = [
    { header: 'Menu' },
    {
        title: 'Modern',
        icon: ApertureIcon,
        chip: 'New',
        chipColor: 'surface',
        chipBgColor: 'secondary',
        to: '/dashboards/modern'
    },
    {
        title: 'Menu Level',
        icon: BoxMultipleIcon,
        to: '#',
        children: [
            {
                title: 'Level 1',
                icon: PointIcon,
                to: '/'
            },
            {
                title: 'Level 1',
                icon: PointIcon,
                to: '/',
                children: [
                    {
                        title: 'Level 2',
                        icon: PointIcon,
                        to: '/'
                    },
                    {
                        title: 'Level 2',
                        icon: PointIcon,
                        to: '/',
                        children: [
                            {
                                title: 'Level 3',
                                icon: PointIcon,
                                to: '/'
                            }
                        ]
                    }
                ]
            }
        ]
    }
];

export default sidebarItem;
