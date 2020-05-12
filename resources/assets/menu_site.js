const menu_items = [{
        name: 'Dashboard',
        link: '/site/dashboard',
        icon: ' fa fa-home'
    }, {
        name: 'Booking',
        link: '/site/booking',
        icon: ' fa fa-calendar'
    }, {
        name: 'Targets',
        link: '/site/targets',
        icon: ' fa fa-bullseye'
    }, {
        name: 'Finance',
        link: '/site/finance',
        icon: ' fa fa-pie-chart'
    }, {
        name: 'Notifications',
        link: '/site/notifications',
        icon: ' fa fa-bell-o'
    }, {
        name: 'Settings',
        link: '/site/settings',
        icon: ' fa fa-cog'
    }, {
        name: 'Pages',
        title: ""
    }, {
        name: "Pages",
        icon: "fa fa-files-o",
        child: [{
            name: 'Login',
            link: '/site',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Register',
            link: '/register',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Forgot password',
            link: '/forgotpassword',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Reset password',
            link: '/reset_password',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Lockscreen',
            link: '/lockscreen',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Form Validations',
            link: '/site/form_validations',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Datepickers',
            link: '/site/vue-datepicker',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Modals',
            link: '/site/modals',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Blank',
            link: '/site/blank',
            icon: 'fa fa-angle-double-right'
        }, {
            name: 'Tasks',
            link: '/site/tasks',
            icon: 'fa fa-angle-double-right'
        }]
    },
];
export default menu_items;
